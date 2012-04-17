<?php
require_once 'sqlbase.php';
function update_vk_likes_for_game ($game_id)
{
	$game_id = intval ($game_id);
	$result = json_decode (file_get_contents ("https://api.vkontakte.ru/method/likes.getList?owner_id=2118784&page_url=http://kogda-igra.ru/game/$game_id&type=sitepage"), true);
	var_dump ($result);
	$count = intval ($result['response']['count']);
	$sql = connect();
	$sql -> Run ("UPDATE ki_games SET vk_likes = $count WHERE id = $game_id");
}
?>