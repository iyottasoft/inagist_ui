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
  <link href='http://fonts.googleapis.com/css?family=Cabin:500' rel='stylesheet' type='text/css' />
  <link rel="publisher" href="https://plus.google.com/101948688477206152601" />
  
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/skin.css" />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/main_v15.css?v=13" />
  
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
  <script type="text/javascript">
    window.baseurl="<?=$baseurl?>";
    <? if(isset($user)) echo "window.user='$user';"; ?>
  </script>
  <style type="text/css">
/* footer */
#templatemo_footer {width: 890px;margin: 0px;padding-left:30px;padding-right:30px;background-color: #fff;height: 160px;}
#templatemo_footer .footer_box {float: left;width: 260px;padding-right: 45px;background-color: #fff;}
#templatemo_footer ul {padding: 0; margin: 0; list-style: none; }
#templatemo_footer li {padding: 0; margin: 0 0 5px 0; }
#templatemo_footer li a {display: block;color: #333;text-decoration: none;border-bottom: 1px dotted #ccc;}
#templatemo_footer li a:hover {color: #666;}
#templatemo_footer .last {padding-right: 0;}
/* end of footer */
/* copyright */
#templatemo_copyright {width: 890px;margin: 0px;padding: 10px 30px;text-align: center;background-color: #000;clear: both;color:#FFF;}
/* end of footer */
  </style>
</head>
<body>
<div id="maincontainer">
  <?php if (!$hideheader){?>	
  <div id="header"><div id="headerInner"><?=$header?></div></div>
  <?php }?>
  <div id="content" >
    <?=$content?>
  </div>
  <div id="scriptContainer">
    <!--  script type="text/javascript" src="<?=$cdn_base?>js/common.js"></script>
    <script type="text/javascript" src="<?=$cdn_base?>js/jquery.ticker.js"></script>
    <script type="text/javascript" src="<?=$cdn_base?>js/main.min.js"></script -->
    <script type="text/javascript">
      (function() {
        var s1 = document.getElementsByTagName('script')[0];
        var twS = document.createElement('script'); twS.type = 'text/javascript'; twS.async = true;
        twS.src = '<?=$cdn_base?>js/minified/all_home.min.js?v=0.0.1';
        s1.parentNode.insertBefore(twS, s1);
      })();
    </script>
  </div>
  
  	<div id="templatemo_footer">
        <div class="footer_box">
        	<h4>Navigation</h4>
        	<ul>
        		<li><a href="http://blog.inagist.com/">Blog</a></li>
                <li><a href="http://<?=$domain?>/about">About Us</a></li>
                <li><a href="http://iyottasoft.com/team">Team</a></li>
                <li><a href="mailto:info-at-iyottasoft-dot-com" class="last">Contact Us</a></li>
            </ul>  
        </div>
        
        <div class="footer_box">
        	<h4>Solutions</h4>
        	<ul>
                <li><a href="https://chrome.google.com/extensions/detail/oangdphebgapkakpmiiceehanhopodgo">Chrome Extension</a></li>
        	    <li><a href="http://store.ovi.com/publisher/inagist.com">Ovi Store Apps</a></li>
                <li><a href="http://wordpress.org/extend/plugins/related-tweets-from-in-a-gist/">Wordpress Plugin</a></li>
                <li><a href="/?r=main/getWidgetSnippet">Custom Widget</a></li>
            </ul>
        </div>
        
        <div class="footer_box last">
        	<h4>Follow Us</h4>
            <ul>
        	    <li><a href="http://twitter.com/inagist">Twitter</a></li>
              <li><a href="http://facebook.com/inagist">Facebook</a></li>
              <li>
                <div align="center" style="width:110px; display: inline-block;"><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a></div>
                <div align="center" style="width:80px; display: inline-block; padding-left: 10px;"><g:plusone size="medium" count="true"></g:plusone></div>
                <div align="center" style="width:80px; display: inline-block;"><fb:like href="http://facebook.com/inagist" layout="button_count" show_faces="false" width="80" font=""></fb:like></div>
              </li>
            </ul>
        </div>
        
        <div class="cleaner"></div>
    </div>
    
    <div id="templatemo_copyright">
        Copyright &copy; 2010 <a href="http://iyottasoft.com">Iyottasoft.com</a> 
        | <a href="/?r=main/terms">Terms</a> 
        | Powered By <a href="http://twitter.com">Twitter</a> 
    </div>
</div>
 <script type="text/javascript"> 
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16053252-1']);
  _gaq.push(['_setSessionCookieTimeout',7200000]);
  _gaq.push(['_trackPageview']);
  _gaq.push(['_trackPageLoadTime']);
<? if (isset($_SESSION['user_id'])) { ?>
  _gaq.push(['_setVar', 'SignedIn']);
<? } ?>
  var _qevents = _qevents || [];
  _qevents.push({qacct:"p-ddpcjPlbaSPxY"});
 
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);

    var twS = document.createElement('script'); twS.type = 'text/javascript'; twS.async = true;
    twS.src = 'http://platform.twitter.com/widgets.js';
    var st = document.getElementsByTagName('script')[0]; st.parentNode.insertBefore(twS, st);
    var elem = document.createElement('script');
    elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
    elem.async = true;
    elem.type = "text/javascript";
    //var scpt = document.getElementsByTagName('script')[0];
    st.parentNode.insertBefore(elem, st);
    $(twS).load(function(){
      function clickEventToAnalytics(intent_event) {
        if (intent_event) {
          _gaq.push(['_trackSocial', 'twitter', intent_event.type, intent_event.region]);
        };
      }
      function tweetIntentToAnalytics(intent_event) {
        if (intent_event) {
          _gaq.push(['_trackSocial', 'twitter', intent_event.type, intent_event.data.tweet_id]);
        };
      }
      function favIntentToAnalytics(intent_event) {
        if (intent_event) {
          _gaq.push(['_trackSocial', 'twitter', intent_event.type, intent_event.data.tweet_id]);
        };
      }
      function retweetIntentToAnalytics(intent_event) {
        if (intent_event) {
          _gaq.push(['_trackSocial', 'twitter', intent_event.type, intent_event.data.source_tweet_id]);
        };
      }
      function followIntentToAnalytics(intent_event) {
        if (intent_event) {
          _gaq.push(['_trackSocial', 'twitter', intent_event.type, intent_event.data.user_id + " (" + intent_event.data.screen_name + ")"]);
        };
      }
      twttr.events.bind('click',    clickEventToAnalytics);
      twttr.events.bind('tweet',    tweetIntentToAnalytics);
      twttr.events.bind('retweet',  retweetIntentToAnalytics);
      twttr.events.bind('favorite', favIntentToAnalytics);
      twttr.events.bind('follow',   followIntentToAnalytics);
    });
    window.fbAsyncInit = function() {
      if (FB && FB.Event && FB.Event.subscribe) {
        FB.Event.subscribe('edge.create', function(targetUrl) {
          _gaq.push(['_trackSocial', 'facebook', 'like', targetUrl]);
        });
      }
    };
  })();

</script>

<noscript>
<div style="display:none;">
<img src="//pixel.quantserve.com/pixel/p-ddpcjPlbaSPxY.gif" border="0" height="1" width="1" alt="Quantcast"/>
</div>
</noscript>
<!-- End Quantcast tag -->
<div id="fb-root"></div>
<script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script>
<script src="http://connect.facebook.net/en_US/all.js#appId=184217584951764&amp;xfbml=1"></script>
</body>
</html>
