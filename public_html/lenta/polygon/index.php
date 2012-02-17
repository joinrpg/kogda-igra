<?php
	require_once 'funcs.php';
	require_once 'logic.php';
	require_once 'show_updates.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

	write_header('Лента изменений :: Полигоны');
		$topmenu = new TopMenu();
	$topmenu -> pagename = 'Лента изменений :: Полигоны';
	$topmenu -> show();
	
	$updates = get_polygon_updates_24hr ();
	echo '<table>';
	foreach ($updates as $item)
  {
    write_update_line($item, 1);
  }
	echo '</table>';
	
	write_footer();

	?>