<?php
	require_once 'funcs.php';
	require_once 'logic.php';
	require_once 'show_updates.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

	write_header('Лента изменений :: Внутреняя');
	echo '<h1>Лента изменений :: Внутреняя</h1>';
	show_greeting();
	$updates = get_updates_24hr ();
	echo '<table>';
	foreach ($updates as $item)
  {
    write_update_line($item, 1);
  }
	echo '</table>';
	
	write_footer();

	?>