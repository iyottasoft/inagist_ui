<?php
  $userid = $_REQUEST['userid']? $_REQUEST['userid']:'ipl';
  $site_properties = array(
    'iuserid' => $userid,
    'level' => 3,
    'heading' => $userid,
    'subheading' => 'Twitter Action',
    'title' => $userid . ' Twitter Feed Live',
    'right_top_heading' => 'Prominent Tweet',
    'left_top_heading' => 'Prominent Tweet',
    'trend_heading' => 'Trends',
    'middle_heading' => 'Influential Tweets',
    'live_heading' => 'Live Stream'
  );
  $mylink = mysql_pconnect('mysql_master', 'inagist', 'inagist');
  if ($mylink) {
    $db = mysql_select_db('inagist', $mylink);
    $result = mysql_query("select * from site_properties where userid = '".mysql_real_escape_string($userid)."'");
    if ($result){ 
      $user_properties = mysql_fetch_assoc($result);
      foreach ($user_properties as $uproperty => $uvalue)
        $site_properties[$uproperty] = $uvalue;
      mysql_free_result($result);
    }
    mysql_close($mylink);
  }

session_set_cookie_params(30 * 60 * 60, '/', '.inagist.com');
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
            "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<title><?=$site_properties['title']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta property="og:title" content="<?=$site_properties['title']?>"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="http://inagist.com/<?=$site_properties['iuserid']?>/live"/>
<meta property="og:image" content="http://inagist.com/netroy/images/logo_new.png"/>
<meta property="og:site_name" content="<?=$site_properties['title']?>"/>
<meta property="fb:admins" content="567443251,114760285236150,534346942"/>
<meta property="fb:app_id" content="184217584951764"/>
<meta property="og:description" content="<?=$site_properties['title']?>" />
<meta charset="UTF-8"> 
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
<meta name="description" content="{description}" > 
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<link href='http://fonts.googleapis.com/css?family=Cabin+Sketch:bold' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Dancing+Script' rel='stylesheet' type='text/css'>
<link rel="shortcut icon" href="/netroy/favicon.ico"> 
<link rel="apple-touch-icon" href="/apple-touch-icon.png"> 
<link rel="stylesheet" href="/netroy/ui_v3/css/styles.css?v=0.0.7" type='text/css' > 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>

<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>
<body>
  <div id="site_header_container">
    <div id="site_header">
    <?php if (isset($_SESSION['user_id'])) { ?>
      <div id="logged_in_user">
        <a href="http://inagist.com/logout" accesskey="l">
          <img src="<?=$_SESSION['profile_image_url']?>" title="Logout <?=$_SESSION['name']?>" /></a>
        <a id='tweet_now_button' href="#" accesskey="n"><span class='tweet-now' title='Tweet Now (Alt+N)'></span></a>
      </div>
    <?php } else { ?>
      <div id="notloggedin">
        <a title="Login with Twitter to see what people you follow are talking about here" 
            href="http://inagist.com/login">
          <span id='loginBt'></span>
        </a>
      </div>
    <?php } ?>
    </div>
  </div>
  <div id="container">
    <header><?=$site_properties['heading']?></header>
    <nav role="trends"></nav>
    <div id="notable" role="notable" class="clearfix"> 
      <ul id="notable_tweet_list"></ul>
	</div>
    <div id="main" role="main" class="clearfix">
      <ul id="tweet_list"></ul>
    </div>
    <footer class="verdana clearfix">
      <div class="links">
        <a href="http://inagist.com/services">Services</a> <b>&#8226;</b> 
        <a href="http://blog.inagist.com/">Blog</a> <b>&#8226;</b> 
        <a href="http://inagist.com/about">About Us</a> <b>&#8226;</b> 
        <a href="http://inagist.com/?r=main/getWidgetSnippet">Widget</a> <b>&#8226;</b> 
        <a href="https://chrome.google.com/extensions/detail/oangdphebgapkakpmiiceehanhopodgo" target="_blank">Chrome Extension</a>
      </div>
      <div class="shareables">
        <a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a> <b>&#8226;</b>
        <fb:like layout="button_count" show_faces="false" width="80" height="20" font=""></fb:like>
      </div>
      <div class="notice">
        &copy; 2011 <a href="http://iyottasoft.com">Iyottasoft.com</a> <b>&#8226;</b> 
        Powered By <a href="http://twitter.com">Twitter</a>
	  </div>
    </footer>
  </div>
  <script src="/netroy/live_ui/js/instaket.js"></script> 
  <script src="/netroy/ui_v3/js/libs/pretty.js"></script> 
  <script src="/netroy/ui_v3/js/libs/twitterlib.min.js"></script>
  <script src="/netroy/ui_v3/js/libs/jquery.getParams.js"></script>
  <script type="text/javascript" src="/netroy/live_ui/js/swfobject.js"></script>
  <script type="text/javascript" src="/netroy/live_ui/js/FABridge.js"></script>
  <script type="text/javascript" src="/netroy/live_ui/js/web_socket.js"></script>
  <script src="/netroy/ui_v3/js/twitterActions.js"></script>
  <script type="text/javascript" src="/netroy/live_ui/js/jquery.embedly.min.js"></script>
  <script type="text/javascript">
    userid = "<?=$site_properties['iuserid']?>";
    level = <?=$site_properties['level']?>;
    official_accounts = "<?=$site_properties['official']?>".split(',');
    <?php if (isset($site_properties['official_list'])){ ?>
    official_list = "<?=$site_properties['official_list']?>";
    <?php } 
    if (isset($site_properties['official_hashtag'])){ ?>
    official_hashtag = "<?=$site_properties['official_hashtag']?>";
    <?php } 
    if (isset($_SESSION['user_id'])) {
      $response_text = json_encode(array('user_id' => $_SESSION['user_id'], 
                                         'profile_image_url' => $_SESSION['profile_image_url'],
                                         'name' => $_SESSION['name']));
    ?>
    logged_in_user = <?=$response_text?>;
    <?php } ?>
  </script>
  <script src="/netroy/ui_v3/js/main.js?v=0.0.19"></script>

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16053252-9']);
  _gaq.push(['_setSessionCookieTimeout',7200000]);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js#appId=184217584951764&amp;xfbml=1"></script>
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<div id="media_preview_container" class="none"><div id="media_preview"></div></div>
</body> 
</html>
