<?php
	require_once 'funcs.php';
  require_once 'logic/problems.php';

	if (!check_edit_priv())
	{
		return_to_main();
	}
	
	
	write_header('Проблемные игры');
	echo '<h1>Проблемные игры :: Дубли</h1>';
	show_greeting();
	$calendar = get_merge_candidates();
	echo '<table>';
	echo '<tr><th>Игра</th><th>Количество дублей</th></tr>';
	foreach ($calendar as $game)
	{
    echo "<tr><td><a href=\"/edit/merge/?name={$game['name']}\">{$game['name']}</a></td><td>{$game['gamecount']}</td>";
	}
	echo '</table><br />';
	
	
	write_footer();

	?>