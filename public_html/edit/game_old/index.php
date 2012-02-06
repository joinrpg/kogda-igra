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

	if (!check_edit_priv())
		return_to_main();

	function message ($msg)
	{
		write_header ('Ролевые игры 2008 Санкт-Петербург');
		echo "<p>$msg</p>";
		write_footer();
	}

	function show_email ($value, $hide_email)
	{
		$value = htmlspecialchars ($value);
		echo "<tr><td><label><strong>E-mail</strong></label></td>";
		echo "<td><input type=\"text\" name=\"email\" maxlength=\"100\" size=\"100\" value=\"$value\" />";
		echo "<br/>";
		$checked = $hide_email ? ' checked="checked" ' : '';
		echo "<input type=\"checkbox\" name=\"hide_email\"$checked id=\"hide_email\" value=\"1\"/><label for=\"hide_email\">Спрятать</label>";
		echo "</td></tr>\n";
	}

	function show_dd ($label, $name, $value)
	{
		echo "<tr><td><label><strong>$label</strong></label></td>";
		$array = get_array ($name);
		echo "<td><select name=\"$name\" size=\"1\">";
		foreach ($array as $key => $kname)
		{
			if ($key == $value)
				echo "<option value=\"$key\" selected=\"selected\">$kname</option>";
			else
				echo "<option value=\"$key\">$kname</option>";
		}
		echo "</select></td>";
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

	function show_date_control($label, $name, $value, $len)
	{
    load_dict_tables();
    global $year_list;
    $value = getdate (strtotime($value));
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

	function show_form ($data)
	{
		$deleted = intval($data['deleted_flag']) != 0;
		echo '<form action="/edit/game/" method="post" id="edit"> <table>';
		show_tb ('Название игры', 'name', 100, $data['name']);

		show_regions_dd ($data['sub_region_id']);
		show_tb ('Сайт игры', 'uri', 100, $data['uri']);
		show_date_control ('День начала игры', 'begin', $data['begin'], 'time');
		show_length_control ('Продолжительность игры (дней)', 'time', $data['time']);
		show_dd ('Тип', 'type', $data['type']);
		show_polygons_dd ($data['polygon']);
		show_tb ('Мастерская группа', 'mg', 100, $data['mg']);
		show_email ($data['email'], $data['hide_email']);
		show_tb ('Кол-во игроков', 'players_count', 20, $data['players_count']);
		show_allrpg_info_id ( $data['allrpg_info_id']);
		show_dd ('Настройки', 'show_flags', $data['show_flags']);
		show_dd ('Статус', 'status', $data['status']);
		show_tb ('Комментарий', 'comment', 100, $data['comment']);
		echo "<tr><td>";
		echo "<input type=\"checkbox\" name=\"send_email\" id=\"send_email\" checked value=\"1\"/><label for=\"send_email\">Уведомить мастеров об изменениях.</label>";
		echo "</td>\n";
		echo "<td>";
		submit ('Сохранить', 'save', $data['id'], $deleted ? ' Нажмите «Сохранить» для восстановления удаленной игры' : '',  TRUE);
		echo "</td></tr>\n";
		echo '</table>';
		echo '</form>';
		if (!$deleted)
		{
			echo '<form action="/edit/game/" method="post" id="delete"><table>';
			submit ('Удалить игру', 'delete', $data['id'], '', FALSE, 2);
			echo '</table></form>';
		}
		$subregion = intval($data['sub_region_id']);
		echo "<script type=\"text/javascript\">update_subregion($subregion);</script>";
		echo "<script type=\"text/javascript\">update_time_placeholder('begin', 'time');</script>";

		show_review_list($data['id']);
		show_photos($data['id']);

		show_history ($data['id']);



		write_footer();
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
        echo "<td>
          <a href=\"{$photo['photo_uri']}\"><img src=\"/photo/preview/$photo_id\" style=\"border:none\"></a>
          <br> {$photo['photo_author']}
          <br><a href=\"/edit/photo/?id=$photo_id&game_id=$id\">Изменить</a>
        </td>";
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
    do_add_game_review ($id, get_post_field ('author'), get_post_field('topic_id'), get_post_field('review_uri'));
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
		$id = do_game_update(
			$id,
			get_post_field ('name'),
			get_post_field ('uri'),
			get_post_date_field ('begin'),
			get_post_field ('time'),
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
			get_post_field ('allrpg_info_id')
		);

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

	function do_edit ($id)
	{
		global $sql;
		global $region_table;
		global $polygons_table;


		if ($id)
			$data = $sql -> GetObject ('ki_games', $id);

		if (isset($data))
		{
      $hdr = $data['name'];
		}
		else
		{
      $hdr = 'Добавление новой игры';
    }
    write_header("Kogra-igra.Ru — $hdr");

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
      echo "<p>Профиль</a></p>";
			show_form ($data);
		}
		else
		{
			$id = 0;
			show_form (null);
		}

	}
global $sql;
$sql = connect();


// MAIN
	$id = array_key_exists ('id', $_POST) ? intval($_POST['id']) : (array_key_exists ('id', $_GET) ? intval($_GET['id']) : 0) ;

	$action = array_key_exists ('action', $_POST) ? $_POST['action'] : 0;

	if ($action === 'delete')
	{
		if (do_game_delete ($id) === FALSE)
		{
			return_to_main();
		}
		else
		{
			header("Location: /edit/game/?id=$id");
			die();
		}
	}
	elseif ($action === 'save')
		do_save ($id);
	elseif ($action === 'add_review')
    do_addreview($id);
  elseif ($action === 'delete_review')
    do_deletereview($id);

	do_edit ($id);


?>