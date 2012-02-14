<?php
	require_once 'funcs.php';
	require_once 'user_funcs.php';
	require_once 'logic/gamebase.php';
	require_once 'logic/photo.php';
	require_once 'calendar.php';
	require_once 'view.php';
	require_once 'review.php';
	
	function write_widget_table ($date, $id, $game)
	{
		echo "<table class=\"widget_table\">";
		 if (!$date -> is_passed())
		 {
			$machine_date = $date -> get_machine_date();
			$details = "http://kogda-igra.ru/game/$id";
			echo "<tr><td><a
			href=\"http://www.google.com/calendar/event?action=TEMPLATE&text={$game['name']}&dates={$machine_date}&sprop=website:kogda-igra.ru&details=$details\">";
			echo 'Напомнить в Google Calendar</a></td></tr>';
		}
		echo '<tr><td><div id="vk_like"></div><script type="text/javascript">VK.Widgets.Like("vk_like", {type: "button"});</script></td></tr>';
		/*echo "<tr><td><iframe src=\"http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fkogda-igra.ru%2Fgame%2F{$id}&amp;layout=standard&amp;show_faces=false&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=40\" scrolling=\"no\" frameborder=\"0\" style=\"border:none; overflow:hidden; width:450px; height:65px;\" allowTransparency=\"true\"></iframe></td></tr>";*/
		echo "</table>";
	}


	$id = array_key_exists ('id', $_GET) ? intval($_GET['id']) : 0;
	if (!$id)
	{
    return_to_main();
	}

	$game = get_game_by_id($id);

	$redirect = $game['redirect_id'];
	if ($redirect > 0)
	{
    header ("Location: /game/$redirect");
    die();
	}


	if ($game['show_flags'] && !check_username())
	{
    return_to_main();
	}

	write_header('Kogda-igra.Ru : '. $game['name']);
	$players_count = $game['players_count'] > 0 ? $game['players_count'] : 'Неизвестно';
  $game_type_name = $game['game_type_name'];
  $polygon_name = $game['polygon_name'];
  $game_name = $game['name'];
  if ($game['uri'])
      {
        $uri = "<a href=\"{$game['uri']}\" rel=\"nofollow\">{$game['uri']}</a>";
      }
      else
      {
        $uri = 'нет';
      }

   	if (!$game['email'])
	{
    $email = '';
	}
	else
	{
    $email = $game['email'];
    $email = "<a href=\"mailto:$email\">$email</a>";
	 if ($game['hide_email'])
    {
      $email =  check_username() ? "<em>Скрытый:</em>$email" : '';
    }
	}
	$deleted_flag = $game['deleted_flag'];
	if ($deleted_flag)
	{
		if (check_edit_priv())
		{
			redirect_to("/edit/game/index.php?id=$id");
		}
		if ($deleted_flag == 1)
		{
			echo '<p>Данная запись об игре удалена. Такой игры никогда не было и она оказалась здесь только по ошибке редакторов. Приносим свои извинения.</p>';
		}
		elseif ($deleted_flag == -1)
		{
			echo '<p>Игра находится на модерации.</p>';
		}
			
		write_footer();
		die();
	}
  $date = new GameDate($game);
  $year = $date->year();
  
    
