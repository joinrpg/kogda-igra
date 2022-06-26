<?php
require_once 'sqlbase.php';
require_once 'logic/internal_log.php';
require_once 'email.php';

function get_photo_by_id($photo_id)
{
  $sql = connect();
  $photo_id = intval($photo_id);
  $result = _get_photo("kp.\"photo_id\" = $photo_id");
  return is_array($result) ? $result[0] : FALSE;
}

function _get_photo($where)
{
  $sql = connect();
  return $sql -> Query("
    SELECT kp.*, u.user_id, u.username, kg.name AS gamename
    FROM \"ki_photo\" kp 
    LEFT JOIN \"users\" u ON u.\"user_id\" = kp.author_id
    LEFT JOIN \"ki_games\" kg ON kg.id = kp.game_id
    WHERE $where
    ORDER BY kp.photo_id");
}

function save_photo ($photo_id, $original_uri, $author, $game_id, $author_lj, $photo_comment, $photo_good_flag)
{
  $sql = connect();
  $photo_id = intval ($photo_id);
  $game_id = intval ($game_id);
  $photo_good_flag =intval ($photo_good_flag);
  $uri = $sql -> QuoteAndClean($original_uri);
  $photo_comment = $sql -> QuoteAndClean ($photo_comment);
  
  if ($author_lj)
  {
    $author_name = $author_lj;
    $author_lj = get_user_id_from_name ($author_lj, FALSE);
    $author = 'NULL';
  }
  else
  {
    $author_lj = 'NULL';
    $author_name = $author;
    $author = $sql -> QuoteAndClean ($author);
  }
  
  $list = "SET 
		\"photo_author\" = $author, 
		\"author_id\" = $author_lj,
		\"photo_uri\" = $uri,
		\"game_id\" = $game_id,
		\"photo_comment\" = $photo_comment,
		\"photo_good_flag\" = $photo_good_flag
		";
		
	$sql -> begin();
		
	
	$update_flag = $photo_id > 0;
	$update_text = $original_uri ? ($author_name .'/' . $original_uri) : $author_name;
	if ($update_flag)
	{
    $sql -> Run ("UPDATE ki_photo $list WHERE \"photo_id\" = $photo_id LIMIT 1");
    internal_log_photo(16, $photo_id, $game_id, $update_text);
	}
	else
	{
    $sql -> Run ("INSERT INTO ki_photo $list");
		$photo_id = $sql -> LastInsert ();
		internal_log_photo(15, $photo_id, $game_id, $update_text);
		update_photo_count_for_game($game_id);
	}
	
	$sql -> commit();
	
	$email = new PhotoUpdatedEmail ($game_id, $update_flag, $author_name);
	$email -> send();
	
	return $photo_id;
}

class PhotoUpdatedEmail extends Email
{
	function PhotoUpdatedEmail ($game_id, $updated, $author_name)
	{
		$this -> game_data = get_game_by_id ($game_id);
		$this -> updated = $updated;
		$this -> author_name = $author_name;
	}
	
	function get_sender()
	{
		return 'photo@kogda-igra.ru';
	}
	
	function get_recipient ()
	{
		return '';
	}
	
	function get_subject ()
	{
		$update_str = ($this -> updated) ? "обновлено" : "добавлено";
		return "Kogda-Igra.Ru: Фото автора {$this -> author_name} к игре \"{$this -> game_data['name']}\" $update_str ";
	}
	
	function get_message ()
	{
		return $this -> get_subject() . "\r\n " . get_game_profile_link ($this->game_data['id'], true);
	}
}

function update_photo_count_for_game($game_id)
{
  $sql = connect();
  return $sql -> Run(
       "UPDATE \"ki_games\"
        SET photo_count = (SELECT COUNT(*) AS photo_count FROM \"ki_photo\" WHERE game_id = $game_id)
        WHERE id = $game_id");

}

function delete_photo($id)
{
  $sql = connect();
  $sql -> begin();
  
  $id = intval($id);
  $photo = get_photo_by_id($id);
  $sql -> Run ("DELETE ki_photo FROM ki_photo WHERE \"photo_id\" = $id");
  update_photo_count_for_game($photo['game_id']);
  internal_log_photo(15, $id, $photo['game_id'], $photo['photo_author'] .'/' . $photo['photo_uri']);
  $sql -> commit();
}

function _normalize_photo_array ($photos)
{
  if (!is_array($photos))
  {
		return NULL;
  }
  foreach ($photos as $photo)
  {
    $author = $photo['username'] ? $photo['username'] : $photo['photo_author'];
    if ($photo['photo_good_flag'])
    {
      $result['good'][$author][] = $photo;
    }
    else
    {
      $result['all'][$author][] = $photo;
    }
  }
  return $result;
}

function get_photo_by_game_id($game_id)
{
  $sql = connect();
  $game_id = intval($game_id);
  return _normalize_photo_array(_get_photo("kp.\"game_id\" = $game_id"));
  
}

function get_photo_by_user($author_id)
{
  $sql = connect();
  $author_id = intval($author_id);
  return _normalize_photo_array(_get_photo("kp.author_id = $author_id"));
}
?>