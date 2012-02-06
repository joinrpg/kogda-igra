<?php
	require_once 'review.php';
	require_once 'funcs.php';
	require_once 'calendar.php';
	require_once 'logic/gamelist.php';

$region_result = get_region_param();
$region = $region_result['id'];
$region_name = $region_result['name'];

$year = array_key_exists('year', $_GET) ? intval($_GET['year']) : 0;

if ($year !=0 && !validate_year($year))
{
  return_to_main();
}

header('Content-type: application/vnd.ms-excel; Charset=utf-8');

header("Content-Disposition: attachment; filename=\"{$region_name} {$year}.xls\"");

	$calendar = new Calendar(get_main_calendar($year, $region, false));
	$calendar -> show_only_future = $show_only_future;
	$calendar -> check_border = FALSE;
	$calendar -> show_reviews = FALSE;
	$calendar -> export_mode = TRUE;
	$calendar -> editor = FALSE;
	$calendar -> show_cancelled_games_checkbox = FALSE;
	$calendar -> write_calendar();

?>