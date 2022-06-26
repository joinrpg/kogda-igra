<?php
  require_once 'funcs.php';
  require_once 'logic/edit.php';
  require_once 'logic/gamebase.php';
  require_once 'calendar.php';
  require_once 'logic/search.php';
  
  class MergeCalendar extends Calendar
  {
    function write_editor_box($game_id)
    {
      global $id, $old_ids;
      
      
      foreach ($old_ids as $oid)
      {
        if ($oid != $game_id)
        {
          $new_id_list[] = $oid;
        }
      }
      
      $id_str = get_id_list($new_id_list);
       if ($this -> editor)
      {
        echo "<td class=\"game_edit\">";
                
        echo "<a href=\"/edit/merge/?id=$id&old_id=$id_str\">Убрать из списка</a>";
        
        echo "</td>\n";
      }
    }
  }
  
  $id = array_key_exists ('id', $_GET) ? intval($_GET['id']) : 0;
  $old_id_param = array_key_exists ('old_id', $_GET) ? $_GET['old_id'] : 0;
  $old_id_strings = explode(",", $old_id_param);
  foreach ($old_id_strings as $old_id_elem)
  {
    $old_id_cnd = intval($old_id_elem);
    if ($old_id_cnd && $old_id_cnd != id)
    {
      $old_ids [] = $old_id_cnd;
    }
  }
  
  if (array_key_exists('name', $_GET))
  {
    $name =  $_GET['name'];
    $search = get_search_by_name($name);

    foreach ($search as $game)
    {
      $old_ids [] = $game['id'];
    }
    if ($id == 0 && count($old_ids) > 0)
    {
      $id = $old_ids[0];
      unset($old_ids[0]);
    }
  }
  
  $add_id = array_key_exists ('add_id', $_GET) ? intval($_GET['add_id']) : 0;
  if ($add_id > 0)
  {
    $old_ids [] = $add_id;
  }
  
  function get_id_list($old_ids)
  {
    return implode(',', $old_ids);
  }
  
  if (array_key_exists('action', $_GET) && $_GET['action'] == merge)
  {
    foreach ($old_ids as $old)
    {
      do_game_merge($id, $old);
    }
    header("Location: /game/$id");
    die();
  }
  
	if (!$id || !check_edit_priv())
	{
    return_to_main();
	}
	
	$main_game = get_calendar_game_by_id($id);
	$old_games = _get_games("id IN (" . get_id_list($old_ids) . ")");
	
	write_header("Слияние игр");
		echo "<h1>Слияние игр</h1>";
	if (count($old_ids) > 0)
	{
	?>
	
	<strong>Внимание: отменить слияние игр невозможно!</strong><br>
	<strong>Важно:</strong> Сливать можно только две копии одной игры (например, игра была перенесена с 2008 на 2009 год). 
	Сливать вторые части, разные игры с одним названием, коны разных лет и т.д. — нельзя.
	<form action="" method="get" id="merge-games">
	<input type="hidden" name="id" value="<?php echo $id; ?>">
	<input type="hidden" name="old_id" value="<?php echo get_id_list($old_ids); ?>">
	<input type="hidden" name="action" value="merge">
	<input type="submit" value="Слить записи в одну" onclick="return window.confirm('Отменить слияние невозможно! Действительно слить?');">
	</form>
	<?php
	}
  echo '<hr>';
	echo "<strong>Эта игра останется в базе</strong>";
	$calendar = new Calendar($main_game);
	$calendar -> show_only_future = FALSE;
	$calendar -> show_reviews = FALSE;
	$calendar -> write_calendar();
	
	echo "<hr>";
	echo "<h2>Старые даты</h2>";
	echo "<strong>Эти игры — просто старые даты главной</strong>";
  $calendar = new MergeCalendar($old_games);
	$calendar -> show_only_future = FALSE;
	$calendar -> show_reviews = FALSE;
	$calendar -> use_checkbox = TRUE;
	$calendar -> write_calendar();
	
	?>
	
	<strong>Добавить к слиянию:</strong>
	<form action="" method="get" id="add-merge-game">
	<input type="hidden" name="id" value="<?php echo $id; ?>">
	<input type="hidden" name="old_id" value="<?php echo get_id_list($old_ids); ?>">
	<select name="add_id" size="1">
	<?php
    $all = _get_games("kgd.\"order\" = 0");
    foreach ($all as $game)
    {
      $gamedate = new GameDate($game);
      $str = $game['name'] . "(" . $gamedate -> show_date_string(true) .")";
      echo "<option value=\"{$game['id']}\">$str</option>";
    }
	?>
	</select>
	<input type="submit" value="Добавить">
	</form>
	<?php
	
	write_footer(TRUE);
?>