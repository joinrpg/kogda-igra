<?php
	require_once 'funcs.php';
	require_once 'logic.php';
	require_once 'calendar.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

	$hdr = 'Проблемные игры :: Нет email';
	$topmenu = new TopMenu();
	$topmenu -> pagename = $hdr;
	$topmenu -> show();
	
	
	$calendar = new Calendar(get_noemail_games(TRUE));
	$calendar -> write_calendar();
	
	write_footer();

	?>