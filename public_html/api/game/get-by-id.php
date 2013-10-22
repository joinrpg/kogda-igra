
<?php
	require_once 'funcs.php';
	require_once 'logic/gamebase.php';
	require_once 'api.php';

	$id = array_key_exists('id', $_GET) ? intval($_GET['id']) : '';
  $result = get_game_by_id($id);
  if (is_array($result))
  { 
    echo json_encode(strip_game_object_before_json($result));
  }
  else
  {
    echo "{}";
  }
	


	?>