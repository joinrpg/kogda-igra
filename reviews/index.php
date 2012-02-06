<?php
	require_once 'logic/gamelist.php';
	require_once 'funcs.php';
	require_once 'calendar.php';

// MAIN

  write_header('Игры с рецензиями');
	echo '<h1>Игры с рецензиями</h1>';
	show_greeting();
	$calendar = new Calendar(get_reviewed_games());
	$calendar -> show_reviews = TRUE;
	$calendar -> write_calendar();

write_footer(TRUE);
?>