<?php
require_once 'review.php';
require_once 'uifuncs.php';
require_once 'uri_funcs.php';

  function append_zero($text)
  {
  		return (strlen($text) == 1) ? "0$text" : $text;
  	}

class GameDate
{
  function __construct ($game)
  {
    $this->bdate = strtotime ($game['begin']);
    $this -> days = $game['time']-1;
    $this -> end_date = getdate(strtotime ("+{$this->days} day", $this->bdate));
    $this -> begin_date = getdate ($this->bdate);
    $this -> dow =
      $this -> days == 0 ?
          get_day_of_week($this -> begin_date)
        : get_day_of_week($this->begin_date) . ' — ' . get_day_of_week($this->end_date);
  }

  public static function format_machine_date($s)
  {
  	return $s['year'] . append_zero($s['mon']) . append_zero($s['mday']) . 'T000000';
  }
  
    
  public static function get_russian_month_name ($month_num)
  {
		static $month_names2 = array (
		  1 => 'Январь',
		  2 => 'Февраль',
		  3 => 'Март',
		  4 => 'Апрель',
		  5 => 'Май',
		  6 => 'Июнь',
		  7 => 'Июль',
		  8 => 'Август',
		  9 => 'Сентябрь',
		  10 => 'Октябрь',
		  11 => 'Ноябрь',
		  12 => 'Декабрь');
		
		return $month_names2[$month_num];
  }
  
  public static function get_month_id ($month)
  {
		static $month_ids = array ('', 'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec');
		return $month_ids[$month];
  }

	function get_machine_date_begin()
	{
			return GameDate :: format_machine_date($this -> begin_date);
	}

	function get_machine_date_end()
	{
			return GameDate :: format_machine_date($this -> end_date);
	}

  function get_machine_date()
  {
  		return $this -> get_machine_date_begin()
  			. '/'
  			. $this -> get_machine_date_end();
  }

  function year()
  {
    return $this->begin_date['year'];
  }
  
  function month ()
  {
		return $this -> begin_date['mon'];
  }

  function show_date_string($show_year)
  {
    return get_date_text($this->begin_date, $this->end_date, $show_year);
  }

  function get_js_string_begin()
  {
    $mon = $this->begin_date['mon'] - 1;
    return "new Date({$this->begin_date['year']}, $mon, {$this->begin_date['mday']})";
  }

  function get_js_string_end()
  {
    $mon = $this->end_date['mon'] - 1;
    return "new Date({$this->end_date['year']}, $mon, {$this->end_date['mday']})";
  }

  function is_passed()
  {
    return strtotime ("+{$this->days} day", $this->bdate) < time();
  }
}

class Calendar
{
  function __construct ($games_array)
  {
    $this -> games_array = $games_array;
    $this -> colspan = 0;
    $this -> edtitor = FALSE;
    $this -> use_checkbox = FALSE;
    $this -> check_border = FALSE;
    $this -> show_reviews = TRUE;
    $this -> show_only_future = FALSE;
    $this -> write_updates = FALSE;
    $this -> show_status = TRUE;
    $this -> show_cancelled_games_checkbox = TRUE;
    $this -> editor = check_edit_priv();
    $this -> prev_date = NULL;
    $this -> export_mode = FALSE;
    $date = getdate();
    $this -> current_month = $date['mon'];
  }

  function _write_calendar_header ()
  {
    $colspan = 7;
    if ($this -> show_status)
    {
			$colspan++;
    }
    if ($this -> export_mode)
    {
			$colspan++;
    }
    if ($this -> editor)
    {
			$colspan++;
    }
    echo '<table id="calendar" cellpadding="2" cellspacing="0"><tr>';

    if ($this -> show_status)
    {
      echo "<th class=\"status-column\">Статус</th>";
    }
   ?>

          <th class="name-column">Название</th>
        <th class="region-column">Регион</th>
          <th class="date-column">Сроки</th>
          <th class="type-column">Тип игры</th>
          <th class="polygon-column">Полигон</th>
                          <th class="players-column" title="Количество игроков">Иг-ов</th>
  <?php
   if ($this -> export_mode)
   {
      echo '<th class="email-column">Email</th>';
   }
  ?>
          <th class="mg-column">Мастерская группа</th>

  <?php

    if ($this -> editor)
    {
      echo "<th class=\"edit-column\">&nbsp;</th>\n";
      $colspan++;
    }
    echo '</tr>';

    $this -> colspan = $colspan;
  }

