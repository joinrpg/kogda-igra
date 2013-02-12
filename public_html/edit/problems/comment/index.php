<?php
	require_once 'funcs.php';
	require_once 'logic/problems.php';
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
      clear_comment ($checkbox);
    }
    header('Location: /edit/problems/comment/');
    die();
  }

	$topmenu = new TopMenu();
	$topmenu -> pagename = 'Проблемные игры :: Есть комментарий';
	$topmenu -> show();
	
	echo '<form action="" method="post" id="mark">';
	
	$calendar = new Calendar(get_games_with_comment());
	$calendar -> use_checkbox = TRUE;
	$calendar -> write_calendar();
	
	echo '</table><br />';
	echo '<input type="hidden" name="action" value="mark" />';
	echo '<div style="text-align:right"><input type="submit" value="Стереть комментарий" /></div>';
  echo '</form>';
	
	write_footer();

	?>