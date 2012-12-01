<?php
	require_once 'funcs.php';
	require_once 'user_funcs.php';

	if (!check_my_priv (USERS_CONTROL_PRIV) )
		{
			return_to_main();
		}

	write_header('Управление пользователями');

	show_greeting();

	$uid = array_key_exists ('id', $_POST) ? $_POST['id'] : (array_key_exists ('id', $_GET) ? $_GET['id'] : get_user_id());
	$revoke = array_key_exists ('revoke', $_POST) ? $_POST['revoke'] : 0;
	$grant = array_key_exists ('grant', $_POST) ? $_POST['grant'] : 0;
   $userdata = get_user_by_id($uid);
   $username = $userdata['username'];

		if ($revoke)
		{
			revoke_priv ($uid, $revoke);
			echo '<p><strong>Привилегия отозвана</strong></p>';
		}

		if ($grant)
		{
			grant_priv ($uid, $grant);
			echo '<p><strong>Привилегия предоставлена</strong></p>';
		}

		function repack_array($array, $keyname, $valuename)
		{
			if ( is_array ($array) )
			{
				$result = array();
				foreach ($array as $member)
				{
					$key = $member [$keyname];
					$value = $member [$valuename];
					$result [$key] = $value;
				}
				return $result;
			}
			else
				return null;
		}
		
		function write_priv_row($id, $desc, $present)
		{
      $action_name = $present ? 'revoke' : 'grant';
			$button_name = $present ? 'Запретить' : 'Разрешить';
      echo "<tr> <td> $desc </td>";
      if ($present)
      {
        echo "<td>&nbsp;</td>";
      }
			echo "<td> <form action=\"\" method=\"post\" name=\"{$action_name}-$id\">";
			
      echo "<input type=\"hidden\" name=\"$action_name\" value=\"$id\" />";
      
			echo "<input type=\"submit\" value=\"$button_name\">";
			echo '</form> </td>';
			if (!$present)
      {
        echo "<td>&nbsp;</td>";
      }
			echo '</tr>';
		}

		$privs = get_user_privs ($uid);
		$privs = repack_array ($privs, 'id', 'desc');
		
		$all_privs = get_all_privs();
		$all_privs = repack_array ($all_privs, 'id', 'desc');
		
		echo '<br>';
		echo '<table class="calendar" cellpadding="2" cellspacing="2"> ';
		echo '<tr> <th colspan="3">' . show_user_link ($username, $uid) . '</th></tr>';
		echo '<tr> <th>Привилегия</th> <th> Разрешить </th> <th> Запретить </th></tr>';
		foreach ($all_privs as $id => $desc)
		{
			write_priv_row($id, $desc, array_key_exists($id, $privs));
		}
		echo '</table>';



	write_footer();
?>