  function write_calendar()
  {
    $this -> _write_calendar_header();

    foreach ($this -> games_array as $game)
    {
      $this ->_write_calendar_entry ($game);
    }
    echo '</table>';
  }

  public static function format_game_date ()
  {
  }

  public static function format_game_name ($name, $uri)
  {
    $name = htmlspecialchars($name);
    return $uri ? "<a href=\"$uri\" rel=\"nofollow\">$name</a>" : $name;
  }
  
  function write_game_icons ($game)
  {
		$uri = trim($game['uri']);
		if ($uri)
		{
			echo Calendar::get_link_icon($uri, $uri, '[S]', 'world_link.png') . '&nbsp;';
		}
		$vk_club = trim ($game['vk_club']);

		if ($vk_club)
		{
		  $link = format_vk_link($vk_club);
			echo Calendar::get_link_icon($link, $link, '[VK]', 'vk.png') . '&nbsp;';
		}
		$lj_comm = trim ($game['lj_comm']);
		if ($lj_comm)
		{
		  $link = format_lj_link ($lj_comm);
			echo Calendar::get_link_icon($link, $link, '[LJ]', 'livejournal.png') . '&nbsp;';
		}
  }
  
  function write_game_name ($game)
  {
		
		if (!$this -> export_mode)
		{
			$this -> write_game_icons ($game);
			echo Calendar::format_game_name ($game['name'], "/game/{$game['id']}");
		}
		else
		{
			 echo Calendar::format_game_name ($game['name'], '');
		}
  }
  
  function show_border_if_needed($date)
  {
		if ($this -> check_border)
    {
      $this -> check_date_border ($date);
    }
  }
  
  function get_date_string ($date)
  {
		return $date -> show_date_string(!$this -> check_border);
  }
  
  function _write_calendar_entry ($game)
  {
      $masked = $game['show_flags'] && 1;

      if ($masked && !check_username())
        return;

      $id = $game['id'];
      if ($id <= 0 && $this->write_updates)
      {
        return;
      }

      $date = new GameDate($game);

      $type = $game['game_type_name'];
      $polygon = $game['polygon_name'];
      $sub_region_disp_name = $game['sub_region_disp_name'];
      $sub_region_name = $game['sub_region_name'];
      $status_name= $game['status_name'];
      $status_style= $game['status_style'];
      if ($status_name == 'OK' && $date -> is_passed())
      {
        $status_name = "Прошла?";
        $status_style="status-unknown";
      }
      if ($game['order'] > 0)
      {
         $status_name = "Перенесена!";
         $status_style="status-unknown";
      }
       if ($this -> show_reviews)
       {
       $status_name .= $this->get_review_cell_text2($game);
       $status_name .= $this->get_photo_text($game);
       }



      $players_count = $game['players_count'] > 0 ? $game['players_count'] : '&nbsp;';

      $this -> show_border_if_needed ($date);


      

      $style = '';
      if ($masked)
      {
        $style = ' style="background-color: #C0C0C0"';
      }
      $cancelled = $game['cancelled_status'] > 0 ?' class="cancel_game"' : '';
      echo "<tr$cancelled$style>";
      if ($this -> show_status)
      {
        $id_str = $game['order'] > 0 ? '' :  "id=\"$id\"";
        echo "<td class=\"$status_style\" $id_str>$status_name</td>";
      }

      echo "<td class=\"game_name\">";
      $this -> write_game_name ($game);
      echo "</td>";
      echo "<td title=\"$sub_region_name\" class=\"game_region\">$sub_region_disp_name</td>";
      $show_date = $this -> get_date_string($date);
      echo "<td title=\"{$date->dow}\" class=\"game_date\">$show_date</td>";
      echo "<td class=\"game_type\">$type</td>";
      echo "<td class=\"game_polygon\">$polygon</td>";
      echo "<td class=\"game_players_count\">$players_count</td>";
      if ($this -> export_mode)
      {
        $email = get_email_link_for_export($game);
        echo "<td class=\"game_email\">$email</td>";
        echo "<td class=\"game_mg\">" . $game['mg'] . '</td>';
      }
      else
      {
        $mg = get_email_link($game) . htmlspecialchars($game['mg']);
        echo "<td class=\"game_mg\">$mg</td>";
      }
      $this->write_editor_box ($game['id']);

      echo "</tr>\n";
  }

