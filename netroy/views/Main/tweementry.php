<?php
session_set_cookie_params(30 * 60 * 60, '/', '.tweementary.com');
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
            "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<title>Volkswagen - Tweementary, Have your say on the match and discover what others are talking about on the IPL</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta property="og:title" content="Volkswagen - Tweementary, Have your say on the IPL"/>
<meta property="og:type" content="website"/>
<meta property="og:url" content="http://tweementary.com/"/>
<meta property="og:image" content="http://tweementary.com/netroy/tweementary/images/tweementry.jpg"/>
<meta property="og:site_name" content="Volkswagen - Tweementary"/>
<meta property="og:powered_by" content="inagist.com"/>
<meta property="fb:admins" content="567443251,114760285236150,534346942"/>
<meta property="fb:app_id" content="127264600681949"/>
<meta property="og:description" content="Volkswagen - Tweementary, Have your say on the match and discover what others are talking about on the IPL" />

<link href="/netroy/tweementry/css/twimentry.css?v=0.0.61" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="/netroy/favicon.ico" />
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16053252-10']);
  _gaq.push(['_trackPageview']);
  _gaq.push(['_trackPageLoadTime']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</head>

<body>
<div style="width: 1000px; margin: 0px auto;">
  <div id="logo"><a href="http://www.volkswagen.co.in/" target="_blank"><img src="/netroy/tweementry/images/volkswagen_das_auto.jpg" width="123" height="86" border="0" title="Volkswagen. Das Auto."/></a>
  </div>
  <div id="twimentry">
    <a href="http://<?=$_SERVER["SERVER_NAME"]?>/"><img src="/netroy/tweementry/images/tweementry.jpg" width="223" height="34" border="0" title="Tweementary"/></a>
  </div>
</div>
<div id="bg">
<div id="container">
<?php
if (isset($_SESSION['tweementry_user_id'])) {?>
  <form id="form1" name="form1" method="post" action="javascript:void(0);">
  <div class="grey_bg">
		<div class="white_bg5">
        <div  style="vertical-align: middle; width:320px; padding-left:5px; float:left;">
          <div style="padding-top:25px; vertical-align: middle; width:60px; padding-left:15px; float:left;">
            <img src=<?=$_SESSION['tweementry_profile_image_url']?> class="logged_in_user_image" /><br/>
          </div>
          <div style="width: 100px; float: left;">
            <div id="active_user_name"><?=$_SESSION['tweementry_name']?></div>
            <div class="signout" id="logout"><a href="/partner/logout?partner=tweementry">Logout</a></div>
          </div>
          <img style="margin-top: -15px;" src="/netroy/tweementry/images/bird.gif" />
        </div>
        <div>
        <div id="rpo_greyBg">
          <div class="blue_title" id="match_alert">What's your take on the match?</div>
        </div>
            <textarea name="tweet_text" type="text" class="tweet_field" id="tweet_text" rows="2" cols="50">#ipl </textarea>
            <div style="float:left; padding-left:5px; padding-top:75px;"><a href="javascript:void(0);" id="submit_button"><img src="/netroy/tweementry/images/bt-submit.jpg" border="0" /></a></div>
        </div>
    </div>
  </div>
  </form>
<?php } else { ?>
<div id="content_blue_bg">
<div style="width:768px; float:left;">
	<div id="header_title"></div>
    <div id="header_line1"></div>
    <div id="header_line2"></div>
</div>
<div style="padding-top:35px; float:left;"><img src="/netroy/tweementry/images/header_brk_line.jpg" border="0" /></div>
<div style="width: 200px; height: 40px; padding-top: 95px; float: left;">
  <div align="center" style="width:80px; float:right;"><fb:like href="http://tweementary.com/" layout="button_count" show_faces="false" width="80" font=""></fb:like></div>
  <div align="center" style="width:110px; float:right;"><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a></div>
</div>
</div>
<div class="grey_bg">
  <div class="white_bg4">
    <div style="width:551; padding-left:68px; padding-top:35px; float:left;"><img src="/netroy/tweementry/images/join_in_tweementary.jpg" /></div>
    <div style="float:left; width:49px; padding-left:50px; padding-top:15px; padding-right:35px;"><img src="/netroy/tweementry/images/bird.jpg" /></div>
    <div style="float:left; width:150px; padding-top:35px; "><img id="login_button" src="/netroy/tweementry/images/signin_twitter2.jpg" border="0" title="Sign in with Twitter"/></div>
  </div>
</div>
<?php }?>

 <div style="height:15px;"></div>
 <div class="content_grey_bg">
 	<div class="white_bg2 curtained">
    	<div style="float:left; width:175px;">
        <div class="teams_title">
          <span id="away_team"></span><br />vs<br />
          <span id="home_team"></span>
        </div>
		    <div class="light_blue_text" id="match_status" style="width: 160px; margin: 0px 5px; padding-top: 5px;"></div>
      </div>
      <div style="float:left;"><img src="/netroy/tweementry/images/line2.jpg" /></div>
      <div style="width:150px; padding-left:22px; padding-top:10px; padding-right:22px; float:left;">
    	<div class="light_blue_title"><span id="batting_team"></span></div>
        <div class="live_score" id="current_score"></div>
        <div class="grey_18">in <span id="current_overs"></span> overs </div>
    </div>
    <div style="float:left;"><img src="/netroy/tweementry/images/line2.jpg" /></div>
    <div style="width:228px; padding-left:22px; padding-top:10px; padding-right:5px; float:left;">
    	  <div class="black_text14" id="striker_batsman"></div>
        <div class="black_text14" id="non_striker_batsman"></div>
        <div class="black_text14">Current run rate <span id="current_run_rate"></span></div>
		    <div class="black_text14" id="last_ball_comment"></div>
        <div class="over_statistics">THIS OVER</div>
        <div id="over_details">
        </div>
    </div>
    </div>
    
 </div>
 <div class="players_score">
 	<div class="white_bg2">
    <div class="trending_topics_header">Trending Topics</div>
    <div style="width:105px; text-align:center; padding-top:30px; height:100px; float:left;">
      <img src="/netroy/tweementry/images/tt-bird.jpg">
    </div>
    <div class="players_score_box">
    </div>
  </div>
</div>

<div style="clear:both;"></div>

<div style="height:18px; padding:8px 0px; width:100%">
    	<div id="latest" class="sortable">Latest</div>
      <div class="bar">|</div>
      <div id="popular" class="sortable sortable_selectable">Popular</div>
      <div class="bar">|</div>
      <div id="trends_selected_header"></div>
</div>
<div id="comment_container">
  <div id="search_results"></div>
  <div id="live_comments"></div>
  <div id="tweet_loader"><img src="/netroy/images/ajax-loader.gif" /><br/>Loading Tweets. Please wait!</div>
</div>

<div style="clear:both;"></div>
<div class="grey_bg">
  <div class="white_bg4">
	<div style="padding-top:20px; margin-left:22px; width:115px; float:left;">
    <img src="/netroy/tweementry/images/official_parteners.jpg" width="115" height="48" />
  </div>
  <div id="footer_text">Volkswagen Tweementary is merely an aggregator of the contents of this site. 
    <br />
    Volkswagen is not  liable for comments or Tweets made by any person or entity.
    <br />
    Volkswagen Tweementary <a href="/netroy/tweementry/privacy.html" target="_blank">Disclaimer & Privacy Policy</a>
  </div>
        <div align="center" style="width:80px; float:right; padding-bottom:20px; padding-top:45px;"><fb:like href="http://tweementary.com/" layout="button_count" show_faces="false" width="80" font=""></fb:like></div>
        <div align="center" style="width:110px; float:right; padding-bottom:20px; padding-top:45px;"><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a></div>
</div></div>
</div>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script> 
  <script src="/netroy/tweementry/js/libs/instaket.js"></script> 
  <script src="/netroy/js/jquery.oauth.js"></script> 
  <script src="/netroy/tweementry/js/libs/pretty.js"></script> 
  <script src="/netroy/tweementry/js/libs/twitterlib.min.js"></script> 
  <script type="text/javascript" src="/netroy/live_ui/js/swfobject.js"></script>
  <script type="text/javascript" src="/netroy/live_ui/js/FABridge.js"></script>
  <script type="text/javascript" src="/netroy/live_ui/js/web_socket.js"></script>
  <script src="/netroy/tweementry/js/main.js?v=0.0.73"></script> 
  <script src="/netroy/tweementry/js/twitterActions.js?v=0.0.5"></script> 
  <script type="text/javascript"> 
    official_hashtag = "#ipl";
  <?php
  if (isset($_SESSION['tweementry_user_id'])) {
    $response_text = json_encode(array('user_id' => $_SESSION['tweementry_user_id'], 
                                       'profile_image_url' => $_SESSION['tweementry_profile_image_url'],
                                       'name' => $_SESSION['tweementry_name']));
  ?>
    logged_in_user = <?=$response_text?>;
  <?php } ?>
    initial_data = <?=$content?>;
    match_details = <?=$match_details?>;
  </script> 
<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js#appId=127264600681949&amp;xfbml=1"></script>
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
</body>
</html>
