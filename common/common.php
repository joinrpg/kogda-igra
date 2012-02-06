<?php


function get_lj_path($ljuser, $comm)
{
	if (!$ljuser)
		return '';
	if ($comm)
	 return "http://community.livejournal.com/$ljuser";
	$legacy_path = (substr ($ljuser, -1) == '_') || ($ljuser[1] == '_');
	if ($legacy_path)
	{
		return "http://users.livejournal.com/$ljuser";
	} else {
		$ljuser = str_replace ('_', '-', $ljuser);
		return "http://$ljuser.livejournal.com";
	}
}

	function get_driver()
	{
		static $driver;
		if (!isset($driver))
		{
			require 'config.php';
			$driver = new Sql ($sql_server, $sql_user, $sql_pass, $sql_db);
				$driver->Run ('SET NAMES utf8');
				$driver->Run ('SET CHARACTER SET utf8');
		}
		return $driver;
	}
	
	
function validate_year ($year)
{
	if (settype ($year, 'int') && ($year > 1980) && ($year < 2025))
	{
				return $year;
	}

	return 0;

}
?>