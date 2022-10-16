<?php
	function active_button ($uri, $text, $add = '')
	{
		echo "<div class='active'><a href=\"$uri\">$text</a>$add</div>";
	}

	function joinrpg_box ()
	{
		echo "<div class='active'>
			<a href=\"https://joinrpg.ru\">JoinRpg</a>
			<a href=\"https://t.me/joinrpg\"><img src='/img/telegram.png' /></a>
			<a href=\"https://vk.com/joinrpg\"><img src='/img/vk.png' /></a>
			</div>";
	}
	
	
	function passive_button ($text)
	{
		echo "<div class='passive'>$text</div>";
	}
	
		function show_button ($uri, $text, $add = '')
	{
		if ($uri == $_SERVER['REQUEST_URI'])
		{
			passive_button ($text);
		}
		else
		{
			active_button ($uri, $text, $add);
		}
	}
	
			function real_button ($uri, $text)
		{
		if ($uri == $_SERVER['REQUEST_URI'])
			{
				passive_button ($text);
			}
			else
			{
				action_button ($uri, $text, 'get');
			}
		}
		
		function action_button ($uri, $text, $method, $par = NULL)
		{
			echo "<div class=active><form action=\"$uri\" method=$method style=\"display:inline\"><input type=submit value=\"$text\">";
			if (is_array($par))
			{
				foreach ($par as $key => $value)
				{
					echo "<input type=hidden name=\"$key\" value=\"$value\">";
				}
			}
			echo '</form></div>';
		}
		
			function get_photo_author ($photodata)
	{
    $username = $photodata['username'];
    $user_id = $photodata['user_id'];
    $author = $photodata['photo_author'];
    return $username ? show_user_link ($username, $user_id) : $author;
	}
	
	function show_avatar($email)
	{
  $gravatar_email = $email ? $email : 'nobody@example.com';
  $gravatar_email = md5( strtolower( trim( $gravatar_email ) ) );

  echo "<img src=\"http://www.gravatar.com/avatar/$gravatar_email.jpg?d=mm\">";
	}
	
	function get_game_profile_link ($id, $full = false)
	{
		$host = $full ? SITENAME_SCHEME . "://" . SITENAME_HOST : "";
		return "$host/game/$id/";
	}
	
	function get_game_edit_link ($id, $full = false)
	{
		$host = $full ? SITENAME_SCHEME . "://" . SITENAME_HOST : "";
		return "$host/edit/game?id=$id";
	}
	
		function get_game_edit_photo_link ($id, $full = false)
	{
		$host = $full ? SITENAME_SCHEME . "://" . SITENAME_HOST : "";
		return "$host/edit/game/$id/photo/";
	}
?>
