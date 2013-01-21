<?php
	require_once 'top_menu.php';
	require_once 'funcs.php';
	require_once 'calendar.php';
	require_once 'logic/gamelist.php';

// MAIN

  write_header('Игры с фото/видео');
	$topmenu = new TopMenu();
	$topmenu -> pagename = 'Фото/видео';
	$topmenu -> show();
	$calendar = new Calendar(get_photo_games());
	$calendar -> write_calendar();

write_footer(TRUE);
?>