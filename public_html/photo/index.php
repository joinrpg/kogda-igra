<?php
	require_once 'review.php';
	require_once 'funcs.php';
	require_once 'calendar.php';
	require_once 'logic/gamelist.php';

// MAIN

  write_header('Игры с фототчетами');
	echo '<h1>Игры с фототчетами</h1>';
	show_greeting();
	$calendar = new Calendar(get_photo_games());
	$calendar -> write_calendar();

write_footer(TRUE);
?>