  public static function get_link_icon($link, $title, $alt, $icon)
  {
    $link = htmlspecialchars($link);
    $title = htmlspecialchars($title);
    return "<a href=\"$link\"><img src=\"/img/$icon\" width=\"16\" height=\"16\" title=\"$title\" alt=\"$link\" class=\"link_icon\"/></a>";
  }

  public static function get_email_pic($email_str)
  {
    $email_str = trim($email_str);
    return Calendar::get_link_icon('mailto:'. $email_str, $email_str, '[@]', 'email.png') . '&nbsp;';
  }

  function write_editor_box($id)
  {
    if ($this -> editor)
      {
        echo "<td class=\"game_edit\">";
        if ($this -> use_checkbox)
        {
          echo "<input type=\"checkbox\" name=\"mark[]\" value=\"$id\" />";
          //Правильный путь
          //echo "&nbsp;<input type=submit formaction=\"/edit/game/?id=$id\" value=\"Изменить\">";
          //IE9 не поддерживает formaction
          echo "&nbsp;<a href=\"/edit/game/?id=$id\">Изменить</a>";
        }
        else
        {
          echo "<form action=\"/edit/game/?id=$id/\" method=post id=ledit_form style=\"display:inline\"><input type=submit value=\"Изменить\"></form>";
        }
        echo "</td>\n";
      }
  }

  function get_photo_text($game)
  {
    $photo_count =  Calendar::get_field_int($game, 'photo_count');
    if ($photo_count == 0)
    {
      return '';
    }
    return "<br><a href=\"/game/{$game['id']}/\">" .  Calendar::format_by_count($photo_count, 'фотоотчет', 'фототчета', 'фотоотчетов') . '</a>';
  }

  function get_field_int($game, $name)
  {
    return array_key_exists($name, $game) ? intval ($game[$name]) : 0;
  }

  static function format_by_count($count, $singular, $double_plurar, $plurar)
  {
    switch ($count)
    {
      case 1: return "$count&nbsp;$singular";
      case 2:
      case 3:
      case 4:
        return "$count&nbsp;$double_plurar";
      default:
        return "$count&nbsp;$plurar";
    }
  }

  function get_review_cell_text2 ($game)
  {
    if (!array_key_exists('show_review_flag', $game) && intval($game['show_review_flag']) > 0)
    {
      return '';
    }
   $review_count =  Calendar::get_field_int($game, 'review_count');

    if ($review_count == 0)
     {
      return "";
     }

    return "<br><a href=\"/game/{$game['id']}/\">" .  Calendar::format_by_count($review_count, 'рецензия', 'рецензии', 'рецензий') . '</a>';

  }
  
  function write_border ($month_name)
  {
		
		echo "<tr class=\"month_header\"><td colspan={$this -> colspan}>$month_name</td></tr>";
  }
  

  
  function get_russian_short_month_name ($month_num)
  {
				static $month_names = array (
		  1 => 'Янв',
		  2 => 'Фев',
		  3 => 'Мар',
		  4 => 'Апр',
		  5 => 'Май',
		  6 => 'Июнь',
		  7 => 'Июль',
		  8 => 'Авг',
		  9 => 'Сен',
		  10 => 'Окт',
		  11 => 'Ноя',
		  12 => 'Дек');
		
		return $month_names[$month_num];
  }

  
  function get_month_with_games()
  {
		foreach ($this -> games_array as $game)
		{
			$date = new GameDate ($game);
			
			$tmp[$date -> month()] = TRUE;
		}
		
		$result = array();
		for ($i = 0; $i <= 12; $i++)
		{
			if (array_key_exists ($i, $tmp))
			{
				$result[] = $i;
			}
		}
		return $result;
  }
  
