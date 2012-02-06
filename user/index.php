<?php
	require_once 'funcs.php';
	require_once 'user_funcs.php';
	require_once 'logic/stat.php';
	require_once 'review.php';
	require_once 'logic/photo.php';

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

	write_header('Kogda-igra.Ru :: Пользователи :: '. $username);
	show_greeting();
  echo "<h2>Профиль $username</h2>";

  $date = $userdata['lastvisit'] ? formate_single_date ($userdata['lastvisit']) : 'Никогда';
  $editor_stat = get_editor_stat_by_id ($id);
	echo '<p>';
	echo "<b>Пользователь</b>: {$username}<br>";
	$privs = get_privs_desc_for_user($id);
	if ($privs)
	{
    echo "<b>На сайте</b>: $privs<br>";
  }
	echo "<b>ЖЖ</b>: " . show_lj_user($username) . " <br>";

	echo "<b>Был в последний раз</b>: $date <br>";
	$email = $userdata['email'];
	if ($email)
	{
    echo "<b>Email</b>: {$email} <br>";
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

  $review = new ReviewForUser ($id);
  $review -> show();

  $photos = get_photo_by_user($id);


		if (is_array($photos))
		{
			echo "<h3>Фотоотчеты пользователя</h3>";

      foreach ($photos as $photo)
      {
        $photo_id = $photo['photo_id'];
        echo "<p><a href=\"{$photo['photo_uri']}\"><img style=\"border:none\" src=\"/photo/preview/$photo_id\"></a>
          <br> <b>Игра</b>: <a href=\"/game/{$photo['game_id']}\">{$photo['gamename']}</a>";
        echo "<br> <a href=\"{$photo['photo_uri']}\">[Ссылка на фото]</a>";

        echo "</p>";
      }
		}

  if (check_my_priv(USERS_CONTROL_PRIV))
  {
    echo "<a href=\"/edit/users/$id\">Редактировать права пользователя</a>";
  }

	write_footer();



?>