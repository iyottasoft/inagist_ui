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
  <meta name="google-site-verification" content="jzZZ1eEghbCm2UVjo0UJoow7MccwgYVWzTq8WZurrSY" />
  <meta name="language" content="en" />
  <meta name="keywords" content="<?=$keywords?>" />
  <meta name="description" content="<?=$description?>" />
  <title><?=$title?></title>
  <link rel="shortcut icon" href="<?=$cdn_base?>favicon.ico" />
  <link href='http://fonts.googleapis.com/css?family=Cabin:500' rel='stylesheet' type='text/css' />
  
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/skin.css" />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/main_new.css?v=0.0.3" />
  <link rel="publisher" href="https://plus.google.com/101948688477206152601" />
  
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>  
  <script type="text/javascript" src="<?=$cdn_base?>js/jquery.jcarousel.min.js"></script>
  <script type="text/javascript" src="<?=$cdn_base?>js/jquery-images-ondemand.0.1.min.js"></script>
  <script type="text/javascript" src="<?=$cdn_base?>js/jquery.oembed.min.js"></script>
  <script type="text/javascript">
    window.baseurl="<?=$baseurl?>";
    <? if(isset($user)) echo "window.user='$user';"; ?>
  </script>
  <script type='text/javascript' src='http://partner.googleadservices.com/gampad/google_service.js'>
  </script>
</head>
<body>
<div id="maincontainer">
  <?php if (!$hideheader){?>	
  <div id="header"><div id="headerInner"><?=$header?></div></div>
  <?php }?>
  <div id="content">
    <?=$content?>
  </div>
  <div id="scriptContainer">
    <script type="text/javascript" src="<?=$cdn_base?>js/common.js"></script>
    <script type="text/javascript" src="<?=$cdn_base?>js/jquery.ticker.js"></script>
    <script type="text/javascript" src="<?=$cdn_base?>js/main.min.js"></script>
  </div>
  <div id="footer" style="padding-top:3px; padding-bottom: 3px; margin-top: 0px;">
  	<script type="text/javascript">
	  var _gaq = _gaq || [];
  	  _gaq.push(['_setAccount', 'UA-16053252-1']);
      _gaq.push(['_setSessionCookieTimeout',7200000]);
  	  _gaq.push(['_trackPageview']);
      _gaq.push(['_trackPageLoadTime']);
    var _qevents = _qevents || [];
      _qevents.push({qacct:"p-ddpcjPlbaSPxY"});

	  (function() {
      var bsa = document.createElement('script');
         bsa.type = 'text/javascript';
         bsa.async = true;
         bsa.src = '//s3.buysellads.com/ac/bsa.js';
      (document.getElementsByTagName('head')[0]||document.getElementsByTagName('body')[0]).appendChild(bsa);

	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);

      var elem = document.createElement('script');
      elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
      elem.async = true;
      elem.type = "text/javascript";
      var st = document.getElementsByTagName('script')[0]; st.parentNode.insertBefore(elem, st);
	  })();
	</script>
  <div class="left" style="width: 300px;text-align: left;">
	powered by <a href="http://twitter.com" target="_blank">twitter &reg;</a> 
  </div>
  <div class="left" style="text-align: center; width: 340px" align="center">
    <a href="http://blog.inagist.com/">Blog</a> |
    <a href="http://<?=$domain?>/about">About Us</a> |
    <a href="http://<?=$domain?>/all" title="all channels">Channels</a>
  </div>
  <div class="right" style="width:300px">
  	<span style="padding-top:2px;">Follow Us On &nbsp;&nbsp;</span>
    <a href="http://twitter.com/inagist" target="_blank">
    	<img src="<?=$cdn_base?>/images/tweet.png" border="0" align="right" style="width: 22px; height: 22px;"/></a>
    <a href="http://facebook.com/inagist" target="_blank">
    	<img src="<?=$cdn_base?>/images/facebook.png" border="0" align="right" style="padding-right: 10px;width: 22px; height: 22px;"/></a>
  </div>
  </div>
</div>

</body>
</html>
