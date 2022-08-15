<?php
require_once 'funcs.php';

function internal_log_game ($update_type, $game_id, $msg = FALSE)
{
	$sql = connect();
	$user_id = get_user_id();
	$ip = $user_id ? 'NULL' : "'{$_SERVER['REMOTE_ADDR']}'";
	$update_type  = intval ($update_type);
	$game_id = intval ($game_id);
	
	$last = $sql -> Query("
		SELECT * FROM ki_updates
		WHERE game_id = $game_id 
		AND (NOW() - INTERVAL '15 minutes') < update_date ORDER BY update_date DESC LIMIT 1 ");
		
	if (is_array($last) && !$msg)
	{
		$last = $last[0];
		$last_user = $last['user_id'];
		$last_type = $last['ki_update_type_id'];
		$last_id = $last['ki_update_id'];
		if ($last_user == $user_id && $last_type == $update_type)
		{
			$sql -> Run("
				UPDATE ki_updates
				SET update_date = NOW()
				WHERE ki_update_id = $last_id
			"); 
			return; //Совместим две записи для уменьшения мировой энтропии.
		}
	}
	$msg = $sql -> QuoteAndClean($msg);
	$sql -> Run ("
		INSERT INTO ki_updates 
		(ki_update_type_id, user_id, update_date, msg, ip_address, game_id)
		
		VALUES	
		($update_type, $user_id, NOW(), $msg, $ip, $game_id)
		");
}

function internal_log_review ($update_type, $review_id, $game_id, $msg = FALSE)
{
		$sql = connect();
	$user_id = get_user_id();
	$update_type  = intval ($update_type);
	$review_id = intval ($review_id);
	$game_id = intval ($game_id);
	$msg = $sql -> QuoteAndClean($msg);
	$sql -> Run ("
		INSERT INTO ki_updates 
		(ki_update_type_id, user_id, update_date, msg, review_id, game_id)
		VALUES
		($update_type, $user_id, NOW(), $msg, $review_id, $game_id)
		");
}

function internal_log_add_uri ($add_uri_id)
{
	$sql = connect();
	
	$user_id = get_user_id();
	$ip = $user_id ? 'NULL' : "'{$_SERVER['REMOTE_ADDR']}'";
	$add_uri_id = intval ($add_uri_id);
	$update_type = 19;
	$sql -> Run ("
		INSERT INTO ki_updates 
		(ki_update_type_id, user_id, update_date, msg, ip_address, add_uri_id)
		VALUES 
		($update_type, $user_id, NOW(), '', $ip, $add_uri_id)
		");
}

function internal_log_photo ($update_type, $photo_id, $game_id, $msg = FALSE)
{
	$sql = connect();
	$user_id = get_user_id();
	$update_type  = intval ($update_type);
	$photo_id = intval ($photo_id);
	$game_id = intval ($game_id);
	$msg = $sql -> QuoteAndClean($msg);
	$sql -> Run ("
		INSERT INTO ki_updates 
		(ki_update_type_id, user_id, update_date, msg, photo_id, game_id)
		VALUES
		($update_type, $user_id, NOW(), $msg, $photo_id, $game_id)
		");
}

function internal_log_polygon ($update_type, $polygon_id)
{
	$sql = connect();
	$user_id = get_user_id();
	$last = $sql -> Query("
		SELECT * FROM ki_updates
		WHERE polygon_id = $polygon_id
		AND (NOW() - INTERVAL '15 minutes') < update_date ORDER BY update_date DESC LIMIT 1");
	if (is_array($last))
	{
		$last = $last[0];
		$last_user = $last['user_id'];
		$last_type = $last['ki_update_type_id'];
		$last_id = $last['ki_update_id'];
		if ($last_user == $user_id && $last_type == $update_type)
		{
			$sql -> Run("
				UPDATE ki_updates
				SET update_date = NOW()
				WHERE ki_update_id = $last_id
			"); 
			return; //Совместим две записи для уменьшения мировой энтропии.
		}
	}
	$sql -> Run ("
		INSERT INTO ki_updates 
		(ki_update_type_id, user_id, update_date, polygon_id, msg)
		VALUES
		($update_type, $user_id, NOW(), $polygon_id, '')
		");
}

function internal_log_user ($update_type, $updated_user_id, $msg)
{
  $sql = connect();
	$user_id = get_user_id();
	$update_type  = intval ($update_type);
	$updated_user_id = intval ($updated_user_id);
	$msg = $sql -> QuoteAndClean($msg);

	$sql -> Run ("
		INSERT INTO ki_updates 
		(ki_update_type_id, user_id, update_date, msg, updated_user_id)
		VALUES
		($update_type, $user_id, NOW(), $msg,  $updated_user_id)
    ");
}
?>