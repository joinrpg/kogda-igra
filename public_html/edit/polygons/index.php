<?php
	require_once 'funcs.php';
	require_once 'user_funcs.php';
	require_once 'logic/polygons.php';
	require_once 'logic/dictionary.php';
	require_once 'forms.php';
	require_once 'top_menu.php';
	
	if (!check_my_priv(EDIT_POLYGONS_PRIV))
		return_to_main();



	function do_delete ($id)
	{

		if ($id == 0)
			return_to_main();

		do_polygon_delete ($id);
		do_edit ("Полигон удален");
	}

	function do_save ($id)
	{
		do_polygon_update ($id, 
			get_post_field ('polygon_name'),
			get_post_field ('sub_region_id'));
		
		do_edit('Полигон добавлен');
	}
	
		function show_regions_dd ($region_table, $value)
	{
		echo "<select name=\"sub_region_id\" size=\"1\">";
		foreach ($region_table as $row)
		{
			write_option ($row['sub_region_id'], $row['sub_region_id'] == $value, $row['sub_region_name']);
		}
		echo "</select>";
	}

	function do_edit ($msg = FALSE)
	{
		global $sql;
		$topmenu = new TopMenu();
		$topmenu -> pagename = 'Панель управления :: Полигоны';
		$topmenu -> show();
		
		echo '<h1>Панель управления :: Полигоны</h1>';
		
		if ($msg)
		{
			echo "<p>$msg</p>";
		}
		$data = $sql -> Query ('SELECT kp.polygon_id, kp.polygon_name, kp.sub_region_id, kp.meta_polygon, COUNT(kg.id) AS game_present, kr.region_name
		FROM ki_polygons kp
		INNER JOIN ki_sub_regions ksr ON ksr.sub_region_id = kp.sub_region_id
		INNER JOIN ki_regions kr ON kr.region_id = ksr.region_id
		LEFT JOIN ki_games kg ON kp.polygon_id = kg.polygon
		WHERE kp.meta_polygon = 0
		GROUP BY kp.polygon_id, kp.polygon_name, kp.sub_region_id, ksr.sub_region_name, kp.meta_polygon, kr.region_name
		ORDER BY kr.region_name, ksr.sub_region_name,  kp.polygon_name');
	
	$region_table = get_region_dict ();
	
		echo '<table>';
		echo '<tr><td colspan="2">';
			echo '<form action="" method="post" id="add"> ';
			echo "<label>Полигон:&nbsp;</label>";
			echo "<input type=\"hidden\" name=\"id\" value=\"0\"/>";
			echo "<input type=\"hidden\" name=\"action\" value=\"save\"/>";
			echo "<input type=\"text\" name=\"polygon_name\" maxlength=\"50\" size=\"50\" value=\"\" />&nbsp;&nbsp;";
			show_regions_dd ($region_table, 1);
			echo "&nbsp;&nbsp;<input type=\"submit\" name=\"save\" value=\"Добавить\"/>";
			echo '</form>';
			echo '</td></tr>';
		if (is_array($data))
		foreach ($data as $row)
		{

			$id = $row['polygon_id'];
			$name = htmlspecialchars($row['polygon_name']);
			$sub_region_id = $row['sub_region_id'];
			$game_present = $row['game_present'] > 0;
			$meta = $row['meta_polygon'] > 0 ? 'checked' : '';
			echo '<tr>';
			if ($game_present)
			{
				echo '<td colspan="2">';
			}
			else
			{
				echo '<td>';
			}
			echo "<form action=\"\" method=\"post\" id=\"edit$id\">";
			echo "<label>Полигон:&nbsp;</label>";
			echo "<input type=\"hidden\" name=\"id\" value=\"$id\"/>";
			echo "<input type=\"hidden\" name=\"action\" value=\"save\"/>";
			echo "<input type=\"text\" name=\"polygon_name\" maxlength=\"50\" size=\"50\" value=\"$name\" />&nbsp;&nbsp;";
			show_regions_dd ($region_table, $sub_region_id);
			echo "&nbsp;&nbsp;<input type=\"submit\" name=\"save\" value=\"Сохранить\"/>";
			if (!$game_present)
			{

				echo '</form></td>';
				echo "<td><form action=\"\" method=\"post\" id=\"delete$id\">";
				echo "<input type=\"hidden\" name=\"action\" value=\"delete\"/>";
				echo "<input type=\"hidden\" name=\"id\" value=\"$id\"/>";
				echo "<input type=\"submit\" name=\"delete\" value=\"Удалить\"/>";
			}
			echo '</form>';
			echo '</td></tr>';
		}

			echo '</table>';

	}
	
	global $sql;
	$sql = connect();


// MAIN
	$id = array_key_exists ('id', $_POST) ? $_POST['id'] : 0 ;

	$action = array_key_exists ('action', $_POST) ? $_POST['action'] : 0;

	if ($action === 'delete')
		do_delete ($id);
	elseif ($action === 'save')
		do_save ($id);
	else
		do_edit ();


?>