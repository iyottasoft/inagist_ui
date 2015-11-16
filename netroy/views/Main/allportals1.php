  <script type='text/javascript'>
    GS_googleAddAdSenseService("ca-pub-2412868093846820");
    GS_googleEnableAllServices();
  </script>
  <script type='text/javascript'>
    GA_googleAddSlot("ca-pub-2412868093846820", "All_Trends_Top");
  </script>
  <script type='text/javascript'>
    GA_googleFetchAds();
  </script>

<div class="tweetcontent" style="padding-top:20px;padding-bottom: 20px; margin: auto; width: 728px;">
  <!-- All_Trends_Top -->
  <script type='text/javascript'>
    GA_googleFillSlot("All_Trends_Top");
  </script>
</div> 
<?php
global $portal_tweets,$user_map,$portal_map;

$trendingTweets = xcache_get("ig_latest_trends");
$categoryPortal = array();
if (count($trendingTweets) > 0) {
  $trending_tweet_split = array_chunk($trendingTweets, ceil(count($trendingTweets) / 3), true);
	$categoryPortal[0][10]['name']="Trending Now";
  foreach ($trending_tweet_split as $trending_tweet_chunk){
    $categoryPortal[0][10]['channels'].="	
    <div class='section_w300 margin_r_10 left'>
    <div class='channeltitle'><a href='http://inagist.com/' style='color:#FFD800;'> Trending Now <img src='netroy/images/see_more_btn.png' align='right' border='0' /></a></div><ul style='padding: 0px; list-style: none; margin: 0px;'>";
    $tweettext = "";
    foreach ($trending_tweet_chunk as $tweet)
    {
      $tweet = $tweet["tweet"];
      //print_r($tweet);
    $tweettext .= "<li class='tweet' style='list-style: none;'>
              <a href='http://twitter.com/".$tweet['user']['screen_name']."' target='_blank' rel='nofollow' data-trackAction='TweetUserProfileClick' data-trackLabel='".$tweet['user']['screen_name']."'><img src='".$tweet["user"]["profile_image_url"]."' alt='".$tweet['user']['screen_name']."' align='left' class='image_wrapper twtpic' style='margin-right:10px;' /></a>
            <a href='http://twitter.com/".$tweet['user']['screen_name']."' target='_blank' rel='nofollow' data-trackAction='TweetUserProfileClick' data-trackLabel='".$tweet['user']['screen_name']."'>
          <span style='padding-right: 0px;' class='user'>".$tweet['user']['screen_name']."</span>
        </a>: <a href='http://inagist.com/".$tweet['user']['screen_name']."/".$tweet['id']."/' target='_blank' data-trackAction='TweetDetailPage' style='color:white;'>".
        $tweet["text"]."</a>
          <div class='meta'>
            <span class='time'>".Utils::timeAgo($tweet["created_at"],1)."</span>";
          $tweettext .= "</div></li>";
    }		
    $categoryPortal[0][10]['channels'].=$tweettext."</ul></div>";
  }
}

foreach ($portal_tweets as $portaluser => $tweets)
{
  $tweets = array_slice($tweets, 0, 3, true);
	$domain = $user_map[$portaluser];
	$categoryid = $portal_map[$domain]['category_id'];
	$sortorder = $portal_map[$domain]['sortorder'];
	$categoryname = $portal_map[$domain]['category_name'];
	$categoryPortal[$sortorder][$categoryid]['name']=$categoryname;
	if ($portal_map[$domain]['name']!='')
	{
		$categoryPortal[$sortorder][$categoryid]['channels'].="	
		<div class='section_w300 margin_r_10 left'>
		<div class='channeltitle'><a href='http://$domain' style='color:#FFD800;'>".$portal_map[$domain]['name']."<img src='netroy/images/see_more_btn.png' align='right' border='0' /></a></div><ul style='padding: 0px; list-style: none; margin: 0px;'>";
		$tweettext = "";
		foreach ($tweets as $tweet)
		{
			//print_r($tweet);
		$tweettext .= "<li class='tweet' style='list-style: none;'>
	            <a href='http://twitter.com/".$tweet['user']['screen_name']."' target='_blank' rel='nofollow' data-trackAction='TweetUserProfileClick' data-trackLabel='".$tweet['user']['screen_name']."'><img src='".$tweet["user"]["profile_image_url"]."' alt='".$tweet['user']['screen_name']."' align='left' class='image_wrapper twtpic' style='margin-right:10px;' /></a>
	        	<a href='http://twitter.com/".$tweet['user']['screen_name']."' target='_blank' rel='nofollow' data-trackAction='TweetUserProfileClick' data-trackLabel='".$tweet['user']['screen_name']."'>
					<span style='padding-right: 0px;' class='user'>".$tweet['user']['screen_name']."</span>
        </a>: <a href='http://inagist.com/".$tweet['user']['screen_name']."/".$tweet['id']."/' target='_blank' data-trackAction='TweetDetailPage' style='color:white;'>".
        $tweet["text"]."</a>
          <div class='meta'>
            <span class='time'>".Utils::timeAgo($tweet["created_at"],1)."</span>";
          $tweettext .= "</div></li>";
		}		
		$categoryPortal[$sortorder][$categoryid]['channels'].=$tweettext."</ul></div>";
	}// if portal name is not empty	
}

  ksort($categoryPortal);				
  $open_category=$_GET['cat'];
  if ($open_category=='' || $open_category==null)
  	$open_category=1;//open news by default
  foreach ($categoryPortal as $sortorderid => $catPortal){
  foreach ($catPortal as $categoryid => $channel)
  {
  	if ($categoryid!=$open_category)
  	{
  		$style = " style='display:none'";
  		$expand_collapse=" collapseexpand "; 
  	}	
  	else
  	{
  		$style="";
  		$expand_collapse=" expandcollapse ";
  	}	
  	if ($categoryid > 0){
  		echo '<div style="padding-bottom:20px;">
  		  <span class="'.$expand_collapse.' categoryname" id="ex_'.$categoryid.'"><span></span>'.$channel['name'].'</span>
  		  <div class="channels" id="'.$categoryid.'_navigation" '.$style.' >	
  		  '.$channel['channels'].'
  		  </div>
  		  </div>
  		  <div class="clear"></div>';
  	}
  }
  }
  //echo "</ul>";
?>
	<script language="javascript">
  	$(function(){
      $(".tweet a").click(function(e){
        var target = e.target || e.srcElement;

        while (target && target.nodeName.toLowerCase() !== 'a') {
          target = target.parentNode;
        }
        var trackAction=$(target).attr("data-trackAction");
        var trackLabel=$(target).attr("data-trackLabel");
        trackLabel = trackLabel ? trackLabel : $(target).attr("href");
        _gaq.push(['_trackEvent', 'TweetAction', trackAction, trackLabel]);
      });
  	});
	</script>