echo '<div style="float:right" class="top_menu">';
  show_top_menu(-1, $year, TRUE);
  echo '</div>';
  echo "<h2>{$game['name']}";
  if (check_edit_priv())
  {
    echo " (<a href=\"/edit/game/index.php?id=$id\">Изменить</a>)";
  }
  echo "</h2>";
  if ($game['comment'])
  {
		echo "<p class='game_comment_header'>({$game['comment']})</p>";
	}
  write_widget_table ($date, $id, $game);


	echo '<p>';
	echo "<b>Статус</b>: {$game['status_name']}<br>";
	echo '<b>Дата</b>: ' .  ($date -> show_date_string(true)) . "<br>";
	echo "<b>Тип игры</b>: $game_type_name<br>";
  echo "<b>Регион</b>: {$game['sub_region_name']}<br>";
  echo "<b>Полигон</b>: $polygon_name ";
  if (intval($game['meta_polygon']) == 0)
  {
    $poly_uri = urlencode($polygon_name);
    echo "(<a href=\"/find/$poly_uri\">искать</a>)";
  }
  echo "<br>";
  echo "<b>Сайт</b>: $uri<br>";
  
  echo "<b>Кол-во игроков</b>: $players_count";
  $comment = trim($game['comment']);
  if ($comment)
  {
		echo "<br><b>Комментарий</b>: $comment";
	}
  echo "<br><b>Мастерская группа</b>: ";
  echo make_search_string($game['mg']);
  echo "\n<br>";


  echo "<b>Email</b>: $email";
  if ($email)
  {
    echo " (<a href=\"/find/{$game['email']}\">искать</a>)";
  }
  echo "<br><br>";
  
  $allrpg_info_id = $game['allrpg_info_id'];
  if ($allrpg_info_id)
  {
    $subobj_str = ($date -> is_passed()) ? 'past' : 'future';
    echo "<h3>allrpg.info</h3>";
    echo "<a href=\"http://inf.allrpg.info/events/$allrpg_info_id/\">Посмотреть профиль события</a>";
    echo "<br><a href=\"http://calendar.allrpg.info/portfolio/subobj=$subobj_str&act=add&game=$allrpg_info_id\">Добавить в портфолио</a>";
  }
  
$old_dates = get_game_dates($id);

  if (count($old_dates) > 1)
  {
    echo '<table><tr><th>Старые даты</th>';
    foreach ($old_dates as $date_row)
    {
      if ($date_row['order'] > 0)
      {
        $date = new GameDate($date_row);
        echo '<tr><td>';
        echo $date -> show_date_string (true);
        echo '</tr></td>';
      }
    }
    echo '</table>';
  }

  $review = new Review ($id);
  $review -> show();

  $photos = get_photo_by_game_id($id);

		if (is_array($photos) || check_my_priv(PHOTO_PRIV) || check_my_priv(PHOTO_SELF_PRIV))
		{
			echo "<h3>Фотоотчеты об игре «{$game['name']}»</h3>";
		}
		if (is_array($photos))
		{
			$good_present = is_array($photos['good']);
			if ($good_present)
			{
        echo '<p><strong>Выбор модератора</strong></p>';
			}
			show_photos_array($photos['good']);
			if ($good_present && is_array($photos['all']))
			{
        echo '<p><strong>Остальные</strong></p>';
			}
      show_photos_array ($photos['all']);
		}
	if (check_my_priv(PHOTO_PRIV) || check_my_priv(PHOTO_SELF_PRIV))
  {
    echo "<br><a href=\"/edit/photo/?game_id=$id\">Добавить фотоотчет</a>";
  }
  show_intersections($id, $game['name']);
	write_footer(TRUE);
function show_photos_array($photo_array)
{
  foreach ($photo_array as $auth_photo)
	{
    echo '<table class="photo_table"><tr>';
    foreach ($auth_photo as $photo)
    {
      show_photo($photo);
    }
    echo '</tr></table>';
	}
}
function show_photo ($photo)
{
    $photo_id = $photo['photo_id'];
    $photo_author = get_photo_author($photo);
    $photo_comment = htmlspecialchars($photo['photo_comment']);
    echo "<td><a href=\"{$photo['photo_uri']}\"><img style=\"border:none\" src=\"/photo/preview/$photo_id\"></a> <br> <b>Автор</b>: $photo_author";
 
        if (check_my_priv(PHOTO_PRIV) && $photo['author_id'] == 0)
        {
          echo " (<a href=\"/edit/problems/update-author/?author=$photo_author\">Исправить</a>)";
        }
        if ($photo_comment)
        {
          echo "<br> <i>$photo_comment</i>";
        }
        echo "<br> <a href=\"{$photo['photo_uri']}\">[Ссылка на фото]</a>";
        if (check_my_priv(PHOTO_PRIV) || (check_my_priv(PHOTO_SELF_PRIV) && $photo['author_id'] == get_user_id()))
        {
          echo "<br><a href=\"/edit/photo/?id=$photo_id&game_id=$id\">Изменить</a>";
        }
        echo "</td>";
}

?>