<?php
  class Twitter_Partner extends Controller{

    public function Login($params=null){
		//TODO move this representation to DB / xcache
		echo "here";exit;
		$partner_id="";
		if ($_GET['partner']!='')
			$partner_id=$_GET['partner'];
			
		$partners_twitter = array ("190235"=>array("name"=>"livemint","key"=>"eQgz3mye6bWYUduShTGPg","sec"=>"opCWCiG1TJg0Hn19ogfkBIE52scriIpQbkUw9fC8"));
		
		$twitter_key=$partners_twitter[$_GET['partner']]['key'];
		$twitter_sec=$partners_twitter[$_GET['partner']]['sec'];
		      			
		define('TWITTER_CONSUMER_KEY',      $twitter_key);
		define('TWITTER_CONSUMER_SECRET',   $twitter_sec);
		define('TWITTER_REQUEST_URL',       'https://api.twitter.com/oauth/request_token');
		define('TWITTER_ACCESS_URL',        'https://api.twitter.com/oauth/access_token');
		define('TWITTER_AUTHORIZE_URL',     'https://api.twitter.com/oauth/authorize');
		 
		session_start();
		if (!isset($_SESSION['twitter_'.$partner_id])) {
		    setOAuth($partner_id);
		    $_SESSION['twitter_'.$partner_id] = getUserInfo($_SESSION['oauth_token_'.$partner_id], $_SESSION['oauth_token_secret_'.$partner_id]);
		    $_SESSION[$partner_id.'_user_id'] = $_SESSION['twitter_'.$partner_id]['screen_name'];
    		$_SESSION[$partner_id.'_profile_image_url'] = $_SESSION['twitter_'.$partner_id]['profile_image_url'];
    		$_SESSION[$partner_id.'_name'] = $_SESSION['twitter_'.$partner_id]['name'];
		}
		
		echo "
		<html>
		<head>
		<title>Connect</title>
		</head>
		<body>
		<script>
		window.close();
		</script>
		</body>
		</html>";
    	
    }
    
    public function Reply($params=null){
		$reply_url = "http://api.twitter.com/1/statuses/update";
		$app = 'twitter';
		global $partners_twitter;
		//TODO move this representation to DB / xcache
		$partners_twitter = array ("190235"=>array("name"=>"livemint","key"=>"eQgz3mye6bWYUduShTGPg","sec"=>"opCWCiG1TJg0Hn19ogfkBIE52scriIpQbkUw9fC8"));
		      
		session_set_cookie_params(60 * 60, '/', '.inagist.com');
		session_start();
		
		if (isset($_REQUEST['partner']) && $_REQUEST['partner']!='')
		{
      		if ($partners_twitter[$_REQUEST['partner']]!=null)
      		{
      			$conskey[$app]=$partners_twitter[$_REQUEST['partner']]['key'];
      			$conssec[$app]=$partners_twitter[$_REQUEST['partner']]['sec'];		
      		}      		
	    }
	      
		$response_text = json_encode(array('response' => 400, 'text' => 'Not Authorized'));
		
		if (isset($_SESSION[$_REQUEST['partner'].'_user_id']) && isset($_REQUEST['tweet_id']) && isset($_REQUEST['tweet_text'])) {
		  $oauth = new OAuth($conskey[$app], $conssec[$app], OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
		  $oauth->setToken($_SESSION['oauth_token_'.$_REQUEST['partner']], $_SESSION['oauth_token_secret_'.$_REQUEST['partner']]);
		
		  $tweet_id = $_REQUEST['tweet_id'];
		  $params = array('status'=>$_REQUEST['tweet_text'],'in_reply_to_status_id'=>$_REQUEST['tweet_id']);
		  $oauth->fetch("$reply_url.json", $params, OAUTH_HTTP_METHOD_POST);
		  $response_info = $oauth->getLastResponseInfo();
		  $response_text = $oauth->getLastResponse(); 
		  $json = json_decode($response_text,true);
		  if ($response_info['http_code'] == 200 && isset($json['id'])) {
		    $response_text = json_encode(array('response' => 200, 'text' => json_decode($response_text)));
		  } else {
		    $response_text = json_encode(array('response' => $response_info['http_code'], 'text' => $response_text));
		  }
		}
		header('Content-Type: application/json; charset=utf-8');
		print_r($response_text);
    }
    
    public function Retweet($params=null){
    	
    	$retweet_url = "http://api.twitter.com/1/statuses/retweet";
    	$app = 'twitter';
		global $partners_twitter;
		//TODO move this representation to DB / xcache
		$partners_twitter = array ("190235"=>array("name"=>"livemint","key"=>"eQgz3mye6bWYUduShTGPg","sec"=>"opCWCiG1TJg0Hn19ogfkBIE52scriIpQbkUw9fC8"));
		      
		session_set_cookie_params(60 * 60, '/', '.inagist.com');
		session_start();
		
		if (isset($_REQUEST['partner']) && $_REQUEST['partner']!='')
		{
      		if ($partners_twitter[$_REQUEST['partner']]!=null)
      		{
      			$conskey[$app]=$partners_twitter[$_REQUEST['partner']]['key'];
      			$conssec[$app]=$partners_twitter[$_REQUEST['partner']]['sec'];		
      		}      		
	    }
    	
		$response_text = json_encode(array('response' => 400, 'text' => 'Not Authorized'));
		
		if (isset($_SESSION[$_REQUEST['partner'].'_user_id']) && isset($_GET['tweet_id'])) {
		  $oauth = new OAuth($conskey[$app], $conssec[$app], OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
		  $oauth->setToken($_SESSION['oauth_token_'.$_REQUEST['partner']], $_SESSION['oauth_token_secret_'.$_REQUEST['partner']]);
			
		  $tweet_id = $_GET['tweet_id'];
		  $oauth->fetch("$retweet_url/$tweet_id.json", array(), OAUTH_HTTP_METHOD_POST);
		  $response_info = $oauth->getLastResponseInfo();
		  $response_text = $oauth->getLastResponse(); 
		  if ($response_info['http_code'] == 200) {
		    $response_text = json_encode(array('response' => 200, 'text' => json_decode($response_text)));
		  } else {
		    $response_text = json_encode(array('response' => $response_info['http_code'], 'text' => $response_text));
		  }
		}
		header('Content-Type: application/json; charset=utf-8');
		print_r($response_text);
    }
}
  
 
function setOAuth($partner_id="")
{
    //  pecl_oauth
    if($partner_id!=''){
	    $oauth = new OAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
	    $oauth->enableDebug();
	    try {
	        if (isset($_GET['oauth_token'], $_SESSION['oauth_token_secret'])) {
	            $oauth->setToken($_GET['oauth_token'], $_SESSION['oauth_token_secret']);
	            $accessToken = $oauth->getAccessToken(TWITTER_ACCESS_URL);
	            $_SESSION['oauth_token_'.$partner_id] = $accessToken['oauth_token'];
	            $_SESSION['oauth_token_secret_'.$partner_id] = $accessToken['oauth_token_secret'];
	 
	            $response = $oauth->getLastResponse();
	            parse_str($response, $get);
	            if (!isset($get['user_id'])) {
	                throw new Exception('Authentication failed.');
	            }
	        } else {
	            $requestToken = $oauth->getRequestToken(TWITTER_REQUEST_URL);
	            $_SESSION['oauth_token_secret'] = $requestToken['oauth_token_secret'];
	            header('Location: ' . TWITTER_AUTHORIZE_URL . '?oauth_token=' . $requestToken['oauth_token']);
	            die();
	        }
	    } catch (Exception $e) {
	        var_dump($oauth->debugInfo);
	        die($e->getMessage());
	    }
    }
}
 
function getUserInfo($token, $secret)
{
    $oauth = new OAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
    $oauth->setToken($token, $secret);
    $oauth->fetch('http://twitter.com/account/verify_credentials.json');
    $buf = $oauth->getLastResponse();
    return json_decode($buf, true);
}
 
?>
