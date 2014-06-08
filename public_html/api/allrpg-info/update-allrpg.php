
<?php
	require_once 'logic/edit.php';
	require_once 'funcs.php';
	require_once 'logic/allrpg.php';

	$id = intval(get_post_field('id'));
	$allrpg_id = intval(get_post_field('allrpg'));
	if (!$id || !$allrpg_id)
	{
    header("HTTP/1.1 404 Not Found");
    echo "$id $allrpg_id";
    die();
	}
	
	if (!check_edit_priv())
	{
    return_to_main();
	}
  set_allrpg ($id, $allrpg_id);
	


	?>