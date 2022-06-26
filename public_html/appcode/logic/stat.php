<?php
require_once 'funcs.php';
function get_editors_statistics()
{
  $sql = connect();
  return $sql -> Query('
  SELECT COUNT(ki_update_id) AS update_count, username, users.user_id,
  SUM(CASE WHEN ki_update_type_id = 1 THEN 1 ELSE 0 END) AS new_count
  FROM ki_updates 
  INNER JOIN users ON users.user_id = ki_updates.user_id
  WHERE (update_date + INTERVAL 3 MONTH) > NOW()
  GROUP BY username, users.user_id
  ORDER BY COUNT(ki_update_id)  DESC
  ');
}

function get_editor_stat_by_id($user_id)
{
  $sql = connect();
  $user_id = intval($user_id);
  return $sql -> GetRow("
  SELECT COUNT(ki_update_id) AS update_count, 
  SUM(CASE WHEN ki_update_type_id = 1 THEN 1 ELSE 0 END) AS new_count
  FROM ki_updates 
  WHERE (update_date + INTERVAL 3 MONTH) > NOW() AND ki_updates.user_id = $user_id
  ");
}

function get_full_statistics()
{
  $sql = connect();
  return $sql -> Query("
      SELECT 
			DISTINCT year, 
			(
        SELECT COUNT(*) FROM \"ki_games\" kg 
        INNER JOIN \"ki_game_date\" kgd ON kgd.game_id = kg.id
        WHERE YEAR(kgd.\"begin\") = kyc.year AND kg.deleted_flag = 0
			) AS total_count,
			(
        SELECT 
          COUNT(*) 
          FROM \"ki_games\" kg 
          INNER JOIN \"ki_game_date\" kgd ON kgd.game_id = kg.id
          INNER JOIN \"ki_status\" ks ON ks.status_id = kg.status
          WHERE YEAR(kgd.\"begin\") = kyc.year AND ks.good_status <> 0 AND kg.deleted_flag = 0
			) AS notcancelled_count,
			(
        SELECT COUNT(*) 
        FROM \"ki_games\" kg 
        INNER JOIN ki_game_types kgt ON kg.type = kgt.game_type_id
        INNER JOIN \"ki_game_date\" kgd ON kgd.game_id = kg.id
        WHERE 
          YEAR(kgd.\"begin\") = kyc.year 
          AND kg.deleted_flag = 0
          AND kgt.game_type_real_game <> 0
			) AS total_game_count,
			(
        SELECT 
          COUNT(*) 
          FROM \"ki_games\" kg 
          INNER JOIN \"ki_status\" ks ON ks.status_id = kg.status
          INNER JOIN ki_game_types kgt ON kg.type = kgt.game_type_id
          INNER JOIN \"ki_game_date\" kgd ON kgd.game_id = kg.id
          WHERE 
            YEAR(kgd.\"begin\") = kyc.year 
            AND ks.good_status <> 0 
            AND kg.deleted_flag = 0
            AND kgt.game_type_real_game <> 0
			) AS game_notcancelled_count
			FROM \"ki_years_cache\" kyc
			ORDER BY year
  ");
}

function get_statistics()
{
  $sql = connect();
  return $sql -> Query("
    SELECT 
      COUNT(name) AS game_count, YEAR(kgd.\"begin\") AS year
    FROM \"ki_games\" kg
    INNER JOIN \"ki_game_date\" kgd ON kgd.game_id = kg.id
    GROUP BY YEAR(kgd.\"begin\")
    ORDER BY YEAR(kgd.\"begin\") DESC
  ");
}

?>