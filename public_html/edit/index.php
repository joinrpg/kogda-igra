<?php
	function show_count_link($link, $text, $count)
	{
    if ($count)
    {
      echo "<li><a href=\"$link\">$text</a>&nbsp;($count)</li>";
    }
    else
    {
      echo "<li>$text (0)</li>";
    }
  }
  
	require_once 'funcs.php';
	require_once 'user_funcs.php';
	require_once 'logic.php';
	require_once 'forms.php';
	require_once 'logic/gamelist.php';
	require_once 'calendar.php';
	require_once 'top_menu.php';
	require_once 'logic/updates.php';
	require_once 'show_updates.php';

	if (!check_edit_priv())
		return_to_main();
	
	
	$problems_row = get_problems_summary();
	$noinfo = intval($problems_row['noinfo_count']);
	$passed = intval($problems_row['passed_count']);
	$email = intval($problems_row['noemail_count']);
	$email_future = intval($problems_row['noemail_count_future']);
	$polygon = intval($problems_row['nopolygon_count']);
	$review = intval($problems_row['reviewed_count']);
	$noplayers_count = intval ($problems_row['noplayers_count']);
	$noplayers_count_future = intval ($problems_row['noplayers_count_future']);
	$noallrpg_info_count = intval ($problems_row['noallrpg_info_count']);
	$noallrpg_info_count_future = intval ($problems_row['noallrpg_info_count_future']);
	$comment_count = intval($problems_row['comment_count']);
	$old_games_count = intval($problems_row['old_games_count']);
	
	$editor_stats = get_editors_statistics();
	$users_report = get_user_privs_report();
	
	$user_data = array();
	foreach ($editor_stats as $editor_row)
	{
		$user_data[$editor_row['username']] = $editor_row;
	}
	
	foreach ($users_report as $user_row)
	{
		if (array_key_exists('privs', $user_row))
    {
       $user_data[$user_row['username']]['privs'] = $user_row['privs'];
    }
	}

	write_header('Панель управления');

	$topmenu = new TopMenu();
	$topmenu -> pagename = 'Панель управления';
	$topmenu -> show();
	
	
	$list = get_games_for_moderate();
	if (is_array($list))
	{
		echo '<h2>Модерация игр</h2>';
		$calendar = new Calendar($list);
		$calendar -> show_cancelled_games_checkbox = FALSE;
		$calendar -> write_calendar();
	}
	
	$uri_list = get_add_uri_list();
	if (is_array($uri_list))
	{
		echo '<h2>Ссылки на анонсы</h2>
			<table>';
		foreach ($uri_list as $uri)
		{
			$link = htmlspecialchars($uri['uri']);
			$id = $uri['add_uri_id'];
			$allrpg_info_id = $uri['allrpg_info_id'];
			if ($allrpg_info_id &&!$link)
			{
				$link = "http://inf.allrpg.info/events/$allrpg_info_id/";
			}
			echo "<tr><td><a href=\"/edit/game/?add_uri_id=$id\">$link</a></td></tr>";
		}
		echo '</table>';
		
	}
	?> 
	<br />
	<table class="control_panel" style="clear:both">
	<tr>
    <th rowspan=2>Панель управления</th>
    <th rowspan=2>Материалы</th>
    <th rowspan=2>Лента изменений</th>
    <th rowspan=2>Экспорт</th>
    <th colspan=2>Проблемные игры</th>
  </tr>
  <tr>
		<th>Все</th>
		<th>В будущем</th>
  </tr>
	<tr>
        <td>
      <ul>
				<li><a href="/stat/">Статистика</a></li>
        <li><a href="/edit/deleted-games/">Удаленные игры</a></li>
        <?php
	if (check_my_priv (EDIT_POLYGONS_PRIV))
	{
		echo '<li><a href="/edit/polygons/">Полигоны</a></li>';
	}
	?>		<li><a href="https://docs.google.com/document/pub?id=10ldHSE3Ss3b8co46rd8vyHgePf-Ohw7bhkCIy76Vfrk">Инструкция для редакторов</a></li>
       </ul>
     
     </td>
      
     <td>
     <ul>
        <?php 	show_count_link('/reviews/', 'Игры с рецензиями', $review); ?>
        <?php 	show_count_link('/photo/', 'Игры с фотоотчетами', intval($problems_row['photed_count'])); ?>
      </ul>
     </td>
    
     <td>
      <ul>
        <li><a href="/lenta/internal/">Все изменения</a></li>
        <li><a href="/lenta/polygon/">Полигоны</a></li>
        <li><a href="/lenta/patrol/">Патруль</a></li>
        <li><a href="/lenta/photo/">Фото</a></li>
        <li><a href="/lenta/photo-patrol/">Фото-Патруль</a></li>
      </ul>
     </td>
     <td>
      
      <form action="/data/export/excel/" method="get">
        <?php
          show_dropdown_with_data ('region', get_array('region_display'));
          echo '<br>';
          show_dropdown ('year');
        ?>
        <br>
        <input type="submit" value="Экспорт в XLS" >
      </form>
     </td>
     <td>
           <ul>
              <?php show_count_link('/edit/problems/noinfo/', 'Нет информации', $noinfo);
        show_count_link('/edit/problems/passed/', 'Прошедшие игры в статусе «OK»', $passed);
        show_count_link('/edit/problems/noemail/', 'Нет email', $email);
        show_count_link('/edit/problems/nopolygon/', 'Нет полигона', $polygon);
        show_count_link('/edit/problems/noplayers-count/', 'Не указано кол-во игроков', $noplayers_count);
        show_count_link('/edit/problems/allrpg-info/', 'Нет ссылки на allrpg.info', $noallrpg_info_count);
        show_count_link('/edit/problems/comment/', 'Комментарии', $comment_count);
        show_count_link('/edit/old-games/', 'Импорт из rpg.ru', $old_games_count);
        ?>
        <!--<li><a href="/edit/problems/merge/">Возможные дубли</a></li>-->
            </ul>
       </td>
       <td>
           <ul>
              <?php //show_count_link('/edit/problems/noinfo/', 'Нет информации', $noinfo);
        show_count_link('/edit/problems/noemail-future/', 'Нет email', $email_future);
        //show_count_link('/edit/problems/nopolygon/', 'Нет полигона', $polygon);
        show_count_link('/edit/problems/noplayers-count-future/', 'Не указано кол-во игроков', $noplayers_count_future);
        show_count_link('/edit/problems/allrpg-info-future/', 'Нет ссылки на allrpg.info', $noallrpg_info_count_future);
        ?>
      </ul>
     </td>
   </tr>
  </table>
	
  <br />
  	<table>
  	<tr><th colspan=4>Пользователи</th></tr>
  	<tr><th>Имя</th><th>Правки за 3 месяца</th><th>Новые игры за 3 месяца</th><th>Права</th></tr>
  	<?php 
  	
  	
  	foreach ($user_data as $username => $editor_data)
  	{
      echo "<tr>
        <td>" . show_user_link($username) ."</td>
        <td>";
        if (array_key_exists('update_count', $editor_data))
        {
					echo "<a href=\"/lenta/user/{$editor_data['user_id']}\">{$editor_data['update_count']}</a>";
        }
        else
        {
					echo '&nbsp;';
        }
        echo "</td>
        <td>". (array_key_exists('new_count', $editor_data) ? $editor_data['new_count'] : '&nbsp;') . "</td>
        <td>". (array_key_exists('privs', $editor_data) ? $editor_data['privs'] : '&nbsp;') . "</td>
        </tr>\n";
  	}
  	if (check_my_priv (USERS_CONTROL_PRIV))
	{
		echo '<tr><td colspan=4>';
		?>
      <form action="/edit/users/by-email/" method="post" id="edituser">
        <label>Email</label>
        <input type="text" name="username" width="20">
        <input type="submit" value="Редактировать профиль">
      </form>
		<?php
		echo '</td></tr>';
	}
	echo '</table>';
	write_footer();

?>