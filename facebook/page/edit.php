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
    'official' => 'ladygaga',
    'official_list' => null
  );
 
    require '../src/facebook.php';

    // Create our Application instance (replace this with your appId and secret).
    $facebook = new Facebook(array(
      'appId'  => '184217584951764',
      'secret' => 'f4641363e21b68bc4efc2cf8fd8f7404',
      'cookie' => true,
    ));
    session_set_cookie_params(30 * 60 * 60, '/', '.inagist.com');
    session_start();

    $session = $facebook->getSession();

    $me = null;
    $accounts = null;
    $admin = false;
    // Session based API call.
    if ($session) {
      try {
        $uid = $facebook->getUser();
        $me = $facebook->api('/me');
        $accounts = $facebook->api('/me/accounts');
        foreach ($accounts['data'] as $authedApps){
          if ($authedApps['id'] == $_REQUEST['fb_page_id'])
            $admin = true;
        }
      } catch (FacebookApiException $e) {
        error_log($e);
      }
    }
    $facebook_request = $facebook->getSignedRequest();
?>
<link rel="stylesheet" type="text/css" href="../css/reset.css">
<link rel="stylesheet" type="text/css" href="../css/720.css">
<link rel="stylesheet" type="text/css" href="../css/main.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
</head>
<body>
<div id="nav">
    <div class="container_9">
        <div id="sitename "class="grid_3">In-A-Gist</div>
        <div id="tagline" class="grid_4 push_2">
            Facebook Page Settings
        </div>
        <div class="clear"></div>
    </div>
</div>
<?php if ($admin) { 
  $page = $facebook->api("/".$_REQUEST['fb_page_id']);  
  $new_properties = array();
  foreach($site_properties as $site_property => $site_val){
    if (isset($_REQUEST[$site_property]))
      $new_properties[$site_property] = $_REQUEST[$site_property];
  }
  $mylink = mysql_pconnect('mysql_master', 'inagist', 'inagist');
  if ($mylink) {
    $db = mysql_select_db('inagist', $mylink);
    if (count($new_properties) > 0){
      $update_statement = "update site_properties set ";
      foreach ($new_properties as $upkey => $upvalue)
        $update_statement .= "$upkey = '".mysql_real_escape_string($upvalue)."', ";
      $update_statement = substr($update_statement, 0, -2)." where fb_page_id = '".$page['id']."'";
      $result = mysql_query($update_statement);
    }
    $result = mysql_query("select * from site_properties where fb_page_id = '".$page['id']."'");
    if ($result){
      $user_properties = mysql_fetch_assoc($result);
      foreach ($user_properties as $uproperty => $uvalue)
        $site_properties[$uproperty] = $uvalue;
      mysql_free_result($result);
    }
    mysql_close($mylink);
  }
?>
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

    <div id="channel_title">
      <div class="container_9">
        <h1 class="grid_5"><?=$page['name']?></h1>
        <div class="clear"></div>
      </div>
    </div>

    <div id="story_col" class="container_9">
      <form method="post" id="settings" action="#">
        <div class="grid_2"> User Id </div>
        <div class="grid_5"><input id="iuserid" type="text" value="<?=$site_properties['iuserid']?>" name="iuserid" /></div>
        <div class="clear"></div>
        <div class="grid_2"> Level </div>
        <div class="grid_5"><input id="level" type="text" value="<?=$site_properties['level']?>" name="level" /></div>
        <div class="clear"></div>
        <div class="grid_2"> Heading </div>
        <div class="grid_5"><input id="heading" type="text" value="<?=$site_properties['heading']?>" name="heading" /></div>
        <div class="clear"></div>
        <div class="grid_2"> Sub Heading </div>
        <div class="grid_5"><input id="subheading" type="text" value="<?=$site_properties['subheading']?>" name="subheading" /></div>
        <div class="clear"></div>
        <div class="grid_2"> Title </div>
        <div class="grid_5"><input id="title" type="text" value="<?=$site_properties['title']?>" name="title" /></div>
        <div class="clear"></div>
        <div class="grid_2"> Official Ids </div>
        <div class="grid_5"><input id="official" type="text" value="<?=$site_properties['official']?>" name="official" /></div>
        <div class="clear"></div>
        <div class="grid_2"> Official List </div>
        <div class="grid_5"><input id="official_list" type="text" value="<?=$site_properties['official_list']?>" name="official_list" /></div>
        <div class="clear"></div>
        <div class="grid_2"> Custom CSS </div>
        <div class="grid_5"><textarea name="css" rows="3" cols="80"><?=$site_properties['css']?></textarea></div>
        <div class="clear"></div>
        <div class="grid_3 push_3"> <input type="submit" name="submit"/> </div>
        <div class="clear"></div>
      </form>
    </div>
<?php } else { ?>
<div id="error">
  <div class="container_9">
  <div class="grid_3 push_3"> Access Denied</div>
</div>
<?php } ?>
<div class="clear"></div>
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
