<?php
$expireAge = 30*60;
header("Cache-Control: max-age=$expireAge");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $expireAge) . " GMT");
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
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/common.css" />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/main.css" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
  <script type="text/javascript" src="<?=$cdn_base?>js/jquery.oauth.js"></script>
  <script type="text/javascript" src="<?=$cdn_base?>js/pretty.js"></script>
  <script type="text/javascript">window.baseurl="<?=$baseurl?>";<? if(isset($user)) echo "window.user='$user';"; if(isset($list)) echo "window.list='$list';"; if(isset($hours)) echo "window.hours='$hours';";?>
  <? if (isset($_SESSION[$partner_id.'_user_id'])) echo "window.loggedinuser='".$_SESSION[$partner_id.'_user_id']."';";
  if (isset($partner_id)) echo "window.partnerid='$partner_id'; ";
  if (isset($partner_id)) echo "window.twtcnt='$twtcnt'; ";
  ?> 
  </script>
  <style type="text/css">
  span#loginBt{display:block;background-image:url('/netroy/images/login-old.png');width:78px;height:24px;padding:0px;}
  #leftpane{width:<?=($width-20)?>px; text-align:left; padding-top:30px; padding-bottom: 30px; }
  #tweets{width:<?=$width?>px; height:<?=($height-60)?>px; overflow: auto;}
  td.text{padding:0px;}
  td.meta{padding-top:5px;}
  div.preview div.msg a img{width:0px; height: 0px; display: none;}
  img.tweetuser{width:26px;height:26px;background-color:#EEE;border:1px solid #BBB;margin-right:5px;padding:2px;}
  <?php
  	if (!$reply)
  		echo " span.retweet-icon, span.favorite a.fav, span.replybt, span.retweetbt, span.prevbt, span.respbt {display:none;} ";
  ?>
  
  #inagistheading{position: fixed; right: 0px;top:0px; width:100%; background-color: #FFF; color:#000;text-align: center; font-weight: bold; padding:7px 0px; font-size:14px;-moz-border-radius:0px 0px 0 0;-webkit-border-radius:6px 6px 0 0; }
  #inagistpower{position: fixed; right: 0px;bottom:0px; width:100%; background-color: #FFF; color:#000;font-weight: bold; padding:4px 0px;font-size:14px;-moz-border-radius:0 0 0px 0px;-webkit-border-radius:0 0 6px 6px; }
  <?php
  	if ($bgcolor!=null && $bgcolor!='')
  		echo "body,div.body,div.preview,img.tweetuser{background-color:#$bgcolor;} #inagistpower,#inagistheading{color:#$bgcolor;}";
  	if ($lcolor!=null && $lcolor!='')	
  		echo "a, a:visited, a:link, td.text a{color:#$lcolor;}";
  	if ($tcolor!=null && $tcolor!='')	
  		echo "table.tweets td.text,div.preview div.msg,td.meta span.time,div.preview div.msg span.time{color:#$tcolor;}  #inagistpower,#inagistheading{background-color:#$tcolor;} #tweets{border:1px solid #$tcolor;}";
  	if ($bcolor!=null && $bcolor!='')	
  		echo "div#tweets div.body, img.tweetuser{border:1px solid #$bcolor;} div.preview div.msg {border:1px dotted #$bcolor;} ";
  		 
  ?>
  </style>
  <?php
  	if ($css!=null && $css!='')
  		echo "<link rel='stylesheet' type='text/css' href='$css' />";
  ?>
</head>
<body>
<div id="wrapper">
  <div id="leftpane"><?=$left?></div>
  <div class="clear"> </div>
  <div id="scriptContainer">
    <script language="javascript">
  $(function(){
	  window.widgetUrl = true;
  });
  </script>
    <script type="text/javascript" src="<?=$cdn_base?>js/partner_common.js?v=1.2"></script>
    <script type="text/javascript" src="<?=$cdn_base?>js/modal.js"></script>
    <script type="text/javascript" src="<?=$cdn_base?>js/main.min.js"></script>
  </div>
</div>

<div id="inagistheading">
<?=$widgettitle?>

<?php
if ($reply) { 
?>

<div class="right" style="font-size: 14px; ">
  <? if(isset($_SESSION[$partner_id.'_user_id'])) {
      $url = "http://twitter.com/".$_SESSION[$partner_id.'_user_id'];
      $name = (isset($_SESSION[$partner_id.'_name']))?$_SESSION[$partner_id.'_name']:$_SESSION[$partner_id.'_user_id'];
  ?>
    <div class="right">
    <? if(isset($_SESSION[$partner_id.'_profile_image_url'])){ ?>
      <div class="right">
        <a href="<?=$url?>"><img src="<?=$_SESSION[$partner_id.'_profile_image_url']?>" class="tweetuser" /></a>
      </div>
    <? } ?>
      <div class="right" align="right" style="text-align: right;padding-right: 10px;">
        <a href="<?=$url?>"><b><?=$name?></b></a>
        <br/><a href="http://<?=$domain?>/partner/logout?partner=<?=$partner_id?>">logout</a>
      </div>
      <script type="text/javascript">window.loggedinuser="<?=$_SESSION[$partner_id.'_user_id']?>";</script>
    </div>
  <? }else{?>
    <div id="notloggedin">
        <span id="loginBt"></span>
    </div>
  <? } ?>
</div>
<?php 
}
?>
</div>
<div id="inagistpower">
<a href="http://twitter.com/inagist" target="_blank"><img src="<?=$cdn_base?>images/widgettwitter.png" border="0" style="height: 16px; padding-left:6px;" align="left"/></a>
<a href="http://inagist.com/" target="_blank"><img src="<?=$cdn_base?>images/widgetlogo.png" border="0" style="width: 51px; height: 18px; padding-right:6px;" align="right"/></a>
</div>

<script>
	$(document).ready(function(){
	    $('#notloggedin').click(function(){
	    	$.oauthpopup({path: '/partner/login?partner=<?=$partner_id?>',callback: function(){
	        	window.location.reload();
	        }
	    });
	    });	    
	});
</script>
<script type="text/javascript">
  var _gaq = _gaq || [];
  <?php
  if ($googleanalyticsid!='') 
  	echo " _gaq.push(['_setAccount', '".$googleanalyticsid."'],
                     ['_trackPageview'],
                     ['_trackPageLoadTime'],
                     ['b._setAccount', 'UA-16053252-6'],
                     ['b._trackPageview'],
                     ['b._trackPageLoadTime'],
                     ['b._trackEvent', 'PartnerWidget', 'Display', '".$partner_id."']); ";
  else 
  	echo " _gaq.push(['_setAccount', 'UA-16053252-6'],['_trackPageview'],['_trackPageLoadTime']); ";
  ?>

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>  
</body>
</html>
