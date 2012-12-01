<?php
require_once 'funcs.php';
require_once 'review.php';

function write_update_line($game, $colspan)
{
	return write_update_line_with_ip($game, $colspan, 1); 
}

function write_update_line_with_ip($game, $colspan, $show_ip)
{
    echo "<tr><td colspan=\"$colspan\">";
		$update_text = htmlspecialchars ($game['ki_update_type_name']);
		$user = htmlspecialchars ($game['username']);
		$date = formate_single_date($game['update_date']);
		if ($user)
		{
			$user = show_user_link($user);
		}
		else
		{
			$user = 'Аноним';
		}
		if ($game['ip_address'] and $show_ip)
		{
			$ip = "{$game['ip_address']}";
			$ip_info = " ({$ip}<a href=\"/lenta/ip/{$ip}\">?</a>)";
		}
		else
		{
			$ip_info = '';
		}
		if ($game['update_type_polygon_flag'] > 0)
		{
      $update_text .= ' '. $game['polygon_name'].'';
		}
		if ($game['id'])
		{
      $update_text .= ' «<a href="/game/'. $game['id'] . '">' . $game['name'].'</a>»';
		}
		if ($game['updated_user_name'])
		{
      $update_text .= " " . show_user_link ($game['updated_user_name']);
		}
		if ($game['update_type_review_flag'])
		{
			$review_uri = ReviewBase :: get_review_uri($game);
			if ($review_uri)
			{
				$update_text .= " [<a href=\"$review_uri\">Текст рецензии</a>]";
			}
		}
		$uri = $game['uri'];
		$allrpg_info_id = $game['allrpg_info_id'];
		if ($allrpg_info_id &&!$uri)
		{
			$uri = "http://inf.allrpg.info/events/$allrpg_info_id/";
		}
		if ($uri)
		{
			$update_text .= " <a href=\"$uri \">$uri </a>";
		}
		echo "$date $user$ip_info: $update_text {$game['msg']}";
		echo "</td></tr>";
}

?>