<?php
$_SERVER['FULL_URL'] = 'http';
$script_name = '';
if(isset($_SERVER['REQUEST_URI'])) {
    $script_name = $_SERVER['REQUEST_URI'];
} else {
    $script_name = $_SERVER['PHP_SELF'];
    if($_SERVER['QUERY_STRING']>' ') {
        $script_name .=  '?'.$_SERVER['QUERY_STRING'];
    }
}
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
    $_SERVER['FULL_URL'] .=  's';
}
$_SERVER['FULL_URL'] .=  '://';
if($_SERVER['SERVER_PORT']!='80')  {
    $_SERVER['FULL_URL'] .=
    $_SERVER['HTTP_HOST'].':'.$_SERVER['SERVER_PORT'].$script_name;
} else {
    $_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].$script_name;
}
$_SERVER['FULL_URL']=str_replace('www.inagist.com','inagist.com',$_SERVER['FULL_URL']);
if (isset($_GET['mobile']) || ($display_mode == 'mobile'))
  $mobile = true;
else
  $mobile = false;

$expireAge = 10*60;
header("Cache-Control: max-age=$expireAge");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $expireAge) . " GMT");
$tweetTime = strtotime($currentTweet['created_at']);
if (isset($_REQUEST['source']) && $_REQUEST['source'] == 'relatedTweets')
  $nofollow = 'nofollow, ';
else
  $nofollow = '';
// tell robots to expire content after 4 weeks
//if (isset($currentTweet['created_at']))
//  header("X-Robots-Tag: ".$nofollow."unavailable_after: " . gmdate("d M Y H:i:s", $tweetTime + 28*86400) . " GMT");
$selfCanonicalUrl = "http://inagist.com/".
                    $currentTweet['user']['screen_name']."/".$currentTweet['tweetid']."/";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
  <title> <?=$title?> | In-A-Gist</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
  <meta name="language" content="en" />
  <meta name="title" content="<?=str_replace("\"", "", $title)?> | In-A-Gist" />
  <meta name="keywords" content="<?=$keywords?>" />
  <meta name="description" content="<?=$odescription?>" />
  <meta property="fb:app_id" content="184217584951764" />
  <meta property="og:title" content="<?=str_replace("\"", "", $title)?> | In-A-Gist" />
  <meta property="og:description" content="<?=$odescription?>" />
  <meta property="og:type" content="article" />
  <meta property="og:image" content="<?=$currentTweet['user']['profile_image_url']?>" />
  <meta property="og:url" content="http://inagist.com/<?=$currentTweet['user']['screen_name']?>/<?=$currentTweet['tweetid']?>/" />
  <meta property="article:published_time" content="<?=date("c", $tweetTime)?>" />
  <link rel="shortcut icon" href="<?=$cdn_base?>favicon.ico" />
  <link rel="publisher" href="https://plus.google.com/101948688477206152601" />
  <link href='http://fonts.googleapis.com/css?family=Cabin:500|Droid+Serif|Inconsolata&v2' 
    rel='stylesheet' type='text/css' />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/all.min.css?v=0.0.3" />
  <?php if ($mobile) {?>
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/mobile_detail.css?v=0.0.4" />
  <meta name = "viewport" content = "width = device-width;">
  <?php } ?>
  <script language="javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setCustomVar', 1, 'RelatedCount', '<?=$relatedTweetsCount?>', 3],
              ['_setCustomVar', 2, 'RelatedSearchTerms', '<?=$relatedSearchTerms?>', 3],
              ['_setCustomVar', 3, 'RelatedInTimeSpan', '<?=$relatedStoriesInTimeSpan?>', 3]);
    var currentTweetId = "<?=$currentTweet['tweetid']?>";
    var title = " <?=str_replace("\"", "", $title)?> ";
    var newTweetsFound = 0;
    var developingStory = <?=$relatedStoriesInTimeSpan > 5 ? 'true' : 'false'?>;
    var currentTweetUserNumericId = "<?=$currentTweet['user']['id']?>";
    var currentUserId = "<?=$_SESSION['user_id']?>";
    var currentUserNumericId = "<?=$_SESSION['twitter_id']?>";
    window.baseurl="<?=$baseurl?>";
    <? if(isset($user)) echo "window.user='$user';"; ?>
  </script>	
