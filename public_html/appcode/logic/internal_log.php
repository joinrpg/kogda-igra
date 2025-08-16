<?php
require_once 'funcs.php';

function get_client_ip() {
    // Список возможных заголовков, которые могут содержать IP-адрес клиента
    $ipHeaders = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ];
    
    $clientIp = null;
    
    // Проверяем все возможные заголовки
    foreach ($ipHeaders as $header) {
        if (!empty($_SERVER[$header])) {
            $ips = explode(',', $_SERVER[$header]);
            $ips = array_map('trim', $ips);
            
            // Берем первый IP из списка (если их несколько)
            $ip = $ips[0];
            
            // Проверяем, что IP валидный
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                $clientIp = $ip;
                break;
            }
        }
    }
    
    // Если ничего не нашли, используем REMOTE_ADDR
    return $clientIp ?: $_SERVER['REMOTE_ADDR'];
}

function get_user_ip_if_anon()
{
	$user_id = get_user_id();
	if ($user_id)
	{
		return NULL;
	}
	return get_client_ip();
}

function internal_log_game ($update_type, $game_id, $msg = FALSE)
{
	$sql = connect();
	$user_id = get_user_id();
	$ip =  $sql -> QuoteAndClean(get_user_ip_if_anon());
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
	$ip =  $sql -> QuoteAndClean(get_user_ip_if_anon());
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