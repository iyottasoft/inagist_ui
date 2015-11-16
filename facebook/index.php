<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<?php
  $channels = array("worldnewsgist" => "World News", 
                    "worldbizgist" => "Business",
                 //   "scitechgist" => "Science",
                    "hitechgist" => "Technology",
                    "sportsinagist" => "Sports",
                    "indiagist" => "India",
                    "libya" => "Libya",
                    "bahrain" => "Bahrain");
  $special_channels = array("libya","bahrain");
  $userid = isset($_REQUEST['userid']) ? $_REQUEST['userid'] : "worldnewsgist";
  $currentChannel = "@$userid";
  foreach ($channels as $channel => $channelname){
    if ($channel == $userid){$currentChannel = $channelname;}
  }
  $channelTitle = "Top Stories";
  if (isset($_REQUEST['trend']))
    $channelTitle = $_REQUEST['trend'];
  elseif (isset($_REQUEST['s']))
    $channelTitle = $_REQUEST['s'];

    require 'src/facebook.php';

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

    session_set_cookie_params(30 * 60 * 60, '/', '.inagist.com');
    session_start();
?>
<link rel="stylesheet" type="text/css" href="css/reset.css?v=1.0">
<link rel="stylesheet" type="text/css" href="css/720.css?v=1.0">
<link rel="stylesheet" type="text/css" href="css/main.css?v=1.2.5">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
<script type="text/javascript" src="js/main.js?v=1.1.1"></script>
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
          xfbml   : true // parse XFBML
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
<?php if (!isset($me)) { ?>
  <script>
    top.location.href="<?php echo($facebook->getLoginUrl(array("next" => "http://apps.facebook.com/inagist/")));?>";
  </script>
<?php } else { ?>

<div id="nav">
    <div class="container_9">
  <ul id="channels" class="grid_9">
<?php
  if (isset($_SESSION['user_id'])){
?>
  <li <?php if ($_SESSION['user_id'] == $userid){echo("id=\"current_channel\"");}?>> 
    <a href="?userid=<?=$_SESSION['user_id']?>">@<?=$_SESSION['user_id']?></a>
  </li>
<?php
  }
  foreach ($channels as $channel => $channelname){
?>
  <li <?php if ($channel == $userid){echo("id=\"current_channel\"");}?> 
      <?php if (in_array($channel, $special_channels)){echo("class=\"special_channel\"");}?>>
    <a href="?userid=<?=$channel?>"><?=$channelname?></a>
  </li>
<?php
  }
?>
        </ul>
        <div class="clear"></div>
    </div>
</div>

<div id="channel_title">
    <div class="container_9">
        <h1 class="grid_5"><?=$currentChannel?> &rarr; <?=$channelTitle?></h1>
        <form method="get" id="searchform" action="#">
            <div id="search" class="grid_4">
                <input id="search_userid" type="hidden" value="<?=$userid?>" name="userid">
                <input id="search_box" type="text" value="" name="s">
                <input id="search_button" type="image" value="Search"
                src="icon/search-lens.png">
            </div>
        </form>
        <div class="clear"></div>
    </div>
</div>

<!-- stories -->
<div id="story_col" class="container_9">
    <table id="stories">

<!-- ***** -->
      <tr>
          <th>
          <th class="story_sorting">
              <span class="currsort" id="by_freshness">New</span>
              <span class="sort" id="by_strength">Top</span>
          <th>
          <th>
      </tr>

<?php
  include 'Utils.php';
  include 'APIUtils.php';
  function tweetSort($a, $b){
    return ($a->count > $b->count) ? -1 : 1;
  }
  function tweetAgeSort($a, $b){
    return ($a->id > $b->id) ? -1 : 1;
  }
  if (isset($_REQUEST['trend']))
    $timeLine = APIUtils::getTrendTweets($userid, $_REQUEST['trend']);
  elseif (isset($_REQUEST['s']))
    $timeLine = APIUtils::search($userid, $_REQUEST['s']);
  else
    $timeLine = APIUtils::mergeTimeLine($userid, 3,6);
  usort($timeLine, "tweetAgeSort");
  $rowcount = 0;
  $hideClass = "";
  foreach ($timeLine as $entry){
    if ($rowcount > 15)
      $hideClass = "noshow";
?>
      <tr id="<?=$entry->id_str?>_<?=$entry->count?>" class="sortable_story <?=$hideClass?>">
          <td class="story_topic">
<?php
      $entry->share_url = $entry->url ? $entry->url : "http://twitter.com/".$entry->user->screen_name."/status/".$entry->id_str;
    if (isset($entry->phrase)){
?>
              <a href="?userid=<?=$userid?>&trend=<?=$entry->phrase->text?>"><?=$entry->phrase->text?></a>
<?php
    } else {
?>
              &nbsp;
<?php
    }
?>
          </td>
          <td class="story_text">
              <?=Utils::linkify($entry->text)?>
          </td>
          <td class="story_icon">
              <a href="http://twitter.com/<?=$entry->user->screen_name?>" target="_blank" rel="nofollow">
                  <img class="user_img" src="<?=$entry->user->profile_image_url?>" alt="<?=$entry->user->screen_name?>">
              </a>
          </td>
          <td class="story_info">
              <a href="http://twitter.com/<?=$entry->user->screen_name?>" target="_blank" rel="nofollow"
                  class="story_user"><?=$entry->user->screen_name?></a>
              <br>
              <div>
                <a href="http://twitter.com/<?=$entry->user->screen_name?>/status/<?=$entry->id_str?>" target="_blank">
                  <?=Utils::timeAgo($entry->created_at)?>
                </a>
              </div>
<?php
    if (isset($entry->phrase)){
?>
              <div><?=$entry->phrase->count?> tweets</div>
<?php
    } elseif (isset($entry->related) && is_array($entry->related)) {
?>
              <div><?=sizeof($entry->related)?> tweets</div>
<?php
    }
    if ($me):
?>
              <div class="fbshare"> Share </div>
<?php endif ?>
          </td>
      </tr>
      <script>
        $("#<?=$entry->id_str?>_<?=$entry->count?> .fbshare").data("tweet", <?php echo(json_encode($entry)); ?>);
      </script>
<?php
    $rowcount++;
  }
?>

    </table>
</div>
<!-- /stories -->


<div id="footer">
    Copyright &copy; 2011
    <a href="http://iyottasoft.com/">Iyotta Software Pvt. Ltd.</a>
    |
    <a href="http://inagist.com/">In-A-Gist</a>
    |
    Powered By <a href="http://twitter.com/">Twitter</a>
</div>
<script type="text/javascript">
  function applySort(type){
    if (type == "by_freshness")
      sortByFreshness();
    else
      sortByStrength();
  }

  (function() {
    $(".sort").live('click', function(e){
      applySort($(e.target).attr("id"));
      $(".story_sorting span").toggleClass('sort currsort');
    });
    $(".fbshare").live('click', function(e){
      tweetData = $(e.target).data("tweet");
      FB.ui(
         {
           method: 'feed',
           display: 'popup',
           name: tweetData.text,
           link: tweetData.share_url,
           message: tweetData.text + " via @" + tweetData.user.screen_name
         }
       );
      });
    })();
  </script>
<?php } ?>

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
