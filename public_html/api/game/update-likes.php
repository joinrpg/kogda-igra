<?php
require_once 'vk.php';
$id = array_key_exists('id', $_GET) ? intval($_GET['id']) : '';
update_vk_likes_for_game ($id);