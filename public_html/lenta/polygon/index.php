<?php
	require_once 'funcs.php';
	require_once 'logic.php';
	require_once 'show_updates.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

	write_header('Лента изменений :: Полигоны');
	echo '<h1>Лента изменений :: Полигоны</h1>';
	show_greeting();
	$updates = get_polygon_updates_24hr ();
	echo '<table>';
	foreach ($updates as $item)
  {
    write_update_line($item, 1);
  }
	echo '</table>';
	
	write_footer();

	?>