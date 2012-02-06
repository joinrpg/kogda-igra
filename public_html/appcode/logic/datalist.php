<?php
require_once 'funcs.php';

function get_mg_list()
{
	$sql = connect();
	$result = $sql -> Query("SELECT DISTINCT kg.mg FROM ki_games kg WHERE `deleted_flag` = 0 ORDER BY kg.mg");
	foreach ($result as $row)
	{
		$mg[] = $row['mg'];
	}
	return $mg;
}

function get_email_list()
{
	$sql = connect();
	$result = $sql -> Query("SELECT DISTINCT kg.email FROM ki_games kg WHERE `deleted_flag` = 0 ORDER BY kg.email");
	foreach ($result as $row)
	{
		$mg[] = $row['email'];
	}
	return $mg;
}
?>