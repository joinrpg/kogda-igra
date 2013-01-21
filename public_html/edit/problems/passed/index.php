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
  
	write_header('Kogda-igra.Ru :: Проблемные игры :: Прошедшие?');
	$topmenu = new TopMenu();
	$topmenu -> pagename = 'Kogda-igra.Ru :: Проблемные игры :: Прошедшие?';
	$topmenu -> show();
	
	echo '<form action="" method="post" id="mark">';
	$calendar = new Calendar(get_passed_games());
	$calendar -> use_checkbox = TRUE;
	$calendar -> write_calendar();
	echo '</table><br />';
	echo '<input type="hidden" name="action" value="mark" />';
	echo '<div style="text-align:right"><input type="submit" value="Отметить как прошедшие" /></div>';
  echo '</form>';
	
	write_footer();

	?>