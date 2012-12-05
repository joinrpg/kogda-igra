<?php
	function format_vk_link ($vk_club)
	{
		$vk_club = trim ($vk_club);
		return "http://vk.com/$vk_club";
	}
	
	function format_lj_link ($lj_comm)
	{
		$lj_comm = trim ($lj_comm);
		return "http://$lj_comm.livejournal.com/profile";
	}
	
	function normalize_link($link)
	{
		static $prestfixes = array ('http://', 'vk.com/', 'vkontakte.ru/', '.livejournal.com', '.lj.ru', '/');
		return str_replace ($prestfixes, '', $link);
	}
?>