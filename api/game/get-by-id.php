
<?php
	require_once 'funcs.php';
	require_once 'logic/gamebase.php';

	$id = array_key_exists('id', $_GET) ? intval($_GET['id']) : '';
  $result = get_game_by_id($id);
  if (is_array($result))
  {
    if ($result['show_flags'])
    {
      echo "{\"id\":\"$id\", \"access-denied\":\"1\"}";die();
    }
    if ($result['redirect_id'])
    {
      $redirect_id = $result['redirect_id'];
      echo "{\"id\":\"$id\", \"redirect_id\":\"$redirect_id\"}";die();
    }
    if ($result['hide_email'])
    {
      unset($result['email']);
    }
    $enabled_fields = 
      array ("id", "name", "uri", "begin", "time", "type", "polygon", "mg", "email", "status", "comment", "region", "sub_region_id", "deleted_flag", 
      "players_count", "allrpg_info_id", "polygon_name", "game_type_name", "sub_region_disp_name", "sub_region_name", "status_name");
    $response = array();
    foreach ($result as $key => $value)
    {
      if (array_search($key, $enabled_fields))
      {
        $response[$key] = $result[$key];
      }
    }
    
    echo json_encode($response);
  }
  else
  {
    echo "{}";
  }
	


	?>