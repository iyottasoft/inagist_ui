<?php
  if(!isset($_GET["url"])) exit;
  $url = urldecode($_GET["url"]);
  $domain = preg_replace("/\/.*/","",preg_replace("/^http(s)?:\/\//","",$url));
  $rdomain = preg_replace("/\/.*/","",preg_replace("/^http(s)?:\/\//","",$_SERVER['HTTP_REFERER']));
  if (preg_match("/inagist\.com/", $domain)){
    $opts = array(
      'http' => array(
        'header'=> 'Cookie: ' . $_SERVER['HTTP_COOKIE']."\r\n"
      )
    );
    $context = stream_context_create($opts);

    header("Cache-Control: no-cache, must-revalidate");
    header("Content-type: application/json");
    //echo json_encode($_SERVER);exit;
    echo file_get_contents($url,false,$context);
  }
?>