  function check_date_border($date)
{
	$colspan = $this -> colspan;
	
	if ($this -> prev_date && ($this -> prev_date -> month()  == $date -> month()))
	{
    return;
	}
	if (!$this -> prev_date)
	{
		foreach ($this -> get_month_with_games() as $i)
		{
			$id = GameDate :: get_month_id ($i);
			$month_name = GameDate :: get_russian_month_name ($i);
			
			$month_menu[] = ($i == $date -> month())
        ? "<b>$month_name</b>" 
        : "<a href=\"#$id\">$month_name</a>";
		}
		$this -> write_border ('<br>' . implode (" ", $month_menu));
	}
	else 
	{
		$id = GameDate :: get_month_id ($date -> month());
		$month_name = GameDate :: get_russian_month_name ($date -> month());
		
		$this -> write_border ("<b id=\"$id\"> <br>$month_name</b> <a href=\"#top\">↑</a>");
	}
	$this -> prev_date = $date;
}

}
//*** END of calendar class

function write_calendar_header ($editor)
{
  $calendar = new Calendar (NULL);
  $calendar -> editor = check_edit_priv();
	$calendar -> _write_calendar_header ();
	return $calendar -> colspan;
}



function get_email_link($game)
{
  	if (!$game['email'])
	{
    return '';
	}

  if ($game['hide_email'])
	{

      return check_username() ? ('<em>Скрытый:</em>' . Calendar::get_email_pic($game['email'])) : '';
	}

	return Calendar::get_email_pic($game['email']);

}

function get_email_link_for_export($game)
{
  	if (!$game['email'])
	{
    return '';
	}

  if ($game['hide_email'] && !check_username())
	{

      return  '';
	}

	return $game['email'];

}

function write_calendar_entry ($game, $colspan, $check_border = TRUE, $use_checkbox = FALSE)
{
  $calendar = new Calendar (NULL);
  $calendar -> check_border = $check_border;
  $calendar -> use_checkbox = $use_checkbox;
  $calendar -> colspan = $colspan;
  $calendar -> editor = check_edit_priv();
	$calendar -> _write_calendar_entry ($game);
}

function write_new_games_box ($games, $reviews = FALSE, $photos = FALSE)
{
  if (!is_array($games) AND !is_array($reviews)AND !is_array($photos))
  {
  return;
  }
    echo '<div class="new_games">';
    if (is_array($games))
    {
      echo '<strong>Свежие игры</strong>: ';
      $sep_write = FALSE;
      foreach ($games as $game_data)
      {
        if ($sep_write)
        {
          echo '&nbsp;| ';
        }
        echo Calendar::format_game_name ($game_data['name'], "/game/{$game_data['id']}");
        $sep_write = TRUE;
      }
    }
    if (is_array($reviews))
    {
      if (is_array($games))
      {
        echo '<br>';
      }
      echo '<strong>Свежие <a href="/reviews/">рецензии</a></strong>: ';
      $sep_write = FALSE;
      foreach ($reviews as $review_data)
      {
        if ($sep_write)
        {
          echo ' | ';
        }

        $review_uri = Review :: get_review_uri ($review_data);
        $review_author = Review :: get_review_author($review_data);
        $game_name = Calendar::format_game_name ($review_data['name'], "/game/{$review_data['id']}");
        echo "<a href=\"$review_uri\">Рецензия</a> $review_author на $game_name";
        $sep_write = TRUE;
      }
    }
    if (is_array($photos))
    {
      if (is_array($games) || is_array($reviews))
      {
        echo '<br>';
      }
      echo '<strong>Свежие <a href="/photo/">фототчеты</a></strong>: ';
      $sep_write = FALSE;
      foreach ($photos as $review_data)
      {
        if ($sep_write)
        {
          echo ' | ';
        }

        $review_author = $review_data['photo_author'];
        $game_name = Calendar::format_game_name ($review_data['name'], "/game/{$review_data['id']}");
        echo "$review_author на $game_name";
        $sep_write = TRUE;
      }
    }
    echo '</div>';
}

function get_region_param ()
{
  if (array_key_exists('region', $_GET))
    {
      $region =  intval($_GET['region']);
      $region_arr = get_array('region');
      if (!array_key_exists($region, $region_arr))
      {
        $region = 0;
      }
    }
    else
    {
      $region = 0;
    }

  $result ['id'] = $region;
  $result ['name'] = $region == 0 ? 'Россия' : $region_arr[$region];
  return $result;
}
?>