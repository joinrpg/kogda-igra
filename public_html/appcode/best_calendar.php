<?php
require_once 'logic/gamelist.php';
require_once 'calendar.php';

class BestCalendar extends Calendar {
  function __construct ($year, $region)
  {
    Calendar::__construct(get_best($year, $region));
  }
  
   function get_columns()
  {
    if ($this -> show_status)
    {
       $columns []= array ('name' => 'Статус', 'column-class' => 'status-column');
    }
    $columns []= array ('name' => 'Название', 'column-class' => 'name-column');
    $columns []= array ('name' => 'Регион', 'column-class' => 'region-column');
    $columns []= array ('name' => 'Сроки', 'column-class' => 'date-column');
    $columns []= array ('name' => 'Тип игры', 'column-class' => 'type-column');
    $columns []= array ('name' => 'Полигон', 'column-class' => 'polygon-column');
    $columns []= array ('name' => 'Иг-ов', 'column-class' => 'players-column', 'title' => 'Количество игроков');
    $columns []= array ('name' => 'Мастерская группа', 'column-class' => 'mg-column');
    $columns []= array ('name' => 'Лайки', 'column-class' => 'vk-likes-column');
    if ($this -> editor)
    {
      $columns []= array ('name' => '&nbsp;', 'column-class' => 'edit-column');
    }
    return $columns;
  }
  
  //TODO: This should be in Calendar
  function write_entry ($game)
  {
      $masked = $game['show_flags'] && 1;

      if ($masked && !check_username())
        return;

      $id = $game['id'];
      if ($id <= 0 && $this->write_updates)
      {
        return;
      }

      $date = new GameDate($game);

      $type = $game['game_type_name'];
      $polygon = $game['polygon_name'];
      $sub_region_disp_name = $game['sub_region_disp_name'];
      $sub_region_name = $game['sub_region_name'];

      $players_count = $game['players_count'] > 0 ? $game['players_count'] : '&nbsp;';

      $this -> show_border_if_needed ($date);

      $style = '';
      if ($masked)
      {
        $style = ' style="background-color: #C0C0C0"';
      }
      $cancelled = $game['cancelled_status'] > 0 ?' class="cancel_game"' : '';
      echo "<tr$cancelled$style>";
      if ($this -> show_status)
      {
        echo $this -> status_cell_creator($game, $date);
      }

      echo "<td class=\"game_name\">";
      $this -> write_game_name ($game);
      echo "</td>";
      echo "<td title=\"$sub_region_name\" class=\"game_region\">$sub_region_disp_name</td>";
      $show_date = $this -> get_date_string($date);
      echo "<td title=\"{$date->dow}\" class=\"game_date\">$show_date</td>";
      echo "<td class=\"game_type\">$type</td>";
      echo "<td class=\"game_polygon\">$polygon</td>";
      echo "<td class=\"game_players_count\">$players_count</td>";
      if ($this -> export_mode)
      {
        $email = $this -> get_email_link ($game);
        echo "<td class=\"game_email\">$email</td>";
        echo "<td class=\"game_mg\">" . $game['mg'] . '</td>';
      }
      else
      {
        $mg = $this-> get_email_link($game) . htmlspecialchars($game['mg']);
        echo "<td class=\"game_mg\">$mg</td>";
      }
      echo "<td class=\"game_vk_likes\">{$game['vk_likes']}</td>";
      $this->write_editor_box ($game['id']);

      echo "</tr>\n";
  }
}
?>