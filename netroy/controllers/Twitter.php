<?php

  class Twitter extends Controller{

    public function Index($params=null){
      // return user info... false for not logged in user
      
    }

    private function returnPage($return_url=null){
      global $domain;
      if (($_SERVER["HTTP_REFERER"]=='http://inagist.com/') && (isset($_SESSION['user_id'])) && ($return_url==null))
      	$return_url = "http://$domain/".$_SESSION['user_id']; 
      if($return_url==null){
        if(isset($_SESSION['return_url'])){
          $return_url = $_SESSION['return_url'];
          unset($_SESSION['return_url']);
        }elseif(isset($_SESSION['user_id'])){
          $return_url = "http://$domain/".$_SESSION['user_id'];
        }elseif(isset($_SERVER["HTTP_REFERER"])){
          $return_url = $_SERVER["HTTP_REFERER"];
        }else{
          $return_url = "http://$domain/";
        }
      }
      header("Location: $return_url");
      exit;
    }

    private function initSession($duration=604800,$cdomain=".inagist.com"){
      global $domain;

      // if already logged in avoid all the jazz
      if (isset($_SESSION['user_id'])) {
        $this->returnPage();
      }
      if(isset($_SERVER["HTTP_REFERER"]) && !isset($_SESSION['return_url'])){
        $_SESSION['return_url'] = $_SERVER["HTTP_REFERER"];
      }

      // Login for a week
      session_set_cookie_params($duration,'/',$cdomain);
      session_start();
    }

    private function getOAuthToken($mode='authenticate'){
      global $twitter_oauth, $twitter_api, $twitter_key, $twitter_sec,$twitter_auth_return, $domain;
      $oauth_req_url = "$twitter_oauth/request_token";
      $oauth_auth_url= "$twitter_oauth/$mode";
      $oauth_acc_url = "$twitter_oauth/access_token";
      $return_url = "http://$domain$twitter_auth_return?app=twitter&mode=$mode";

      $this->initSession();

      // In state=1 the next request should include an oauth_token, If it doesn't go back to 0
      if ((!isset($_SESSION['state'])) || (!isset($_GET['oauth_token'])
          && $_SESSION['state']!=0
          && !isset($_SESSION['oauth']))){
        $_SESSION['state'] = 0;
      }

      try{
        $oauth = new OAuth($twitter_key, $twitter_sec, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
        if(!isset($_GET['oauth_token']) && ($_SESSION['state'] == 0)) {
          $request_token_info = $oauth->getRequestToken($oauth_req_url, $return_url);
          $_SESSION['secret'] = $request_token_info['oauth_token_secret'];
          $_SESSION['state'] = 1;
          header("Location: $oauth_auth_url?oauth_token=".$request_token_info['oauth_token']);
          exit;
        } elseif($_SESSION['state']==1) {
          $oauth->setToken($_GET['oauth_token'],$_SESSION['secret']);
          $access_token_info = $oauth->getAccessToken($oauth_acc_url);
          $_SESSION['state'] = 2;
          $_SESSION['twitter_token'] = $access_token_info['oauth_token'];
          $_SESSION['twitter_secret'] = $access_token_info['oauth_token_secret'];
        }
        if (isset($_SESSION['twitter_token'])) {
          list($twitter_id, $twitter_rest) = split('-', $_SESSION['twitter_token']);
          $oauth->setToken($_SESSION['twitter_token'], $_SESSION['twitter_secret']);
          $oauth->fetch("$twitter_api/users/show/$twitter_id.json");
          $user_json = json_decode($oauth->getLastResponse());

          $_SESSION['twitter_id'] = $twitter_id;
          $_SESSION['user_id'] = $user_json->{'screen_name'};
          //$_SESSION['profile_background_image_url'] = $user_json->{'profile_background_image_url'};
          $_SESSION['profile_image_url'] = $user_json->{'profile_image_url'};
          $_SESSION['name'] = $user_json->{'name'};
        }else {
          $this->returnPage($return_url);
        }
      }catch(Exception $e){
        // TODO: add log4php or something for logging issues
        echo "<pre> OAuth failed </pre>";
        //print_r($e);
        exit;
      }
    }

    // Authenticate the user from twitter to add to backend
    /*public function Authenticate($params=null){
      $this->getOAuthToken('authorize');
      
      $this->returnPage();
    }*/

    public function Login($params=null){
      global $domain;
      $this->getOAuthToken('authenticate');
      $db = new DB();
      $rows = $db->query("select id,enabled from credentials where id=".mysql_real_escape_string($_SESSION['twitter_id']));
      if($rows == 0){
        //$this->getOAuthToken('authorize');
        $vals = array($_SESSION['twitter_id'],$_SESSION['twitter_token'],$_SESSION['twitter_secret'],$_SESSION['user_id']);
        $sql = "insert into credentials (id, twitter_token, twitter_secret, user_id) ";
        $sql.= "values ('".implode("','",$vals)."') ";
        $db->query($sql);
        $_SESSION["authorization_time"] = time();
        $this->returnPage("http://$domain/".$_SESSION['user_id']);
      }else{
        $sql = "update credentials set twitter_token='".$_SESSION['twitter_token']."', twitter_secret='";
        $sql.= $_SESSION['twitter_secret']."', user_id ='".$_SESSION['user_id']."' where id = ".$_SESSION['twitter_id'];
        $db->query($sql);

        $first = current($rows);
        $_SESSION["enabled"] = $first["enabled"];
        $this->returnPage();
      }
    }

    public function Logout($params=null){
      global $domain;
      unset($_SESSION['user_id']);
      if (isset($_COOKIE[session_name()])) {
        session_start();
        session_destroy();
        setcookie(session_name(), "", time() - 3600, "/", ".inagist.com");
      }
      $this->returnPage();
    }
  }
