<?php
require_once 'user_funcs.php';
require_once 'sqlbase.php';
require_once 'top_menu.php';

function write_header ($title, $edit = FALSE)
{
	static $already_written;
	if ($already_written)
	{
		return;
	}
	$already_written = TRUE;
	?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <title><?php echo $title; ?></title>

    <link media="all" rel="stylesheet" href="/default.css" type="text/css">

    <meta name="keywords" content="Календарь, ролевые игры">
    <meta name="description" content="Календарь полевых ролевых игр">
<meta name="verify-v1" content="kgJUNdPugrqlSlUu5/n8UOibKHmPBQKUJJQvua61RYQ=">
<meta charset=utf-8>
<link rel="search" type="application/opensearchdescription+xml" title="<?php echo SITENAME_MAIN ?>!" href="/opensearch.xml">
    <script src="/js/default.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://vk.com/js/api/openapi.js?115"></script>
<script src="https://login.persona.org/include.js" type="text/javascript"></script>
<script type="text/javascript">
  VK.init({apiId: 2118784, onlyWidgets: true});
</script>
    <?php
  if (get_user_id())
  {
		echo '<script type="text/javascript">window.loggedIn = true;</script>';
  }
  if ($edit)
  {
    echo '<script src="/js/edit.js" type="text/javascript"></script>';
  }
  echo '</head><body>';
  
  if (GA_ANALYTICS != '')
  {
		?>
		<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		<?php
		echo "ga('create', '" . GA_ANALYTICS . "', 'auto');";
		echo "ga('send', 'pageview');";
		echo "</script>";
	}
	
	if (!array_key_exists("csrf_token", $_SESSION))
	{
		$_SESSION['csrf_token'] = md5(mt_rand());
	}
	echo "<form><input type=\"hidden\" name=\"csrf_token\" id=\"csrf_token\" value=\"{$_SESSION['csrf_token']}\"></form>";
}

function write_footer($show_analytics = FALSE, $uri = '')
{
  echo "</body>	</html>";
}

