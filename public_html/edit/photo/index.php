<?php
	require_once 'funcs.php';
	require_once 'user_funcs.php';
	require_once 'forms.php';
	require_once 'logic/photo.php';
	require_once 'logic/gamebase.php';

  $photomoderator = check_my_priv(PHOTO_PRIV);
	if (!$photomoderator && !check_my_priv(PHOTO_SELF_PRIV))
		return_to_main();

function action_edit($id)
{
  global $photomoderator;
  $game_id = array_key_exists ('game_id', $_GET) ? intval($_GET['game_id']) : 0;
  $photo_data = get_photo_by_id($id);
  
  if ($game_id == 0)
  {
    $game_id = intval($photo_data['game_id']);
  }
  if (!$game_id)
  {
    return_to_main();
  }
  if ($id > 0 && !can_edit_photo())
  {
    return_to_main();
  }
  $game_data = get_game_by_id($game_id);
  $game_name = $game_data['name'];
  
  $msg = array_key_exists ('msg', $_GET) ? $_GET['msg'] : '';
  
  
  
  write_header("Фотоотчет к игре «{$game_name}»");
  echo "<p style=\"color:red\">$msg</p>";
  echo "<h1>Фотоотчет к игре «<a href=\"/game/$game_id\">$game_name</a>»</h1>";

  if ($id)
  {
    $img = "<img src=\"/photo/preview/$id\">";
  }
  else
  {
    echo '<p>Поддерживается также заливка видеотчетов со следующих хостингов:</p>
		<ul>
			<li>Vimeo (ссылка должна выглядеть как vimeo.com/54374267)</li>
		</ul>';
    $img = '';
  }
  echo '<form action="/edit/photo/" method="post" id="edit" enctype="multipart/form-data"> <table>';
  show_tb ('Ссылка на альбом', 'photo_uri', 300, $photo_data['photo_uri']);
  if ($photomoderator)
  {
    $author_data = get_user_by_id ($photo_data['author_id']);
    show_tb ('Автор (ЖЖ)', 'author_lj', 100, $author_data['username']);
    show_tb ('Автор (если нет ЖЖ)', 'photo_author', 100, $photo_data['photo_author']);
    echo "<tr><td><label><strong>Выбор модератора</strong></label></td>";
		echo "<td>";
		show_dropdown_with_data('photo_good_flag', array(0 => 'Обычная', 1 => 'Лучшая'), $photo_data['photo_good_flag']);
		echo "</td>";
  }
  else
  {
    $userdata = get_user();
    echo "<tr><td><label>Автор</label></td><td>" .show_user_link ($userdata['username'], $userdata['id']) . "</td></tr>";
  }
  show_tb ('Комментарий', 'photo_comment', 200, $photo_data['photo_comment']);
  echo "<tr><td><label>Превью</label></td><td>$img <br><input type=\"file\" id=\"preview\" name=\"preview\"></td></tr>";
  submit ('Сохранить', 'save', $id, '',  FALSE);
  show_hidden('game_id', $game_id);
  echo '</table>';
	echo '</form>';
	
	echo '<form action="/edit/photo/" method="post" id="delete" onsubmit="return ask_if_delete();"> <table>';
	if ($id && can_edit_photo())
	{
    submit ('Удалить', 'delete', $id, '',  FALSE);
  }
	echo '</table>';
	echo '</form>';
  write_footer();
}

function action_save($id)
{
  global $photomoderator;
		$exists = array_key_exists('preview', $_FILES) && $_FILES['preview']['size'] > 0;
		$new = $id == 0;
		
		/*if (!$exists && !$id)
		{
      header("Location: /edit/photo/?id=$id");
      die();
		}*/
		$photo_author = $photomoderator ? get_post_field ('photo_author') : NULL;
		$userdata = get_user();
		$author_lj = $photomoderator ? get_post_field ('author_lj') : $userdata['username'];
		$id = save_photo(
			$id,
			get_post_field ('photo_uri'),
			$photo_author,
			get_post_field ('game_id'),
			$author_lj,
			get_post_field ('photo_comment'),
			get_post_field ('photo_good_flag')
		);
				
		if ($id === FALSE)
		{
			return_to_main();
		}

		if ($exists)
		{
      save_image($id, $new);
    }
    
    if (!$exists && $new)
    {
      copy(get_image_file_name('nopreview.png'), get_image_file_name($id));
    }

		header("Location: /edit/photo/?id=$id");
		die();
}

function can_edit_photo()
{
  global $photomoderator;
  return $photomoderator || $photo_data['author_id'] == get_user_id();
}

function action_delete($id)
{
  
  $photo_data = get_photo_by_id($id);
  $game_id = $photo_data['game_id'];
  if (can_edit_photo())
  {
    unlink("/home/leotsar/www/site2/public_html/photo/preview/$id");
    delete_photo($id);
  }
  header("Location: /game/$game_id");
	die();
}

function cancel_save($id, $new, $msg)
{
  if ($new)
  {
    delete_photo($id);
    $game_id = intval(get_post_field ('game_id'));
    header("Location: /edit/photo/?game_id=$game_id&msg=$msg");
  }
  else
  {
    header("Location: /edit/photo/?id=$id&msg=$msg");
  }
  die();
}

function get_image_file_name ($id)
{
  return "/home/leotsar/www/site2/public_html/photo/preview/$id";
}

function save_image($id, $new)
{
    $image_file = get_image_file_name($id);
		unlink($image_file);
		if (!move_uploaded_file($_FILES['preview']['tmp_name'], $image_file))
		{
       cancel_save($id, $new, 'Загрузка не удалась');
		}
		
		$image_type = $_FILES['preview']['type'];
		
		if ($image_type == 'image/png')
		{
      $gd = imagecreatefrompng($image_file);
		}
		else if ($image_type == 'image/jpeg')
		{
      $gd = imagecreatefromjpeg($image_file);
		}
		else if ($image_type == 'image/pjpeg')
		{
      $gd = imagecreatefromjpeg($image_file);
		}
		else
		{
      cancel_save($id, $new, 'Неизвестный формат файла');
		}
		
    $old_x = imageSX($gd);
    $old_y = imageSY($gd);
    
    if ($old_y > $old_x)
    {
      $new_y = 300;
      $new_x = $old_x / $old_y * 300;
    }
    else
    {
      $new_x = 300;
      $new_y = $old_y / $old_x * 300;
    }
    
    $tmb = imagecreatetruecolor($new_x, $new_y);
    
    imagecopyresampled($tmb, $gd, 0, 0, 0, 0, $new_x, $new_y, $old_x, $old_y);
    unlink($image_file);
    imagepng($tmb, $image_file);
    imagedestroy($gd);
    imagedestroy($tmb);
}
	
// MAIN
	$id = array_key_exists ('id', $_POST) ? intval($_POST['id']) : (array_key_exists ('id', $_GET) ? intval($_GET['id']) : 0) ;

	$action = array_key_exists ('action', $_POST) ? $_POST['action'] : 0;

	if ($action === 'delete')
	{
		action_delete ($id);
			return_to_main();
	}
	elseif ($action === 'save')
		action_save ($id);

	action_edit ($id);


?>