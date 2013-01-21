<?php
	require_once 'funcs.php';
	require_once 'user_funcs.php';
	require_once 'logic/stat.php';
	require_once 'review.php';
	require_once 'logic/photo.php';
	require_once 'media.php';
	require_once 'uifuncs.php';

	$username = array_key_exists ('id', $_GET) ? $_GET['id'] : 0;
	if (!$username)
	{
    return_to_main();
	}

	$userdata = get_user_by_name ($username);

	if (!is_array($userdata))
	{
    return_to_main();
	}

  $id = $userdata['user_id'];
  $email = $userdata['email'];

	write_header('Kogda-igra.Ru :: Пользователи :: '. $username);
	$topmenu = new TopMenu();
	$topmenu -> pagename = $username;
	$topmenu -> show();
	
	echo '<div style="float:left">';

	show_avatar ($email);

  $date = $userdata['lastvisit'] ? formate_single_date ($userdata['lastvisit']) : 'Никогда';
  $editor_stat = get_editor_stat_by_id ($id);
	echo '<p>';
	$privs = get_privs_desc_for_user($id);
	if ($privs)
	{
    echo "$privs<br>";
  }
  
  if (strpos($username, '@') === FALSE)
  {
    echo "<b>ЖЖ</b>: " . show_lj_user($username) . " <br>";
  }

	echo "<b>Был в последний раз</b>: $date <br>";

	if ($email)
	{
    echo "<b>Email</b>: <a href=\"mailto:{$email}\">{$email}</a> <br>";
  }
	$update_count = $editor_stat['update_count'];
  $new_count = $editor_stat['new_count'];
  if ($update_count > 0 || $new_count > 0)
  {
    echo "<b>Правок в базе за 3 месяца:</b> $update_count ";
    if (check_edit_priv())
    {
      echo "(<a href=\"/lenta/user/$id\">Лента правок</a>)";
    }
    echo "<br>";
    echo "<b>Новых игр в базе за 3 месяца:</b> $new_count <br>";
  }
  
  echo '</div>';
  
  	write_user_menu();

  $review = new ReviewForUser ($id);
  $review -> show();
  
	$media = new MediaBlock (get_photo_by_user($id));
	$media-> show_game = TRUE;
	$media -> show();


	write_footer();

function write_user_menu()
{
	echo '<div class=menu_box>';
	echo '<div class=menu_strip>';

	if (check_my_priv(USERS_CONTROL_PRIV))
	{
		real_button ("/edit/users/$id", "Редактировать права пользователя");
	}

	echo '</div>';
	echo '</div>';
}


?>