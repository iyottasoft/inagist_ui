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
    $_SERVER['FULL_URL'] .= $_SERVER['HTTP_HOST'].$script_name;
} else {
    $_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].$script_name;
}
if (isset($no_cache) && $no_cache) {
  header('HTTP/1.1 410 Gone');
  if(preg_match('/(googlebot|yahoo|bingbot|baidu|skipcache)/i', $_SERVER['HTTP_USER_AGENT'])){
    header("Cache-Control: no-cache");
    exit;
  }
}
$expireAge = 120;
header("Cache-Control: max-age=$expireAge");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $expireAge) . " GMT");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xml:lang="en" lang="en">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
  <meta name="google-site-verification" content="jzZZ1eEghbCm2UVjo0UJoow7MccwgYVWzTq8WZurrSY" />
  <meta name="language" content="en" />
  <meta name="keywords" content="<?=$keywords?>" />
  <meta name="description" content="<?=$description?>" />
  <meta property="fb:app_id" content="184217584951764" />
  <meta property="og:title" content="<?=$title?>" />
  <meta property="og:description" content="<?=$description?>" />
  <meta property="og:type" content="article" />
  <meta property="og:url" content="<?=$_SERVER['FULL_URL']?>" />
  <title><?=$title?></title>
  <link rel="shortcut icon" href="<?=$cdn_base?>favicon.ico" />
  <link rel="publisher" href="https://plus.google.com/101948688477206152601" />
  <link href='http://fonts.googleapis.com/css?family=Maven+Pro:400,700|Brawler&v2' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/reset.css" />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/common.css?v=1.9.8" />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/main.css?v=1.9.8" />
  
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
  <script type="text/javascript" src="<?=$cdn_base?>js/minified/jquery.embedly.min.js?v=1"></script>
  <script type="text/javascript" src="<?=$cdn_base?>js/minified/pretty.min.js"></script>
  <script type="text/javascript" src="<?=$cdn_base?>js/minified/twitterlib.min.js"></script>
  <script type="text/javascript">window.baseurl="<?=$baseurl?>";<? if(isset($user)) echo "window.user='$user';"; ?></script>
  <script type='text/javascript' src='http://partner.googleadservices.com/gampad/google_service.js'>
  </script>
  <script type='text/javascript'>
  GS_googleAddAdSenseService("ca-pub-2412868093846820");GS_googleEnableAllServices();
  </script>
  <style type="text/css">
	body{font:12px/14px Arial,sans-serif;}
	
	/*Channel Name Div */
	.channel{padding-left:80px;padding-right:80px; border-bottom: 1px solid #111; border-collapse: separate;}
	.homepagetitle{background-color: #01141A; width: 100%;}
	.channelname {text-transform: uppercase;padding-bottom:20px; padding-right:5px; font-size: 17px;
	height: 100%; text-align: right; width:180px; color:#AAA; font-family: tahoma; vertical-align: bottom;}
	.channelname a{text-decoration: underline; line-height: 18px;} 
	.homepagename{width: 490px; text-transform: lowercase;border-left: 1px solid #111; font-size: 17px;
	height: 75px; text-align: center; color:#AAA; font-family: tahoma; vertical-align: middle;}
	.tweet{width: 600px; font-size: 15px;border-left: 1px solid #111; }
	
	/* Actual Tweet List */
	table.tweets{width:100%;}
	table.tweets td{vertical-align:top;align:left;}
	td.pic{padding:5px;width:50px;}
	td.pic img{width:48px;height:48px;background-color:#EEE;border:1px solid #BBB;}
	td.text{padding:4px;padding-top:0px;}
	td.text a{color:#389DE6;/*#7bdced;*/text-decoration:none;}
	td.text div{min-height:50px;padding-top:1px;}
	td.text span.retweet-icon{background-color:#333333;background-position:-80px -2px;border:1px solid #444444;display:inline-block;height:12px;width:16px;margin:1px 2px -3px;}
  </style>
  
</head>
<body>
<div id="wrapper">
  <div id="header"><div id="headerInner"><?=$header?></div></div>
  <div id="content">
    <?=$content?>
  </div>
  <?php if ($show_ads) { ?>
  <div class="tweetcontent ad_container" style="margin-bottom: 10px;" id="tweetdetail_google_links_ad">
    <script type='text/javascript'>
    GA_googleAddSlot("ca-pub-2412868093846820", "Tweet_Detail_Bottom");
    </script>
    <script type='text/javascript'>
    GA_googleFetchAds();
    </script>
    <!-- Detail_Pages_Large_Unit -->
    <script type='text/javascript'>
    GA_googleFillSlot("Tweet_Detail_Bottom");
    </script>
  </div> 
  <?php } ?>
  <div id="scriptContainer">
    <!--script type="text/javascript" src="<?=$cdn_base?>js/minified/common.min.js"></script>
    <script type="text/javascript" src="<?=$cdn_base?>js/minified/jquery.ticker.min.js"></script>
    <script type="text/javascript" src="<?=$cdn_base?>js/minified/modal.min.js"></script-->
    <script type="text/javascript" src="<?=$cdn_base?>js/minified/all.min.js"></script>
  </div>
  <div align="center" style="border-top: 2px solid #333;" id="footer"><?=$footer?></div>
</div>
</body>
</html>
