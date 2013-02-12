<?php
	require_once 'funcs.php';
	require_once 'logic.php';
	require_once 'calendar.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

	$topmenu = new TopMenu();
	$topmenu -> pagename = 'Проблемные игры :: Нет полигона';
	$topmenu -> show();
	
	$calendar = new Calendar(get_nopolygon_games());
	$calendar -> write_calendar();
	
	write_footer(); 

	?>