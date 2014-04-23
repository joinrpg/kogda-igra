<?php
function strip_game_object_before_json($result)
{
    if ($result['show_flags'])
    {
      return array(
        'id' => $result['id'],
        'access-denied' => 1,
        );
    }
    if ($result['redirect_id'])
    {
      return array(
        'id' => $result['id'],
        'redirect_id' => $result['redirect_id'],
        );
    }
    if ($result['hide_email'])
    {
      unset($result['email']);
    }
    $enabled_fields = 
      array ("id", "name", "uri", "begin", "time", "type", "polygon", "mg", "email", "status", "comment", "region", "sub_region_id", "deleted_flag", 
      "players_count", "allrpg_info_id", "polygon_name", "game_type_name", "sub_region_disp_name", "sub_region_name", "status_name", 'vk_club', 'lj_comm');
    $response = array();
    foreach ($result as $key => $value)
    {
      if (array_search($key, $enabled_fields) === FALSE)
      {
        continue;
      }
        $response[$key] = $result[$key];
    }
    return $response;
}
?>