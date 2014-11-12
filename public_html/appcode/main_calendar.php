<?php
require_once 'logic/gamelist.php';
require_once 'calendar.php';

class MainCalendar extends Calendar {
  function __construct ($year, $region, $konvent)
  {
    Calendar::__construct(get_main_calendar($year, $region, FALSE, $konvent));
  }
  
  function get_date_string ($date)
  {
		return $date -> show_date_string(FALSE);
  }
  
  function write_border ($month_name)
  {
		$colspan = count($this -> columns);
		echo "<tr class=\"month_header\"><td colspan=$colspan>$month_name</td></tr>";
  }
  
  function get_month_with_games()
  {
		foreach ($this -> games_array as $game)
		{
			$date = new GameDate ($game);
			
			$tmp[$date -> month()] = TRUE;
		}
		
		$result = array();
		for ($i = 0; $i <= 12; $i++)
		{
			if (array_key_exists ($i, $tmp))
			{
				$result[] = $i;
			}
		}
		return $result;
  }
  
  function show_border_if_needed($date)
  {
	if ($this -> prev_date && ($this -> prev_date -> month()  == $date -> month()))
	{
    return;
	}
	if (!$this -> prev_date)
	{
		foreach ($this -> get_month_with_games() as $i)
		{
			$id = GameDate :: get_month_id ($i);
			$month_name = GameDate :: get_russian_month_name ($i);
			
			$month_menu[] = ($i == $date -> month())
        ? "<b>$month_name</b>" 
        : "<a href=\"#$id\">$month_name</a>";
		}
		$this -> write_border ('<br>' . implode (" ", $month_menu));
	}
	else 
	{
		$id = GameDate :: get_month_id ($date -> month());
		$month_name = GameDate :: get_russian_month_name ($date -> month());
		
		$this -> write_border ("<b id=\"$id\"> <br>$month_name</b> <a href=\"#top\">^</a>");
	}
	$this -> prev_date = $date;
  }
}
?>