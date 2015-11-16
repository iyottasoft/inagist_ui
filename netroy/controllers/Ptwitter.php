<?php
  class Ptwitter extends Controller{

		private static $partners_twitter = array (
      "190235"=>array("name"=>"livemint","key"=>"12JDk55hI4gzUZgC6ytjKQ","sec"=>"Wacz2NIw2mpDAgc1ki1XWKnb3X8NC5tj2O2G9fTLjo", "domain" => ".inagist.com"),
      "tweementry"=>array("name"=>"tweementry","key"=>"awNm10Z8oCAmzPlmugn4Rg","sec"=>"P2VuOwDEs5bel5qMlMD7G0Bib0LGcXmtzW8vywLUfE", "domain" => ".inagist.com"),
      "default"=>array("name"=>"inagist","key"=>"CQqgZr8OR5Aeq2omzyEj4g","sec"=>"muvUeGz4J5665TgiZKQv0KuXFZwdUt136glE5DS3lE", "domain" => ".tweementary.com"));

    public static function getPartnerId($partner_id=null){
      if (isset($partner_id) && $partner_id!= '' && array_key_exists($partner_id, self::$partners_twitter))
        return $partner_id;
      else
        return "default";
    }

    public function Login($params=null){
      $partner_id=self::getPartnerId($_GET['partner']);
      $twitter_key=self::$partners_twitter[$partner_id]['key'];
      $twitter_sec=self::$partners_twitter[$partner_id]['sec'];
                  
      define('TWITTER_CONSUMER_KEY',      $twitter_key);
      define('TWITTER_CONSUMER_SECRET',   $twitter_sec);
      define('TWITTER_REQUEST_URL',       'https://api.twitter.com/oauth/request_token');
      define('TWITTER_ACCESS_URL',        'https://api.twitter.com/oauth/access_token');
      define('TWITTER_AUTHORIZE_URL',     'https://api.twitter.com/oauth/authenticate');
       
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
            
      $partner_id=self::getPartnerId($_REQUEST['partner']);
      session_set_cookie_params(60 * 60, '/', self::$partners_twitter[$partner_id]['domain']);
      session_start();
      $twitter_key=self::$partners_twitter[$partner_id]['key'];
      $twitter_sec=self::$partners_twitter[$partner_id]['sec'];
          
      $response_text = json_encode(array('response' => 400, 'text' => 'Not Authorized'));
      
      if (isset($_SESSION[$partner_id.'_user_id']) && isset($_REQUEST['tweet_text'])) {
        $oauth = new OAuth($twitter_key, $twitter_sec, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
        $oauth->setToken($_SESSION['oauth_token_'.$partner_id], $_SESSION['oauth_token_secret_'.$partner_id]);
      
        $params = array('status'=>$_REQUEST['tweet_text']);
        if (isset($_REQUEST['tweet_id'])) $params['in_reply_to_status_id'] = $_REQUEST['tweet_id'];
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
		      
      $partner_id=self::getPartnerId($_REQUEST['partner']);
      session_set_cookie_params(60 * 60, '/', self::$partners_twitter[$partner_id]['domain']);
      session_start();
      $twitter_key=self::$partners_twitter[$partner_id]['key'];
      $twitter_sec=self::$partners_twitter[$partner_id]['sec'];
      
      $response_text = json_encode(array('response' => 400, 'text' => 'Not Authorized'));
      
      if (isset($_SESSION[$partner_id.'_user_id']) && isset($_GET['tweet_id'])) {
        $oauth = new OAuth($twitter_key, $twitter_sec, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
        $oauth->setToken($_SESSION['oauth_token_'.$partner_id], $_SESSION['oauth_token_secret_'.$partner_id]);
        
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
    
    public function Favorite($params=null){
    	$favorite_url = "http://api.twitter.com/1/favorites/create";
		      
      $partner_id=self::getPartnerId($_REQUEST['partner']);
      session_set_cookie_params(60 * 60, '/', self::$partners_twitter[$partner_id]['domain']);
      session_start();
      $twitter_key=self::$partners_twitter[$partner_id]['key'];
      $twitter_sec=self::$partners_twitter[$partner_id]['sec'];
      
      $response_text = json_encode(array('response' => 400, 'text' => 'Not Authorized'));
      
      if (isset($_SESSION[$partner_id.'_user_id']) && isset($_GET['tweet_id'])) {
        $oauth = new OAuth($twitter_key, $twitter_sec, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
        $oauth->setToken($_SESSION['oauth_token_'.$partner_id], $_SESSION['oauth_token_secret_'.$partner_id]);
        
        $tweet_id = $_GET['tweet_id'];
        $oauth->fetch("$favorite_url/$tweet_id.json", array(), OAUTH_HTTP_METHOD_POST);
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
    
    //logout partners  
    public function Logout($params=null){
      $partner_id="default";
      if (isset($params['partner']) && $params['partner']!='' && array_key_exists($params['partner'], self::$partners_twitter))
          $partner_id = $params['partner'];
	    $_SESSION['twitter_'.$partner_id] = null;
      $_SESSION[$partner_id.'_user_id'] = null;
    	$_SESSION[$partner_id.'_profile_image_url'] = null;
    	$_SESSION[$partner_id.'_name'] = null;
    	
	    $return_url = $_SERVER["HTTP_REFERER"];
	    header("Location: $return_url");
	    exit;
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
