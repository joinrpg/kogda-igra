<?php
require_once 'funcs.php';

function get_intersections($game_id)
{
  $game_id = intval($game_id);
  return _get_games(" kg.id <> kgd2.game_id  AND
			kg.deleted_flag = 0
			AND ks.cancelled_status = 0
			AND kgd2.game_id = $game_id AND kgd.\"order\" = 0 AND kgd2.\"order\" = 0", "
			INNER JOIN ki_game_date kgd2 ON 
				(kgd.begin <= (kgd2.begin + kgd2.time) AND kgd.begin >= kgd2.begin)
       			 OR (kgd2.begin <= (kgd.begin + kgd.time) AND kgd2.begin >= kgd.begin)
			 ",
			 '',
			 "kg.players_count DESC, kgd.begin ASC, kgd.time ASC");
}

?>