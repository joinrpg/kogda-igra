<?php
require_once 'sqlbase.php';

function get_updates_24hr()
{
	return _get_updates("(NOW() - INTERVAL 3 MONTH) < update_date");
}

function get_polygon_updates_24hr()
{
	return _get_updates("(NOW() - INTERVAL 3 MONTH) < update_date AND update_type_polygon_flag = 1");
}

function get_photo_updates()
{
	return _get_updates("(NOW() - INTERVAL 3 MONTH) < update_date AND update_type_photo_flag = 1");
}

function get_adv_updates_for_week()
{
		return _get_updates("(NOW() - INTERVAL 2 WEEK) < update_date AND advertise_update_flag = 1 AND COALESCE(kg.deleted_flag,0) = 0 AND COALESCE(krev.show_review_flag, 1) = 1 
			AND (ki_game_date.begin IS NULL OR ki_game_date.begin > NOW())
				", 3);
}

function _get_updates($where, $limit = 0)
{
  $sql = connect();
  $sql -> Run("SET SQL_BIG_SELECTS=1");
  $limit = ($limit) ? "LIMIT $limit" : "";
  return $sql -> Query("
		SELECT ku.*, kut.ki_update_type_name, kut.update_type_polygon_flag, kut.update_type_game_flag, kut.update_type_photo_flag, kut.update_type_review_flag, kut.update_type_user_text,
		kg.*, kp.polygon_name, kgt.game_type_name, ksr.sub_region_disp_name, ksr.sub_region_name, kgt.show_all_regions, users.username, ks.status_name, ks.status_style, updated_user.username AS updated_user_name, krev.review_uri, krev.topic_id, krev.show_review_flag, kau.uri, kau.allrpg_info_id
		FROM ki_updates ku
		
		INNER JOIN ki_update_types kut ON ku.ki_update_type_id = kut.ki_update_type_id
		LEFT JOIN ki_photo kph ON kph.photo_id = ku.photo_id
		LEFT JOIN ki_games kg ON kg.id = ku.game_id OR kph.game_id = kg.id
		LEFT JOIN ki_game_date ON ki_game_date.game_Id = kg.id AND ki_game_date.order = 0 
		LEFT JOIN ki_polygons kp ON kg.polygon = kp.polygon_id OR ku.polygon_id = kp.polygon_id
		LEFT JOIN ki_sub_regions ksr ON kg.sub_region_id = ksr.sub_region_id
		LEFT JOIN ki_game_types kgt ON kg.type = kgt.game_type_id
		LEFT JOIN \"ki_regions\" kr ON ksr.region_id = kr.region_id
		LEFT JOIN \"ki_status\" ks ON ks.status_id = kg.status
		LEFT JOIN \"ki_review\" krev ON krev.review_id = ku.review_id
		LEFT JOIN ki_add_uri kau ON kau.add_uri_id = ku.add_uri_id
		LEFT JOIN \"users\" updated_user ON updated_user.user_id = ku.updated_user_id OR updated_user.user_id = krev.author_id
		LEFT JOIN users ON users.user_id = ku.user_id
		
		WHERE $where
		ORDER BY update_date DESC 
		$limit
	");
}

function get_updates_for_game($game_id)
{
  $game_id = intval ($game_id);
  $sql = connect();
  $sql -> Run("SET SQL_BIG_SELECTS=1");
  return $sql -> Query("
		SELECT ku.*, kut.ki_update_type_name, kut.update_type_polygon_flag, kut.update_type_game_flag, kut.update_type_photo_flag, kut.update_type_review_flag, kut.update_type_user_text,
		kg.*, kp.polygon_name, kgt.game_type_name, ksr.sub_region_disp_name, ksr.sub_region_name, kgt.show_all_regions, users.username, ks.status_name, ks.status_style, updated_user.username AS updated_user_name, krev.review_uri, krev.topic_id, krev.show_review_flag, kau.uri, kau.allrpg_info_id
		FROM ki_updates ku
		
		INNER JOIN ki_update_types kut ON ku.ki_update_type_id = kut.ki_update_type_id
		LEFT JOIN ki_photo kph ON kph.photo_id = ku.photo_id
		INNER JOIN ki_games kg ON kg.id = ku.game_id
		LEFT JOIN ki_game_date ON ki_game_date.game_Id = kg.id AND ki_game_date.order = 0 
		INNER JOIN ki_polygons kp ON kg.polygon = kp.polygon_id
		LEFT JOIN ki_sub_regions ksr ON kg.sub_region_id = ksr.sub_region_id
		LEFT JOIN ki_game_types kgt ON kg.type = kgt.game_type_id
		LEFT JOIN \"ki_regions\" kr ON ksr.region_id = kr.region_id
		LEFT JOIN \"ki_status\" ks ON ks.status_id = kg.status
		LEFT JOIN \"ki_review\" krev ON krev.review_id = ku.review_id
		LEFT JOIN ki_add_uri kau ON kau.add_uri_id = ku.add_uri_id
		LEFT JOIN \"users\" updated_user ON updated_user.user_id = ku.updated_user_id OR updated_user.user_id = krev.author_id
		LEFT JOIN users ON users.user_id = ku.user_id
		WHERE kg.id = $game_id
		ORDER BY update_date DESC 
	");
}

function get_updates_for_ip($ip)
{
	if(!filter_var($ip, FILTER_VALIDATE_IP))
	{
		return null;
	}
	return _get_updates("ku.ip_address = '$ip'");
}

function get_last_update_date_for_game($game_id)
{
		$sql = connect();
		$result = $sql -> GetRow("
			SELECT MAX(update_date) AS update_date FROM ki_updates ku WHERE game_id = $game_id
		");
		return strtotime($result['update_date']);
}

function get_updates_by_user_id($user_id)
{
	$user_id = intval ($user_id);
	return _get_updates("(NOW() - INTERVAL 3 MONTH) < update_date AND ku.user_id = $user_id");
}

function get_updates_except_user_id($user_id)
{
	$user_id = intval ($user_id);
	return _get_updates("(NOW() - INTERVAL 3 MONTH) < update_date AND ku.user_id <> $user_id");
}

function get_photo_updates_except_user_id($user_id)
{
	$game_id = intval ($user_id);
	return _get_updates("(NOW() - INTERVAL 3 MONTH) < update_date AND ku.user_id <> $user_id  AND update_type_photo_flag = 1");
}
?>