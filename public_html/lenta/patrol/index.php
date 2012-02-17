<?php
	require_once 'funcs.php';
	require_once 'logic.php';
	require_once 'show_updates.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

  write_header( "Лента изменений :: Патруль");
		$topmenu = new TopMenu();
	$topmenu -> pagename = 'Лента изменений :: Патруль';
	$topmenu -> show();
  echo "<p>В этот список включаются все изменения, кроме тех, которые совершили вы.</p>	";
	
	$updates = get_updates_except_user_id (get_user_id());
	echo '<table>';
	foreach ($updates as $item)
  {
    write_update_line($item, 1);
  }
	echo '</table>';
	
	write_footer();

	?>