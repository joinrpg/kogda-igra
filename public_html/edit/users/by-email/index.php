<?php
	require_once 'funcs.php';
	require_once 'user_funcs.php';

  $username = get_post_field('username');
	if (!check_my_priv (USERS_CONTROL_PRIV) || !$username)
		{
			return_to_main();
		}

	$id = try_login_user_by_email ($username, false);
	header("Location: /edit/users/$id");
?>