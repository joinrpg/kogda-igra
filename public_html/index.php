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
	
	$topmenu = new TopMenu();
	$topmenu -> year = $year;
	$topmenu -> region_name = $region_name;
	$topmenu -> region = $region;
	$topmenu -> show ();
  
  if (false) {
  ?>
  <br />
  <div class="adblock">[<a href="/about/#adv" title="Реклама">?</a>] СПб, 3 сентября: в числе первых дойди до «<a href="http://worlds-end-pub.livejournal.com">Края Света</a>»!</div>
  <?php
  }
	
	$best_game = get_best_vk_game();
  
	if (true) {
  ?>
  <br />
  <div class="adblock">[<a href="/about/#vk_like" title="Популярная игра">?</a>] Самая популярная игра: <a href="/game/<?php echo $best_game['id'];?>"><?php echo $best_game['name'];?></a>	</div>
  <?php
  }
  
	$calendar = new Calendar(get_main_calendar($year, $region, FALSE));
	$calendar -> check_border = TRUE;
	$calendar -> show_reviews = TRUE;
	$calendar -> write_calendar();

	show_login_box();
	write_footer(TRUE);

	?>