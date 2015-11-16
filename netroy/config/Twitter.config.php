<?php
  $tConfig = array(
    "oauth"=>"https://api.twitter.com/oauth",
    "api"=>"http://api.twitter.com/1",
    "key"=>"GgzauM2qvpu7nglP4qZF3Q",
    "sec"=>"sXIRr0JzLY7sPemq2bbUptTQnGU24txre9Mww2ntDbU",
    "auth_return"=>"/login",
    "wait_time"=>3600
  );
  $twitterConfig = array(
    "local"=>$tConfig,
    "prod"=>$tConfig
  );
var_extract($twitterConfig,"twitter");
?>
