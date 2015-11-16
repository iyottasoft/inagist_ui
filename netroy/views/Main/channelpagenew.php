<?php
  global $portal_tweets,$user_map,$portal_map,$cdn_base;
  $trends_flag=false;
  $today = date("Ymd");
  $default_portals = array("worldnews.inagist.com","worldbiz.inagist.com",
  "soccer.inagist.com", "tech.inagist.com","india.inagist.com",
  "sports.inagist.com", "health.inagist.com","hollywood.inagist.com",
  "london.inagist.com", "bayarea.inagist.com", 
  "france.inagist.com", "singapore.inagist.com", "bargains.inagist.com",
  "nyc.inagist.com", "cricket.inagist.com",  
  "airports.inagist.com", "airlines.inagist.com", "travel.inagist.com");
?>
					
	<table border="0" cellpadding="0" cellspacing="0" align="center" class="clear" width="950" style="padding: 20px; background-color: #FFF;" >
	<tr>
	<td>
		<div class="home_content" style="width: 200px; line-height: 14px; padding-top: 0px;">
			<a title="In-A-Gist" href="http://inagist.com/">
	    		<img src="netroy/images/logo_new.png" alt="inagist" border="0" align="left"/>    		
	  		</a>  		
		</div>

		<div id="searchwrapper"  style="float: right; margin-top: 10px;">
		  <form method="get" action="/search" onsubmit="return formSubmit();">
		  <?php if ($user!='') {?>
			  <input type="hidden" name="user" value="<?=$user?>"/>
		  <?php }?>
		  <?php if ($_REQUEST['q']!=''){?>	 
			  <input type="text" class="searchbox" name="q" value="<?=strip_tags($_REQUEST['q'])?>" size="25" id="q"/>
		  <?php }	else {?>
			  <input type="text" class="searchbox" name="q" value="Search ..." size="25" id="q" onclick="document.forms[0].q.value ='';"/>
		  <?php }?>	
			<input type="image" src="/netroy/images/search-lens.png" class="searchbox_submit" value="" />
		  </form>
		</div>
	</td>
	</tr>
	<tr><td>
		<div class="home_content" style="width:240px;">
			<img src="netroy/images/star.png" align="right" />
			<span><a href="http://inagist.com/all">40+ channels</a> on various subjects and latest trends</span>
		</div>
		<div class="home_content" style="width:275px;">
			<img src="netroy/images/hand.png" align="right" />
			<span>Top ranked stories from trusted sources based on popularity and quality</span>
		</div>
				
		<div class="home_content" style="margin-right:0px;">
		  <? if(isset($_SESSION['user_id'])) 
		  	 {
		      $url = "http://inagist.com/".$_SESSION['user_id'];
		      $name = (isset($_SESSION['name']))?$_SESSION['name']:$_SESSION['user_id'];
		  	  if(isset($_SESSION['profile_image_url']))
		  	  { ?>
		        <a href="<?=$url?>"><img src="<?=$_SESSION['profile_image_url']?>" align="left" class="image_wrapper" style="width: 40px;height: 40px; margin-right:10px;margin-top:0px;"/></a>
		   <? } ?>
		   		<span style="font-size:18px">
		        <a href="<?=$url?>"><?=$name?></a>
		        <br/>
		        <a href="http://inagist.com/settings">settings</a> | <a href="http://inagist.com/logout">logout</a></span>
		      <script type="text/javascript">window.loggedinuser="<?=$_SESSION['user_id']?>";</script>
		  <? }
		  	else
		  	{
		  		?>
		    <img src="netroy/images/tool.png" align="right" />
		    <span><a href="/login">Sign in</a> with twitter and choose your channels</span>
		  <? } ?>
		</div>
	</td></tr>
	</table>
	
	
	<table cellspacing="0" cellpadding="0" border="0" align="center" width="940">
	<tr>
		<td class="trends_section" style="border-top: 2px dotted #FFFFFF;padding-bottom: 5px;">
	  	<div class="trending" style="border-bottom: 0px solid #333; width:940px; float:left; " id="trends_tick">
	  	<h2 style="color:#fff; font-size: 16px; float:left; padding:10px 0px 4px 0px; margin:0px; font-weight:bold;"><a href="http://inagist.com/alltrends" style="color:#fff;  font-family: 'Delicious Bold'; font-size:20px">trends &gt;</a></h2>
  		<div id="scrollingText" class="inner" style="width:88%;">
			<ul style="left: 0px; margin-top:12px; padding-left:0px;">		
 			<?php
      $trendcount = 0;
      foreach ($trenddata as $trend){
        if ($trendcount >= 12) continue;
        $trendcount++;
        if ($channelname != ''){
 		  ?>
					<li class="atrend"><a href="/trends/<?=urlencode(strtolower($trend["phrase"]))?>/" target="_blank" class="tlink"><?=$trend["phrase"]?></a></li>
			<?php 
        } else {
 		  ?>
					<li class="atrend"><a href="/<?=$user?>/trends/<?=urlencode(strtolower($trend["phrase"]))?>/" target="_blank" class="tlink"><?=$trend["phrase"]?></a></li>
			<?php
        }
      }
      foreach ($portals as $portal_id => $portal_tweet){
        if ($trendcount >= 12) continue;
        $portaluser = $portal_tweet['handle'];
        $domain = $user_map[$portaluser];
        if (!in_array($domain,$default_portals) || ($domain == $_SERVER["SERVER_NAME"])) continue;
      	$home_trends_map = xcache_get("ig_home_trends_map_".$portaluser);
        $usertrends=$home_trends_map[$portal_tweet['portal']];
        foreach ($usertrends as $trend){
          $trendcount++;
 		  ?>
					<li class="atrend"><a href="http://<?=$domain?>/trends/<?=urlencode(strtolower($trend["phrase"]))?>/" target="_blank" class="tlink"><?=$trend["phrase"]?></a></li>
			<?php 
			  }
      }
 			?>
 			</ul> 
		</div>
		</div>
		</td>
	</tr>
  <?php
  if (count($popular_tweets) > 0) {
  ?>
	<tr>
		<td class="channels_section" style="border-top: 2px dotted #FFFFFF; background-color: #333;">
	  	<div class="trending" style="border-bottom: 0px solid #333; width:940px; float:left; " id="now_popular_sec">
	  	<h2 style="color:#fff;font-family: 'Delicious Bold'; font-size:20px; float:left; padding:10px 0px 4px 0px; margin:0px; font-weight:bold;"><a href="" style="color:#fff;  font-family: 'Delicious Bold'; font-size:20px">popular &gt;</a></h2>
  		<div id="now_popular_tweets" class="inner" style="width:88%;">
			<ul style="left: 0px; margin-top:12px; padding-left:0px;">		
 			<?php
 			foreach ($popular_tweets as $tweetIndex => $popularTweet)
 			{
        if (is_array($popularTweet['tweet'])){
          $tweet = $popularTweet['tweet'];
          $tweetUser = $tweet['user'];
 				?>
					<li class="atrend"><a href="http://inagist.com/<?=$tweetUser['screen_name']?>/<?=$tweet['id_str']?>/" target="_blank" class="tlink" title="<?=str_replace("\"","",$tweet['text'])?>" style="width: 160px; height: 25px;"><img src="<?=$tweetUser['profile_image_url']?>" style="height: 18px; width: 18px; margin-right: 3px;"/><?=Utils::removeUrls($tweet['text'])?></a></li>
			<?php 
        }
			}	 
 			?>
 			</ul> 
		</div>
		</div>
		</td>
	</tr>
  <?php } ?>
	<tr><td class="channels_section" style="border-top: 2px dotted #FFFFFF; border-bottom: 2px dotted #FFFFFF;">
	<h1 class ="channels_topic"><?=$channelname?></h1>
  <div class='section_w924'>	
	<?php
