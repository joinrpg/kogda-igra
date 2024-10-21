<?php
session_set_cookie_params (60*60*24*30);
session_start();
require_once 'common.php';
require_once 'mysql.php';
require_once 'sqlbase.php';

function get_username()
{
	$user = get_user();
	return $user['username'];
}

function get_user_by_id ($user_id)
{
  $sql = connect();
  $users = $sql -> GetAll('users', '*', "\"user_id\" = '$user_id'");
  if ( count ($users) != 1)
    return null;
  return $users[0];
}

function get_user()
{
	static $user;
	if (isset($user))
		return $user;
	$user_id = get_user_id();
	if ($user_id)
	{
		$user = get_user_by_id($user_id);
		return $user;
  }
  else
  	return null;
}

function get_user_by_name ($username)
{
	$sql = connect();
	$users = $sql -> GetAll('users', '*', "\"username\" = '$username'");
	return ($users != FALSE) ? $users[0] : NULL;
}

function get_user_id_from_name ($username, $lastvisit = TRUE)
{

	$user = get_user_by_name ($username);

	if ($user != FALSE)
	{
		$user_id = $user['user_id'];
		return $user_id;
	}
	else
	{
	  $lastvisit = $lastvisit ? "NOW()" : "NULL";
	  $sql = connect();

	  $sql -> Run ("INSERT INTO users 
	  (\"username\", \"email\", \"lastvisit\", \"create_date\")
	  VALUES
	  ( '$username',  NULL, $lastvisit,  NOW())
	  ");

		return $sql -> LastInsert ();
	}
}

function get_user_by_email($email)
{
	$sql = connect();
	$users = $sql -> GetAll('users', '*', "\"email\" ILIKE '$email'");
	return ($users != FALSE) ? $users[0] : NULL;
}

function try_login_user_by_email ($email, $lastvisit = TRUE)
{
	
	$user = get_user_by_email ($email);

	if (is_array($user))
	{
		$user_id = $user['user_id'];
		return $user_id;
	}
	else
	{
	  $lastvisit = $lastvisit ? "NOW()" : "NULL";
	  $sql = connect();
    $sql -> Run ("INSERT INTO users 
	(\"username\", \"email\", \"lastvisit\", \"create_date\")
	VALUES
	( '$email',  '$email', $lastvisit,  NOW())
	");
		return $sql -> LastInsert ();
	}
}

function set_username($ljuser, $email = NULL)
{
	if ($ljuser)
	{
		$user_id = get_user_id_from_name ($ljuser);
		setcookie('ljuser-name', $ljuser, time() + 60 * 60 * 24 * 30, '/', '', FALSE, TRUE);
	}
	elseif ($email)
	{
		$user_id = try_login_user_by_email($email);
	}
	if ($user_id)
	{
		$driver = connect();
		$driver -> Run ("UPDATE users SET \"lastvisit\" = NOW() WHERE \"user_id\" = $user_id");
		$_SESSION['user_id'] = $user_id;
	}
}

function set_email($user_id, $email)
{
	$driver = connect();
	
	$user_id = intval ($user_id);
	$email = $driver -> QuoteAndClean ($email);
	
	$driver -> Run ("UPDATE users SET \"email\" = $email WHERE \"user_id\" = $user_id AND (\"email\" IS NULL OR \"email\" = '')");
}

function get_user_id()
{
	return array_key_exists('user_id', $_SESSION) ? $_SESSION['user_id'] : 0;
}

function clear_username ()
{
	unset($_SESSION['user_id']);
}

function is_logged_in()
{
	return get_user() != null;
}

function check_username()
{
	return check_my_priv (EDIT_GAMES_PRIV);
}

function check_edit_priv()
{
	return check_my_priv (EDIT_GAMES_PRIV);
}

define ('USERS_CONTROL_PRIV', 'USERS_CONTROL');
define ('EDIT_GAMES_PRIV', 'EDIT_GAMES');
define ('EDIT_POLYGONS_PRIV', 'EDIT_POLYGONS');
define ('PHOTO_PRIV', 'PHOTO');
define ('PHOTO_SELF_PRIV', 'PHOTO_SELF');
function check_priv ($user_id, $priv_name)
{
	$sql = "SELECT \"desc\" FROM \"privs\", \"user_privs\" WHERE \"privs\".\"name\" = '$priv_name' AND \"privs\".\"id\" = \"user_privs\".\"pid\" AND $user_id = \"user_privs\".\"uid\"";
	$driver = connect();
	$result = $driver -> Query($sql);
	return ($result!==FALSE);
}

function _privs_format($user_array)
{
  $driver = connect();
    foreach ($user_array as $user_row)
  {
    $result[$user_row['user_id']] = $user_row;
  }
  $privs = get_sql_array("privs", "id", "desc");
  $user_privs = $driver -> Query("SELECT uid, pid FROM user_privs WHERE pid IN (SELECT id FROM privs WHERE hidden_flag = 0)");
  foreach ($user_privs as $row)
  {
    $uid = $row['uid'];
    $pid = $row['pid'];
    if (!array_key_exists($uid, $result))
    {
      $result[$uid] = array();
    }
    if (!array_key_exists('privs', $result[$uid]))
    {
			$result[$uid]['privs'] = '';
    }
    if ($result[$uid]['privs'])
    {
      $result[$uid]['privs'] .= ' :: ';
    }
    $result[$uid]['privs'] .= '[' . $privs[$pid] . ']';
  }
  return $result;
}

function get_user_privs_report ()
{
  return (_privs_format(get_users_array()));

}

function get_privs_desc_for_user ($user_id)
{
  $user_array[$user_id] = get_user_by_id($user_id);

  $result = _privs_format($user_array);
  return $result[$user_id]['privs'];

}

function check_my_priv ($priv_name)
{
	if (is_logged_in ())
		return check_priv (get_user_id(), $priv_name);
	else
		return false;
}

function get_user_privs ($uid)
{
	$driver = connect();
	$sql = "SELECT \"id\", \"desc\" FROM \"privs\", \"user_privs\" WHERE \"privs\".\"id\" = \"user_privs\".\"pid\" AND $uid = \"user_privs\".\"uid\" ORDER BY \"id\"";
	return $driver -> Query ($sql);
}

function get_all_privs ()
{
	$driver = connect();
	$sql = 'SELECT id, "desc" FROM "privs"';
	return $driver -> Query ($sql);
}

function get_users_array ()
{
	$driver = connect();
	return $driver -> Query ('SELECT * FROM "users" ORDER BY "username"');
}

function revoke_priv ($uid, $pid)
{
	$driver = connect();
	$driver -> Run ("DELETE FROM \"user_privs\" WHERE \"uid\" = $uid AND \"pid\" = $pid");
}

function grant_priv ($uid, $pid)
{
	$driver = connect();
	$driver -> Run ("INSERT INTO \"user_privs\" (\"uid\", \"pid\") VALUES ($uid, $pid) ");
}
?>