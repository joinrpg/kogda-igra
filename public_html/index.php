<?php
	require_once 'funcs.php';
	require_once 'mysql.php';
	require_once 'common.php';
	require_once 'user_funcs.php';
	require_once 'logic.php';
	require_once 'calendar.php';

	
$year = array_key_exists('year', $_GET) ? intval($_GET['year']) : 0;
if ($year !=0 && !validate_year($year))
{
  return_to_main();
}
$now = getdate();

if ($year == 0)
{
  $show_only_future = $now['year'] == DEFAULT_YEAR;
  $year = DEFAULT_YEAR;

}
else
{
  $show_only_future = false;
}

$region_result = get_region_param();
$region = $region_result['id'];
$region_name = $region_result['name'];

	write_header ("Ролевые игры $year $region_name");
?>

  	<div class="add">
			<a href="mailto:rpg@kogda-igra.ru">Присылайте</a> информацию по&nbsp;форме:
			<ul>
				<li>Название игры</li>
				<li>Cайт</li>
				<li>Точные сроки</li>
				<li>Полевая/городская</li>
				<li>Регион проведения</li>
				<li>Полигон (или просто: выбран/не выбран)</li>
				<li>Кол-во игроков</li>
				<li>Мастерская группа</li>
				<li>E-mail</li>
			</ul>
		</div>
		<div class="add">
      <strong>Новости сервиса</strong> :: <strong><a href="/about/">О нас</a></strong>
      <br> 10 декабря — <a href="http://community.livejournal.com/kogda_igra/5300.html">Фотоочеты</a>
      <br> 27 октября — <a href="http://community.livejournal.com/kogda_igra/5110.html">Добавлен 2007 год</a>
      <br> 14 октября — <a href="http://community.livejournal.com/kogda_igra/4355.html">Политика добавления игр</a>
      <br> 10 августа — <a href="http://community.livejournal.com/kogda_igra/4199.html">Изменения интерфейса</a>
      <br /><a href="http://community.livejournal.com/kogda_igra/">Все новости</a>
      </div>
<h1>Ролевые игры <?php echo $year; ?></h1>

    <p>
			Календарь полевых ролевых игр на&nbsp;<?php echo $year; ?>&nbsp;год (<strong><a href="/about/">О нас</a></strong>).
			<br />Предназначен прежде всего для мастеров, но&nbsp;надеемся, что он&nbsp;будет полезен и&nbsp;игрокам.
			<br /> Если вы заметили неточность, хотите добавить информацию об игре, указать на ошибку
			или внести ценное предложение, пишите: <a href="mailto:rpg@kogda-igra.ru">rpg@kogda-igra.ru</a>.
			<br>Информация по <a href="/about/#regions">другим регионам</a>.
		</p>

<div style="float:left" class="top_menu">
<?php
	show_top_menu ($region, $year, $show_only_future);
	echo '</div>';
	if (check_username())
	{
		?>
		<div class="masked">
      <p>
        Время, место, название и сам факт проведения выделенных серым цветом игр может быть секретом. <strong>Не&nbsp;разглашайте</strong>
        информацию без разрешения мастеров. Доступ к календарю не&nbsp;является приглашением на&nbsp;игру.
      </p>
		</div>

		<?php
	}

  
  if (false) {
  ?>
  <br />
  <div class="adblock">[<a href="/about/#adv" title="Реклама">?</a>] СПб, 3 сентября: в числе первых дойди до «<a href="http://worlds-end-pub.livejournal.com">Края Света</a>»!</div>
  <?php
  }
  write_new_games_box (get_new_games_for_week(), get_new_reviews(), get_new_photos());
	$calendar = new Calendar(get_main_calendar($year, $region, $show_only_future));
	$calendar -> show_only_future = $show_only_future;
	$calendar -> check_border = TRUE;
	$calendar -> show_reviews = TRUE;
	$calendar -> write_calendar();

	show_login_box();
	write_footer(TRUE);

	?>