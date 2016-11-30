<?php
require_once 'funcs.php';
require_once 'user_funcs.php';

if ($_GET && $_SESSION['csrf_token'])
{
	if ($_GET['csrf_token'] == $_SESSION['csrf_token'])
	{
		$assert = $_GET['token'];
		$c = curl_init("https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=$assert");
		if ($c)
		{
      curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			$result_str = curl_exec($c);
			if ($result_str)
			{
				$result = json_decode($result_str, true);
				{
					$user_id = get_user_id();
					$email = $result['email'];
					if ($user_id)
					{
						set_email ($user_id, $email);
					}
					else
					{
						set_username (NULL, $email);
					}
				}
			}
		}
	}
	die();
}
return_to_main();
?>