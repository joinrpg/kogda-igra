<?php
require_once 'funcs.php';
require_once 'email.php';
require_once 'review.php';

function set_allrpg ($id, $allrpg)
{
  $sql = connect();
  $id = intval($id);
  $allrpg = intval($allrpg);
  
  $sql -> begin();
  $sql -> Run ("UPDATE \"ki_games\" SET allrpg_info_id = $allrpg WHERE id = $id");
  $sql -> commit();
}

function mark_as_passed($id)
{
  $sql = connect();
  $id = intval($id);
 
  $sql -> Run ("START TRANSACTION");
  $sql -> Run ("UPDATE \"ki_games\" SET status = 1 where id = $id");
  internal_log_game (8, $id);
  $sql -> Run ("COMMIT");
}

function remove_by_ip($ip)
{
	$sql = connect();
	$ip = $sql -> QuoteAndClean ($ip);
	
	$rq = "UPDATE  ki_games kg 
		INNER JOIN ki_updates ku ON ku.game_id = kg.id 
		SET deleted_flag = 1
		WHERE \"ip_address\" LIKE $ip AND deleted_flag = -1";
	$sql -> Run ($rq);
}

function _add_uri ($uri)
{
	$sql = connect();
	$uri = $sql -> QuoteAndClean ($uri);
	
	if (!$sql -> GetRow ("SELECT * FROM ki_add_uri WHERE uri LIKE $uri LIMIT 1"))
	{
		$sql -> Run ("INSERT INTO ki_add_uri (uri) VALUES ($uri)"); 
		return $sql -> LastInsert ();
	}
	else
	{
		return 0;
	}
}

function add_uri ($uri)
{
	$sql = connect();
	$sql -> begin();
	
	$try_uri = str_replace('http:', '', $uri);
	$try_uri = str_replace ('/', '', $try_uri);
	$try_uri = str_replace ('inf.allrpg.infoevents', '', $try_uri);
	$allrpg_info_id = intval ($try_uri);
	
	if ($allrpg_info_id)
	{
		if (!$sql -> GetRow ("SELECT * FROM ki_games WHERE allrpg_info_id = $allrpg_info_id LIMIT 1"))
		{
			$sql -> Run ("INSERT INTO ki_add_uri (allrpg_info_id) VALUES ($allrpg_info_id)"); 
			$id = $sql -> LastInsert ();
		}
		else
		{
			$id = 0;
		}
	}
	else
	{
		$id = _add_uri ($uri);
	}
	if ($id)
	{
		internal_log_add_uri($id);
	}
	$sql -> commit();
	return $id;
}

function resolve_add_uri ($id)
{
	$sql = connect();
	$id = intval ($id);
	
	return $sql-> Run ("UPDATE ki_add_uri SET resolved = 1 WHERE add_uri_id = $id");
}

function get_added_uri ($id)
{
	$sql = connect();
	$id = intval($id);
	
   return $sql -> GetRow ("SELECT * FROM ki_add_uri WHERE add_uri_id = $id");
}

function clear_comment($id)
{
  $sql = connect();
  $id = intval($id);
 
  $sql -> Run ("UPDATE \"ki_games\" SET comment = NULL where id = $id");
}

function load_old_game($old_id)
{
   $sql = connect();
   $old_id = intval ($old_id);
   
   $result = $sql -> Query ("SELECT * FROM old_games WHERE old_game_id = $old_id");
   return is_array($result) ? $result[0] : NULL;
}

function delete_old_game ($old_id)
{
  $sql = connect();
  $old_id = intval ($old_id);
  
  return $sql-> Run ("DELETE FROM old_games WHERE old_game_id = $old_id");
}

function try_to_find_region ($region_name)
{
  $sql = connect();
  $region_name = $sql -> QuoteAndClean (trim($region_name));
  $q = "SELECT sub_region_id FROM ki_sub_regions WHERE sub_region_name LIKE $region_name";
  $result = $sql -> Query ($q);
  return is_array($result) ? $result[0]['sub_region_id'] : NULL; 
}

function do_updatedate($id, $new_date, $days)
{
	$sql = connect ();

	$id = intval ($id);
	$new_date = $sql -> QuoteAndClean ($new_date);
	$days = intval ($days);

	if (0 >= $id )
		return FALSE;

	$sql -> Run ('START TRANSACTION');
	$sql -> Run ("UPDATE \"ki_game_date\" SET begin = $new_date, time = $days WHERE game_id = $id AND \"order\" = 0");
	internal_do_update_year_index ($sql);
	$sql -> Run ('COMMIT');
	return TRUE;
}

