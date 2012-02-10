<?php
	require_once 'funcs.php';
	require_once 'mysql.php';
	require_once 'common.php';
	require_once 'user_funcs.php';
	require_once 'logic.php';
	require_once 'calendar.php';
	require_once 'top_menu.php';

	$year = array_key_exists('year', $_GET) ? intval($_GET['year']) : 0;
	$region_result = get_region_param();
	$region = $region_result['id'];
	$region_name = $region_result['name'];

	if (!$year)
	{
		$year = DEFAULT_YEAR;
	}
	
	if ($year !=0 && !validate_year($year))
	{
		return_to_main();
	}

	write_header ("Ролевые игры $year $region_name");
	
	$topmenu = new TopMenu();
	$topmenu -> year = $year;
	$topmenu -> region_name = $region_name;
	$topmenu -> region = $region;
	$topmenu -> show ();
	
	if (check_username() && false)
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
	$calendar = new Calendar(get_main_calendar($year, $region, $show_only_future));
	$calendar -> check_border = TRUE;
	$calendar -> show_reviews = TRUE;
	$calendar -> write_calendar();

	show_login_box();
	write_footer(TRUE);

	?>