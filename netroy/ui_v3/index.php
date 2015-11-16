<?php
  $userid = $_REQUEST['userid']? $_REQUEST['userid']:'ladygaga';
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
    $result = mysql_query("select * from site_properties where userid = '$userid'");
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
<link rel="shortcut icon" href="/favicon.ico"> 
<link rel="apple-touch-icon" href="/apple-touch-icon.png"> 
<link rel="stylesheet" href="css/styles.css?v=1" type='text/css' > 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>

<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>
<body>
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
        <a href="mailto:info@iyottasoft.com" class="last">Contact Us</a> <b>&#8226;</b> 
        <a href="http://inagist.com/?r=main/getWidgetSnippet">Widget</a> <b>&#8226;</b> 
        <a href="https://chrome.google.com/extensions/detail/oangdphebgapkakpmiiceehanhopodgo" target="_blank">Chrome Extension</a>
      </div>
      <div class="notice">
        Copyright &copy; 2011 <a href="http://iyottasoft.com">Iyottasoft.com</a> <b>&#8226;</b> 
        Powered By <a href="http://twitter.com">Twitter</a>
	  </div>
    </footer>
  </div>
  <script src="js/libs/instaket.js"></script> 
  <script src="js/libs/pretty.js"></script> 
  <script src="js/libs/twitterlib.min.js"></script>
  <script src="js/libs/jquery.getParams.js"></script>
  <script src="js/twitterActions.js"></script>
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
  <script src="js/main.js"></script>
</body> 
</html>
