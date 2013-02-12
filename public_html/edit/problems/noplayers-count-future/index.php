<?php
	require_once 'funcs.php';
	require_once 'logic/problems.php';
	require_once 'calendar.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

	$topmenu = new TopMenu();
	$topmenu -> pagename = 'Проблемные игры :: Нет кол-ва игроков';
	$topmenu -> show();
	
	$calendar = new Calendar(get_noplayers_count_games(TRUE));
	$calendar -> write_calendar();
	
	write_footer(); 

	?>