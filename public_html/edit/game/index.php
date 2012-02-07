<?php
	require_once 'funcs.php';
	require_once 'user_funcs.php';
	require_once 'logic.php';
	require_once 'calendar.php';
	require_once 'review.php';
	require_once 'view.php';
	require_once 'forms.php';
	require_once 'logic/edit.php';
	require_once 'logic/photo.php';
	require_once 'show_updates.php';
	require_once 'email.php';
	require_once 'logic/review.php';
	require_once 'logic/datalist.php';


	function show_email ($value, $hide_email)
	{
		$value = htmlspecialchars ($value);
		echo "<tr><td><label><strong>E-mail</strong></label></td>";
		echo "<td><input type=\"email\" name=\"email\" id=\"email\" list=\"emaillist\" autocomplete=off maxlength=\"100\" size=\"30\" value=\"$value\" />";
		echo "<select id=\"allrpg_emails\" style=\"visibility:hidden\" onChange=\"set_email_field();\"></select>";
		echo "<br/>";
		if (check_edit_priv())
		{
			$checked = $hide_email ? ' checked="checked" ' : '';
			echo "<input type=\"checkbox\" name=\"hide_email\"$checked id=\"hide_email\" value=\"1\"/><label for=\"hide_email\">Спрятать</label>";
		}
		echo "</td></tr>\n";
	}

	function show_regions_dd ($value)
	{
		echo "<tr><td><label><strong>Регион</strong></label></td>";
		global $region_table;
		global $subregion;
		echo "<td><select name=\"sub_region\" size=\"1\" onChange=\"update_region_name(this);\">";
		$subregion = $region_table[0]['sub_region_id'];
		foreach ($region_table as $row)
		{
			if ($row['sub_region_id'] == $value)
			{
				$selected = 'selected="selected"';
				$subregion = $row['sub_region_id'];
			}
			else
				$selected = '';
			echo "<option value=\"{$row['sub_region_id']}\" $selected >{$row['sub_region_name']}</option>";
		}
		echo "</select>";
		echo "<div id=\"region_placeholder\"></div>";
		echo "</td>";
	}
	
	function write_mg_datalist()
	{
		write_datalist(get_mg_list(), 'mgnames');
		write_datalist(get_email_list(), 'emaillist');
	}
	
	function write_datalist($datalist, $datalist_id)
	{
		echo "<datalist id=\"$datalist_id\">";
		foreach ($datalist as $item)
		{
			$item = htmlspecialchars ($item);
			echo "<option value=\"$item\">";
		}
		echo '</datalist>';
	}

	function show_polygons_dd ($value)
	{
		echo "<tr><td><label><strong>Полигон</strong></label></td>";
		global $polygons_table;
		echo "<td><select name=\"polygon\" size=\"1\" id=\"polygon_select\">";
		foreach ($polygons_table as $row)
		{
			write_option ($row['polygon_id'], $row['polygon_id'] == $value, $row['polygon_name']);
		}
		echo "</select>";
		echo "</td>";
	}

	function write_option($value, $is_selected, $option_name)
	{
    $selected = $is_selected ? ' selected="selected"' : '';
		echo "<option value=\"$value\"$selected>$option_name</option>";
	}

	function show_date_control_internals ($name, $value, $len)
	{
    $value = getdate (strtotime($value));
    global $year_list;
     echo "<input type=\"text\" name=\"{$name}_day\" id=\"{$name}_day\" maxlength=\"2\" size=\"2\" value=\"{$value['mday']}\" onChange=\"update_time_placeholder('$name', '$len');\" /> ";
		  echo "<select name=\"{$name}_month\" id=\"{$name}_month\" size=\"1\" onChange=\"update_time_placeholder('$name', '$len');\">";
      for ($i = 1; $i < 13; $i++)
      {
        write_option($i, $i == $value['mon'], get_month_name($i));
      }
      echo "</select> ";
      echo "<select name=\"{$name}_year\" id=\"{$name}_year\" size=\"1\" onChange=\"update_time_placeholder('$name', '$len');\">";
      foreach ($year_list as $year)
      {
        $year = intval($year['year']);
        write_option($year, $year == intval($value['year']), $year);
      }
      echo "</select>";
	}

	function show_new_date_control ($name, $value)
	{
    $value = getdate (strtotime($value));
    global $year_list;
     echo "<input type=\"text\" name=\"{$name}_day\" id=\"{$name}_day\" maxlength=\"2\" size=\"2\" value=\"{$value['mday']}\" /> ";
		  echo "<select name=\"{$name}_month\" id=\"{$name}_month\" size=\"1\" \">";
      for ($i = 1; $i < 13; $i++)
      {
        write_option($i, $i == $value['mon'], get_month_name($i));
      }
      echo "</select> ";
      echo "<select name=\"{$name}_year\" id=\"{$name}_year\" size=\"1\" \">";
      foreach ($year_list as $year)
      {
        $year = intval($year['year']);
        write_option($year, $year == intval($value['year']), $year);
      }
      echo "</select>";
	}

	function show_date_control($label, $name, $value, $len)
	{
    load_dict_tables();
    global $year_list;

		echo "<tr><td><label><strong>$label</strong></label></td>";
		echo "<td>";
		 show_date_control_internals ($name, $value, $len);
		echo " <span id=\"{$name}_placeholder\"></div>";
		echo "</td></tr>\n";
	}

	function show_length_control ($label, $name, $value)
	{
		$value = htmlspecialchars ($value);
		echo "<tr><td><label><strong>$label</strong></label></td>";
		echo "<td><input type=\"text\" name=\"$name\" id =\"$name\" maxlength=\"10\" size=\"10\" value=\"$value\" onChange=\"update_time_placeholder('begin', 'time');\" />";
		echo " <span id=\"{$name}_placeholder\"></div>";
		echo "</td></tr>\n";
	}



	function show_form ($data, $old_id)
	{
		$deleted = intval($data['deleted_flag']) == 1;
		$moderate_mode = intval($data['deleted_flag']) == -1;
		echo '<form action="/edit/game/" method="post" id="edit">';
		write_mg_datalist();
		echo '<table>';
		if (check_edit_priv())
		{
			echo '<tr><td colspan=2><label><a href="https://docs.google.com/document/pub?id=10ldHSE3Ss3b8co46rd8vyHgePf-Ohw7bhkCIy76Vfrk"><strong>Справка</strong> для редакторов</a></label></td>';
		}
		show_required_tb ('Название игры', 'name', 100, $data['name'], 'text');

		show_regions_dd ($data['sub_region_id']);
		show_uri_tb ('Сайт игры', 'uri', 100, $data['uri']);
		if ($data['id'] == 0)
		{
      show_date_control ('День начала игры', 'begin', $data['begin'], 'time');
      show_length_control ('Продолжительность игры (дней)', 'time', $data['time']);
		}
		else
		{
      $date_obj = new GameDate($data);
      $date_str = $date_obj -> show_date_string(true);
      echo "<tr><td><label><strong>Дата игры</strong></label></td><td>$date_str";
      echo "</td>
      </tr>";
		}
		show_dd ('Тип', 'type', $data['type']);
		show_polygons_dd ($data['polygon']);
		show_tb_with_list ('Мастерская группа', 'mg', 100, $data['mg'], 'mgnames');
		show_email ($data['email'], $data['hide_email']);
		show_tb ('Кол-во игроков', 'players_count', 20, $data['players_count']);
		if (check_edit_priv())
		{
			show_allrpg_info_id ( $data['allrpg_info_id']);
			show_dd ('Настройки', 'show_flags', $data['show_flags']);
			show_dd ('Статус', 'status', $data['status']);
			show_tb ('Комментарий', 'comment', 100, $data['comment']);
			echo "<tr><td>";
			echo "<input type=\"checkbox\" name=\"send_email\" id=\"send_email\" checked value=\"1\"/><label for=\"send_email\">Уведомить мастеров об изменениях.</label>";
			echo "</td>\n";
		}
		

		echo "<td>";
		if (($data['id'] == 0) || $moderate_mode)
		{
			$button_name = 'Добавить';
		}
		else
		{
			if ($deleted)
			{
				$button_name = 'Восстановить';
			}
			else
			{
				$button_name = 'Сохранить';
			}
		}
		submit ($button_name, 'save', $data['id'], '',  TRUE);
		echo "</td></tr>\n";
		echo '</table>';
		if ($old_id)
		{
			echo "<input type=\"hidden\" name=\"old_id\" value=\"$old_id\">";
		}
		if ($moderate_mode)
		{
				echo "<input type=\"hidden\" name=\"moderate_mode\" value=1>";
		}
		echo '</form>';
		if (!$deleted && $data['id'] > 0)
		{
			echo '<form action="/edit/game/" method="post" id="delete"><table>';
			submit ('Удалить игру', 'delete', $data['id'], '', FALSE, 2);
			echo '</table></form>';
		}
		$subregion = intval($data['sub_region_id']);
		echo "<script type=\"text/javascript\">update_subregion($subregion);</script>";
		if ($data['id'] > 0)
		{
      echo "<script type=\"text/javascript\">update_allrpg_info(" .$date_obj -> get_js_string_begin() . ", " .$date_obj -> get_js_string_end() . ");</script>";
		}
		else
		{
      echo "<script type=\"text/javascript\">update_time_placeholder('begin', 'time');</script>";
		}

    if ($data['id'] > 0 && !$moderate_mode)
    {
			show_dates ($data['id'], $data);
			show_review_list($data['id']);
			show_photos($data['id']);
			show_history ($data['id']);
    }
		write_footer();
	}

	function date_action($action, $label, $id, $game_date_id, $order)
	{
     echo "<td><form action=\"\" method=\"post\" id=\"olddate_{$action}_{$game_date_id}\" style=\"display:inline\">";
       echo "<input type=\"hidden\" id=\"order\" name=\"order\" value=\"$order\">";
       submit ($label, "{$action}_date", $id, '', TRUE);
       echo "</form></td>";
	}

	function show_date_row ($date, $id, $count)
	{
     $game_date_id = intval($date['game_date_id']);
     $order = intval($date['order']);
     $order_str = ($order > 0) ? $order : 'Текущая';

     $date_obj = new GameDate($date);
     $date_str = $date_obj -> show_date_string(true);
     echo "<tr><td>$order_str</td><td>$date_str";
     echo "</td>";
     if ($count > 1)
     {
        date_action ('delete', 'Удалить', $id, $game_date_id, $order);
        if ($order > 0)
        {
          date_action ('up', 'Вверх', $id, $game_date_id, $order);
        }
        else
        {
          echo "<td>&nbsp;</td>";
        }
        if ($order < $count - 1)
        {
          date_action ('down', 'Вниз', $id, $game_date_id, $order);
        }
        else
        {
          echo "<td>&nbsp;</td>";
        }
     }
     else
     {
      echo "<td colspan=\"3\">&nbsp;</td>";
     }
     echo "</tr>";
	}

	function do_changedate($id)
	{

    if (get_post_field('save_old_date'))
    {
      do_movedate ($id, get_post_date_field('new_date'), get_post_field('time'));
    }
    else
    {
      do_updatedate ($id, get_post_date_field('new_date'), get_post_field('time'));
    }

    $email = new GameUpdatedEmail ($id, TRUE);
    $email -> send();

    header("Location: /edit/game/?id=$id");
		die();
	}

	function action_deletedate($id)
	{
    $order = get_post_field('order');
    do_deletedate($id, $order);

    if ($order == 0)
    {
      $email = new GameUpdatedEmail ($id, TRUE);
      $email -> send();
    }
    header("Location: /edit/game/?id=$id");
      die();
	}

	function action_change_date_order($id, $sign)
	{
     $order = get_post_field('order');
     do_change_date_order ($id, $order, $sign);
     if (($order == 0) || ($order + $sign == 0))
     {
     $email = new GameUpdatedEmail ($id, TRUE);
      $email -> send();
     }
	}

	function show_dates($id, $data)
	{
    ?>
    <h3>Перенос игры</h3>

     <form action="" method="post" id="gamedate">
      <table>
      <tr>
        <th colspan="3">Перенести на</th>
      </tr>
      <?php
      echo "<tr>
          <td>Дата начала</td>
          <td>";
         show_new_date_control ('new_date', $data['begin']);
         echo "</td>
         <td rowspan=\"2\">";
         submit('Перенести', 'save_date', $id, '', TRUE);
         echo '<br><input name="save_old_date" id="save_old_date" type="checkbox" checked value="1"><label for="save_old_date">Сохранить старую дату в базе</label></td>';
         echo "</tr>
         <tr>
         <td>Продолж-ность (дней)</td>
         <td><input type=\"text\" name=\"time\" id =\"time\" maxlength=\"10\" size=\"10\" value=\"{$data['time']}\" >";

         echo '</tr></table></form>';
    if ($id > 0)
    {
      ?>
      <br>
      <h3>История дат</h3>
      <table>
      <tr>
        <th>Порядок</th><th>Дата начала</th><th colspan="3">Действия</th>
      </tr>
      <?php
      $dates = get_game_dates($id);


      foreach ($dates as $date)
      {
        show_date_row($date, $id, count($dates));
      }

      $date_obj = new GameDate($dates[0]);
      echo '</table>';

      echo '<script type="text/javascript">';
      echo 'update_allrpg_info ( ';
      echo $date_obj -> get_js_string_begin();
      echo ', ';
      echo $date_obj -> get_js_string_end();
      echo ');</script>';
    }


	}

	function show_allrpg_info_id($value)
	{
		$value = htmlspecialchars ($value);
		echo "<tr><td><label><strong>Allrpg.info id</strong></label></td>";
		echo "<td>
      <input type=\"text\" name=\"allrpg_info_id\" id=\"allrpg_info_id\" onChange=\"updateAllrpgInfoLink();\" maxlength=\"20\" size=\"20\" value=\"$value\" />
      <select id=\"allrpg_games\" style=\"visibility:hidden\" onChange=\"updateAllrpgInfo();\"></select>
      <a href=\"\" id=\"allrpg_info_link\" style=\"visibility:hidden\">Профиль</a>
      </td></tr>\n";
	}

	function show_photos($id)
	{
	return;
    $photos = get_photo_by_game_id($id);
		echo "<h3>Фотоотчеты</h3>";
		echo "<table>";
		if (is_array($photos))
		{
      $count = count($photos);
      echo "<tr colspan=\"$count\"><td><a href=\"/photo/$id\">Все фотки</a></td></tr>";
      echo "<tr>";
      foreach ($photos as $photo)
      {
        $photo_id = $photo['photo_id'];
        $photo_author = get_photo_author ($photo);
                $photo_comment = htmlspecialchars($photo['photo_comment']);
        echo "<td>
          <a href=\"{$photo['photo_uri']}\"><img src=\"/photo/preview/$photo_id\" style=\"border:none\"></a>
          <br> $photo_author";
        if (check_my_priv(PHOTO_PRIV) && $photo['author_id'] == 0)
        {
          echo " (<a href=\"/edit/problems/update-author/?author=$photo_author\">Исправить</a>)";
        }
        if ($photo_comment)
        {
          echo "<br> <i>$photo_comment</i>";
        }
        if (check_my_priv(PHOTO_PRIV) || (check_my_priv(PHOTO_SELF_PRIV) && $photo['author_id'] == get_user_id()))
        {
          echo "<br><a href=\"/edit/photo/?id=$photo_id&game_id=$id\">Изменить</a>";
        }
        echo "</td>";
      }
      echo "</tr>";

		}
		else
		{
      $count = 1;
    }
    echo "<tr colspan=\"$count\"><td><a href=\"/edit/photo/?game_id=$id\">Добавить новый</a></td></tr>";
		echo "</table>";
	}

	function show_history($id)
	{

    $calendar = get_updates_for_game($id);
    echo "<h3>История изменений</h3><table>";

    foreach ($calendar as $game)
    {
      write_update_line($game, 1);
    }
    echo '</table>';
	}

	function show_review_list($id)
	{
    $review = new Review ($id);
    $review -> show_edit = TRUE;
    $review -> show();
	}

	function do_addreview($id)
	{
    do_add_game_review ($id, get_post_field ('author'), get_post_field('topic_id'), get_post_field('review_uri'), get_post_field('author_lj'));
    header("Location: /edit/game/?id=$id");
		die();

	}

	function do_deletereview($id)
	{
    do_delete_game_review (get_post_field('review_id'));
    header("Location: /edit/game/?id=$id");
		die();
	}

	function do_save ($id)
	{
    $old = $id > 0;
    $user_add = !check_edit_priv();
    
    if ($user_add)
    {
			$id = do_game_update(
				$id,
				get_post_field ('name'),
				get_post_field ('uri'),
				get_post_field ('type'),
				get_post_field ('polygon'),
				get_post_field ('mg'),
				get_post_field ('email'),
				0,
				0,
				'',
				get_post_field ('sub_region'),
				0,
				get_post_field ('players_count'),
				0,
				0,
				1
			);
    }
    else
    {
			$id = do_game_update(
				$id,
				get_post_field ('name'),
				get_post_field ('uri'),
				get_post_field ('type'),
				get_post_field ('polygon'),
				get_post_field ('mg'),
				get_post_field ('email'),
				get_post_field ('show_flags'),
				get_post_field ('status'),
				get_post_field ('comment'),
				get_post_field ('sub_region'),
				get_post_field ('hide_email'),
				get_post_field ('players_count'),
				get_post_field ('send_email'),
				get_post_field ('allrpg_info_id'),
				0
			);
		}

		$old_id = get_post_field('old_id');
		if ($old_id > 0)
		{
      delete_old_game($old_id);
		}
		if (!$old)
		{
      do_movedate ($id, get_post_date_field('begin'), get_post_field('time'));
		}

		if (get_post_field ('send_email') && !$user_add)
    {
      $email = new GameUpdatedEmail ($id, $old &&!get_post_field('moderate_mode'));
      $email -> send();
    }
    
    if ($user_add)
    {
			$email = new GameReqModerateEmail ($id);
			$email -> send();
    }

		if ($id === FALSE)
		{
			return_to_main();
		}

		header("Location: /edit/game/?id=$id");
		die();
	}

	function load_dict_tables()
	{
		global $region_table;
		global $polygons_table;
		global $year_list;
		static $dict_tables_loaded;

		$sql = connect ();
		if (!$dict_tables_loaded)
		{
			$region_table = $sql -> Query ('
				SELECT ksr.*, kr.region_name
				FROM ki_sub_regions ksr
				INNER JOIN ki_regions kr ON kr.region_id = ksr.region_id
				ORDER BY ksr.sub_region_name');
			$polygons_table = $sql -> Query ('
				SELECT kp.*
				FROM ki_polygons kp
				ORDER BY (1-kp.meta_polygon), kp.polygon_name');
			$year_list = get_year_list_full();
			$dict_tables_loaded = TRUE;
		}
	}

	function parse_old_game($old_id)
	{
      global $msg;
      $old_data = load_old_game($old_id);

      if (!isset($old_data))
      {
        return NULL;
      }
      $data['name'] = preg_replace("~\([0-9]* / [0-9]*\)~", "", $old_data['game_name']);
      $dates = preg_split("/[ -]/", $old_data['game_date']);

      $begin = $dates[0];
      $beg_count = substr_count($begin, ".");

      if ($beg_count == 0)
      {
        $begin = "01.01.$begin";
      }
      elseif ($beg_count == 1)
      {
        $begin = "01.$begin";
      }

      $date2 = $dates[1];
      if ($date2[0] == '(')
      {
        $data['time'] = intval(str_replace("(", "", $date2));
      }
      elseif (count($date2) > 0)
      {
        $diff = strtotime($date2) - strtotime($begin);
        $data['time'] = floor($diff/(60*60*24));
      }
      else
      {
        $data['time'] = 1;
      }

      $data['begin'] = $begin;
      $data['uri'] = $old_data['game_uri'];
      if ($data['uri'] == "0")
      {
        $data['uri'] = '';
      }

      $reg_pos = strpos($old_data['game_region'], ',');
      if ($reg_pos > 0)
      {
        $region_name = substr($old_data['game_region'], $reg_pos + 1);
      }
      else
      {
        $region_name = $old_data['game_region'];
      }
        $sub_region = try_to_find_region ($region_name);
        if ($sub_region)
        {
          $data['sub_region_id'] = $sub_region;
        } else {
          $msg= "<p><strong style=\"color:red\">Регион не распознан!</strong> Имя региона: «{$old_data['game_region']}»</p>";
        }

      $data['status'] = 1;

      return $data;
	}

	function do_edit ($id)
	{
		global $region_table;
		global $polygons_table;


		if ($id)
		{
			$data = get_game_for_edit($id);
      $old_id = NULL;
		} else
		{
      $old_id = array_key_exists ('old_id', $_GET) ? intval($_GET['old_id']) : 0;
      $data = parse_old_game($old_id);
		}

		if (isset($data))
		{
      $hdr = $data['name'];
		}
		else
		{
      $hdr = 'Добавление новой игры';
    }
    write_header("Kogra-igra.Ru — $hdr", true);
    
    global $msg;
    if ($msg)
    {
      echo $msg;
    }
		load_dict_tables();

		write_js_table($region_table, 'tbl_subregions');
		write_js_table($polygons_table, 'tbl_polygons');
		if (isset($data))
		{
      echo "<h1><a href=\"/game/$id\">$hdr</a></h1>";
    }
    else
    {
      echo "<h1>$hdr</h1>";
    }
		show_greeting();
		if (isset($data))
		{
			show_form ($data, $old_id);
		}
		else
		{
			$id = 0;
			show_form (null);
		}

	}

function request_edit_priv()
{
	if (!check_edit_priv())
	{
		return_to_main();
	}
}

// MAIN

	$id = array_key_exists ('id', $_POST) ? intval($_POST['id']) : (array_key_exists ('id', $_GET) ? intval($_GET['id']) : 0) ;
	$action = array_key_exists ('action', $_POST) ? $_POST['action'] : '';

	switch ($action)
	{
		case 'delete':
			request_edit_priv();
			if (do_game_delete ($id) === FALSE)
			{
				return_to_main();
			}
			else
			{
				header("Location: /edit/game/?id=$id");
				die();
			}
			break;
		case 'save':
			if ($id)
			{
				request_edit_priv();
			}
			do_save($id);
			break;
		case 'add_review':
			request_edit_priv();
			do_addreview($id);
			break;
		case 'delete_review':
			request_edit_priv();
			do_deletereview($id);
			break;
		case 'save_date':
			request_edit_priv();
			do_changedate($id);
			break;
		case 'delete_date':
			request_edit_priv();
			action_deletedate($id);
			break;
		case 'up_date':
			request_edit_priv();
			action_change_date_order($id, -1);
			break;
		case 'down_date':
			request_edit_priv();
			action_change_date_order($id, +1);
			break;
		default:
			if ($id)
			{
				request_edit_priv();
			}
			do_edit($id);
	}
?>