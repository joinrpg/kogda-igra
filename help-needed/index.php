<?php
	require_once 'funcs.php';
	require_once 'calendar.php';

class EmailNeededCalendar extends Calendar
{
  function __construct()
  {
    parent::__construct (get_noemail_games(TRUE));
    $this-> show_status = false;
    $this -> editor = true;
  }
  
  function write_editor_box($id)
  {
    echo "<td>";
    echo "<a href=\"/help-needed/email/$id/\">Сообщить email</a>";
    echo "</td>";
  }
}

  write_header('Требуется информация');
	echo '<h1>Требуется информация</h1>';
	show_greeting();
	echo '<h2>Нет e-mail мастеров</h2>';
	echo '<p>Нам очень не хватает e-mail мастеров, чтобы в случае необходимости связаться с ними и уточнить детали их игры. Если у вас есть их контакты, будем рады вашей помощи.</p>';
	$calendar = new EmailNeededCalendar();

	$calendar -> write_calendar();
	
	echo '<h2>Нет данных</h2>';
	echo '<p>Мы не знаем про эти игры, прошли они или нет. Что с ними стало? Помогите разобраться.</p>';
	$calendar = new Calendar(get_problem_games(TRUE, FALSE));
	$calendar -> show_status = false;
	$calendar -> write_calendar();
  write_footer(TRUE);
?>