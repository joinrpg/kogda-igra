<?php
    require_once 'funcs.php';
    require_once 'user_funcs.php';

    if (!check_my_priv (USERS_CONTROL_PRIV) )
        {
            return_to_main();
        }





    $uid = array_key_exists ('id', $_POST) ? $_POST['id'] : (array_key_exists ('id', $_GET) ? $_GET['id'] : get_user_id());
    $revoke = array_key_exists ('revoke', $_POST) ? $_POST['revoke'] : 0;
    $grant = array_key_exists ('grant', $_POST) ? $_POST['grant'] : 0;
   $userdata = get_user_by_id($uid);
   $username = $userdata['username'];

   $change_email_result = null;
   if (array_key_exists('new_email', $_POST)) {
       $new_email = trim($_POST['new_email']);
       $ok = force_set_email($uid, $new_email);
       $change_email_result = $ok ? 'ok' : 'error';
       if ($ok) {
           $userdata = get_user_by_id($uid);
       }
   }

   write_header('Пользователь — ' . $username);

   $topmenu = new TopMenu();
    $topmenu -> pagename = 'Пользователь — ' . $username;
    $topmenu -> show();

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
                return array();
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

        echo '<br>';
        echo '<h3>Сменить email</h3>';
        if ($change_email_result === 'error') {
            echo '<p style="color:red"><strong>Ошибка: пользователь с таким email уже существует</strong></p>';
        } elseif ($change_email_result === 'ok') {
            echo '<p><strong>Email изменён</strong></p>';
        }
        $current_email = htmlspecialchars($userdata['email'] ?? '');
        echo '<form action="" method="post">';
        echo '<input type="hidden" name="id" value="' . intval($uid) . '" />';
        echo 'Текущий email: <strong>' . $current_email . '</strong><br><br>';
        echo 'Новый email: <input type="email" name="new_email" maxlength="100" size="40" required />';
        echo ' <input type="submit" value="Сменить email">';
        echo '</form>';

    write_footer();
?>