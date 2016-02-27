
<?php
	require_once 'funcs.php';
	require_once 'logic/gamelist.php';
	require_once 'top_menu.php';
	
	$topmenu = new TopMenu();
  $topmenu -> pagename = 'Архив старых лет';
  $topmenu -> show();
?>

<h1>Архив старых лет</h1>
<?php
$years_list = get_year_list ('/');
foreach ($years_list as $year_val)
{
	$year = $year_val['year'];
	$year_text = "<a href=\"/$region$year/\">$year</a>";
	echo "$sep$year_text";
	$sep = " :: ";
}

  write_footer(TRUE);
?>