<?php

require_once 'mysql.php';

function get_sql_array_rq ($request, $id, $name)
{
	$sql = connect();
   $arr = $sql -> Query ($request);
   foreach ($arr as $row)
   {
       $result[$row[$id]] = $row[$name];
   }
   return $result;
}


function get_sql_array ($table, $id, $name, $order = FALSE)
{
	$sql = connect();
	if (!$order)
	{
		$order = $id;
	}
   $arr = $sql -> Query ("SELECT `$id`, `$name` FROM `$table` ORDER BY $order");
   foreach ($arr as $row)
   {
       $result[$row[$id]] = $row[$name];

   }
   return $result;
}
	function get_scalar ($name, $key)
	{
		$lib = get_array ($name);
		if (array_key_exists ($key, $lib))
		{
			return $lib[$key];
		}

		return NULL;
	}

	function connect()
	{
		static $driver;
		if (!isset($driver))
		{
			$driver = new Sql ('mysql.1.leotsar.cz8.ru', 'dbu_leotsar_1', '3iNYxdDViuj', 'db_leotsar_1');
			$driver->Run ('SET NAMES utf8');
			$driver->Run ('SET CHARACTER SET utf8');
		}
		return $driver;
	}

?>