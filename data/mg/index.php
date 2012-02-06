<?php
require_once 'funcs.php';
require_once 'logic/datalist.php';

write_header('Список МГ');
echo '<h1>Список МГ</h1><table>';

$mg = get_mg_list();

foreach ($mg as $mg_name)
{
	$url = 'http://kogda-igra.ru/find/' . urlencode($mg_name);
	$mg_name = htmlspecialchars($mg_name);
	echo "<tr><td><a href=\"$url\">$mg_name</a></td></tr>";
}

echo '</table>';
write_footer();
?>