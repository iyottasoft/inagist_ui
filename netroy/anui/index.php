<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
            "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<?php
  $channels = array("worldnewsgist" => "World News", 
                    "worldbizgist" => "Business",
                 //   "scitechgist" => "Science",
                    "hitechgist" => "Technology",
                    "sportsinagist" => "Sports",
                    "indiagist" => "India");
  $cached_specials = xcache_get("ig_home_special_channels");
  $special_channels = array_keys(array_slice($cached_specials, 0, 2));
  foreach ($special_channels as $special_channel){
    $channel_description = $cached_specials[$special_channel];
    $channels[$special_channel] = $special_channel;
  }
  $userid = isset($_REQUEST['userid']) ? strip_tags($_REQUEST['userid']) : "worldnewsgist";
  $currentChannel = "@$userid";
  foreach ($channels as $channel => $channelname){
    if ($channel == $userid){$currentChannel = $channelname;}
  }
  $channelTitle = "Top Stories";
  if (isset($_REQUEST['trend']))
    $channelTitle = strip_tags($_REQUEST['trend']);
  elseif (isset($_REQUEST['q']))
    $channelTitle = strip_tags($_REQUEST['q']);
?>
<title>Now What in <?=$currentChannel?></title>

<link rel="stylesheet" type="text/css" href="/css/reset.css?v=1.0">
<link rel="stylesheet" type="text/css" href="/css/720.css?v=1.0">
<link rel="stylesheet" type="text/css" href="/css/main.css?v=1.2.4">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
<script type="text/javascript" src="/js/main.js?v=1.0"></script>
</head>
<body>

<div id="header">
    <div class="container_9">
        <div id="sitename" class="grid_4">Now What?!</div>
        <div id="tagline" class="grid_5">
            See the trending stories on Twitter.
        </div>
        <div class="clear"></div>
    </div>
</div>

<div id="nav">
    <div class="container_9">
        <ul id="channels" class="grid_9">
<?php
  foreach ($channels as $channel => $channelname){
?>
  <li <?php if ($channel == $userid){echo("id=\"current_channel\"");}?> 
      <?php if (in_array($channel, $special_channels)){echo("class=\"special_channel\"");}?>>
    <a href="/u/<?=$channel?>"><?=$channelname?></a>
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
            <div id="search" class="grid_4">
              <form method="get" id="searchform" action="" style="display: inline-block;">
                <input id="search_box" type="text" value="" name="q">
              </form>
              <input id="search_button" type="image" value="Search"
                src="/icon/search-lens.png">
            </div>
        <div class="clear"></div>
    </div>
</div>

<div id="ad_slot_1" class="container_9">
  <script type="text/javascript"><!--
  google_ad_client = "ca-pub-2412868093846820";
  /* 728x90 Tweet Conversation Page */
  google_ad_slot = "0678983111";
  google_ad_width = 728;
  google_ad_height = 90;
  //-->
  </script>
  <script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
  </script>
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
    $timeLine = APIUtils::getTrendTweets($userid, strip_tags($_REQUEST['trend']));
  elseif (isset($_REQUEST['q']))
    $timeLine = APIUtils::search($userid, strip_tags($_REQUEST['q']));
  else
    $timeLine = APIUtils::mergeTimeLine($userid, 3,6);
  usort($timeLine, "tweetAgeSort");

  foreach ($timeLine as $entry){
?>
      <tr id="<?=$entry->id_str?>_<?=$entry->count?>" class="sortable_story">
          <td class="story_topic">
<?php
    if (isset($entry->phrase)){
?>
              <a href="?trend=<?=$entry->phrase->text?>"><?=$entry->phrase->text?></a>
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
              <a href="http://twitter.com/intent/user?screen_name=<?=$entry->user->screen_name?>" target="_blank" rel="nofollow">
                  <img class="user_img" src="<?=$entry->user->profile_image_url?>" alt="<?=$entry->user->screen_name?>">
              </a>
          </td>
          <td class="story_info">
              <a href="http://twitter.com/intent/user?screen_name=<?=$entry->user->screen_name?>" target="_blank" rel="nofollow"
                  class="story_user"><?=$entry->user->screen_name?></a>
              <br>
              <div>
                <a href="http://inagist.com/<?=$entry->user->screen_name?>/<?=$entry->id_str?>/?utm_source=nowwhat&utm_medium=web" target="_blank">
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
?>
              <div class="tweet_actions">
                <a href="http://twitter.com/intent/tweet?in_reply_to=<?=$entry->id_str?>">
                  <span class="icon-reply"></span>
                </a>
                <a href="http://twitter.com/intent/retweet?tweet_id=<?=$entry->id_str?>">
                  <span class="icon-retweet"></span>
                </a>
                <a href="http://twitter.com/intent/favorite?tweet_id=<?=$entry->id_str?>">
                  <span class="icon-favorite"></span>
                </a>
              </div>
          </td>
      </tr>
<?php
  }
?>

    </table>
</div>
<!-- /stories -->
<div id="ad_slot_2" class="container_9">
  <script type="text/javascript"><!--
  google_ad_client = "ca-pub-2412868093846820";
  /* 728x90 Tweet Conversation Page */
  google_ad_slot = "0678983111";
  google_ad_width = 728;
  google_ad_height = 90;
  //-->
  </script>
  <script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
  </script>
</div>


<div id="footer">
    Copyright &copy; 2011
    <a href="http://inagist.com/">In-A-Gist</a>
    |
    Powered By <a href="http://twitter.com/">Twitter</a>
    |
    <div align="center" style="width:80px; display: inline-block;"><fb:like layout="button_count" show_faces="false" width="80" font=""></fb:like></div>
    |
    <div align="center" style="width:110px; display: inline-block;"><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a></div>
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
  })();
</script>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16053252-7']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js#appId=212959832055395&amp;xfbml=1"></script>
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
</body>
</html>
