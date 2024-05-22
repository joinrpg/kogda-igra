<?php
require_once 'funcs.php';
require_once 'logic/gamebase.php';

function get_search($search_string)
{
  $sql = connect();
  $search_string = $sql -> QuoteAndClean("%$search_string%");
  
   return _get_games ("kg.deleted_flag = 0 AND kgd.\"order\" = 0
			AND (kg.name ILIKE $search_string 
				OR kg.mg ILIKE $search_string 
				OR kg.email ILIKE $search_string 
				OR kp.polygon_name ILIKE $search_string
				OR kg.uri ILIKE $search_string
				OR kg.lj_comm ILIKE $search_string
				OR kg.fb_comm ILIKE $search_string
				)");
}

function get_search_by_name($search_string)
{
  $sql = connect();
  $search_string = $sql -> QuoteAndClean("$search_string");
  
   return _get_games ("kg.deleted_flag = 0 AND kgd.\"order\" = 0
			AND (kg.name ILIKE $search_string)");
}


function get_suggestions($search_string)
{
  $sql = connect();
  $search_string = $sql -> QuoteAndClean("%$search_string%");
  
  $query = "SELECT DISTINCT
		kg.name AS name
		FROM \"ki_games\" kg
		INNER JOIN \"ki_status\" ks ON ks.status_id = kg.status
		WHERE
			kg.deleted_flag = 0
			AND (kg.name ILIKE $search_string)
		GROUP BY name
		UNION
		SELECT DISTINCT
		kg.mg AS name
		FROM \"ki_games\" kg
		INNER JOIN \"ki_status\" ks ON ks.status_id = kg.status
		WHERE
			kg.deleted_flag = 0
			AND (kg.mg ILIKE $search_string)
		GROUP BY mg
		UNION
		SELECT  DISTINCT kp.polygon_name
		FROM \"ki_polygons\" kp
		WHERE
       (kp.polygon_name ILIKE $search_string)
    GROUP BY polygon_name
		ORDER BY name";

	return $sql -> Query($query);
}
?>