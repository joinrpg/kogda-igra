<?php
	require_once 'funcs.php';
	require_once 'logic/updates.php';
	require_once 'show_updates.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

   $ip = array_key_exists ('ip', $_GET) ? $_GET['ip'] : 0;
	if (!$ip || !filter_var($ip, FILTER_VALIDATE_IP))
	{
		return_to_main();
	}
	
	$topmenu = new TopMenu();
	$topmenu -> pagename = "Лента $ip";
	$topmenu -> show();
	
	$updates = get_updates_for_ip ($ip);
	echo '<table>';
	foreach ($updates as $item)
  {
  	 write_update_line_with_ip($item, 1, 0);
  }
	echo '</table>';
	
	write_footer();

	?>