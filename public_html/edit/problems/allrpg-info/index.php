<?php
	require_once 'funcs.php';
	require_once 'logic/problems.php';
	require_once 'calendar.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

	$topmenu = new TopMenu();
	$topmenu -> pagename = 'Проблемные игры :: Нет ссылки на allrpg.info';
	$topmenu -> show();
	
	$calendar = new Calendar(get_noallrpg_info_games());
	$calendar -> write_calendar();
	
	write_footer();

	?>