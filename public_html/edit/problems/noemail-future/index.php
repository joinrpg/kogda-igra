<?php
	require_once 'funcs.php';
	require_once 'logic.php';
	require_once 'calendar.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

	write_header('Проблемные игры :: Нет email');
	echo '<h1>Проблемные игры :: Нет email</h1>';
	show_greeting();
	$calendar = get_noemail_games(TRUE);
	$colspan = write_calendar_header(TRUE);

	foreach ($calendar as $game)
	{
		write_calendar_entry ($game, $colspan, FALSE);
	}
	echo '</table>';
	
	write_footer();

	?>