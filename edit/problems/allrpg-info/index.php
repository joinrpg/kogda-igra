<?php
	require_once 'funcs.php';
	require_once 'logic/problems.php';
	require_once 'calendar.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

	write_header('Проблемные игры :: Нет ссылки на allrpg.info');
	echo '<h1>Проблемные игры :: Нет ссылки на allrpg.info</h1>';
	show_greeting();
	$list = get_noallrpg_info_games();
	$calendar = new Calendar($list);
	$calendar -> write_calendar();
	
	write_footer();

	?>