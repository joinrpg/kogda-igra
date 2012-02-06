<?php
	require_once 'funcs.php';
	require_once 'logic.php';
  require_once 'calendar.php';
  require_once 'logic/edit.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}
	
	$action = array_key_exists ('action', $_POST) ? $_POST['action'] : 0;

	$mark = array_key_exists ('mark', $_POST) ? $_POST['mark'] : 0;
	
if ($action == 'mark' && is_array($mark))
  {
    foreach ($mark as $checkbox)
    {
      mark_as_passed ($checkbox);
    }
    header('Location: /edit/problems/passed');
    die();
  }
  
	write_header('Проблемные игры');
	echo '<h1>Проблемные игры :: Прошедшие игры</h1>';
	show_greeting();
	$calendar = get_passed_games();
	echo '<form action="" method="post" id="mark">';
	$colspan = write_calendar_header(TRUE);

	foreach ($calendar as $game)
	{
		write_calendar_entry ($game, $colspan, FALSE, TRUE);
	}
	echo '</table><br />';
	echo '<input type="hidden" name="action" value="mark" />';
	echo '<div style="text-align:right"><input type="submit" value="Отметить как прошедшие" /></div>';
  echo '</form>';
	
	write_footer();

	?>