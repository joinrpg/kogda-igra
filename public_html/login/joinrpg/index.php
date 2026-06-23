<?php
require_once 'funcs.php';
require_once 'user_funcs.php';
require_once 'config.php';

// OAuth 2.0 Authorization Code flow (with PKCE) against id.joinrpg.ru.
// Accounts are matched across systems by email.

function joinrpg_base64url ($data)
{
    return rtrim (strtr (base64_encode ($data), '+/', '-_'), '=');
}

function joinrpg_redirect_uri ()
{
    return SITENAME_SCHEME . '://' . SITENAME_HOST . '/login/joinrpg/';
}

// Phase A: no authorization code yet -> start the flow and redirect to id.joinrpg.ru.
function joinrpg_start_login ()
{
    $state = joinrpg_base64url (random_bytes (16));
    $verifier = joinrpg_base64url (random_bytes (32));
    $challenge = joinrpg_base64url (hash ('sha256', $verifier, true));

    $_SESSION['joinrpg_oauth_state'] = $state;
    $_SESSION['joinrpg_oauth_verifier'] = $verifier;

    $query = http_build_query (array (
        'client_id' => JOINRPG_CLIENT_ID,
        'redirect_uri' => joinrpg_redirect_uri (),
        'response_type' => 'code',
        'scope' => 'openid email',
        'state' => $state,
        'code_challenge' => $challenge,
        'code_challenge_method' => 'S256',
    ));

    redirect_to (JOINRPG_ID_BASE . '/connect/authorize?' . $query);
}

// Phase B: exchange the authorization code for an access token (server-to-server).
function joinrpg_fetch_access_token ($code, $verifier)
{
    $c = curl_init (JOINRPG_ID_BASE . '/connect/token');
    if (!$c)
    {
        return NULL;
    }
    curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($c, CURLOPT_POST, true);
    curl_setopt ($c, CURLOPT_POSTFIELDS, http_build_query (array (
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => joinrpg_redirect_uri (),
        'client_id' => JOINRPG_CLIENT_ID,
        'client_secret' => JOINRPG_CLIENT_SECRET,
        'code_verifier' => $verifier,
    )));
    $result_str = curl_exec ($c);
    curl_close ($c);
    if (!$result_str)
    {
        return NULL;
    }
    $result = json_decode ($result_str, true);
    return is_array ($result) && array_key_exists ('access_token', $result)
        ? $result['access_token'] : NULL;
}

// Phase B: read the user's email from the userinfo endpoint.
function joinrpg_fetch_email ($access_token)
{
    $c = curl_init (JOINRPG_ID_BASE . '/connect/user_info');
    if (!$c)
    {
        return NULL;
    }
    curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($c, CURLOPT_HTTPHEADER, array ("Authorization: Bearer $access_token"));
    $result_str = curl_exec ($c);
    curl_close ($c);
    if (!$result_str)
    {
        return NULL;
    }
    $result = json_decode ($result_str, true);
    return is_array ($result) && array_key_exists ('email', $result)
        ? $result['email'] : NULL;
}

if (array_key_exists ('code', $_GET))
{
    $state = array_key_exists ('joinrpg_oauth_state', $_SESSION) ? $_SESSION['joinrpg_oauth_state'] : NULL;
    $verifier = array_key_exists ('joinrpg_oauth_verifier', $_SESSION) ? $_SESSION['joinrpg_oauth_verifier'] : NULL;
    unset ($_SESSION['joinrpg_oauth_state']);
    unset ($_SESSION['joinrpg_oauth_verifier']);

    $got_state = array_key_exists ('state', $_GET) ? $_GET['state'] : '';
    if ($state && $verifier && hash_equals ($state, $got_state))
    {
        $access_token = joinrpg_fetch_access_token ($_GET['code'], $verifier);
        if ($access_token)
        {
            $email = joinrpg_fetch_email ($access_token);
            if ($email)
            {
                $user_id = get_user_id ();
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
    return_to_main ();
}
elseif (array_key_exists ('error', $_GET))
{
    // User cancelled or the provider returned an error.
    return_to_main ();
}
else
{
    joinrpg_start_login ();
}
?>
