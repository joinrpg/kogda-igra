
<?php
	require_once 'funcs.php';
	require_once 'logic/gamelist.php';
	require_once 'api.php';

  send_json_header();
	$timestamp = get_request_field('timestamp');
  $result = get_games_by_timestamp($timestamp);
  if (is_array($result))
  {
    foreach ($result as $game)
    {
      $response[] = strip_game_object_before_json($game);
    }
    
    echo json_encode($response);
  }
  else
  {
    echo "[]";
  }
	


	?>