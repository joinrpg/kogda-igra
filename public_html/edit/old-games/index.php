<?php
	require_once 'funcs.php';
	require_once 'logic/gamelist.php';
	require_once 'calendar.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}

	$sql = connect();

	if (get_post_field('delete'))
	{
			$old_game_id = intval(get_post_field('old_game_id'));
			$sql -> Run ("DELETE FROM old_games WHERE old_game_id = $old_game_id");
			header("Location: /edit/old-games/");
			die();
	}

	write_header('Старые игры rpg.ru');
	echo '<h1>Старые игры rpg.ru</h1>';
	show_greeting();

	$list = $sql -> Query ("SELECT * FROM `old_games`");
	?>
	<table><tr><th>Игра</th><th>Дата</th><th>Регион</th><th>Ссылка</th><th>&nbsp;</th></tr>
	<?php
	foreach ($list as $game)
	{
    echo "<tr>
      <td><a href=\"/edit/game/?old_id={$game['old_game_id']}\">Игра: {$game['game_name']}</a></td>
      <td>{$game['game_date']}</td>
      <td>{$game['game_region']}</td>
      <td>{$game['game_uri']}</td>
      <td>
      	<form action=\"\" method=\"post\" onSubmit=\"return confirm('Действительно хотите удалить {$game['game_name']}?');\">
      	<input type=\"hidden\" name=\"delete\" value=\"1\">
      	<input type=\"hidden\" name=\"old_game_id\" value=\"{$game['old_game_id']}\">
      	<input type=\"submit\" value=\"Удалить\">
      	</form>
      </td>
      </tr>";
	}
	echo '</table>';

	write_footer();

	?>