$portalDisplayed = array();
$tweetIndex = 0;
foreach ($tweets as $tweetId => $tweet){
  if (isset($tweet["text"]) && ($tweetIndex < 26)){
    $portalDisplayed[] = $tweet['id'];
    $nofollow = ($tweet["retweets"] >= 3) ? "": "rel='nofollow'";
    $tweettext = "<div class='tweet'>
    <div class='tweet_msg_box'>
      <div class='tweet_text'>
          <li style='list-style: none;'>
          <a href='http://inagist.com/".$tweet['user']['screen_name']."/".$tweet['id_str'].
        "/' class='tweet_link' target='_blank' $nofollow>".$tweet["actual_text"].
        "</a></li>";
        $tweettext .= "</div></div>"; //tweet_msg_box
        $tweettext .= "<p>
        <a title='".$tweet['user']['name']."' href='http://twitter.com/intent/user?screen_name=".$tweet['user']['screen_name']."' target='_blank'>
          <img  src='".$tweet["user"]["profile_image_url"]."' alt='".$tweet['user']['screen_name']."' align='left' class='image_wrapper twtpic' style='margin-right:2px;' />
        </a>
        <span class='meta'>
          <a title='".$tweet['user']['name']."' href='http://twitter.com/intent/user?screen_name=".$tweet['user']['screen_name']."' target='_blank'>
        <span style='padding-right: 0px;' class='user'>".$tweet['user']['screen_name']."</span>
      </a><br/>
      <span class='time'>".Utils::timeAgo($tweet["created_at"],1)."</span>				
    </span>	 
        </p>
        </div>";
    echo ($tweettext);
    if (($tweetIndex == 5) && (count($tweets) >= 10)) { ?>
      <div class='ad_big' style='float: right; display: inline-block; width: 294px; height: 272px; margin-right: 10px;'>
      <div style='float: right; width: 250px; height: 250px;'>
        <script type='text/javascript' src='http://partner.googleadservices.com/gampad/google_service.js'>
        </script>
        <script type='text/javascript'>
          GS_googleAddAdSenseService("ca-pub-2412868093846820");GS_googleEnableAllServices();
        </script>
        <script type='text/javascript'>
          GA_googleAddSlot("ca-pub-2412868093846820", "Channel_Page_Bottom");GA_googleAddSlot("ca-pub-2412868093846820", "Channel_Page_TOP");
        </script>
        <script type='text/javascript'>
          GA_googleFetchAds();
        </script>
        <!-- Channel_Page_TOP -->
        <script type='text/javascript'>
          GA_googleFillSlot("Channel_Page_TOP");
        </script>
      </div>
      </div>
    <?php } elseif (($tweetIndex == 15) && (count($tweets) >= 20)){ ?>
      <div class='ad_big' style='float: left; display: inline-block; width: 300px; height: 272px; margin-right: 8px;'>
    <?php if ($show_alternate) { ?>
      <fb:recommendations site="inagist.com" width="300" height="260" header="false" colorscheme="light" font="verdana" border_color="">
      </fb:recommendations>
    <?php } else { ?>
        <div style='float: left; width: 250px; height: 250px;'>
          <!-- Channel_Page_Bottom -->
          <script type='text/javascript'>
            GA_googleFillSlot("Channel_Page_Bottom");
          </script>
        </div>
    <?php } ?>
      </div>
    <?php }
  }
  $tweetIndex++;
}
?>
</div>
<div class="clear" style="border-top: 2px dotted white;"> </div>
<h1 class ="channels_topic">trending elsewhere</h1>
<div class='section_w924'>
<ul style='padding: 0px; list-style: none; margin: 0px;'>
<?php
// displaying latest tweets
$latest_trends = array_slice(array_filter($latest_trends, function($elem) use($portalDisplayed) {
  $tweetIdStr = $elem['tweet']['id'];
  return (($elem['userid'] != "justinbieber") && !in_array($tweetIdStr, $portalDisplayed));
  }), 0, 12);

