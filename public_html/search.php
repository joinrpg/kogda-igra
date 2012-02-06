<?php


	$search_string = array_key_exists('search', $_REQUEST) ? trim($_REQUEST['search']) : '';
	
	header("Location: /find/$search_string");
	die();

	?>