function do_game_merge ($id, $old_id)
{
  $sql = connect();
  
  $id = intval($id); $old_id = intval($old_id);
  
  $sql -> debug = FALSE;
  
  $sql -> Run ('START TRANSACTION');
  
  $old_dates = get_game_dates ($old_id);
  $sql -> Run ("UPDATE \"ki_review\" SET game_id = $id WHERE game_id = $old_id");
  $sql -> Run ("UPDATE \"ki_photo\" SET game_id = $id WHERE game_id = $old_id");
  $sql -> Run ("UPDATE \"ki_games\" SET deleted_flag = 1, redirect_id = $id WHERE id = $old_id");
  foreach ($old_dates as $old_date)
  {
    
    $order_result = 
    $sql -> GetRow ("SELECT MAX(order) + 1 AS max_order FROM \"ki_game_date\" WHERE game_id = $id AND begin > {$old_date['begin']}");
    $order = intval($order_result['max_order']) - 1;
    $order = 1;
    $sql -> Run ("UPDATE \"ki_game_date\" SET \"order\" = $order + 1 WHERE game_id = $id AND \"order\" >= $order");
    $sql -> Run ("UPDATE \"ki_game_date\" SET game_id = $id, \"order\" = $order WHERE game_id = $old_id AND \"order\" = {$old_date['order']}");
  }
  $sql -> Run ('COMMIT');
  $sql -> debug = FALSE;
}

function do_deletedate ($game_id, $order)
{
  $sql = connect ();

	$game_id= intval ($game_id);
	$order = intval ($order);
	
	if (0 >= $game_id)
		return FALSE;

  $sql -> Run ('START TRANSACTION');
  
  $sql -> Run ("DELETE FROM \"ki_game_date\" WHERE $game_id = game_id AND \"order\" = $order");
  $sql -> Run ("UPDATE \"ki_game_date\" SET \"order\" = \"order\" -1 WHERE $game_id = game_id AND \"order\" > $order");
  
  internal_do_update_year_index ($sql);
  $sql -> Run ('COMMIT');
}

function do_change_date_order ($game_id, $order, $sign)
{
  $sql = connect ();

	$game_id= intval ($game_id);
	$order = intval ($order);
	$sign = intval ($sign);
	$sign = ($sign) > 0 ? 1 : -1;
	$new_order = $order + $sign;
	
	if (0 >= $game_id)
		return FALSE;

  $sql -> Run ('START TRANSACTION');
  
  $sql -> Run ("UPDATE \"ki_game_date\" SET \"order\" =  -1 WHERE $game_id = game_id AND \"order\" = $order");
  
  $sql -> Run ("UPDATE \"ki_game_date\" SET \"order\" = $order WHERE $game_id = game_id AND \"order\" = $new_order");
  $sql -> Run ("UPDATE \"ki_game_date\" SET \"order\" = $new_order WHERE $game_id = game_id AND \"order\" = -1");
  
  internal_do_update_year_index ($sql);
  $sql -> Run ('COMMIT');
}

