<?php
	$sql_server = 	$_ENV['POSTGRES_SERVER'];
	$sql_user = 	$_ENV['POSTGRES_USER'];
	$sql_pass =  	$_ENV['POSTGRES_PASSWORD'];
	$sql_db =  		$_ENV['POSTGRES_DATABASE'];
	$sql_port =		@$_ENV['POSTGRES_PORT'];
	
	define ("MAILGUN_KEY", array_key_exists("MAILGUN_KEY", $_ENV) ? $_ENV["MAILGUN_KEY"] : "");
	define ("SITENAME_MAIN", "КогдаИгра");
	define ("SITENAME_SIGNATURE", "Редакторы КогдаИгры");
	define ("SITENAME_HOST", array_key_exists("SITENAME_HOST", $_ENV) ? $_ENV["SITENAME_HOST"] : "kogda-igra.ru");
	define ("SITENAME_EDITORS_EMAIL", 'rpg@' . SITENAME_HOST);
	define ("SITENAME_EDITORS_BOT", 'kogda_igra_bot');
	define ("SITENAME_SCHEME", "https");
	define ("YA_METRIKA_ID", 100288537);
	
?>