function write_js_table ($array, $name)
{
	echo "<script type=\"text/javascript\">\n";
	echo "var $name = " . json_encode($array);
	echo "</script>\n";
}


	function get_array ($name)
	{
			static $cache;
	if (is_array($cache) && array_key_exists($name, $cache))
	{
		return $cache[$name];
	}
	if ($name == 'region')
	{
		$cache[$name] =  get_sql_array('ki_regions', 'region_id', 'region_name');
	}
	elseif ($name == 'region_uri')
	{
    $cache[$name] =  get_sql_array('ki_regions', 'region_id', 'region_code');
	}
	elseif ($name == 'region_display')
	{
		$cache[$name] = get_array('region');
		unset($cache[$name][1]);
		$cache[$name][0] = 'Россия';
	}
	elseif ($name == 'polygon')
	{
		$cache[$name] = get_sql_array ('ki_polygons', 'polygon_id', 'polygon_name', 'sub_region_id, polygon_name');
	}
	elseif ($name == 'type')
	{
		$cache[$name] = get_sql_array ('ki_game_types', 'game_type_id', 'game_type_name');
	}
	elseif ($name == 'sub_region')
	{
		$cache[$name] = get_sql_array_rq ('SELECT ksp.* FROM ki_sub_regions ksp INNER JOIN ki_regions kr ON kr.region_id = ksp.region_id ORDER BY kr.region_name, ksp.sub_region_name', 'sub_region_id', 'sub_region_name');
	}
	elseif ($name == 'show_flags')
	{
			$cache[$name] = array ('Нормальная', 'Скрытая');
		}
	elseif ($name == 'status')
	{
		$cache[$name] = array ('OK', 'Прошла', '???', 'Отложена', 'Дата?', 'Отменена');
	}
	elseif ($name == 'status_style')
	{
		$cache[$name] = array ('status-ok', 'status-finish', 'status-unknown', 'status-postponedd', 'status-date', 'status-canceled');
	}
	elseif ($name == 'year')
	{
    $cache[$name] = get_sql_array_rq('SELECT DISTINCT year FROM ki_years_cache ORDER BY year', 'year', 'year');
	}
		return $cache[$name];
	}

	function return_to_main()
	{
		redirect_to('/');
	}
	
	function redirect_to($location)
	{
		header("Location: $location");
		die();
	}

	function show_user_link ($username)
	{
    return "<span style=\"white-space: nowrap\"><img src=\"/img/userinfo.gif\" /><a href='/user/$username'><b>$username</b></a></span>";

	}

	function show_lj_user($username)
	{
	
		function get_lj_path($ljuser, $comm)
		{
			if (!$ljuser)
				return '';
			if ($comm)
			 return "http://community.livejournal.com/$ljuser";
			$legacy_path = (substr ($ljuser, -1) == '_') || ($ljuser[1] == '_');
			if ($legacy_path)
			{
				return "http://users.livejournal.com/$ljuser";
			} else {
				$ljuser = str_replace ('_', '-', $ljuser);
				return "http://$ljuser.livejournal.com";
			}
		}
	
		$link = get_lj_path ($username, false);
		return "<span style=\"white-space: nowrap\"><img src=\"/img/userinfo.gif\" /><a href='$link/profile' onClick='javascript:urchinTracker(\"/outgoing/$link/profile\");'><b>$username</b></a></span>";
	}

	//Obsolete, remove
	function show_greeting()
	{
		static $shown;
		if (!$shown)
		{
			$shown = true;
			$topmenu = new TopMenu();
			$topmenu -> show();
		}
	}

	function get_post_field ($name)
	{
		return array_key_exists ($name, $_POST) ? $_POST[$name] : FALSE;
	}
	
	function get_request_field ($name)
	{
		return array_key_exists ($name, $_REQUEST) ? $_REQUEST[$name] : FALSE;
	}

	function get_post_date_field ($name)
	{
    $day = get_post_field($name . "_day");
    $month = get_post_field($name . "_month");
    $year = get_post_field($name . "_year");
		if ($day === FALSE || $month === FALSE || $year === FALSE)
		{
      return FALSE;
		}
		return "$year-$month-$day";
	}

	function get_day_of_week ($date)
	{
		static $days_of_week = array ( 0 => 'Воскресенье', 1 => 'Понедельник', 2 => 'Вторник', 3 => 'Среда', 4 => 'Четверг', 5 => 'Пятница', 6 => 'Суббота');
		return $days_of_week[$date['wday']];
	}

	function get_month_name ($month)
	{
		static $month_names = array (
		  1 => 'января',
		  2 => 'февраля',
		  3 => 'марта',
		  4 => 'апреля',
		  5 => 'мая',
		  6 => 'июня',
		  7 => 'июля',
		  8 => 'августа',
		  9 => 'сентября',
		  10 => 'октября',
		  11 => 'ноября',
		  12 => 'декабря');
		  return $month_names[$month];
	}

	function get_date_text ($begin_date, $end_date, $show_year = FALSE)
	{

		$beg_month = get_month_name ($begin_date['mon']);
		$end_month = get_month_name ($end_date['mon']);
		$beg_year = $show_year ? "&nbsp;{$begin_date['year']}&nbsp;г." : '';

		if ($begin_date['year'] != $end_date ['year'])
		{
			return "{$begin_date['mday']}&nbsp;$beg_month&nbsp;{$begin_date['year']}&ndash;{$end_date['mday']}&nbsp;$end_month&nbsp;{$end_date['year']}";
		}
		elseif ($begin_date['month'] != $end_date['month'])
		{
			return "{$begin_date['mday']}&nbsp;$beg_month&ndash;{$end_date['mday']}&nbsp;$end_month$beg_year";
		}
		elseif ($begin_date['mday'] != $end_date['mday'])
		{
			return "{$begin_date['mday']}&ndash;{$end_date['mday']}&nbsp;$beg_month$beg_year";
		}
		else
		{
			return "{$begin_date['mday']}&nbsp;$beg_month$beg_year";
		}
	}

	function submit ($name, $action, $id, $label = '', $in_cell = FALSE, $colspan = 0)
	{
    if (!$in_cell)
    {
      echo "<tr> <td colspan=\"$colspan\">";
    }
		echo "<input type=\"hidden\" name=\"action\" value=\"$action\" />";
		echo "<input type=\"hidden\" name=\"id\" id=\"id\" value=\"$id\" />";
		echo "<input type=\"submit\"  value=\"$name\" />";
		if ($label != '')
		{
			echo "<label><strong>$label</strong></label>";
		}
		    if (!$in_cell)
    {

		echo "</td> </tr> ";
		}
	}


function formate_single_date($date)
{
  $date = getdate(strtotime ($date));
  $year = $date['year'];
		$mon = get_month_name ($date['mon']);
		$mday = $date['mday'];
		$wday = get_day_of_week ($date);
		$hours = $date['hours'];
		$mins = $date['minutes'];
		if ($mins < 10)
		{
			$mins = "0$mins";
		}
	return "$mday $mon $year года, $hours:$mins";
}

?>