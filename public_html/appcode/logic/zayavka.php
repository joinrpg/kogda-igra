<?php
require_once 'logic/gamebase.php';

function get_zayvka_allrpg_info ()
{
	$sql = connect();
	$result =  $sql -> Query("
		SELECT allrpg_zayvka_id, game_id, kia.name, opened, kg.name AS game_name
		FROM ki_zayavka_allrpg kia
		LEFT JOIN ki_games kg ON kg.id = kia.game_id");
	
	if (is_array($result))
	{
		foreach ($result as $row)
		{
			$zayvka[$row['allrpg_zayvka_id']] = $row;
		}
		return $zayvka;
	}
	else
	{
		return array();
	}
}


function load_allrpg_info_zayvka()
{
	$curl = curl_init ('http://inf.allrpg.info/kogdaigra.php?open_list=1');
	curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
	$json_result = curl_exec ($curl);

	$json_result = iconv("UTF-8","UTF-8//IGNORE",$json_result);

	$result = json_decode($json_result, true);

	foreach ($result as $row)
	{
		$loaded_result[$row['allrpg_id']] = array ('allrpg_zayvka_id' => $row['allrpg_id'], 'name' => $row['name'], 'opened' => 1);
	}
	return $loaded_result; 
}

function save_allrpg_info_appl ($row)
{
	$sql = connect();
	$allrpg_zayvka_id = intval ($row['allrpg_zayvka_id']);
	$name = $sql -> QuoteAndClean ($row['name']);
	$opened = intval ($row['opened']);
	$sql -> Run ("
		INSERT INTO `ki_zayavka_allrpg` (`allrpg_zayvka_id`, `game_id`, `name`, `opened`) VALUES ($allrpg_zayvka_id, NULL, $name, $opened)
		ON DUPLICATE KEY UPDATE `name` = VALUES (name), opened = VALUES (opened)");
}

function get_allrpg_info_appl()
{
	$cached = get_zayvka_allrpg_info();
	$loaded = load_allrpg_info_zayvka();

	foreach ($loaded as $key => $row)
	{
		if (array_key_exists($row['allrpg_zayvka_id'], $cached))
		{
			$cached_row = $cached[$row['allrpg_zayvka_id']];
			if ($row['name'] != $cached_row['name'] || $row['opened'] != $cached_row['opened'])
			{
				save_allrpg_info_appl($row);
			}
			$loaded[$key]['game_id'] = $cached_row['game_id'];
			$loaded[$key]['game_name'] = $cached_row['game_name'];
			unset ($cached[$row['allrpg_zayvka_id']]);
		}
		else
		{
			save_allrpg_info_appl($row);
		}
	}

	foreach ($cached as $row)
	{
		$row['opened'] = 0;
		save_allrpg_info_appl ($row);
	}
	return $loaded;
}


function allrpg_bound_game ($game_id, $allrpg_zayvka_id)
{
	$sql = connect();
	
	$game_id = intval ($game_id);
	$allrpg_zayvka_id = intval ($allrpg_zayvka_id);
	
	$sql -> Run ("UPDATE `ki_zayavka_allrpg` SET game_id = $game_id WHERE allrpg_zayvka_id = $allrpg_zayvka_id");
}

function allrpg_unbound_game ($allrpg_zayvka_id)
{
	$sql = connect();
	
	$allrpg_zayvka_id = intval ($allrpg_zayvka_id);
	
	$sql -> Run ("UPDATE `ki_zayavka_allrpg` SET game_id = NULL WHERE allrpg_zayvka_id = $allrpg_zayvka_id");
}
?>