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

	write_header('Панель управления');
	echo '<h1>Панель управления</h1>';
	echo '<p style="clear:both">';
	$user = show_lj_user(get_username());
	echo "Добро пожаловать, $user!\n</p>";
	echo '<h4>Поиск</h4>';
	show_search_form();
	
	?> 
	<br />
	<table class="control_panel">
	<tr>
    <th>Календари</th>
    <th>Панель управления</th>
    <th>Материалы</th>
    <th>Лента изменений</th>
    <th>Статистика</th>
    <th>Экспорт</th>
  </tr>
	<tr>
    <td>
      <ul>
        <li><a href="/">Вся Россия</a></li>
        <li><a href="/msk/">Москва</a></li>
        <li><a href="/spb/">Санкт-Петербург</a></li>
        <li><a href="/ural/">Урал</a></li>
        <li><a href="/sibir/">Сибирь</a> <sup>BETA</sup></li>
        <li><a href="/south/">ЮФО</a><sup>BETA</sup></li>
        <li><a href="/edit/deleted-games/">Удаленные игры</a></li>
       </ul>
       </td>
        <td>
      <ul>
        <li><a href="/edit/game/">Добавить игру</a></li>
        <?php
	if (check_my_priv (EDIT_POLYGONS_PRIV))
	{
		echo '<li><a href="/edit/polygons/">Полигоны</a></li>';
	}
	
	?>
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
      <ul>
        <li><a href="/stat/">Количество игр</a></li></li>
      </ul>
     </td>
     <td>
      
      <form action="/data/export/excel/" method="get">
        <?php
          show_dropdown_with_data ('region', get_array('region_display'));
          show_dropdown ('year');
        ?>
        <input type="submit" value="Экспортировать" >
      </form>
      <p>Экспорт в формате .xls.</p>
     </td>
   </tr>
  </table>
	<br />
	<table class="control_panel">
		<tr>
    <th colspan="2">Проблемные игры</th>
    </tr>
  	<tr><th>Все</th><th>В будущем</th></tr>
    <tr>	
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
	<table class="control_panel">
		<tr>
    <th>Статистика редакторов</th><th>Пользователи</th>
    </tr>
  	<tr><td>
  	<table><th>Имя</th><th>Правки за 3 месяца</th><th>Новые игры за 3 месяца</th></tr>
  	<?php foreach ($editor_stats as $editor_data)
  	{
      echo "<tr>
        <td>" . show_user_link($editor_data['username'], $editor_data['user_id']) ."</td>
        <td><a href=\"/lenta/user/{$editor_data['user_id']}\">{$editor_data['update_count']}</a></td>
        <td>{$editor_data['new_count']}</td></tr>\n";
  	}
  	?>
    </table></td>
    <td>
    <?php
    if (check_my_priv (USERS_CONTROL_PRIV))
	{
		?>
      <form action="/edit/users/by-name/" method="post" id="edituser">
        <label>Имя пользователя</label>
        <input type="text" name="username" width="20">
        <input type="submit" value="Редактировать профиль">
      </form>
		<?php
	}
    ?>
    </td>
  </table>
	<?php
  $users_report = get_user_privs_report();
  $user_admin = check_my_priv(USERS_CONTROL_PRIV);
  ?>
  <br>
    <table class="control_panel">
      <tr>
        <th>Имя</th><th>Права</th>
      </tr>
  <?php
  foreach ($users_report as $user_row)
  {
    if (array_key_exists('privs', $user_row))
    {
       $username = show_user_link($user_row['username'], $user_row['user_id']);
       
    echo "<tr>
        <td>" .$username ."</td>
        <td>{$user_row['privs']}</td>
        </tr>\n";
        }
  }
  echo "</table>";
  
	write_footer();

?>