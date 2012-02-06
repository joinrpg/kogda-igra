<?php
require_once 'funcs.php';
require_once 'user_funcs.php';
if ($_GET && $_SESSION['csrf_token'])
{
	if ($_GET['csrf_token'] == $_SESSION['csrf_token'])
	{
		$assert = $_GET['assert'];
		$c = curl_init("https://browserid.org/verify");
		if ($c)
		{
			curl_setopt($c, CURLOPT_POST, true);
			curl_setopt($c,CURLOPT_POSTFIELDS, "assertion=$assert&audience=http://kogda-igra.ru");
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			$result_str = curl_exec($c);
			if ($result_str)
			{
				$result = json_decode($result_str, true);
				if ($result['status'] == 'okay')
				{
					set_username (NULL, $result['email']);
					echo $result['email'];
				}
			}
		}
	}
	die();
}

write_header("BrowserID");
if (!array_key_exists("csrf_token", $_SESSION))
{
	$_SESSION['csrf_token'] = md5(mt_rand);
}
?>
<a href="#" onclick="try_login()"><img src="https://browserid.org/i/sign_in_grey.png" alt="sign in button - grey"></a>
<form>
	<input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
</form>
<?php
write_footer();
?>