<?php
function strip_game_object_before_json($result, $whitelisted_fields = null)
{
  if (!$whitelisted_fields)
  {
    $whitelisted_fields = 
      array ("id", "name", "uri", "begin", "time", "type", "polygon", "mg", "email", "status", "comment", "region", "sub_region_id", "deleted_flag", 
      "players_count", "allrpg_info_id", "polygon_name", "game_type_name", "sub_region_disp_name", "sub_region_name", "status_name", 'vk_club', 'lj_comm', 'fb_comm'
      , 'update_date'
    );
  }
    if (array_key_exists('show_flags', $result) && $result['show_flags'])
    {
      return array(
        'id' => $result['id'],
        'access-denied' => 1,
        );
    }
    if (array_key_exists('redirect_id', $result) &&  $result['redirect_id'])
    {
      return array(
        'id' => $result['id'],
        'redirect_id' => $result['redirect_id'],
        );
    }
    if (array_key_exists('hide_email', $result) &&  $result['hide_email'])
    {
      unset($result['email']);
    }

    $response = array();
    foreach ($result as $key => $value)
    {
      if (array_search($key, $whitelisted_fields) === FALSE)
      {
        continue;
      }
        $response[$key] = $result[$key];
    }
    return $response;
}

function send_json_header()
{
  header('Content-Type: application/json; charset=utf-8');
}
?>