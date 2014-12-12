<?php
require_once 'funcs.php';
require_once 'logic/gamebase.php';

function get_passed_games()
{
  return _get_games("ks.future_only_status = 1
			AND ADDDATE(kgd.begin, kgd.time) < NOW()
			AND kg.deleted_flag = 0 AND kgd.`order` = 0");
}

function get_problem_games($in_past = TRUE, $in_future = TRUE)
{  
  $future_string = $in_future ? 'kgd.`begin` > NOW()' : '0 = 1';
  $past_string = $in_past ? 'kgd.`begin` < NOW()' : '0 = 1';

  return _get_games("ks.problem_status = 1
			AND kg.deleted_flag = 0  AND kgd.`order` = 0
			AND ($future_string OR $past_string)");
}

function _get_future_string($future)
{
  return ($future ? 'AND kgd.`begin` > NOW()' : '') . 'AND kgd.`order` = 0';
}

function get_noemail_games($future = FALSE)
{
  $future_string = _get_future_string($future);

  return _get_games("LENGTH(email)  = 0 
			AND kg.deleted_flag = 0
			$future_string");
}

function get_noplayers_count_games($future = FALSE)
{
    $future_string = _get_future_string($future);
  
  return _get_games ("kg.deleted_flag = 0
			AND ks.cancelled_status = 0
			AND kg.players_count IS NULL $future_string");
}

function get_noallrpg_info_games($future = FALSE)
{
  $future_string = _get_future_string($future);
  
  return _get_games ("kg.deleted_flag = 0
			AND ks.cancelled_status = 0
			AND kg.allrpg_info_id IS NULL
			$future_string");
}

function get_nopolygon_games()
{
  return _get_games("kp.meta_polygon = 1 AND kg.deleted_flag = 0 AND ks.cancelled_status = 0 AND kgd.`order` = 0");
}

function get_problems_summary()
{
  $sql = connect();
  
  $query = 'SELECT (
    SELECT COUNT(*)
		FROM `ki_games` kg
		INNER JOIN `ki_status` ks ON ks.status_id = kg.status
		INNER JOIN `ki_game_date` kgd ON kgd.game_id = kg.id AND kgd.`order` = 0
		WHERE
			ks.future_only_status = 1
			AND ADDDATE(kgd.begin,kgd.time) < NOW()
			AND kg.deleted_flag = 0
    ) as passed_count, (
    SELECT COUNT(*)
    FROM `ki_games` kg
		INNER JOIN `ki_status` ks ON ks.status_id = kg.status
		WHERE
			ks.problem_status = 1
			AND kg.deleted_flag = 0
    ) as noinfo_count, (
    SELECT COUNT(*)
    FROM `ki_games` kg
		INNER JOIN `ki_status` ks ON ks.status_id = kg.status
		WHERE
			LENGTH(email) = 0 
			AND kg.deleted_flag = 0
    ) as noemail_count, (
    SELECT COUNT(*)
    FROM `ki_games` kg
		INNER JOIN `ki_status` ks ON ks.status_id = kg.status
				INNER JOIN `ki_game_date` kgd ON kgd.game_id = kg.id AND kgd.`order` = 0
		WHERE
			LENGTH(email) = 0 
			AND kg.deleted_flag = 0
			AND kgd.`begin` > NOW()
    ) as noemail_count_future, (
    SELECT
		COUNT(*)
		FROM `ki_games` kg
		INNER JOIN ki_polygons kp ON kg.polygon = kp.polygon_id
		INNER JOIN `ki_status` ks ON ks.status_id = kg.status
		WHERE
			kp.meta_polygon = 1
			AND kg.deleted_flag = 0
			AND ks.cancelled_status = 0
		) as nopolygon_count, (
		SELECT
      COUNT(*)
		FROM `ki_games` kg
		INNER JOIN `ki_status` ks ON ks.status_id = kg.status
		WHERE
			kg.deleted_flag = 0
			AND ks.cancelled_status = 0
						AND review_count > 0
		) AS reviewed_count, (
		SELECT
      COUNT(*)
		FROM `ki_games` kg
		INNER JOIN `ki_status` ks ON ks.status_id = kg.status
		WHERE
			kg.deleted_flag = 0
			AND ks.cancelled_status = 0
						AND photo_count > 0
		) AS photed_count, (
		SELECT
      COUNT(*)
		FROM `ki_games` kg
		INNER JOIN `ki_status` ks ON ks.status_id = kg.status
		WHERE
			kg.deleted_flag = 0
			AND ks.cancelled_status = 0
			AND kg.players_count IS NULL
		) AS noplayers_count, (
		SELECT
      COUNT(*)
		FROM `ki_games` kg
		INNER JOIN `ki_status` ks ON ks.status_id = kg.status
		INNER JOIN `ki_game_date` kgd ON kgd.game_id = kg.id AND kgd.`order` = 0
		WHERE
			kg.deleted_flag = 0
			AND ks.cancelled_status = 0
			AND kg.players_count IS NULL
			AND kgd.`begin` > NOW()
		) AS noplayers_count_future, (
		SELECT
      COUNT(*)
		FROM `ki_games` kg
		INNER JOIN `ki_status` ks ON ks.status_id = kg.status
		INNER JOIN `ki_game_date` kgd ON kgd.game_id = kg.id AND kgd.`order` = 0
		WHERE
			kg.deleted_flag = 0
			AND ks.cancelled_status = 0
			AND comment <> \'\'
		) AS comment_count, (
		SELECT
      COUNT(*)
		FROM `ki_games` kg
		INNER JOIN `ki_status` ks ON ks.status_id = kg.status
		WHERE
			kg.deleted_flag = 0
			AND ks.cancelled_status = 0
			AND kg.allrpg_info_id IS NULL
		) AS noallrpg_info_count, (
		SELECT
      COUNT(*)
		FROM `old_games` 
		) AS old_games_count, (
		SELECT
      COUNT(*)
		FROM `ki_games` kg
		INNER JOIN `ki_status` ks ON ks.status_id = kg.status
		INNER JOIN `ki_game_date` kgd ON kgd.game_id = kg.id AND kgd.`order` = 0
		WHERE
			kg.deleted_flag = 0
			AND ks.cancelled_status = 0
			AND kg.allrpg_info_id IS NULL
			AND kgd.`begin` > NOW()
		) AS noallrpg_info_count_future';
    
   return $sql -> GetRow ($query);
}

function get_merge_candidates()
{
  $sql = connect();
  return $sql -> Query("SELECT name, COUNT(id) as gamecount FROM ki_games WHERE deleted_flag = 0 GROUP BY name  HAVING COUNT(id)> 1  ORDER BY COUNT(id) DESC");
}


function get_games_with_comment()
{
  return _get_games("comment <> '' AND kg.deleted_flag = 0 AND ks.cancelled_status = 0 AND kgd.`order` = 0");
}

function get_one_problem_allrpg()
{
  return _get_games ("kg.deleted_flag = 0
			AND ks.cancelled_status = 0
			AND kg.allrpg_info_id IS NULL
			AND YEAR(kgd.begin) > 2013");
}

?>