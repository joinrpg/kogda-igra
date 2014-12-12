<?php
require_once 'funcs.php';
require_once 'logic/gamebase.php';

function get_year_list ($region)
{
	$sql = connect();

	$region = intval ($region);
	
	if ($region > 0)
	{
		return $sql -> Query ("
			SELECT 
			DISTINCT year
			FROM `ki_years_cache` kyc
			WHERE kyc.region_id = $region
			ORDER BY year");
	}
	else
	{

		return $sql -> Query("
			SELECT 
			DISTINCT year
			FROM `ki_years_cache` kyc
			ORDER BY year");
	}
}

function get_new_reviews()
{
  $sql = connect();
  return $sql->Query("SELECT kg.*, krev.*, `users`.`username` 
  FROM `ki_games` kg 
   
  INNER JOIN `ki_review` krev ON krev.game_id = kg.id 
   LEFT JOIN `users` ON krev.author_id = `users`.`user_id`
  ORDER BY krev.review_id DESC LIMIT 1");
}

function get_new_photos()
{
  $sql = connect();
  return $sql->Query("SELECT kg.*, kp.* FROM `ki_games` kg INNER JOIN `ki_photo` kp ON kp.game_id = kg.id ORDER BY kp.photo_id DESC LIMIT 1");
}



function get_reviewed_games()
{
    return _get_games("kg.deleted_flag = 0
			AND ks.cancelled_status = 0
			AND review_count > 0
			AND kgd.`order` = 0");
}

function get_photo_games()
{
    return _get_games("kg.deleted_flag = 0
			AND ks.cancelled_status = 0
			AND photo_count > 0
			AND kgd.`order` = 0");
}

function get_future_condition()
{
  return 'MONTH(kgd.`begin`) >= MONTH(NOW())';
}

function get_region_condition ($region)
{
  return 	
  ($region == 0) ?
    ('(kr.region_experimental = 0)') :
    ("(ksr.region_id = $region)");
}

function get_main_calendar($year, $region = 0, $show_only_future = false, $show_only_konvent = false)
{
	$sql = connect();

	$region = intval ($region);
	$year = intval ($year);

	$region_query = get_region_condition ($region);
	$future_query = $show_only_future ? get_future_condition() : '1=1';
  $konvent_query = $show_only_konvent ? 'kg.type = 5' : '1=1';
	
	return _get_games("YEAR(kgd.`begin`) = $year
			AND ($region_query)
			AND kg.deleted_flag = 0
			AND ($konvent_query)
			AND ($future_query)	", '', "kgd.begin, kgd.time");
}

function get_best($year, $region = 0)
{
	$sql = connect();

	$region = intval ($region);
	$year = intval ($year);

	$region_query = get_region_condition ($region);
	$future_query = $region == 0 ? get_future_condition() : '1=1';
	
	return _get_games("YEAR(kgd.`begin`) = $year
			AND ($region_query)
			AND kg.deleted_flag = 0	AND kg.vk_likes > 0 AND kgd.order=0", '', "kg.vk_likes DESC", "LIMIT 10");
}


function get_deleted_games()
{
	return _get_games ("kg.deleted_flag = 1");
}

function get_new_games_for_week()
{
  return _get_games("
    ki_update_type_id = 1
    AND (NOW( ) - INTERVAL 7 DAY) < update_date AND (kgd.`begin` > NOW()) AND (show_flags = 0) AND (kg.deleted_flag = 0) AND (kgd.order = 0)", 
    "INNER JOIN `ki_updates` ki ON kg.id = ki.game_id", 'update_date DESC', 'LIMIT 5');
}


function get_games_for_moderate()
{
	return _get_games ('kg.deleted_flag = -1');
}

function get_add_uri_list()
{
	$sql = connect();
	return $sql -> Query ('SELECT * FROM ki_add_uri WHERE resolved = 0 LIMIT 11');
}

function get_future_games()
{
	return _get_games("kg.deleted_flag =0 AND begin > NOW() AND ks.cancelled_status = 0 AND kgd.`order` = 0", "", "name ASC");
}

function get_games_by_timestamp($timestamp)
{
  $timestamp = intval ($timestamp);
  return _get_games("`update_date` > FROM_UNIXTIME($timestamp) AND kgd.`order`=0 AND kg.deleted_flag =0", "INNER JOIN `ki_updates` ki ON kg.id = ki.game_id", "update_date DESC");
}

?>