<style type="text/css">
  div.preview div.msg  {line-height: 20px;font-size: 14px; }
  div.preview div.stats {display:none;}
  div.preview div.msg span.time,div.preview div.replystats {font-size:9px;}
  .embed {font-family: Cabin, sans-serif; line-height: 16px; margin: 5px; font-size: 14px;}
  .embed .thumb {float: right; margin: 0 0 5px 5px;}
  .embed a {display: block; font-size: 16px; line-height: 22px}
  .embed .provider {display: inline; font-style: italic; font-size: 12px; line-height: 16px;}
</style>

<script type='text/javascript' src='http://partner.googleadservices.com/gampad/google_service.js'>
</script>
<script type='text/javascript'>
GS_googleAddAdSenseService("ca-pub-2412868093846820");GS_googleEnableAllServices();
</script>
<script type='text/javascript'>
GA_googleAddSlot("ca-pub-2412868093846820", "Tweet_Detail_Page_Top");GA_googleAddSlot("ca-pub-2412868093846820", "Tweet_Link_Unit_Button");GA_googleAddSlot("ca-pub-2412868093846820", "TweetDetailPageLargeBottom");GA_googleAddSlot("ca-pub-2412868093846820", "MobileTopUnit");
</script>
<script type='text/javascript'>
GA_googleFetchAds();
</script>

