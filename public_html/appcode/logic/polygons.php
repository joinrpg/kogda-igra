<?php
require_once 'funcs.php';
require_once 'logic/internal_log.php';

function do_polygon_delete ($id)
{
	$sql = connect();
	
	$id = intval ($id);
	
	$sql -> Run ('START TRANSACTION');
	internal_log_polygon (9, $id);
	$sql -> Run ("DELETE FROM ki_polygons WHERE \"polygon_id\" = $id");
	$sql -> Run ('COMMIT');
}

function do_polygon_update ($id, $polygon_name, $sub_region_id)
{

	$sql = connect(); 
	
	$id = intval ($id);
	$polygon_name = $sql -> QuoteAndClean ($polygon_name);
	$sub_region_id = intval ($sub_region_id);
	$meta = intval ($meta);
	
	$sql -> Run ('START TRANSACTION');
	if ($id > 0)
	{
		$prev_data = $sql -> GetObject('ki_polygons', $id);
		
		$old_deleted = intval($prev_data['deleted_flag']);
		if ($old_deleted != 0)
		{
			internal_log_polygon (12, $id);
		}
		
		internal_log_polygon (11, $id);
		$sql -> Run ("UPDATE ki_polygons SET \"polygon_name\" = $polygon_name, \"sub_region_id\" = $sub_region_id, deleted_flag = 0 WHERE \"polygon_id\" = $id");
	}
	else
	{
		
		$sql -> Run ("INSERT INTO ki_polygons (polygon_name, sub_region_id, deleted_flag) VALUES ($polygon_name, $sub_region_id, 0)");
		$id = $sql ->LastInsert();
		internal_log_polygon (10, $id);
	}
	$sql -> Run ('COMMIT');
}
?>