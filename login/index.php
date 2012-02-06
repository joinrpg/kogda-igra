<?php
define('HOST', 'kogda-igra.ru');
define('SCHEME', 'http');
define('PORT', 80);
define('OPENID_PAGE', '/login/');

require_once 'openid.php';
require_once '../appcode/funcs.php';
require_once 'user_funcs.php';

$status = 0;

class KogdaActionHandler extends SimpleActionHandler {

    function doValidLogin($login) {
        global $status;
        global $ljuser;

        $uri = $this->query['open_id'];

        if ( preg_match ('#^http://(.*)\.livejournal\.com/$#', $uri, $matches) )
        {
        	$ljuser = $matches[1];
        } elseif ( preg_match ('#^http://users\.livejournal\.com/(.*)/$#', $uri, $matches) )
        {
        	$ljuser = $matches[1];
        } else
        {
        	$ljuser = '';
        	$status = 3;
        }

        $status = 4;

        set_username ($ljuser);
        
        $return_to = array_key_exists ('return_to', $_SESSION) ? $_SESSION['return_to'] : '/';
        $return_to = strlen($return_to) > 0 ? $return_to : '/';
        header ("Location: $return_to");
        die();
    }

    function doInvalidLogin() {
        global $status;
        $status = 1;
    }

    function doUserCancelled() {
        global $status;
        $status = 2;
    }

    function doErrorFromServer($message) {
         echo "<p>Произошла ошибка! ЖЖ передал ответ: $message</p>";
         die();
    }
}

$handler = new KogdaActionHandler($_REQUEST);

$ljuser = $_REQUEST['lj_user'];

$identity_url = isset( $ljuser ) ? get_lj_path($ljuser, false) : null;

if ($identity_url)
{
	$ret = openid_find_identity_info($identity_url);
  if( !$ret )
  {
  	$status = 3;
  }
  else
  {
  	openid_request ($handler, $ret, array_key_exists('return_to', $_REQUEST) ? $_REQUEST['return_to'] : '');
  }
}
else
{
	openid_check_request($handler);
}
echo $status;
 		echo '<p>Произошла какая-то ошибка! ЖЖ не подтверждает ваше имя, или вы отказались его подтвердить, или что-то еще пошло не так. Если хотите - попробуйте еще раз.</p>';
?>