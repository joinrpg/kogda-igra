<?php
	require_once 'logic/edit.php';
	require_once 'funcs.php';
	require_once 'top_menu.php';
	
	$uri = get_post_field('uri');
	$automated = get_post_field ('automated');
	if ($uri)
	{
		add_uri ($uri);
		
		if ($automated)
		{
			echo '[add_game_status:ok]';
		}
		else
		{
			redirect_to('/game/thanks');
		}
	}
	else
	{
		echo '<form method=post><input type=uri name=uri><input type=submit></form>';
	}
?>