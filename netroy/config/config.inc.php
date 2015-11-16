<?php

session_set_cookie_params(604800, '/', '.inagist.com');
if(isset($_COOKIE[session_name()])) {
  session_start();
}
error_reporting(E_ALL);
ini_set("zlib.output_compression","off");

function __autoload($className){
  require_once($className.'.php');
}

if(!defined("CONFIG_INITIALIZED")){

  $configName = "ig_config";
  
  $masterConfigArray = xcache_get($configName);

  if($masterConfigArray == null || empty($masterConfigArray) || isset($_GET['reloadconfig'])){
    $masterConfigArray = array();

    $hostname = strtolower(trim(file_get_contents("/etc/hostname")));
    $configMode = ($hostname=="netroy")?"local":"prod";

    function var_extract(&$config_array,$prefix=NULL,$postfunc=null){
      global $configMode,$masterConfigArray;
      $configMode = strtolower($configMode);

      // Extract the appropriate config
      //$mode = ($prefix==NULL)?EXTR_OVERWRITE:EXTR_PREFIX_ALL;
      $config = $config_array[$configMode];

      // Post process the config .. just in case
      if(function_exists($postfunc)) $postfunc($config);

      // Iterate and merge into the master-config
      foreach($config as $key=>$value){
        if($prefix!=NULL) $key = $prefix."_".$key;
        $masterConfigArray[$key] = $value;
      }
    }

    $configPattern = "config/*.config.php";
    while(count($configFiles = glob($configPattern))==0){
      $configPattern = "../".$configPattern;
    }

    foreach($configFiles as $config_filename) {
      require_once($config_filename);
    }
    //$masterConfigArray['web_root'] = $_SERVER['DOCUMENT_ROOT'];
    $masterConfigArray['config_mode'] = $configMode;
    
    xcache_set($configName,$masterConfigArray);
  }
  extract($masterConfigArray,EXTR_OVERWRITE);

  $baseurl = "http://".$_SERVER["SERVER_NAME"]."/";
  $api_base = ($config_mode=="local")?"http://$domain/netroy/data":"http://inagist.com/api/v1";
  if(isset($_GET["realdata"])) $api_base = "http://inagist.com/api/v1";

  require_once("utils/MVC.php");
  MVC::importNS("controllers");
  MVC::importNS("utils");
  MVC::importNS("views");

  if($config_mode=="local") MVC::importNS("data");

  // Detect iPhone/iPod/Android/webOs and put it in session
  if(isset($_SESSION['display_mode'])){
    $display_mode = $_SESSION['display_mode'];
  }else{
    $ua = $_SERVER['HTTP_USER_AGENT'];
    $match = array();
    preg_match("/iPhone|iPod|Android|webOS/",$ua,$match);
    if(count($match)!=1) $display_mode = "";
    else $display_mode = "mobile";//$match[0];
    $_SESSION['display_mode'] = $display_mode;
  }
  //$display_mode = "mobile";
  define("CONFIG_INITIALIZED",true);
}
error_reporting(E_ERROR);
?>
