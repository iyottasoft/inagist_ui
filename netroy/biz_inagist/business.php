<?php
  $userid = $_REQUEST['userid']? $_REQUEST['userid']:'ladygaga';
  $site_properties = array(
    'iuserid' => $userid,
    'level' => 2,
    'heading' => $userid,
    'subheading' => 'Twitter Action',
    'title' => $userid . ' Twitter Feed Live'
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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
            "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title><?=$site_properties['title']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/reset.css">
<link rel="stylesheet" type="text/css" href="css/text.css">
<link rel="stylesheet" type="text/css" href="css/960.css">
<link rel="stylesheet" type="text/css" href="css/main.css?v=0.0.3">

<style>
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
<script type="text/javascript" src="js/pretty.js"></script>
<script type="text/javascript" src="js/twitterlib.min.js"></script>
<script type="text/javascript" src="js/instaket.js"></script>
<script type="text/javascript" src="js/jquery.embedly.min.js"></script>
<script type="text/javascript" src="js/jquery.getParams.js"></script>
<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript" src="js/FABridge.js"></script>
<script type="text/javascript" src="js/web_socket.js"></script>
<script type="text/javascript" src="js/main.js?v=0.0.5"></script>
</head>
<body>
<div id="media_preview" class="none"></div>
<div id="header">
    <div class="container_16">
        <div id="sitename "class="grid_5"><?=$site_properties['heading']?></div>

          <div id="search" class="grid_4 push_2">
            <form method="get" id="searchform" action="#">
                <input id="search_box" type="text" value="" name="s">
                <input id="search_button" type="image" value="Search"
                src="icon/search-lens.png">
            </form>
          </div>

        <div id="tagline" class="grid_4 push_3">
            <?=$site_properties['subheading']?>
        </div>
        <div class="clear"></div>
    </div>
</div>

<div id="promoted_tweet_container" class="container_16">
    <div id="tweet1" class="grid_8 block_display"></div>
    <div id="tweet2" class="grid_8 block_display"></div>
    <div class="clear"></div>
</div>

<div id="media_container" class="container_16">
    <div id="promo1" class="grid_4 block_display">
      <?php if ($userid == 'ladygaga'){?>
      <OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab" id="Player_05ba829e-0510-40c6-8861-d877918470cf"  WIDTH="125px" HEIGHT="125px"> <PARAM NAME="movie" VALUE="http://ws.amazon.com/widgets/q?ServiceVersion=20070822&MarketPlace=US&ID=V20070822%2FUS%2Finagistcom-20%2F8014%2F05ba829e-0510-40c6-8861-d877918470cf&Operation=GetDisplayTemplate"><PARAM NAME="quality" VALUE="high"><PARAM NAME="bgcolor" VALUE="#FFFFFF"><PARAM NAME="allowscriptaccess" VALUE="always"><embed src="http://ws.amazon.com/widgets/q?ServiceVersion=20070822&MarketPlace=US&ID=V20070822%2FUS%2Finagistcom-20%2F8014%2F05ba829e-0510-40c6-8861-d877918470cf&Operation=GetDisplayTemplate" id="Player_05ba829e-0510-40c6-8861-d877918470cf" quality="high" bgcolor="#ffffff" name="Player_05ba829e-0510-40c6-8861-d877918470cf" allowscriptaccess="always"  type="application/x-shockwave-flash" align="middle" height="125px" width="125px"></embed></OBJECT> <NOSCRIPT><A HREF="http://ws.amazon.com/widgets/q?ServiceVersion=20070822&MarketPlace=US&ID=V20070822%2FUS%2Finagistcom-20%2F8014%2F05ba829e-0510-40c6-8861-d877918470cf&Operation=NoScript">Amazon.com Widgets</A></NOSCRIPT>
      <?php } ?>
    </div>
    <div id="medias" class="grid_12 block_display"><div id="media1"></div></div>
    <div class="clear"></div>
</div>
<!-- stories -->
<div id="story_col" class="container_16">
  <div id="col_left" class="grid_4">
    <div class="grid_2 container_header"> Trends </div>
    <div class="clear"></div>
    <ul id="live_trends">
    </ul>
  </div>
  <div class="grid_12">
  <div id="col_center" class="grid_6 alpha">
    <div class="grid_4 container_header"> Influential Tweets </div>
    <div class="clear"></div>
    <div id="influential_tweets">
    </div>
  </div>
  <div id="col_right" class="grid_6 omega">
    <div class="grid_4 container_header" id="live_tab"> Live Tweets </div>
    <div class="clear"></div>
    <div id="live_tweets">
    </div>
  </div>
</div>
<div class="clear"></div>
<!-- /stories -->


<div id="footer">
    Copyright &copy; 2010
    <a href="http://inagist.com/">inagist.com</a>
    |
    Powered By <a href="http://twitter.com/">Twitter</a>
</div>

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16053252-9']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</body>
</html>
