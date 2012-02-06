<?php
	require_once 'funcs.php';
	require_once 'user_funcs.php';
	require_once 'calendar.php';
	require_once 'logic/updates.php';
	require_once 'logic/gamebase.php';

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

		if (!$game['email'])
	{
    $email = '';
	}
	else
	{
    $email = $game['email'];
    $email = ":MAILTO=$email";
	 if ($game['hide_email'])
    {
      $email =  '';
    }
	}
	$date = new GameDate($game);
	$updated_date = get_last_update_date_for_game ($id);
	header("Last-Modified: " . gmdate("D, d M Y H:i:s", $updated_date) . " GMT\n");
//	header("Content-type: text/calendar");
//	header("Content-Disposition: attachment; filename=\"game$id.ics\"");
/*
URL:http://kogda-igra.ru/api/game/ical/<?php echo $id;?>

ORGANIZER:CN=<?php echo $game['mg'] . $email;?>
ORGANIZER: mailto:rpg@kogda-igra.ru

SUMMARY: <?php echo $game['name']; ?>
*/
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//kogda-igra.ru//kogda-igra.ru//EN
CALSCALE:GREGORIAN
METHOD:PUBLISH
BEGIN:VEVENT
UID:game<?php echo $id;?>@kogda-igra.ru
STATUS:CONFIRMED
SUMMARY:<?php echo $game['name'];?>

DTSTART:<?php echo $date -> get_machine_date_begin(); ?>

DTEND:<?php echo $date -> get_machine_date_end(); ?>

DTSTAMP:<?php echo GameDate:: format_machine_date(getdate($updated_date)); ?>

END:VEVENT
END:VCALENDAR