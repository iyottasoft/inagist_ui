<?php
session_set_cookie_params(30 * 60 * 60, '/', '.inagist.com');
session_start();
$mylink = mysql_pconnect('mysql_master', 'inagist', 'inagist');
$customer = null;
if ($mylink) {
  $userid = mysql_real_escape_string($_SESSION['user_id']);
  $query = "select customer from partner_user_mapping where userid = '$userid' and enabled = true";
  $db = mysql_select_db('inagist', $mylink);
  $result = mysql_query($query);
  if ($result){ 
    $user_properties = mysql_fetch_assoc($result);
    $customer = $user_properties['customer'];
    mysql_free_result($result);
  } else {
    echo ("<!-- ".mysql_error()." -->");
  }
  mysql_close($mylink);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
            "http://www.w3.org/TR/html4/strict.dtd"> 
<html> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
<title>In-A-Gist - Top Tweet Manager</title> 
 
<link rel="stylesheet" type="text/css" href="css/reset.css?v=1.0"> 
<link rel="stylesheet" type="text/css" href="css/960.css?v=1.0"> 
<link rel="stylesheet" type="text/css" href="css/main.css?v=1.3.4"> 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script> 
<script type="text/javascript" src="js/main.js?v=3.9.2"></script> 
</head> 
<body>
<div id="header"> 
    <div class="container_16"> 
        <div id="sitename" class="grid_4">In-A-Gist</div> 
        <div id="tagline" class="grid_8"> 
            Top Tweets Selector
        </div> 

        <div id="user_info" class="grid_3 push_1">
        <?php if (isset($_SESSION['user_id'])) { ?>
          <div id="logged_in_user">
            <a href="http://inagist.com/logout" accesskey="l"><img src="<?=$_SESSION['profile_image_url']?>" title="Logout <?=$_SESSION['name']?>" /></a> for <?=$customer?>
          </div>
        <?php } else { ?>
          <div id="notloggedin">
            <a title="Login with Twitter" 
                href="http://inagist.com/login">
              <span id='loginBt'></span>
            </a>
          </div>
        <?php } ?>
        </div>

        <div class="clear"></div> 
    </div> 
</div> 
<?php
  if (isset($customer)){
?>
<div id="main_container" class="container_16">
  <div id="trend_region_container" class="section container_16">
    <div id="inagist_channels" class="grid_6">
      <div class="section_header"> Inagist Channels </div>
      <ul class="ulist">
        <li class="trend_region" data-channel-name="indiagist">India</li>
        <li class="trend_region" data-channel-name="worldnewsgist">World News</li>
        <li class="trend_region" data-channel-name="worldbizgist">World Business</li>
        <li class="trend_region" data-channel-name="cricketgist">Cricket</li>
        <li class="trend_region" data-channel-name="sportsgist">Sports</li>
      </ul>
    </div>
    <div id="twitter_woeids" class="grid_6">
      <div class="section_header"> Twitter Regions </div>
      <ul class="ulist">
        <li class="trend_region" data-woeid="23424848">India</li>
        <li class="trend_region" data-woeid="2295411">Mumbai</li>
        <li class="trend_region" data-woeid="23424977">United States</li>
        <li class="trend_region" data-woeid="1">Worldwide</li>
      </ul>
    </div>
    <div id="user_entered" class="grid_4">
      <div class="section_header"> Custom </div>
      <input type="text" id="custom_trend_field"></input>
    </div>
  </div>
  <div class="clear"></div>
  <div id="trends_container" class="section container_16">
    <div id="inagist_trends" class="grid_6">
      <div class="section_header"> Inagist Trends </div>
      <ul id="inagist_trends_list" class="ulist">
      </ul>
    </div>
    <div id="twitter_trends" class="grid_6">
      <div class="section_header"> Twitter Trends </div>
      <ul id="twitter_trends_list" class="ulist">
      </ul>
    </div>
    <div id="custom_trends" class="grid_4">
      <div class="section_header"> Selected </div>
      <ul id="user_defined_list" class="ulist">
      </ul>
    </div>
  </div>
  <div class="clear"></div>
  <div id="tweets_viewer" class="container_16">
    <div class="grid_8 section">
      <div class="section_header"><span id="inagist_trend_header"></span> Inagist </div>
      <ul id="inagist_tweets_viewer" class="ulist">
      </ul>
    </div>
    <div class="grid_8 section">
      <div class="section_header"> <span id="twitter_trend_header"></span> Twitter </div>
      <ul id="twitter_tweets_viewer" class="ulist">
      </ul>
    </div>
  </div>
  <div class="clear"></div>
  <div id="tweets_selected" class="section container_16">
      <div class="section_header"> <span id="selected_trend_header"></span> Published Tweets </div>
      <div class="section_info" id="selected_trend_url"></div>
      <ul id="selected_tweets_list" class="ulist">
      </ul>
  </div>
<?php } else { ?>
<div id="main_container" class="container_16">
  <div id="error" class="grid_5 push_5" style="text-align: center; font-size: 28px; margin: 20px 0px 20px 0px; ">
    Unauthorized
  </div>
</div>
<?php } ?>
  <div class="clear"></div>
  <div id="footer" class="container_16">
    Copyright &copy; 2011
    <a href="http://inagist.com/">In-A-Gist</a>
    |
    Powered By <a href="http://twitter.com/">Twitter</a>
  </div>
</div>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16053252-12']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</body>
</html>