if (count($latest_trends) > 3) {
  for($subColIndex = 0; $subColIndex < count($latest_trends); $subColIndex++){
    if (is_array($latest_trends[$subColIndex]['tweet'])){
      $tweet = $latest_trends[$subColIndex]['tweet'];
      $tweetUser = $tweet['user'];
      $tweettext = "<li class='tweet' style='list-style: none;'>
      <div class='tweet_msg_box'>
        <div class='tweet_text'>
          <a href='http://inagist.com/".$tweetUser['screen_name']."/".$tweet['id_str'].
        "/' class='tweet_link' target='_blank'>".$tweet["text"].
        "</a>";
          $tweettext .= "</div></div>"; //tweet_msg_box
          $tweettext .= "<p>
          <a title='".$tweet['user']['name']."' href='http://twitter.com/intent/user?screen_name=".$tweetUser['screen_name']."' target='_blank'>
            <img  src='".$tweetUser["profile_image_url"]."' alt='".$tweetUser['screen_name']."' align='left' class='image_wrapper twtpic' style='margin-right:2px;' />
          </a>
          <span class='meta'>
            <a title='".$tweet['user']['name']."' href='http://twitter.com/intent/user?screen_name=".$tweetUser['screen_name']."' target='_blank'>
          <span style='padding-right: 0px;' class='user'>".$tweetUser['screen_name']."</span>
        </a><br/>
        <span class='time'>".Utils::timeAgo($tweet["created_at"],1)."</span>				
      </span>	 
          </p>
          </li>";
      echo $tweettext;
    }		
  }
}
?>
  </ul>
  </div>
	<!-- The content goes in here -->
	</td></tr>
	<tr>
		<td class="channels_section" style="border-bottom: 2px dotted #FFFFFF;padding-bottom: 5px;">
	  	<div class="trending" style="border-bottom: 0px solid #333; width:940px; float:left; ">
	  	<h2 style="color:#fff; font-size: 16px; float:left; padding:10px 0px 4px 0px; margin:0px; font-weight:bold;"><a href="http://inagist.com/all" style="font-family: 'Delicious Bold'; font-size:20px">special &gt;</a></h2>
  		<div class="inner" style="width:88%;">
        <ul style="left: 0px; margin-top:12px; padding-left:0px;">		
        <?php
        foreach ($specialchannels as $channelid => $channeldetails){
        ?>
					  <li class="atrend">
              <a href="http://inagist.com/<?=$channelid?>" target="_blank" class="clink" title="<?=$channeldetails["long_description"]?>"><?=$channeldetails["short_description"]?></a>
            </li>
        <?php
        }
        ?>
        </ul> 
		</div>
		</div>
		</td>
	</tr>
	<tr>
		<td class="channels_section" style="border-bottom: 2px dotted #FFFFFF;">
	  	<div class="trending" style="border-bottom: 0px solid #333; width:940px; float:left; " id="channel_tick">
	  	<h2 style="color:#fff; font-size: 16px; float:left; padding:10px 0px 4px 0px; margin:0px; font-weight:bold;"><a href="http://inagist.com/all" style="font-family: 'Delicious Bold'; font-size:20px;">more &gt;</a></h2>
  		<div id="scrollingText" class="inner" style="width:88%;">
			<ul style="left: 0px; margin-top:12px; padding-left:0px;">		
 			<?php
 			foreach ($portal_map as $domain => $portal_details)
 			{
 				?>
					<li class="atrend"><a href="http://<?=$domain?>" target="_blank" class="clink"><?=$portal_details["name"]?></a></li>
			<?php 
			}	 
 			?>
 			</ul> 
		</div>
		</div>
		</td>
	</tr>
	</table>

