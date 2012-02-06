<?php
require_once 'funcs.php';
require_once 'logic/gamebase.php';

function get_game_by_allrpg_id ($id)
{
  $id = intval ($id);
  $result = _get_games("kg.allrpg_info_id = $id");
  return is_array($result) ? $result[0] : NULL;
}
?>