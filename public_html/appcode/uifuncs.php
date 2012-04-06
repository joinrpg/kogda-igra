<?php
	function active_button ($uri, $text, $add = '')
	{
		echo "<div class='active'><a href=\"$uri\">$text</a>$add</div>";
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
				echo "<div class='active'><form action=\"$uri\" method=get style=\"display:inline\"><input type=submit value=\"$text\"></form></div>";
			}
		}
		
			function get_photo_author ($photodata)
	{
    $username = $photodata['username'];
    $user_id = $photodata['user_id'];
    $author = $photodata['photo_author'];
    return $username ? show_user_link ($username, $user_id) : $author;
	}
?>