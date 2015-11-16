<?php
$hostConfig = array(
  "local"=>array(
    "domain"=>"local.inagist.com",
    "cdn_base"=>"http://local.inagist.com/netroy/",
    "api_base"=>"http://local.inagist.com/data",
    "web_root"=>"/Dev/Workspace/tweetcloud.jebu.net/netroy/netroy/"
  ),
  "prod"=>array(
    "domain"=>"inagist.com",
    "cdn_base"=>"http://inagist.com/netroy/",
    "api_base"=>"http://inagist.com/api/v1",
    "web_root"=>"/opt/autobuild/inagist_ui/netroy/"
  )
);
var_extract($hostConfig);
?>
