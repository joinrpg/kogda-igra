<?php
	require_once 'logic.php';
	require_once 'calendar.php';

	function show_intersections($id, $name)
	{
    $result = get_intersections($id);
    if (is_array($result))
    {
    	echo '<hr>';
  		echo "<h3>Пересечения:</h3>";
       $calendar = new Calendar($result);
      $calendar -> show_reviews = TRUE;
      $calendar ->show_cancelled_games_checkbox = FALSE;
      $calendar -> write_calendar();
    }
    else
    {
      echo '<p>Пересечений нет.</p>';
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