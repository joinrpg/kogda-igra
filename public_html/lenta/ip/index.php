<?php
	require_once 'funcs.php';
	require_once 'logic/updates.php';
	require_once 'logic/edit.php';
	require_once 'show_updates.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}
	
	$ip = get_request_field ('ip');
	if (!$ip || !filter_var($ip, FILTER_VALIDATE_IP))
	{
		return_to_main();
	}
	
	$action = get_post_field ('action');
	
	if ($action == 'clean')
	{
		remove_by_ip($ip);
		header("Location: /lenta/ip/$ip");
		die();
	}

	$topmenu = new TopMenu();
	$topmenu -> pagename = "Лента $ip";
	$topmenu -> show();
	
	$updates = get_updates_for_ip ($ip);
	
	action_button ('/lenta/ip/0/', "Стереть запросы от $ip", 'post', array('action' => 'clean', 'ip' => $ip));
	echo '<table>';
	foreach ($updates as $item)
  {
  	 write_update_line_with_ip($item, 1, 0);
  }
	echo '</table>';
	
	write_footer();

	?>