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
<meta property="fb:app_id" content="184217584951764" />
<meta property="og:title" content="<?=$site_properties['title']?>"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="http://inagist.com/<?=$site_properties['iuserid']?>/live"/>
<meta property="og:image" content="http://inagist.com/netroy/images/logo_new.png"/>
<meta property="og:site_name" content="<?=$site_properties['title']?>"/>
<meta property="fb:admins" content="567443251,114760285236150,534346942"/>
<meta property="fb:app_id" content="184217584951764"/>
<meta property="og:description" content="<?=$site_properties['title']?>" />

<link rel="stylesheet" type="text/css" href="/netroy/biz_inagist/css/reset.css">
<link rel="stylesheet" type="text/css" href="/netroy/biz_inagist/css/text.css">
<link rel="stylesheet" type="text/css" href="/netroy/biz_inagist/css/960.css">
<link rel="stylesheet" type="text/css" href="/netroy/biz_inagist/css/main.css?v=0.0.82">

<style type="text/css">
  <?=$site_properties['css']?>
</style>
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

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript" src="/netroy/live_ui/js/pretty.js?v=0.0.2"></script>
<script type="text/javascript" src="/netroy/live_ui/js/twitterlib.min.js"></script>
<script type="text/javascript" src="/netroy/live_ui/js/instaket.js?v=0.0.1"></script>
<script type="text/javascript" src="/netroy/live_ui/js/jquery.embedly.min.js"></script>
<script type="text/javascript" src="/netroy/js/raphael-min.js"></script>
<script type="text/javascript" src="/netroy/js/popup.js"></script>
<script type="text/javascript" src="/netroy/live_ui/js/swfobject.js"></script>
<script type="text/javascript" src="/netroy/live_ui/js/FABridge.js"></script>
<script type="text/javascript" src="/netroy/live_ui/js/web_socket.js"></script>
<script type="text/javascript" src="/netroy/biz_inagist/js/twitterActions.js?v=0.0.17"></script>
<script type="text/javascript" src="/netroy/biz_inagist/js/main.js?v=0.0.068"></script>
<script type="text/javascript" src="/netroy/biz_inagist/js/stats.js?v=0.0.20"></script>
</head>
<body>
<div id="media_preview_container" class="none"><div id="media_preview"></div></div>
<div id="header">
    <div class="container_16">
        <div id="sitename"class="grid_5"><?=$site_properties['heading']?></div>

        <div id="search" class="grid_5">
          <form method="get" id="searchform" action="#">
              <input id="search_box" type="text" value="" name="s">
              <input id="search_button" type="image" value="Search"
              src="/netroy/biz_inagist/icon/search-lens.png">
          </form>
        </div>
 
          <div id="user_info" class="grid_3 push_1">
          <?php if (isset($_SESSION['user_id'])) { ?>
            <div id="logged_in_user">
              <a href="http://inagist.com/logout" accesskey="l"><img src="<?=$_SESSION['profile_image_url']?>" title="Logout <?=$_SESSION['name']?>" /></a>
              <a id='tweet_now_button' href="#" accesskey="n"><span 
                class='tweet-now' 
                title='Tweet Now (Alt+N)'></span></a>
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

        <!-- <div id="tagline" class="grid_4 push_3">
            <?=$site_properties['subheading']?>
        </div>-->
        <a href="http://inagist.com/"><div class="inagist_logo"></div></a>
        <div class="clear"></div>
    </div>
</div>

<div id="promoted_bar" class="container_16">
    <div class="clear"></div>
</div>

<div id="headlines_container" class="container_16 content_container none">
  <img src="/netroy/biz_inagist/images/prev.svg" id="headlines_container_prev" type="image/svg+xml" />
  <img src="/netroy/biz_inagist/images/next.svg" id="headlines_container_next" type="image/svg+xml" />
  <div id="headlines_container_head" class="container_header"></div>
  <div class="clear"></div>
  <div id="headlines_container_body"></div>
</div>

<div id="promoted_bar_1" class="container_16">
    <div class="clear"></div>
</div>

<div id="media_container" class="container_16 content_container">
  <div id="medias" class="grid_16 block_display"><div id="media1"></div></div>
  <div class="clear"></div>
</div>

<div id="promoted_bar_2" class="container_16">
    <div class="clear"></div>
</div>

<div id="promoted_tweet_container" class="container_16 content_container">
    <div class="grid_8">
      <div id="tweet1_header" class="none grid_6 container_header"><?=$site_properties['left_top_heading']?></div>
      <div id="tweet1" class="block_display"></div>
    </div>
    <div class="grid_8">
      <div id="tweet2_header" class="none grid_6 container_header"><?=$site_properties['right_top_heading']?></div>
      <div id="tweet2" class="block_display"></div>
    </div>
    <div class="clear"></div>
</div>

<div id="promoted_bar_3" class="container_16">
    <div class="clear"></div>
</div>

<!-- stories -->
<div id="story_col" class="container_16 content_container">
  <div id="col_left" class="grid_4">
    <div class="grid_2 container_header" id="trends_tab"><?=$site_properties['trend_heading']?></div>
    <div class="clear"></div>
    <div id="trends_chart"></div>
    <div class="clear"></div>
    <ul id="live_trends">
    </ul>
  </div>
  <div class="grid_12">
  <div id="col_center" class="grid_6 alpha">
    <div class="grid_4 container_header" id="influential_tab"><?=$site_properties['middle_heading']?></div>
    <div class="clear"></div>
    <div id="influential_tweets">
    </div>
  </div>
  <div id="col_right_live" class="grid_6 omega">
    <div class="grid_4 container_header live_off" id="live_tab"><?=$site_properties['live_heading']?> - <span id="tweet_counter"></span></div>
    <div class="clear"></div>
    <div id="live_tweets">
    </div>
  </div>
  <div id="col_right_search" class="grid_6 omega none">
    <div class="grid_4 container_header" id="search_tab"></div>
    <div class="clear"></div>
    <div id="search_results">
    </div>
  </div>
</div>

<!--<div class="clear"></div>
<div id="comments_container" class="grid_16">
  <div class="right">
    <fb:comments href="http://inagist.com/<?=$site_properties['iuserid']?>/live" num_posts="3" width="700"></fb:comments>
  </div>
</div>-->
<div class="clear"></div>
<!-- /stories -->

<div id="footer">
    Copyright &copy; 2010
    <a href="http://inagist.com/">inagist.com</a>
    |
    Powered By <a href="http://twitter.com/">Twitter</a>
    |
<div align="center" style="width:110px; display: inline-block;"><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a></div> 
    |
    <fb:like layout="button_count" href="http://inagist.com/<?=$site_properties['iuserid']?>/live" 
      show_faces="false" width="150" colorscheme="dark"></fb:like>
</div>
<div id="fb-root"></div>

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16053252-9']);
  _gaq.push(['_trackPageview']);
  _gaq.push(['_trackPageLoadTime']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);

    var twS = document.createElement('script'); twS.type = 'text/javascript'; twS.async = true;
    twS.src = 'http://platform.twitter.com/widgets.js';
    var st = document.getElementsByTagName('script')[0]; st.parentNode.insertBefore(twS, st);
  })();
</script>
<script src="http://connect.facebook.net/en_US/all.js#appId=184217584951764&amp;xfbml=1"></script>
</body>
</html>
