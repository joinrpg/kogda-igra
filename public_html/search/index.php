<?php
	require_once 'funcs.php';
	require_once 'logic/search.php';
	require_once 'calendar.php';
	require_once 'top_menu.php';

	$search_string = array_key_exists('search', $_GET) ? trim($_GET['search']) : '';
	
  	$result = get_search($search_string);
  
	$topmenu = new TopMenu();
	$topmenu -> pagename = 'Поиск';
	$topmenu -> search = htmlspecialchars($search_string);
	$topmenu -> show();
  
	$calendar = new Calendar($result);
	$calendar -> show_reviews = TRUE;
	$calendar -> write_calendar();
	
	echo "<p><strong>Замечания, предложения по работе поиска?</strong> Напиши $mailto_editors!";
	write_footer();

	?>