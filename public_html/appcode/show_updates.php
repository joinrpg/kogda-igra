<?php
require_once 'funcs.php';
require_once 'review.php';
function write_update_line($game, $colspan)
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
		if ($game['ip_address'])
		{
			$ip = " ({$game['ip_address']})";
		}
		else
		{
			$ip = '';
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
		echo "$date $user$ip: $update_text {$game['msg']}";
		echo "</td></tr>";
}

?>