function do_movedate($id, $new_date, $days)
{
	$sql = connect ();

	$id = intval ($id);
	$new_date = $sql -> QuoteAndClean ($new_date);
	$days = intval ($days);

	if (0 >= $id )
		return FALSE;

	$sql -> Run ('START TRANSACTION');
	$sql -> Run ("UPDATE \"ki_game_date\" SET \"order\" = \"order\" + 1 WHERE game_id = $id ");
	$sql -> Run ("INSERT INTO \"ki_game_date\" 
		(game_id, \"order\", \"begin\", \"time\") 
		VALUES ($id, 0, $new_date, $days)
		");
	internal_do_update_year_index ($sql);
	$sql -> Run ('COMMIT');
	return TRUE;
}


function do_game_delete ($game_id)
{
	$sql = connect ();

	$game_id = intval ($game_id);

	if (0 >= $game_id)
		return FALSE;

	$sql -> Run ('START TRANSACTION');

	$game = $sql -> GetObject ('ki_games', $game_id);

	if ($game === FALSE)
	{
		$sql -> Run ('ROLLBACK');
		return FALSE;
	}

	$begin_date = strtotime ($game['begin']);
	$days = $game['time']-1;
	$end_date = getdate(strtotime ("+$days day", $begin_date));
	$begin_date = getdate ($begin_date);

	internal_log_game(3, $game_id);

	$sql -> Run ("UPDATE ki_games SET deleted_flag = 1 WHERE \"id\" = $game_id");
	
	internal_do_update_year_index ($sql);
	$sql -> Run ('COMMIT');
	return TRUE;
}

function get_year_list_full()
{
  $sql = connect();
  return $sql -> Query("
			SELECT 
			DISTINCT year
			FROM \"ki_years_cache\" kyc
			UNION ALL
			SELECT MAX(year) + 1
			FROM \"ki_years_cache\" kyc
			UNION ALL
			SELECT MIN(year) - 1
			FROM \"ki_years_cache\" kyc
			ORDER BY year DESC
			");
}

function cleanup_string_field ($sql, $field)
{
	$field = trim($field);
	return strlen($field) ? $sql -> QuoteAndClean ($field) : 'NULL';
}

function do_game_update ($id, $name, $uri, $type, $polygon, $mg, $email, $show_flags, $status, $comment, $sub_region, $hide_email, $players_count, $send_email, $allrpg_info_id, $user_add, $vk_club, $lj_comm, $fb_comm)
{
	$sql = connect();
	
	$id 		= intval($id);
	$_name 		= $sql -> QuoteAndClean (trim($name));
	$_uri 		= $sql -> QuoteAndClean (trim($uri));
	$type		= intval ($type);
	$polygon	= intval ($polygon);
	$_mg			= $sql -> QuoteAndClean (trim($mg));
	$_email		= $sql -> QuoteAndClean (trim($email));
	$show_flags	= intval ($show_flags);
	$status		= intval ($status);
	$_comment	= $sql -> QuoteAndClean (trim($comment));
	$sub_region	= intval ($sub_region);
	$hide_email = intval ($hide_email);
	$players_count = intval ($players_count);
	$allrpg_info_id = intval($allrpg_info_id) > 0 ?intval ($allrpg_info_id) : 'NULL' ;
	$user_add = intval ($user_add);

	$vk_club = normalize_link(cleanup_string_field ($sql, $vk_club));
	$lj_comm = normalize_link(cleanup_string_field ($sql, $lj_comm));
	$fb_comm = normalize_link(cleanup_string_field ($sql, $fb_comm));
	
	if ($players_count == 0)
	{
    $players_count = 'NULL';
	}
	
	$user_id	= intval (get_user_id ());
	
	if ($user_id == 0 && !$user_add)
		return FALSE;
		
	if ($id && $user_add)
	{
		return FALSE;
	}
		
	$deleted_flag = $user_add ? -1 : 0;
	
	$list = "SET 
		\"name\" = $_name, 
		\"uri\" = $_uri, 
		\"type\" = $type, 
		\"polygon\" = $polygon,
		\"mg\" = $_mg, 
		\"email\" = $_email, 
		\"show_flags\" = $show_flags, 
		\"status\" = $status, 
		\"comment\" = $_comment, 
		\"sub_region_id\" = $sub_region, 
		\"deleted_flag\" = $deleted_flag,
		\"hide_email\" = $hide_email,
		\"players_count\" = $players_count,
		\"allrpg_info_id\" = $allrpg_info_id,
		\"vk_club\" = $vk_club,
		\"lj_comm\" = $lj_comm,
		\"fb_comm\" = $fb_comm
		";
	
	$sql -> Run ('START TRANSACTION');
	
	if ($id > 0)
	{
		$prev_data = $sql -> GetObject('ki_games', $id);
		
		$old_deleted = intval($prev_data['deleted_flag']);
		$old_status = intval($prev_data['status']);
		$old_begin =getdate(strtotime ($prev_data['begin']));
		if ($old_deleted == 1)
		{
			internal_log_game (7, $id);
		}
		elseif ($old_deleted == -1)
		{
			internal_log_game (20, $id);
		}
		
		
		if ($old_status != $status)
		{
     		$status_names = get_array('status');
			internal_log_game (8, $id, "{$status_names[$old_status]} -> {$status_names[$status]}");
		}
		
		if (($name != $prev_data['name']) || ($uri != $prev_data['uri']) || ($type != $prev_data['type'])
		|| ($polygon != $prev_data['polygon']) || ($mg != $prev_data['mg']) || ($email != $prev_data['email']) || ($show_flags != $prev_data['show_flags'])
		|| ($comment != $prev_data['comment']) || ($sub_region != $prev_data['sub_region_id']) || ($hide_email != $prev_data['hide_email'])
		|| $players_count != $prev_data['players_count'] || ($vk_club != $prev_data['vk_club']) || ($lj_comm != $prev_data['lj_comm']) || ($fb_comm != $prev_data['fb_comm']))
		{
			internal_log_game (2, $id);
		}

		$sql -> Run ("UPDATE ki_games $list WHERE \"id\" = $id");
		$sql -> Run ("DELETE FROM ki_add_uri WHERE \"allrpg_info_id\" = $allrpg_info_id");
	}
	else
	{	
		$sql -> Run ("INSERT INTO ki_games
		(\"name\", \"uri\", \"type\", \"polygon\", mg, email, \"show_flags\", \"status\",  \"comment\", 
		\"sub_region_id\", \"deleted_flag\", \"hide_email\", \"players_count\", \"allrpg_info_id\",
		\"vk_club\", \"lj_comm\", \"fb_comm\")

		VALUES

		($_name, $_uri, $type, $polygon, $_mg, $_email, $show_flags, $status, $_comment,  
		 $sub_region, $deleted_flag, $hide_email, $players_count, $allrpg_info_id,
		 $vk_club, $lj_comm, $fb_comm)
		
		");
		$id = $sql -> LastInsert ();
		internal_log_game ($user_add ? 19 : 1, $id);
		$sql -> Run ("DELETE FROM ki_add_uri WHERE \"allrpg_info_id\" = $allrpg_info_id");
	}

	internal_do_update_year_index ($sql);
	$sql -> Run ('COMMIT');
	
	return $id;
}
?>