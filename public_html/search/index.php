<?php
	require_once 'funcs.php';
	require_once 'mysql.php';
	require_once 'common.php';
	require_once 'user_funcs.php';
	require_once 'logic.php';
	require_once 'calendar.php';

	$search_string = array_key_exists('search', $_GET) ? trim($_GET['search']) : '';
	
  $result = get_search($search_string);
  
  write_header ("Ролевые игры — поиск");
	echo '<h1>Ролевые игры :: Поиск</h1>';
	show_greeting();

  echo "<p><strong>Замечания, предложения по работе поиска?</strong><br> <a href=\"http://community.livejournal.com/kogda_igra/4860.html\">Оставь комментарий в ЖЖ!</a></p>";
  show_search_form(htmlspecialchars  ($search_string));
  echo '<br />';
  
	$calendar = new Calendar($result);
	$calendar -> show_reviews = TRUE;
	$calendar -> write_calendar();

	write_footer(TRUE, "'/search/'");

	?>