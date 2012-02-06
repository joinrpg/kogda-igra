<?php
	require_once 'funcs.php';
	require_once 'logic.php';
	require_once 'show_updates.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

  $header = "Лента изменений :: Патруль";
	write_header($header);
	echo "<h1>$header</h1>
    <p>В этот список включаются все изменения, кроме тех, которые совершили вы.</p>
	";
	
	show_greeting();
	$updates = get_updates_except_user_id (get_user_id());
	echo '<table>';
	foreach ($updates as $item)
  {
    write_update_line($item, 1);
  }
	echo '</table>';
	
	write_footer();

	?>