<?php

require_once 'openid/consumer.php';

class SimpleConsumer extends OpenIDConsumer {
    
    function verify_return_to( $return_to ) {
    
        $parts = parse_url( $return_to );
        
        $host = $parts['host'];
        $scheme = $parts['scheme'];
        $port = $parts['port'] ? $parts['port'] : ($scheme=='https' ? 443 : 80);

        return ($host == HOST) && ($port == PORT);

    }

}

global $openid_consumer;
$openid_consumer = new SimpleConsumer ();

class SimpleActionHandler extends ActionHandler {

    function SimpleActionHandler($query) 
    {
    	  global $openid_consumer;
        $this->query = $query;
        $this->consumer = $openid_consumer;
    }

    function doCheckAuthRequired($server_url, $return_to, $post_data) {
        $response = $this->consumer->check_auth($server_url, $return_to, $post_data,
                                                $this->getOpenID());
        $response->doAction($this);
    }

    function createReturnTo($base_url, $identity_url, $args) {
        if( !is_array( $args ) ) {
            $args = array();
        }
        $args['open_id'] = $identity_url;
        return oidUtil::append_args($base_url, $args);
    }

    function getOpenID() {
        return $this->query['open_id'];
    }
};    

function openid_request ($handler, $ret)
{
	global $openid_consumer;
	list ($identity_url, $server_id, $server_url) = $ret;
	$trust_root = SCHEME . '://' . HOST;
	$app_url = $trust_root . OPENID_PAGE;
	$return_to = $handler->createReturnTo($app_url, $identity_url);
	$redirect_url = $openid_consumer->handle_request ($server_id, $server_url, $return_to, $trust_root);
	header ( "Location: $redirect_url" );
  die (); 
}

function openid_check_request ($handler)
{
	global $openid_consumer;
	if (isset($_REQUEST['openid.mode']) || isset($_REQUEST['openid_mode'])) 
	{
  	$openid = $handler->getOpenID();
    $req = new ConsumerRequest($openid, $_REQUEST, 'GET');
    $response = $openid_consumer->handle_response($req);
    $response->doAction($handler);
  }
}

function openid_find_identity_info($identity_url)
{
	global $openid_consumer;
	return $openid_consumer->find_identity_info($identity_url);
}

?>