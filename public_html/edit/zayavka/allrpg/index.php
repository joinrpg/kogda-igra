<?php

require_once 'logic/zayavka.php';
require_once 'logic/gamelist.php';
require_once 'funcs.php';
require_once 'top_menu.php';

if (!check_edit_priv())
{
	return_to_main();
}

$action = get_post_field ('action');


if ($action == 'bound')
{
	allrpg_bound_game (get_post_field('game_id'), get_post_field('allrpg_zayvka_id'));
	header ("Location: /edit/zayavka/allrpg/");
	die();
}
elseif ($action == 'unbound')
{
		allrpg_unbound_game (get_post_field('allrpg_zayvka_id'));
	header ("Location: /edit/zayavka/allrpg/");
		die();
}

$top_menu = new TopMenu();
$top_menu -> show ();

$loaded = get_allrpg_info_appl();
$games = get_future_games();
echo '<table>';
echo '<tr><th colspan=2>Базы заявок, не связанные с играми</th></tr>';
foreach ($loaded as $row)
{
	$game_id = $row['game_id'];
	if ($game_id)
	{
		continue;
	}
	echo '<tr>';
	echo "<td><a href=\"http://www.allrpg.info/siteroles/{$row['allrpg_zayvka_id']}/\">{$row['name']}</a></td>";
	echo "<td>";
	
		echo "<form id=all method=post><select name=game_id>";
		foreach ($games as $game)
		{
			echo "<option value=\"{$game['id']}\">{$game['name']}</option>";
		}
		echo "</select>";
		echo "<input type=submit value='Привязать'>";
		echo "<input type=hidden name=action value=bound>";
		echo "<input type=hidden name=allrpg_zayvka_id value={$row['allrpg_zayvka_id']}>";
		echo "</form>";

	echo "</td>";
	echo '</tr>';
}
echo '</table>';
echo '<br>';
echo '<table>';
echo '<tr><th colspan=3>Открытые заявки</th></tr>';
foreach ($loaded as $row)
{
	$game_id = $row['game_id'];
	if (!$game_id)
	{
		continue;
	}
	echo '<tr>';
	echo "<td><a href=\"http://www.allrpg.info/siteroles/{$row['allrpg_zayvka_id']}/\">{$row['name']}</a></td>";
	echo "<td>";


		echo "<a href=\"/game/$game_id/\">{$row['game_name']}</a>";
		echo "</td>";
		echo "<td>";
				echo "<form id=all method=post>";
		echo "<input type=submit value='Отвязать'>";
		echo "<input type=hidden name=action value=unbound>";
		echo "<input type=hidden name=allrpg_zayvka_id value={$row['allrpg_zayvka_id']}>";
		echo "</form>";
	echo "</td>";
	echo '</tr>';
}
echo '</table>';

	write_footer();