<?php
	$sql_server = 	$_ENV['POSTGRES_SERVER'];
	$sql_user = 	$_ENV['POSTGRES_USER'];
	$sql_pass =  	$_ENV['POSTGRES_PASSWORD'];
	$sql_db =  		$_ENV['POSTGRES_DATABASE'];
	$sql_port =		$_ENV['POSTGRES_PORT'];
	
	define ("SITENAME_EDITORS_EMAIL", 'rpg@kogda-igra.ru');
	define ("SITENAME_MAIN", "КогдаИгра");
	define ("SITENAME_SIGNATURE", "Редакторы КогдаИгры");
	define ("SITENAME_HOST", array_key_exists("SITENAME_HOST", $_ENV) ? $_ENV["SITENAME_HOST"] : "kogda-igra.ru");
	define ("SITENAME_SCHEME", "https");
	define ("GA_ANALYTICS", "UA-1194519-2");
	
?>