</head>
<body style="background-color: #ccc;">
<div id="wrapper" style="background-color: #eee;">
  <div id="header"><div id="headerInner"><?=$header?></div></div>
  <div id="content">
  <?php
  $show_related = strlen($relatedTweets) > 50;
  $show_replies = strlen($content) > 50;
  $link_ad_unit = false;
  if ($show_ads && !$mobile) { ?>
	<div class="tweetcontent ad_container" style="margin-top: 10px; height: 90px;" id="tweetdetail_google_links_ad">
    <!-- Tweet_Detail_Page_Top -->
    <script type='text/javascript'>
      GA_googleFillSlot("Tweet_Detail_Page_Top");
    </script>
  </div> 
  <?php } ?>
  <div class="tweetcontent" id="tw<?=$currentTweet['tweetid']?>">
	<div class="status">
  <?php if (isset($currentTweet['text'])) {?>
	<div class="left">
	<a href="http://twitter.com/intent/user?screen_name=<?=$currentTweet['user']['screen_name']?>" target="_blank" title="<?=$currentTweet['user']['name']?>">
		<img alt="<?=$currentTweet['user']['name']?> as @<?=$currentTweet['user']['screen_name']?>" data-src="<?=$currentTweet['user']['profile_image_url']?>" style="width:75px; height:75px;" class="post_load"/>
	</a>	
	</div>
	<div class="update-text">		
		<h1 style="clear: none;">
      <?=Utils::linkifyKeywords(Utils::linkifyNoTrack($currentTweet['text']), explode(",", $keywords))?>
    </h1> 
	</div>	
		<span class="meta">
			  <a href="http://twitter.com/intent/user?screen_name=<?=$currentTweet['user']['screen_name']?>" target="_blank" title="<?=$currentTweet['user']['name']?>">
        		<span class="user"><?=$currentTweet["user"]["screen_name"]?></span>
      		  </a> 
			<a target="_blank" href="http://twitter.com/<?=$currentTweet['user']['screen_name']?>/status/<?=$currentTweet['tweetid']?>"><time class="timestamp" data-timestamp="<?=$currentTweet['created_at']?>" datetime="<?=strftime("%FT%TZ", $tweetTime)?>" pubdate><?=strftime("%b %d, %Y %T GMT", $tweetTime)?></time></a>		 
			<span class="action" style="padding-right: 10px;">
				<a title="reply to this" href="#"><span class="replybt"> </span></a>
   			<a title="retweet this" href="#"><span class="retweetbt"> </span></a>
			</span>
      <a href="http://twitter.com/<?=$currentTweet['user']['screen_name']?>" class="twitter-follow-button" data-text-color="222222" data-link-color="0D5575" data-show-count="false" data-width="200px">Follow @<?=$currentTweet['user']['screen_name']?></a>
			
			<span class="right">
			<?php 
        $buffer = array();
        if (isset($currentTweet['retweets']))
          $buffer[] = "<span class='retweets'>".$currentTweet['retweets']." retweets</span>";
        if (isset($currentTweet['mentions']))
          $buffer[] = "<span class='replies'>".$currentTweet['mentions']." replies</span>";
        echo implode(" | ",$buffer);
			?>
			</span>
		</span>	
    <?php
    if (is_array($currentTweet['long_urls'])){
      $displayedUrls = array();
      foreach ($currentTweet['long_urls'] as $curl){
        $udetails = $urlDetails[$curl];
        if (is_array($udetails)){
          $canonicalURL = $udetails['attributes']['canonical'] ? 
            $udetails['attributes']['canonical'] : $udetails['attributes']['location'];
          if (!in_array($udetails['url'], $displayedUrls) &&
            !in_array($canonicalURL, $displayedUrls)){
          $domain = parse_url($udetails['url']);
          $domain = $domain['host'];
          if (isset($udetails['title'])){
      ?>
        <div class="story-details" style="background-color: #eee; margin-top: 5px; padding: 5px; border: 1px black dotted; line-height: 1.8em; overflow: hidden; clear:both;">
          <h2 style="font-size:16px; font-weight: normal; color: #0D5575;">
            <?=Utils::linkifyKeywords(strip_tags($udetails['title']), explode(",", $keywords))?>
          </h2>
      <?php if (isset($udetails['attributes']['image_src'])){ ?>
          <img style="float: right; margin: 5px 0px 0px 5px; border: 1px #999 solid; max-width: 150px; max-height: 90px;" data-src="<?=$udetails['attributes']['image_src']?>" class="post_load"/>
      <?php } if (!$link_ad_unit && !$mobile) {
          $link_ad_unit = true;
      ?>
        <div id='ad_inplace_1' style="float: left; margin-right: 5px; border: 1px #999 solid; width: 125px; height: 125px;">
          <!-- Tweet_Link_Unit_Button -->
          <script type='text/javascript'>
          GA_googleFillSlot("Tweet_Link_Unit_Button");
          </script>
        </div>
      <?php } if (isset($udetails['description'])) { ?>
          <blockquote style="margin-top: 5px; font-size: 14px; line-height: 16px;">
            <?=Utils::linkifyKeywords(strip_tags(substr($udetails['description'],0,1024)), explode(",", $keywords))?>
          </blockquote>
      <?php } ?>
          <a href="<?=$udetails['url']?>" target="_blank" data-trackAction="TweetExpandedLinkClick" rel="nofollow"><?=$domain?></a>
        </div>
      <?php
          } elseif (isset($domain)) {
      ?>
        <div class="story-details" style="background-color: #eee; margin-top: 5px; padding: 5px; border: 1px black dotted; height: 130px; clear: both;">
        <?php if (!$link_ad_unit && !$mobile){
          $link_ad_unit = true;
        ?>
          <div id='ad_inplace_1' style="float: left; margin-right: 5px; border: 1px #999 solid; width: 125px; height: 125px;">
          <!-- Tweet_Link_Unit_Button -->
          <script type='text/javascript'>
          GA_googleFillSlot("Tweet_Link_Unit_Button");
          </script>
          </div>
        <?php } ?>
          <blockquote style="margin-top: 5px; font-size: 14px; line-height: 16px; width: 300px; display: inline-block;">More from <a href="<?=$udetails['url']?>" target="_blank" rel="nofollow" data-trackAction="TweetExpandedLinkClick"><h2 style="font-size:16px;"><?=$domain?></h2></a></blockquote>
        </div>
      <?php
          }
          $displayedUrls[] = $udetails['url'];
          if (is_array($udetails["attributes"]) && isset($canonicalURL))
            $displayedUrls[] = $canonicalURL;
          }
        } else {
          $displayedUrls[] = $curl;
          $domain = parse_url($curl);
          $domain = $domain['host'];
      ?>
        <div class="story-details" style="background-color: #eee; margin-top: 5px; padding: 5px; border: 1px black dotted; height: 130px; clear: both;">
        <?php if (!$link_ad_unit && !$mobile){
          $link_ad_unit = true;
        ?>
          <div id='ad_inplace_1' style="float: left; margin-right: 5px; border: 1px #999 solid; width: 125px; height: 125px;">
          <!-- Tweet_Link_Unit_Button -->
          <script type='text/javascript'>
          GA_googleFillSlot("Tweet_Link_Unit_Button");
          </script>
          </div>
        <?php } ?>
          <blockquote style="margin-top: 5px; font-size: 14px; line-height: 16px; width: 300px; display: inline-block;">More from <a href="<?=$curl?>" target="_blank" rel="nofollow" data-trackAction="TweetExpandedLinkClick"><h2 style="font-size:16px;"><?=$domain?></h2></a></blockquote>
        </div>
      <?php
        }
      }
    }
  } else {
    ?>
	<div class="update-text">		
		<h1 style="clear: none;">Tweet not found.</h1> 
	</div>	
  <?php } ?>
	</div>

  <div align="left" style="float: left; width: 65px;">
    <a href="http://twitter.com/share" class="twitter-share-button" data-count="none" data-url="<?=$selfCanonicalUrl?>">Tweet</a>
  </div>
  <div align="left" style="float: left; width: 50px; overflow: hidden; margin-right: 5px;">
    <fb:like href="<?=$selfCanonicalUrl?>" send="false" layout="button_count" width="100" show_faces="false" 
      colorscheme="dark" font="arial"></fb:like>
  </div>
  <div align="left" style="float: left; width: 65px;">
    <g:plusone size="medium" count="false" href="<?=$selfCanonicalUrl?>"></g:plusone>
  </div>

  <?php if ($searchQuery) { ?>
    <div align="right" style="padding:5px; font-size:14px; float: right;" id="morebtn">
			<a title="More" rel="nofollow" href="/search?q=<?=$searchQuery?>">More on <span style="">'<?=$searchQuery?>'</span></a>
		</div>
  <?php } ?>
    <div class="clear" style="height: 5px;"></div>
  <?php 
    if (is_array($currentTweet['long_urls'])){
      $displayedUrls = array();
      foreach ($currentTweet['long_urls'] as $lIndex => $linkedUrl){
        $udetails = $urlDetails[$linkedUrl];
        if (is_array($udetails) && is_array($udetails['attributes'])){
          $canonicalURL = $udetails['attributes']['canonical'] ? 
            $udetails['attributes']['canonical'] : $udetails['attributes']['location'];
          if (isset($canonicalURL))
            $linkedUrl = $canonicalURL;
          elseif (isset($udetails['url']))
            $linkedUrl = $udetails['url'];
        }
        if (!in_array($linkedUrl, $displayedUrls)){
          $displayedUrls[] = $linkedUrl;
          if (preg_match('/(jpg|jpeg|gif|png)$/i', $linkedUrl)){
      ?>
        <div class="oembed_wrapper" >
          <img data-src="<?=$linkedUrl?>" class="post_load" />
        </div>
      <?php
          } else {
      ?>
        <div class="oembed_wrapper" style="display:none;">
          <a href="<?=$linkedUrl?>" class="oembed" rel="nofollow"><img data-src="/netroy/images/ajax-loader.gif" class="post_load"/></a>
        </div>
  <?php 
          }
        }
      }
    }
    if ($show_related){ ?>
	<div>
		<ul id="tabnav"> 
			<li class="tab1" title="Related by - <?=str_replace("|",",", $relatedSearchTerms)?>"><a href="#" id="related" class="tabnavselected" data-relatedterms="<?=str_replace("|",",", $relatedSearchTerms)?>">Related Tweets</a></li>
      <li id="related_notification_tray" style="font-weight: normal; font-style: italic;"></li>
		</ul>  
	</div>
  <div id="related_<?=$currentTweet['tweetid']?>" class="preview url" 
    style="display: block; margin-bottom:10px;" rel="done">
  		<?=is_array($relatedTweets)? "<img data-src=\"/netroy/images/ajax-loader.gif\" class=\"post_load related_loader\"/>" : $relatedTweets?> 
	</div>
  <?php } ?>
  <div id="sponsored_<?=$currentTweet['tweetid']?>" class="preview url" 
    style="display: block; margin-bottom:10px; height: 125px; overflow: hidden;" rel="done">
      <!-- BuySellAds.com Zone Code -->
      <div id="bsap_1267027" class="bsarocks bsap_cf5bb9ea15dd535784c89430c8777ab0"></div>
      <!-- End BuySellAds.com Zone Code -->
      <a href="http://gan.doubleclick.net/gan_click?lid=41000000034535978&pubid=21000000000506345" rel="nofollow" target="_blank" class="gan_125x125"><img src="http://gan.doubleclick.net/gan_impression?lid=41000000034535978&pubid=21000000000506345" border=0 alt=""></a>
      <a href="http://gan.doubleclick.net/gan_click?lid=41000000034210789&pubid=21000000000506345" rel="nofollow" target="_blank" class="gan_125x125"><img src="http://gan.doubleclick.net/gan_impression?lid=41000000034210789&pubid=21000000000506345" border=0 alt="Cloud VPS Hosting"></a>
      <a href="http://gan.doubleclick.net/gan_click?lid=41000000034983607&pubid=21000000000506345" rel="nofollow" target="_blank" class="gan_125x125"><img src="http://gan.doubleclick.net/gan_impression?lid=41000000034983607&pubid=21000000000506345" border=0 alt="PUMA Introduces the Faas Lightweight Running Shoe"></a>
      <a href="http://gan.doubleclick.net/gan_click?lid=41000613802045537&pubid=21000000000506345" rel="nofollow" target="_blank" class="gan_125x125"><img src="http://gan.doubleclick.net/gan_impression?lid=41000613802045537&pubid=21000000000506345" border=0 alt="267232_Fall50_2011_125x125"></a>
	</div>
  <?php if(!$mobile){ ?>
  <fb:social-bar trigger="20%" read_time="10"></fb:social-bar>
  <?php  
  $popular_tweets = xcache_get("ig_popular_tweets_text");
  //$latest_trends = xcache_get("ig_latest_trends_text");
  ?>
	<div id="popular_tweets_section" class="preview url" style="display: block; margin-bottom: 10px;" rel="done">
    <div class="msg">
      <div class="sub_header"><h2>Popular Now</h2></div>
      <ul style="list-style: none; margin: 0 0 0 0;">
      <?=$popular_tweets?>
      </ul>
    </div>
  </div>
  <?php } if ($show_ads && !$mobile && ($show_related || $show_replies)){ ?>
	<div class="tweetcontent ad_container google_ad_bottom_container" style="margin-bottom:10px;" id="tweetdetail_google_ad">
    <div class="google_ad_336x280 left">
      <!-- TweetDetailPageLargeBottom -->
      <script type='text/javascript'>
        GA_googleFillSlot("TweetDetailPageLargeBottom");
      </script>
    </div>
    <div class="google_ad_336x280 right">
      <fb:recommendations site="inagist.com" width="336" height="280" header="false" font="arial" border_color="">
      </fb:recommendations>
    </div>
  </div> 
  <?php } if ($show_replies){ ?>
	<div>
		<ul id="tabnav"> 
			<li class="tab1"><a href="#" id="allreply" class="tabnavselected">All Replies</a></li>
			<?php if(isset($_SESSION['user_id'])) {	?>
				<li class="tab2"><a href="#" id="relevantreply"  class="tabnavnotselected">Your Friends</a></li>
			<?php }?>
			<li class="tab3"><a href="#" id="prominentreply"  class="tabnavnotselected">Popular</a></li>
		</ul>  
	</div>
	<div id="pre<?=$currentTweet['tweetid']?>" class="preview url" style="display: block;" rel="done">
  		<?=$content?> 
	</div>
  <?php } ?>
	</div>
</div>
  <div id="scriptContainer">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
  </div>
  <div align="center" style="border-top: 2px solid #333; background-color: black;"><?=$footer?></div>
  <script type="text/javascript" src="<?=$cdn_base?>/js/minified/tweetdetail_footer.min.js?v=0.1.2"></script>
</div>
</body>
</html>
