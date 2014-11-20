<?php
    require_once 'base_funcs.php';
	require_once 'funcs.php';
	require_once 'user_funcs.php';
	require_once 'calendar.php';
	require_once 'main_calendar.php';
    require_once 'best_calendar.php';
	require_once 'top_menu.php';

	$year = array_key_exists('year', $_GET) ? intval($_GET['year']) : 0;
	$konvent = get_request_field('konvent') == 1;
	$region_result = get_region_param();
	$region = $region_result['id'];
	$region_name = $region_result['name'];
	$best = get_request_field ('best') == 1;

	if (!$year)
	{
		$year = get_current_year ();
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
	
	if ($best)
	{
        $calendar = new BestCalendar($year, $region);
	}
	else
	{
        $calendar = new MainCalendar($year, $region, $konvent);
        $best_game = get_best(get_current_year(), $region);
        
        if (is_array($best_game))
        {
            $best_link = "/best" . get_region_uri ($region) . get_current_year() . "/";
  ?>
  <br />
  <div class="adblock">Самая популярная игра: <a href="/game/<?php echo $best_game[0]['id'];?>"><?php echo $best_game[0]['name'];?></a> (<a href="<?php echo $best_link; ?>">Другие популярные игры</a>)	</div>
  <?php
        }
    }
  
	
	$calendar -> write_calendar();


	write_footer(TRUE);

	?>