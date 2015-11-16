<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<?php
  $site_properties = array(
    'iuserid' => 'ladygaga',
    'level' => 2,
    'heading' => 'Lady Gaga',
    'subheading' => 'Twitter fan page',
    'title' => 'Lady Gaga Twitter Fan Page',
    'css' => 'body {background-position: -130px 30px; background-image: url(http://a3.sphotos.ak.fbcdn.net/photos-ak-snc1/v342/149/11/10376464573/n10376464573_818751_3634.jpg);}',
    'official' => 'ladygaga'
  );
    require '../src/facebook.php';

    // Create our Application instance (replace this with your appId and secret).
    $facebook = new Facebook(array(
      'appId'  => '184217584951764',
      'secret' => 'f4641363e21b68bc4efc2cf8fd8f7404',
      'cookie' => true,
    ));

    $session = $facebook->getSession();

    $me = null;
    // Session based API call.
    if ($session) {
      try {
        $uid = $facebook->getUser();
        $me = $facebook->api('/me');
      } catch (FacebookApiException $e) {
        error_log($e);
      }
    }
    $facebook_request = $facebook->getSignedRequest();
    $page = $facebook->api("/" . $facebook_request['page']['id'] ? $facebook_request['page']['id'] : $facebook->getAppId());
    
    $mylink = mysql_pconnect('mysql_master', 'inagist', 'inagist');
    if ($mylink) {
      $db = mysql_select_db('inagist', $mylink);
      $result = mysql_query("select * from site_properties where fb_page_id = '".$page['id']."'");
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
<link rel="stylesheet" type="text/css" href="../css/reset.css">
<link rel="stylesheet" type="text/css" href="../css/520.css">
<link rel="stylesheet" type="text/css" href="../css/page.css?v=0.0.11">
<style>
  body {
    background-repeat: no-repeat;
    background-attachment: fixed;
    height: 2000px;
  }
  <?=$site_properties['css']?>
</style>
<script type="text/javascript">
  userid = "<?=$site_properties['iuserid']?>";
  level = <?=$site_properties['level']?>;
  official_accounts = "<?=$site_properties['official']?>".split(',');
  <?php if (isset($site_properties['official_list'])){ ?>
  official_list = "<?=$site_properties['official_list']?>";
  <?php } ?>
</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
<script type="text/javascript" src="/netroy/live_ui/js/pretty.js"></script>
<script type="text/javascript" src="/netroy/live_ui/js/instaket.js"></script>
<script type="text/javascript" src="/netroy/live_ui/js/jquery.embedly.min.js"></script>
<script type="text/javascript" src="/netroy/live_ui/js/jquery.getParams.js"></script>
<script type="text/javascript" src="/netroy/live_ui/js/swfobject.js"></script>
<script type="text/javascript" src="/netroy/live_ui/js/FABridge.js"></script>
<script type="text/javascript" src="/netroy/live_ui/js/web_socket.js"></script>
<script type="text/javascript" src="../js/twitterlib.min.js"></script>
<script type="text/javascript" src="../js/page.js?v=0.0.17"></script>
</head>
<body>
    <div id="fb-root"></div>
    <script type="text/javascript">
      window.fbAsyncInit = function() {
        FB.init({
          appId   : '<?php echo $facebook->getAppId(); ?>',
          session : <?php echo json_encode($session); ?>, // don't refetch the session when PHP already has it
          status  : true, // check login status
          cookie  : true, // enable cookies to allow the server to access the session
          xfbml   : true, // parse XFBML
          channelUrl: "http://inagist.com/fecebook/channel.html"
        });
        FB.Canvas.setAutoResize();
      };
      (function() {
        var e = document.createElement('script');
        e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
        e.async = true;
        document.getElementById('fb-root').appendChild(e);
      }());
    </script>
<div id="media_preview" class="none"></div>
<div id="header">
    <div class="container_9">
        <div id="sitename "class="grid_3"><?=$site_properties['heading']?></div>

        <div id="tagline" class="grid_4 push_2">
            <?=$site_properties['subheading']?>
        </div>
        <div class="clear"></div>
    </div>
</div>

<div id="promoted_tweet_container" class="container_9">
    <div id="tweet1" class="grid_9 block_display"></div>
    <div class="clear"></div>
</div>

<div id="media_container" class="container_9">
    <div id="medias" class="grid_9 block_display"><div id="media1"></div></div>
    <div class="clear"></div>
</div>
<!-- stories -->
<div id="story_col" class="container_9">
  <div class="grid_9">
    <div id="influential_tab" class="grid_3 container_header"> Influential Tweets </div>
    <div id="live_tab" class="grid_3 container_header disabled"> Live Tweets </div>
    <div id="trends_tab" class="grid_2 container_header disabled"> Trends </div>
    <div class="clear"></div>
    <div id="col_center" class="grid_9 omega">
      <div id="influential_tweets">
      </div>
    </div>
    <div id="col_right" class="grid_9 omega none">
      <div id="live_tweets">
      </div>
    </div>
    <div id="col_left" class="grid_9 omega none">
      <ul id="live_trends">
      </ul>
    </div>
</div>
<div class="clear"></div>
<!-- /stories -->

<div id="footer">
    Copyright &copy; 2011
    <a href="http://inagist.com/">inagist.com</a>
    |
    Powered By <a href="http://twitter.com/">Twitter</a>
</div>

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16053252-8']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>

</body>
</html>
