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
  $header = "Лента изменений :: Редактор {$editor['username']}";
	write_header($header);
	echo "<h1>$header</h1>";
	show_greeting();
	$updates = get_updates_by_user_id ($id);
	echo '<table>';
	foreach ($updates as $item)
  {
    write_update_line($item, 1);
  }
	echo '</table>';
	
	write_footer();

	?>