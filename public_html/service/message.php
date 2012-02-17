<?php
	require_once 'funcs.php';
	
	$messages = array(
		'/game/thanks' => array('hdr' => 'Добавление игры', 'msg' => 'Спасибо за добавление игры! Редакторы добавят игру в календарь как можно быстрее.'),
		'/edit/already' => array('hdr' => 'Добавление игры', 'msg' => 'Ссылка уже обработана, спасибо!'),
	);
	
	$message = $messages[$_SERVER['REQUEST_URI']];
	show_message($message['hdr'], $message['msg']);
?>