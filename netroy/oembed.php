<?php
  if(!isset($_GET["url"])) exit;
  $method = $_SERVER['REQUEST_METHOD'];
  if ($method == 'GET'){
    $url = urldecode($_GET["url"]);
    $callback = $_GET["callback"];
    $odomain = $_SERVER['HTTP_ORIGIN'];
    $domain = preg_replace("/\/.*/","",preg_replace("/^http(s)?:\/\//","",$url));

    $endpoints = array(
      "youtube.com" => "http://www.youtube.com/oembed?format=json&url=__URL__",
      "www.youtube.com" => "http://www.youtube.com/oembed?format=json&url=__URL__",
      "flickr.com" => "http://www.flickr.com/services/oembed/?format=json&url=__URL__",
      "www.flickr.com" => "http://www.flickr.com/services/oembed/?format=json&url=__URL__",
      "yfrog.com" => "http://www.yfrog.com/api/oembed?url=__URL__",
      "www.yfrog.com" => "http://www.yfrog.com/api/oembed?url=__URL__"
    );
    $query_endpoint = $endpoints[$domain];
    //if (preg_match("/inagist\.com/", $odomain) && isset($query_endpoint)){
    if (isset($query_endpoint)){
      header("Content-type: application/json");
      $expireAge = 60*60*24*365;
      header("Cache-Control: max-age=$expireAge");
      header("Expires: " . gmdate("D, d M Y H:i:s", time() + $expireAge) . " GMT");

      $query_url = preg_replace('/__URL__/', $url, $query_endpoint);
      $resp = file_get_contents($query_url, false);
      if (isset($callback)){
        echo ($callback . "(". $resp.")");
      } else {
        echo $resp;
      }
    }
  } elseif ($method == 'OPTIONS') {
    if (preg_match("/inagist.com$/i", $_SERVER['HTTP_ORIGIN'])){
      header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
      header('Access-Control-Allow-Credentials: true');
      header('Access-Control-Allow-Methods: GET, OPTIONS');
      header('Content-Length: 0');
      header('Content-Type: text/plain');
    } else {
      header($_SERVER["SERVER_PROTOCOL"]." 405 Method not Allowed");
    }
  } else {
    header($_SERVER["SERVER_PROTOCOL"]." 405 Method not Allowed");
  }
?>
