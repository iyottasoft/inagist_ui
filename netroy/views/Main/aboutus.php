<?php
$_SERVER['FULL_URL'] = 'http';
$script_name = '';
if(isset($_SERVER['REQUEST_URI'])) {
    $script_name = $_SERVER['REQUEST_URI'];
} else {
    $script_name = $_SERVER['PHP_SELF'];
    if($_SERVER['QUERY_STRING']>' ') {
        $script_name .=  '?'.$_SERVER['QUERY_STRING'];
    }
}
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
    $_SERVER['FULL_URL'] .=  's';
}
$_SERVER['FULL_URL'] .=  '://';
if($_SERVER['SERVER_PORT']!='80')  {
    $_SERVER['FULL_URL'] .=
    $_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$script_name;
} else {
    $_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].$script_name;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
  <meta name="language" content="en" />
  <meta name="keywords" content="<?=$keywords?>" />
  <meta name="description" content="<?=$description?>" />
  <title><?=$title?></title>
  <link rel="shortcut icon" href="<?=$cdn_base?>favicon.ico" />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/reset.css" />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/common.css?v=1.6" />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/main.css?1.6" />
  <script type="text/javascript" src="<?=$cdn_base?>js/jquery.min.js"></script>
  <script type="text/javascript" src="<?=$cdn_base?>js/jquery.oembed.min.js"></script>
  <script type="text/javascript">window.baseurl="<?=$baseurl?>";<? if(isset($user)) echo "window.user='$user';"; ?></script>
</head>
<body>
<div id="wrapper">
  <div id="header"><div id="headerInner"><?=$header?></div></div>
  <div id="content">
    <?=$content?>
  </div>
  <div id="scriptContainer">
    <script type="text/javascript" src="<?=$cdn_base?>js/common.js"></script>
    <script type="text/javascript" src="<?=$cdn_base?>js/modal.js"></script>
    <script type="text/javascript" src="<?=$cdn_base?>js/main.min.js"></script>
  </div>
  <div id="footer"><div id="footerInner"><?=$footer?></div></div>
</div>
<div class="sharediv">
<ul class="sharebutton">
	<li><a id="ck_facebook" class="stbar chicklet" href="javascript:void(0);"><img src="/netroy/images/share/facebook.png" /></a>
</li>
	<li><a id="ck_twitter" class="stbar chicklet" href="javascript:void(0);"><img src="/netroy/images/share/twitter.png" /></a>
</li>	
	<li><a id="ck_gbuzz" class="stbar chicklet" target="_blank" href="http://www.google.com/buzz/post?url=<?=urlencode($_SERVER['FULL_URL']);?>&title=<?=urlencode($title)?>"><img src="/netroy/images/share/google.png" /></a>
</li>
	<li><a id="ck_digg" class="stbar chicklet" href="javascript:void(0);"><img src="/netroy/images/share/digg.png" /></a>
</li>
</ul>
<script type="text/javascript">
	var shared_object = SHARETHIS.addEntry({
	title: document.title,
	url: document.location.href
});
shared_object.attachChicklet("facebook", document.getElementById("ck_facebook"));
shared_object.attachChicklet("twitter", document.getElementById("ck_twitter"));
shared_object.attachChicklet("digg", document.getElementById("ck_digg"));
</script>
</div>
</body>
</html>
