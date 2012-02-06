
<?php
	require_once 'funcs.php';
	require_once 'mysql.php';
	require_once 'common.php';
	require_once 'user_funcs.php';
	require_once 'logic.php';
	require_once 'calendar.php';
	define ('DEFAULT_YEAR', 2009);

	$search_string = array_key_exists('search', $_GET) ? trim($_GET['search']) : '';

	$suggestions = get_suggestions($search_string);
	$variants = '';
	foreach ($suggestions as $variant)
	{
    if ($variants != '')
    {
      $variants .= ', ';
    }
    $variants .= "\"{$variant['name']}\"";
	}
		echo "[\"$search_string\", [$variants]]";


	?>