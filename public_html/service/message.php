<?php
	require_once 'funcs.php';
	
	$messages = array(
		'/game/thanks' => array('hdr' => 'Добавление игры', 'msg' => 'Спасибо за добавление игры! Редакторы добавят игру в календарь как можно быстрее.'));
	
	$message = $messages[$_SERVER['REQUEST_URI']];
	show_message($message['hdr'], $message['msg']);
?>