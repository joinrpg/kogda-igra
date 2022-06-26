<?php
require_once 'funcs.php';

function get_game_by_id ($id)
{
  $id = intval ($id);
  $result = _get_games("kg.id = $id AND kgd.\"order\" = 0");
  return is_array($result) ? $result[0] : NULL;
}

function get_calendar_game_by_id ($id)
{
  $id = intval ($id);
  $result = _get_games("kg.id = $id AND kgd.\"order\" = 0");
  return $result;
}

function get_game_for_edit($id)
{
  $sql = connect();
  $id = intval($id);
  $result = $sql -> Query("SELECT
		kg.\"id\", kg.\"name\", kg.\"uri\", kg.\"type\", kg.\"polygon\", kg.\"mg\", kg.\"email\", kg.\"show_flags\", kg.\"status\", 
		kg.\"comment\", kg.\"sub_region_id\", kg.\"deleted_flag\", kg.\"hide_email\", 
		kg.\"players_count\", kg.\"review_count\", kg.\"allrpg_info_id\", kg.\"photo_count\", 
		kgd.\"begin\", kgd.\"time\", kg.\"vk_club\", kg.\"lj_comm\", kg.\"fb_comm\"
		FROM \"ki_games\" kg
		INNER JOIN \"ki_game_date\" kgd ON kgd.game_id = kg.id
		WHERE
			kg.id = $id AND kgd.\"order\" = 0");
			 return is_array($result) ? $result[0] : NULL;
}


function _get_games($where, $add_join = '', $orderby = 'kgd.begin DESC, kgd.time', $limit = '')
{
  $sql = connect();
  $query = "SELECT
		kg.\"id\", kg.\"name\", kg.\"uri\", kg.\"type\", kg.\"polygon\", kg.\"mg\", kg.\"email\", kg.\"show_flags\", kg.\"status\", 
		kg.\"comment\", kg.\"sub_region_id\", kg.\"deleted_flag\",  kg.\"hide_email\", kg.\"vk_likes\", 
		kr.\"region_id\",
		kg.\"players_count\", kg.\"review_count\", kg.\"allrpg_info_id\", kg.\"photo_count\",  kg.\"redirect_id\", 
		kg.\"vk_club\", kg.\"lj_comm\", kg.\"fb_comm\",
		kp.polygon_name, kgt.game_type_name, ksr.sub_region_disp_name, ksr.sub_region_name, kgt.show_all_regions, 
		ks.status_name, ks.status_style, ks.show_date_flag, ks.cancelled_status,
		
		krev.*, ks.show_review_flag, kp.meta_polygon,
		kgd.\"begin\", kgd.\"time\", kgd.\"order\", kia.allrpg_zayvka_id, kia.opened AS allrpg_opened
		FROM \"ki_games\" kg
		INNER JOIN ki_polygons kp ON kg.polygon = kp.polygon_id
		INNER JOIN ki_sub_regions ksr ON kg.sub_region_id = ksr.sub_region_id
		INNER JOIN ki_game_types kgt ON kg.type = kgt.game_type_id
		INNER JOIN \"ki_regions\" kr ON ksr.region_id = kr.region_id
		INNER JOIN \"ki_status\" ks ON ks.status_id = kg.status
		LEFT JOIN \"ki_game_date\" kgd ON kgd.game_id = kg.id AND kgd.hidden_flag = 0
		LEFT JOIN (SELECT MIN(review_id) as review_id, game_id FROM \"ki_review\" GROUP BY game_id) A ON A.game_id = kg.id
		LEFT JOIN \"ki_review\" krev ON krev.review_id = A.review_id
		LEFT JOIN \"ki_zayavka_allrpg\" kia ON kg.id = kia.game_id
		$add_join
		WHERE
			$where
			ORDER BY $orderby $limit";
  return $sql -> Query ($query);
}

function get_game_dates ($game_id)
{
  $sql = connect();
  $game_id = intval ($game_id);
  
  return $sql -> Query ("
     SELECT * FROM \"ki_game_date\"
     WHERE game_id = $game_id
     ORDER BY \"order\"
  ");
}

function internal_do_update_year_index ($sql)
{
	$sql -> Run ("DELETE FROM \"ki_years_cache\"");
	$sql -> Run ("
		INSERT INTO \"ki_years_cache\"
			SELECT 
			DISTINCT YEAR( \"begin\" ) AS year, \"region_id\"
			FROM \"ki_games\" \"kg\"
			INNER JOIN \"ki_game_date\" kgd ON kgd.game_id = kg.id
			INNER JOIN \"ki_sub_regions\" \"ksr\" ON \"ksr\".\"sub_region_id\" = \"kg\".\"sub_region_id\"
			WHERE \"deleted_flag\" = 0
		");
}
?>