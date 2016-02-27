<?php
require_once 'funcs.php';
require_once 'logic/datalist.php';
require_once 'top_menu.php';

	$topmenu = new TopMenu();
	$topmenu -> pagename =  'Список МГ';
	$topmenu -> show();

echo '<h1>Список МГ</h1><ul>';

$mg = get_mg_list();

foreach ($mg as $mg_name)
{
	$url = '/find/' . urlencode($mg_name);
	$mg_name = htmlspecialchars($mg_name);
	echo "<li><a href=\"$url\">$mg_name</a></li>";
}

echo '</ul>';
write_footer();
?>