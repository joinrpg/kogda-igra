<?php
	require_once 'sqlbase.php';
	
	function get_region_dict()
	{
		$sql = connect();
		return $sql -> Query('
				SELECT ksr.*, kr.region_name
				FROM ki_sub_regions ksr
				INNER JOIN ki_regions kr ON kr.region_id = ksr.region_id
				ORDER BY ksr.sub_region_name');
	}
?>