<?php
require_once 'sqlbase.php';
require_once 'config.php';
require_once 'uifuncs.php';
function update_vk_likes_for_game ($game_id)
{
	$game_id = intval ($game_id);
	
	$profile_uri = get_game_profile_link($game_id);
	$result = json_decode (file_get_contents ("https://api.vkontakte.ru/method/likes.getList?owner_id=2118784&page_url=$profile_uri&type=sitepage"), true);
	var_dump ($result);
	$count = intval ($result['response']['count']);
	$sql = connect();
	$sql -> Run ("UPDATE ki_games SET vk_likes = $count WHERE id = $game_id");
}
?>