<?php

class Sql
{
	function Sql ($host, $user, $password, $base)
	{
    	$this -> debug = 0;

		echo "host=$host dbname=$base user=$user password=$password";
		$this->handle = pg_connect("host=$host dbname=$base user=$user password=$password  options='--client_encoding=UTF8'");
		if ($this->handle)
		{
			return $this->handle;
		}
		die(pg_last_error());
		return FALSE;
	}

	function Close ()
	{
		if ($this->handle)
		{
			return @pg_close($this->handle);
		}
		return FALSE;
	}

	function Query ($sql)
	{
    	$start = microtime(true);
		$result = pg_query ( $this->handle, $sql);
    $elapsed_secs = microtime(true) - $start;
    
    if ($elapsed_secs > 1)
    {
      trigger_error ("SLOW QUERY [$elapsed_secs sec]: $sql", E_USER_WARNING);
    }

		if (!$result)
		{
					echo pg_last_error();
			return FALSE;

			}

		$array = FALSE;

		while ($row = pg_fetch_array($result, null, PGSQL_ASSOC))
		{
			$array [] = $row;
		}
		echo pg_last_error();
		return $array;
	}

	function GetAll ($table, $what = '*', $condition = '1', $limit = 0, $orderby = '')
	{
		$sql = "SELECT $what FROM $table WHERE $condition";

		if ($orderby)
			$sql .= " ORDER BY $orderby";

					if ($limit)
			$sql .= " LIMIT $limit";

		return $this->Query ($sql);
	}

	function Quote($value)
	{
    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }

    if (!is_numeric($value)) {
        $value = "'" . pg_escape_string($this-> handle, $value) . "'";
    }

    return $value;
	}

	function QuoteAndClean($value)
	{
    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }

    $value = strip_tags ($value);

    if (!is_numeric($value)) {
        $value = "'" . pg_escape_string($this-> handle, $value) . "'";
    }

    return $value;
	}

	function Run ($request)
	{
    if ($this -> debug)
    {
      echo "$request<br>";
      return 1;
    }
		$res = pg_query ($this->handle, $request);
		
		$error = pg_last_error();
		
		if ($error)
		{
      echo "$request $error";
		}

		return $res;
	}

	function LastInsert ()
	{
		$result =  $this -> GetRow('SELECT lastval();');
		if (!$result)
			return FALSE;

		return $result[0];
	}
	
	function GetAffectedCount()
	{
		return pg_affected_rows($this -> handle);
	}
	
	function GetRow ($query)
	{
		$result = $this -> Query ($query);

		if (!$result)
			return FALSE;

		return $result[0];
	}
	
	function begin()
	{
    $this -> Run ('START TRANSACTION');
	}
	
	function commit()
	{
    $this -> Run ('COMMIT');
	}
	
	function rollback()
	{
    $this -> Run ('ROLLBACK');
	}

	function GetObject ($table, $id = FALSE)
	{
		if ($id == FALSE)
			$id = $this->LastInsert();
		$id = $this -> Quote ($id);
		$sql = "SELECT * FROM $table WHERE \"id\" = $id LIMIT 1";
		$result = $this -> Query ($sql);

		if (!$result)
			return FALSE;


		return $result[0];
	}

	function DeleteObject ($table, $id)
	{

		$id = $this -> Quote ($id);

		$req = "DELETE FROM $table WHERE \"id\" = $id LIMIT 1";

		return $this -> Run ($req);
	}

}

?>