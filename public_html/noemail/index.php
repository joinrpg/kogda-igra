<?php
	require_once 'funcs.php';
	require_once 'logic.php';
	require_once 'calendar.php';

	$topmenu = new TopMenu();
	$topmenu -> pagename = 'Проблемные игры :: Нет e-mail';
	$topmenu -> show();
	
	$calendar = new Calendar(get_noemail_games());
	$calendar -> write_calendar();
	
	write_footer();

	?>