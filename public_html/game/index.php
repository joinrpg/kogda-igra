<?php
	require_once 'funcs.php';
	require_once 'user_funcs.php';
	require_once 'logic/gamebase.php';
	require_once 'logic/photo.php';
	require_once 'calendar.php';
	require_once 'logic/gameinfo.php';
	require_once 'review.php';
	require_once 'top_menu.php';
	require_once 'uifuncs.php';
	
	
	class GameProfileMenu extends TopMenu
	{
		function __construct($game)
		{

			
			parent::__construct();
			$this -> game = $game;
			$this -> show_new_adv = FALSE;
			$this -> show_add_adv = FALSE;
		}
		
		function get_page_title()
		{
			return $this->get_site_title() . ': ' . $this -> game['name'];
		}
		
		function get_page_header()
		{
			return $this ->get_site_header() . ': профиль игры';
		}
	}
	
	class GameProfileCalendar extends Calendar
	{
		function __construct ($game)
		{
						$intersections = get_intersections($game['game_id']);
			
			parent::__construct(array_merge(array($game), $intersections));
			$this -> show_reviews = FALSE;
      $this ->show_cancelled_games_checkbox = FALSE;
      $this -> count = 0;
		}
		
		function write_game_name ($game)
		{
			if ($this -> count > 1)
			{
				parent :: write_game_name ($game);
				return;
			}
			$uri = trim($game['uri']);
			if ($uri)
			{
				echo Calendar::get_link_icon($uri, $uri, '[S]', 'world_link.png') . '&nbsp;';
			}
			echo Calendar::format_game_name ($game['name'], "");
		}
		
		function show_border_if_needed($game, $date)
		{
			$this -> count++;
			if ($this -> count == 2)
			{
				$this -> write_border('<br>Пересечения');
			}
		} 
	}
	
	function write_widget_table ($date, $id, $game)
	{
	
		echo '<div class=menu_box>';
		echo '<span id="vk_like"></span><script type="text/javascript">VK.Widgets.Like("vk_like", {type: "button"});</script>';
		echo '<div class=menu_strip>';
		  $allrpg_info_id = $game['allrpg_info_id'];
  if ($allrpg_info_id)
  {
    $subobj_str = ($date -> is_passed()) ? 'past' : 'future';
    active_button("http://inf.allrpg.info/events/$allrpg_info_id/", 'Профиль allrpg.info');
    real_button("http://calendar.allrpg.info/portfolio/subobj=$subobj_str&act=add&game=$allrpg_info_id", "Добавить в портфолио");
  }
  		 if (!$date -> is_passed())
		 {
			$machine_date = $date -> get_machine_date();
			$details = "http://kogda-igra.ru/game/$id";
			real_button("http://www.google.com/calendar/event?action=TEMPLATE&text={$game['name']}&dates={$machine_date}&sprop=website:kogda-igra.ru&details=$details", "Добавить в Google Calendar");
		}
		echo '</div>';
		echo '</div>';
		
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
	
	$topmenu = new GameProfileMenu($game);
	$topmenu -> show();
  
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
  

  echo '<div style="float:left">';
  echo "<h1>{$game['name']}</h1>";
  
  $comment = trim($game['comment']);
  if ($comment)
  {
		echo "<p class='game_comment_header'>({$game['comment']})</p>";
	}
	echo '</div>';
	write_widget_table ($date, $id, $game);

	
	       $calendar = new GameProfileCalendar($game);

      $calendar -> write_calendar();
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

		if (is_array($photos))
		{
			echo "<h3>Фотоотчеты</h3>";
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