<?php
	require_once 'funcs.php';
	require_once 'logic.php';
	require_once 'calendar.php';

	write_header('Проблемные игры :: Нет e-mail');
	echo '<h1>Проблемные игры :: Нет e-mail</h1>';
	show_greeting();
	$calendar = get_noemail_games();
	$colspan = write_calendar_header(TRUE);

	foreach ($calendar as $game)
	{
		write_calendar_entry ($game, $colspan, FALSE);
	}
	echo '</table>';
	
	write_footer();

	?>