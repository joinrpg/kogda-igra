<?php
	require_once 'funcs.php';
	require_once 'logic.php';
	require_once 'calendar.php';
	require_once 'top_menu.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

	$topmenu = new TopMenu();
	$topmenu -> pagename = 'Проблемные игры :: Нет e-mail';
	$topmenu -> show();
	
	$calendar = new Calendar(get_noemail_games());
	$calendar -> write_calendar();
	
	write_footer();

	?>