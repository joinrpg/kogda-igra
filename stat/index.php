<?php
	require_once 'logic.php';
	require_once 'funcs.php';

  function write_stat($row, $total, $good)
  {
    $total = $row[$total];
    $good = $row[$good];
    $fail_percent = intval (($total - $good) * 100 / $total);
    echo "<td>$total</td>";
    echo "<td>$good</td>";
    echo "<td>$fail_percent</td>";
  }

  write_header("Статистика kogda-igra");
  
  echo "<h1>Статистика kogda-igra</h1>";
    
  echo '<p>Статистика является предварительной, нечеткой и неофициальной.</p>';
  
  echo '<table cellpadding="2" cellspacing="0">';
  echo '<tr>
    <th rowspan="2">Год</th>
    <th colspan="3">
      Мероприятия
    </th>
    <th colspan="3">
      Игры
    </th>
    </tr>';
  echo '<tr>
    <th>Всего</th>
    <th>Успешных</th>
    <th>% Отмен и переносов</th>
    <th>Всего</th>
    <th>Успешных</th>
    <th>% Отмен и переносов</th>
    </tr>';
  $stat = get_full_statistics();
  foreach ($stat as $year_row)
  {
    echo '<tr>';
    echo "<td>{$year_row['year']}</td>";
    write_stat($year_row, 'total_count', 'notcancelled_count');
    write_stat($year_row, 'total_game_count', 'game_notcancelled_count');
    echo '</tr>';
  }
  echo '</table>';
  echo '<p>Под успешными мероприятиями подразумеваются те, которые либо уже прошли, либо пока что планируются к проведению (статус ОК).</p>';
  write_footer(TRUE);
?>