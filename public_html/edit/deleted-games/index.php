<?php
	require_once 'funcs.php';
	require_once 'logic/gamelist.php';
	require_once 'calendar.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

	write_header('Удаленные игры');
	echo '<h1>Удаленные игры</h1>';
	show_greeting();
	$list = get_deleted_games();
	$calendar = new Calendar($list);
	$calendar -> write_calendar();
	
	write_footer();

	?>