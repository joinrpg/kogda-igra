<?php
	require_once 'funcs.php';
	require_once 'calendar.php';
	require_once 'help.php';

$id = array_key_exists ('id', $_POST) ? intval($_POST['id']) : (array_key_exists ('id', $_GET) ? intval($_GET['id']) : 0) ;
if (!$id)
{
  return_to_main();
}

  write_header('Нет e-mail мастеров');
	echo '<h1>Нет e-mail мастеров</h1>';
	show_greeting();
  echo '<h2>Нет e-mail мастеров</h2>';

  $form = new EmailNeededForm($id);
  $form -> show();
  write_footer(TRUE);
?>