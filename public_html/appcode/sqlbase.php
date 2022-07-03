<?php

require_once 'mysql.php';
require_once 'config.php';

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
   $arr = $sql -> Query ("SELECT \"$id\", \"$name\" FROM \"$table\" ORDER BY $order");
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
		global $sql_server, $sql_user, $sql_pass, $sql_db, $sql_port;
		if (!isset($driver))
		{
			$driver = new Sql ($sql_server, $sql_user, $sql_pass, $sql_db, $sql_port);
		}
		return $driver;
	}

?>