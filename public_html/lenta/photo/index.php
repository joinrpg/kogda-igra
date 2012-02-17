<?php
	require_once 'funcs.php';
	require_once 'logic.php';
	require_once 'show_updates.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

  $header = "Лента изменений :: Фотоотчеты";
	write_header($header);
	
	$topmenu = new TopMenu();
	$topmenu -> pagename = $header;
	$topmenu -> show();
	
	echo "
    <p>В этот список включаются все изменения по фотоотчетам.</p>
	";

	$updates = get_photo_updates();
	echo '<table>';
	foreach ($updates as $item)
  {
    write_update_line($item, 1);
  }
	echo '</table>';
	
	write_footer();

	?>