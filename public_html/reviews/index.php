<?php
	require_once 'logic/gamelist.php';
	require_once 'funcs.php';
	require_once 'calendar.php';
	require_once 'top_menu.php';

// MAIN

  write_header('Игры с рецензиями');
  
	$topmenu = new TopMenu();
	$topmenu -> pagename = 'Рецензии';
	$topmenu -> show();
	
	$calendar = new Calendar(get_reviewed_games());
	$calendar -> show_reviews = TRUE;
	$calendar -> write_calendar();

write_footer(TRUE);
?>