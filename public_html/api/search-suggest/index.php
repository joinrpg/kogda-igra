
<?php
	
	require_once 'logic/search.php';
	

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