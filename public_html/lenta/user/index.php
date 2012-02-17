<?php
	require_once 'funcs.php';
	require_once 'logic.php';
	require_once 'show_updates.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

  $id = array_key_exists ('id', $_GET) ? intval($_GET['id']) : 0;
	if (!$id)
	{
    return_to_main();
	}
	
  $editor = get_user_by_id($id);
  $header = "Лента изменений {$editor['username']}";
	write_header($header);
		$topmenu = new TopMenu();
	$topmenu -> pagename = $header;
	$topmenu -> show();
	$updates = get_updates_by_user_id ($id);
	echo '<table>';
	foreach ($updates as $item)
  {
    write_update_line($item, 1);
  }
	echo '</table>';
	
	write_footer();

	?>