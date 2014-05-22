<?php
	require_once 'funcs.php';
	require_once 'user_funcs.php';
	require_once 'logic.php';
	require_once 'calendar.php';
	require_once 'review.php';
	require_once 'uifuncs.php';
	require_once 'forms.php';
	require_once 'logic/edit.php';
	require_once 'show_updates.php';
	require_once 'email.php';
	require_once 'logic/review.php';
	require_once 'logic/datalist.php';
	require_once 'logic/dictionary.php';
	require_once 'top_menu.php';
	require_once 'uri_funcs.php';


	function show_email ($value, $hide_email)
	{
		$value = htmlspecialchars ($value);
		echo "<tr><td><label><strong>E-mail</strong></label></td>";
		echo "<td><input type=\"email\" name=\"email\" id=\"email\" list=\"emaillist\" autocomplete=off maxlength=\"100\" size=\"30\" value=\"$value\" onchange=\"this.value=this.value.trim()\"/>";
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
			echo "<option value=\"{$row['sub_region_id']}\" $selected >{$row['region_name']} - {$row['sub_region_name']}</option>";
		}
		echo "</select>";
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

	function show_date_control_internals ($name, $date_value, $len)
	{
		$now = getdate();
		$selected_year = $date_value ? intval($value['year']) : $now['year'];
    $value = getdate (strtotime($date_value));
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
        write_option($year, $year == $selected_year, $year);
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
		echo "<td><input type=\"text\" name=\"$name\" id =\"$name\" maxlength=\"10\" size=\"10\" required value=\"$value\" onChange=\"length_change('begin', 'time');\" />";
		echo " <span id=\"{$name}_placeholder\"></div>";
		echo "</td></tr>\n";
	}



	function show_form ($data, $old_id, $add_uri_id)
	{
		$deleted = intval($data['deleted_flag']) == 1;
		$moderate_mode = intval($data['deleted_flag']) == -1;
		
		echo '<div class="editblock">';
		echo '<form action="/edit/game/" method="post" id="edit">';
		write_mg_datalist();
		echo '<table class="edit_table">';
		
		$msg = check_edit_priv() 
			? '<a href="https://docs.google.com/document/pub?id=10ldHSE3Ss3b8co46rd8vyHgePf-Ohw7bhkCIy76Vfrk"><b>Справка</b> для редакторов</a>'
			: 'Или <b>заполните</b> форму ниже:';
		
		echo "<tr><td colspan=2><label>$msg</label></td>";
		
		show_required_tb ('Название игры', 'name', 100, $data['name'], 'text');
		$sub_region_id = $data['sub_region_id'];
		$sub_region_id = 
		show_regions_dd ($sub_region_id ? $sub_region_id  : 4 );
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
		show_tb ('Группа вконтакте', 'vk_club', 40, $data['vk_club'], 'uri', false, '', 'http://vk.com/');
		show_tb ('Сообщество ЖЖ', 'lj_comm', 40, $data['lj_comm'], 'uri', false, '', 'http://', '.livejournal.com/profile');
		if (check_edit_priv())
		{
			show_allrpg_info_id ( $data['allrpg_info_id']);
			show_dd ('Настройки', 'show_flags', $data['show_flags']);
			show_dd ('Статус', 'status', $data['status']);
			show_tb ('Комментарий', 'comment', 100, $data['comment']);
		}
		echo '<tr><td colspan=2>';

		
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
		if (check_edit_priv())
		{
			echo "<input type=\"checkbox\" name=\"send_email\" id=\"send_email\" checked value=\"1\"/><label for=\"send_email\">Уведомить мастеров об изменениях.</label>";
		}
		echo "</td></tr>\n";
		echo '</table>';
		if ($old_id)
		{
			echo "<input type=\"hidden\" name=\"old_id\" value=\"$old_id\">";
		}
		if ($add_uri_id)
		{
			echo "<input type=\"hidden\" name=\"add_uri_id\" value=\"$add_uri_id\">";
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
		
		if ($add_uri_id)
		{
			echo '<form method="post" id="resolve_no_add"><table>';
			submit ('Не добавлять', 'resolve_no_add', $add_uri_id, '', FALSE, 2);
			echo '</table></form>';
		}
		
		echo '</div>';
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

    if ($data['id'] > 0)
    {
			show_history ($data['id']);
			if (!$moderate_mode)
			{
				
				echo '<br style="clear:both">';
				show_dates ($data['id'], $data);
				show_review_list($data['id']);
				
			}
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
			$dates = get_game_dates($id);
			if (count($dates) > 1)
			{
				?>
				<h3>История дат</h3>
				<table>
				<tr>
					<th>Порядок</th><th>Дата начала</th><th colspan="3">Действия</th>
				</tr>
				<?php
				

				foreach ($dates as $date)
				{
					show_date_row($date, $id, count($dates));
				}
				echo '</table>';
      }
			$date_obj = new GameDate($dates[0]);
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

	function show_history($id)
	{
		echo "<div class=\"history\">";
    $calendar = get_updates_for_game($id);
    echo '<table>';
		echo "<tr><th>История изменений</th></tr>";
    foreach ($calendar as $game)
    {
      write_update_line($game, 1);
    }
    echo '</table>';
    echo '</div>';
	}

	function show_review_list($id)
	{
    $review = new ReviewEdit ($id);
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
	
		function do_restorereview($id)
	{
    do_restore_game_review (get_post_field('review_id'));
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
				1,
				get_post_field ('vk_club'),
				get_post_field ('lj_comm')
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
				0,
				get_post_field ('vk_club'),
				get_post_field ('lj_comm')
			);
		}

		$old_id = get_post_field('old_id');
		$add_uri_id = get_post_field('add_uri_id');
		if ($old_id > 0)
		{
      delete_old_game($old_id);
		}
		if ($add_uri_id)
		{
			resolve_add_uri($add_uri_id);
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
			$region_table = get_region_dict ();
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
      $add_uri_id = NULL;
		} else
		{
      $old_id = array_key_exists ('old_id', $_GET) ? intval($_GET['old_id']) : 0;
      $add_uri_id = array_key_exists ('add_uri_id', $_GET) ? intval($_GET['add_uri_id']) : 0;
      if ($old_id)
      {
				$data = parse_old_game($old_id);
			}
			if ($add_uri_id)
			{
				$add = get_added_uri($add_uri_id);
				if ($add['resolved'])
				{
					redirect_to('/edit/already');
				}
				$data = array();
				$data['uri'] = $add['uri'];
				$data['allrpg_info_id'] = $add['allrpg_info_id'];
			}
		}

		if (isset($data) && array_key_exists('name', $data))
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
		
		$hdr = $id ? "<a href=\"/game/$id\">$hdr</a>" : $hdr;

		$topmenu = new TopMenu();
		$topmenu -> pagename = $hdr;
		$topmenu -> show_add_adv = false;
		$topmenu -> show();
		
		if (isset($data))
		{
			show_form ($data, $old_id, $add_uri_id);
		}
		else
		{
					echo "<table>
				<tr><th>Добавьте ссылку</th></tr>
				<tr><td>Вы можете просто добавить ссылку на анонс, и наши редакторы разберутся с остальным:</td><tr>
				<tr><td><form method=post action=\"/api/game/add.php\"><input type=uri name=uri size=100 maxlength=100 required><input type=submit value=\"Добавить\"></form></td><tr>
				</table>";
			$id = 0;
			show_form (null, null, null);
			

		}

	}

function request_edit_priv()
{
	if (!check_edit_priv())
	{
		return_to_main();
	}
}

function action_resolve_no_add($id)
{
			if ($id)
		{
			resolve_add_uri($id);
		}
	redirect_to('/edit/');
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
		case 'restore_review':
			request_edit_priv();
			do_restorereview($id);
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
		case 'resolve_no_add':
			request_edit_priv();
			action_resolve_no_add($id);
		default:
			if ($id)
			{
				request_edit_priv();
			}
			do_edit($id);
	}
?>