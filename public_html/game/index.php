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
	require_once 'media.php';
	
	
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
			return $this ->get_site_header() . ': ' . $this -> game['name'];
		}
	}
	
	class GameProfileCalendar extends Calendar
	{
		function __construct ($game)
		{
			$date = new GameDate($game);
			parent::__construct(array($game)); 
			$this -> show_reviews = FALSE;
      $this -> count = 0;
		}
		
		function write_game_name ($game)
		{
			if ($this -> count > 1)
			{
				parent :: write_game_name ($game);
				return;
			}
			$this -> write_game_icons ($game);
			echo Calendar::format_game_name ($game['name'], "");
		}
		
	function get_date_string ($date)
  {
		return $date -> show_date_string(TRUE);
  }
	}
	
	function write_widget_table ($date, $id, $game)
	{
	
		echo '<div class=menu_box>';
		echo "<span id=\"vk_like\"></span><script type=\"text/javascript\">
			VK.Widgets.Like('vk_like', {type: \"button\"});
			var update_likes = 
			function() {
				var req = new XMLHttpRequest();
				var uri = '/api/game/update-likes.php?id=$id';
				req.open ('GET', uri, true);
				req.send();
};
			VK.Observer.subscribe('widgets.like.liked', update_likes);
			VK.Observer.subscribe('widgets.like.unliked', update_likes);
			</script>";
		echo '<div class=menu_strip>';

		$allrpg_zayvka_id = $game['allrpg_zayvka_id'];
		if ($allrpg_zayvka_id && !$date -> is_passed() && $game['allrpg_opened'])
		{
			real_button ("http://www.allrpg.info/order/act=add&subobj=$allrpg_zayvka_id ", 'Заявиться');
		}
		
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
			$details = get_game_profile_link($id, true);
			$host = SITENAME_HOST;
      active_button("http://www.google.com/calendar/event?action=TEMPLATE&text={$game['name']}&dates={$machine_date}&trp=true&sprop=$host&details=$details", "Добавить в Google Calendar");
		}
		if (check_my_priv(PHOTO_PRIV) || check_my_priv(PHOTO_SELF_PRIV))
		{
			active_button (get_game_edit_photo_link($id), "Добавить фотоотчет");
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
    redirect_to ("Location: " . get_game_profile_link($redirect));
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
			redirect_to(get_game_edit_link($id));
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

		$allrpg_zayvka_id = $game['allrpg_zayvka_id'];

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
  
  if ($game['vk_club'])
  { 
  ?>
    
    <div id="vk_groups" style="width:auto"></div>
<script type="text/javascript">
var script = document.createElement('SCRIPT');

script.src = "https://api.vk.com/method/groups.getById?group_id=<?php echo $game['vk_club'];?>&callback=callbackFuncVK";

document.getElementsByTagName("head")[0].appendChild(script);

function callbackFuncVK(result) {
  var gid = result.response[0].gid;
  VK.Widgets.Group("vk_groups", {mode: 2, width: "400", height: "400"}, gid);
} 

</script>
<?php
  }

	show_media(get_photo_by_game_id($id));
	
	if ($allrpg_zayvka_id && !$date -> is_passed() && $game['allrpg_opened'])
		{
			echo "<h2>Роли</h2>";
			echo "<iframe style=\"height:5000em;width:99%; border:0\" src=\"http://www.allrpg.info/gameorders.php?game=$allrpg_zayvka_id\" seamless></iframe>";
		}
	
        


	write_footer(TRUE);

?>