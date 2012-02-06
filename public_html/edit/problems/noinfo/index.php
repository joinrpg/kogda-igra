<?php
	require_once 'funcs.php';
	require_once 'logic.php';
	require_once 'calendar.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

	write_header('Проблемные игры');
	echo '<h1>Проблемные игры :: Неясный статус</h1>';
	show_greeting();
	$calendar = get_problem_games();
	$colspan = write_calendar_header(TRUE);

	foreach ($calendar as $game)
	{
		write_calendar_entry ($game, $colspan, FALSE);
	}
	echo '</table>';
	
	write_footer();

	?>