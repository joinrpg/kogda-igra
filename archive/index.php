
<?php
	require_once 'funcs.php';
	require_once 'logic/gamelist.php';
  write_header("Когда-Игра :: Архив старых лет");
?>

<div style="margin:1em">
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
echo '</div>';

  write_footer(TRUE);
?>