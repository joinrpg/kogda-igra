<?php
	function format_vk_link ($vk_club)
	{
		$vk_club = trim ($vk_club);
		return "https://vk.com/$vk_club";
	}
	
	function format_lj_link ($lj_comm)
	{
		$lj_comm = trim ($lj_comm);
		return "http://$lj_comm.livejournal.com/profile";
	}
	
	function format_fb_link ($fb_comm)
	{
		$fb_comm = trim ($fb_comm);
		return "https://www.facebook.com/groups/$fb_comm/";
	}
	
	function normalize_link($link)
	{
		static $prestfixes = array (
		'http://', 'https://', 
		'vk.com/', 'vkontakte.ru/', 
		'.livejournal.com', '.lj.ru', 
		'facebook.com/groups/', 
		'/');
		return str_replace ($prestfixes, '', $link);
	}
?>