<?php
	require_once 'logic/edit.php';
	require_once 'funcs.php';
	require_once 'top_menu.php';
	
	$uri = get_post_field('uri');
	$automated = get_post_field ('automated');
	if ($uri)
	{
		$id = add_uri ($uri);
		if ($id)
		{
			$email = new AddedURIEmal ($id);
			$email -> send();
		}
		
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
		redirect_to('/edit/game');
	}
?>