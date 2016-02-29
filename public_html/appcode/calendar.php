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
      $s = $this -> end_date;
			return $s['year'] . append_zero($s['mon']) . append_zero($s['mday']) . 'T235900';
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
    $this -> use_checkbox = FALSE;
    $this -> show_reviews = TRUE;
    $this -> show_only_future = FALSE;
    $this -> show_status = TRUE;
    $this -> editor = check_edit_priv();
    $this -> prev_date = NULL;
    $this -> export_mode = FALSE;
    $date = getdate();
    $this -> current_month = $date['mon'];
    
    $this -> columns = $this -> get_columns();
  }
  
  function get_columns()
  {
    if ($this -> show_status)
    {
       $columns []= array ('name' => 'Статус', 'column-class' => 'status-column');
    }
    $columns []= array ('name' => 'Название', 'column-class' => 'name-column');
    $columns []= array ('name' => 'Регион', 'column-class' => 'region-column');
    $columns []= array ('name' => 'Сроки', 'column-class' => 'date-column');
    $columns []= array ('name' => 'Тип игры', 'column-class' => 'type-column');
    $columns []= array ('name' => 'Полигон', 'column-class' => 'polygon-column');
    $columns []= array ('name' => 'Иг-ов', 'column-class' => 'players-column', 'title' => 'Количество игроков');
    if ($this -> export_mode)
    {
     $columns []= array ('name' => 'Email', 'column-class' => 'email-column');
    }
    $columns []= array ('name' => 'Мастерская группа', 'column-class' => 'mg-column');
    if ($this -> editor)
    {
      $columns []= array ('name' => '&nbsp;', 'column-class' => 'edit-column');
    }
    return $columns;
  }

  function write_header ()
  {
    echo '<tr>';
    foreach ($this -> columns as $column)
    {
      $title_string = array_key_exists('title', $column) ? " title=\"{$column['title']}\"" : '';
      echo "<th class=\"{$column['column-class']}\"$title_string>{$column['name']}</th>";
    }
    echo '</tr>';
  }

  function write_calendar()
  {
    echo '<table id="calendar">';
    $this -> write_header();

    foreach ($this -> games_array as $game)
    {
      $this -> write_entry ($game);
    }
    echo '</table>';
  }

  function get_email_link($game)
  {
		$email = trim ($game['email']);
		
		if (!$email)
		{
			return '';
		}
	
		if ($game['hide_email'])
		{
			if (!check_username())
			{
				return  '';
			}
			
			if ($this-> export_mode)
			{
				return $email;
			}
      return '<em>Скрытый:</em>' . Calendar::get_email_pic($email);
		}

    return $this -> export_mode ? $game['email'] : Calendar::get_email_pic($email);
  }
  
  public static function get_email_pic($email_str)
  {
    return Calendar::get_link_icon('mailto:'. $email_str, $email_str, '[@]', 'email.png') . '&nbsp;';
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
  }
  
  function get_date_string ($date)
  {
		return $date -> show_date_string(TRUE);
  }
  
  function status_cell_creator($game, $date)
  {
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
     $status_name .= $this->get_review_cell_text($game);
     $status_name .= $this->get_photo_text($game);
   }
       
    $id_str = $game['order'] > 0 ? '' :  "id=\"{$game['id']}\"";
    return "<td class=\"$status_style\" $id_str>$status_name</td>";
  }
  
  //TODO: rewrite this functions so $columns fully control which columns are written and which are not
  function write_entry ($game)
  {
      $masked = $game['show_flags'] && 1;

      if ($masked && !check_username())
        return;

      $id = $game['id'];

      $date = new GameDate($game);

      $type = $game['game_type_name'];
      $polygon = $game['polygon_name'];
      $sub_region_disp_name = $game['sub_region_disp_name'];
      $sub_region_name = $game['sub_region_name'];

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
        echo $this -> status_cell_creator($game, $date);
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
        $email = $this -> get_email_link ($game);
        echo "<td class=\"game_email\">$email</td>";
        echo "<td class=\"game_mg\">" . $game['mg'] . '</td>';
      }
      else
      {
        $mg = $this-> get_email_link($game) . htmlspecialchars($game['mg']);
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

  function write_editor_box($id)
  {
    if ($this -> editor)
      {
        echo "<td class=\"game_edit\">";
        if ($this -> use_checkbox)
        {
          echo "<input type=\"checkbox\" name=\"mark[]\" value=\"$id\" />";
          echo "&nbsp;"; 
        }
        echo "<a href=\"/edit/game/?id=$id\">Изменить</a>";
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

  function get_review_cell_text ($game)
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
}
//*** END of calendar class

function get_region_param ()
{
  $region_arr = get_array('region');
  $region = FALSE;
  $region_name = get_request_field('region_name');
  if ($region_name !== FALSE)
  {
    $region = array_search ($region_name, get_array('region_uri'));
  }
  if ($region === FALSE)
  {
    $region = get_request_field('region');
  }
  $region = intval($region);
  if (!array_key_exists($region, $region_arr))
  {
    $region = 0;
  }

  $result ['id'] = $region;
  $result ['name'] = $region == 0 ? $region_arr[1] : $region_arr[$region];
  return $result;
}
?>