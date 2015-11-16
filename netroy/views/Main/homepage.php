<?php
  global $portal_map,$domain,$cdn_base;
  $trends_flag=false;
?>
	<div class="trending" style="border-bottom: 1px  solid #333; width:100%; float:left;" id="trends_tick">
  	<h2 style="color:#fff; font-size: 16px; float:left; padding:10px 10px 4px 15px; margin:0px; font-weight:normal;">trending stories ></h2>
		<div id="scrollingText" class="inner" style="width:85%;">
			<ul style="left: 0px;">		
 			<?php
 			foreach ($trenddata as $domain => $usertrends){
 			foreach ($usertrends as $trend)	
			{
				$trends_flag=true;		 
				?>
					<li style="float:left; font-size:14px;"><a href="http://<?=$domain?>/trends?t=<?=urlencode(strtolower($trend["phrase"]))?>" target="_blank" ><?=$trend["phrase"]?></a></li>
			<?php 
			}}	 
 			?>
 			</ul> 
		</div>
	</div>
	<?php
	if (!$trends_flag)
	{
		?>
		<script language="javascript">
			document.getElementById("trendingstories").style.display='none';
		</script>
		<?php 
	} 
	?>	

	<div style="clear:both;height:2em;width: 100%"></div>		
<?
  $i=0;
  $display_portals = array("worldnews.inagist.com","worldbiz.inagist.com",
    "india.inagist.com","geek.inagist.com","scitech.inagist.com","soccer.inagist.com");

  foreach($portals as $id=>$tweet){
    // Dont show portal that
    // 1.is currently being viewed,
    // 2.is not in the map
    if($tweet["portal"]==$_SERVER["SERVER_NAME"] ||
      !array_key_exists($tweet["portal"],$portal_map) ||
      (!isset($all_portals) && !in_array($tweet["portal"], $display_portals)))
      continue;

    $portal = $portal_map[$tweet["portal"]];
    $tweet["created_at"] = Utils::timeAgo($tweet["created_at"],1);
    $tweet["raw_text"] = Utils::removeSpecialChar(Utils::removeUrls($tweet["text"]));
    $tweet["text"] = Utils::linkify($tweet["text"]);
    if($type=="url") $tweet["durl"] = preg_replace("/\/.*/","",preg_replace("/^http(s)?:\/\//","",$tweet["url"]));
    
    $htmlid = preg_replace("/\s/","_",strtolower($portal["handle"]));
    if(isset($tweet["i"])) $i = $tweet["i"];
?>
<table class="channel" align="center" cellpadding="5">
	<tr>
	<td class="channelname" valign="bottom">
		<a href="http://<?=$tweet["portal"]?>/"><?=$portal["name"]?></a>
	</td>
	<td class="tweet">	
	<table cellspacing="0" cellpadding="0" border="0" class="tweets">
    <tr>
      <td rowspan="2" class="pic">
        <a href="http://twitter.com/<?=$tweet['user']['screen_name']?>" target="_blank" rel="nofollow">
          <img src="<?=$tweet["user"]["profile_image_url"]?>" alt="<?=$tweet['user']['screen_name']?>" />
        </a>
      </td>
      <td class="text"><div>
        <a href="http://twitter.com/<?=$tweet['user']['screen_name']?>" target="_blank" rel="nofollow" style="text-decoration: none;">
        	<span class="user" style="padding-right: 0px; color:#FFEB00;"><?=$tweet["user"]["screen_name"]?></span>
      	</a>: <?=$tweet["text"]?></div>
      </td>
    </tr><tr height="14">
      <td class="meta">
      	<span class="time"><?=$tweet["created_at"]?></span>
      <?php
      if (($tweet["mentions"]+$tweet["retweets"])!='' && ($tweet["mentions"]+$tweet["retweets"])!=0 ) {
      	$replies="";$retweets="";
      	if ($tweet["mentions"]!='' && $tweet["mentions"]!=0)
      		$replies = $tweet["mentions"] . " Replies | ";
      	if ($tweet["retweets"]!='' && $tweet["retweets"]!=0)
      		$retweets = $tweet["retweets"] . " Retweets";	 
      ?>              	
      	&nbsp;&nbsp;&nbsp;
      	<a title="<?=$replies?><?=$retweets?>" href="http://inagist.com/<?=$tweet['user']['screen_name']?>/<?=$tweet['id']?>/" target="_blank">
        	<img src="/netroy/images/commenticon.gif" class="cmticon"/><span class="time"><?=($tweet["mentions"]+$tweet["retweets"])?></span>
        </a>
      <?php 
      }?>
      </td>
    </tr>
  </table>
	</td>
	</tr>
</table>
<?
  $i++;
  
  }
  $user_message = '<a href="/login" style="text-decoration: underline;">Sign in</a> with your twitter id to see your personalized twitter trends';
	if(isset($_SESSION['user_id'])) 
    $user_message = '<a href="/'.$_SESSION['user_id'].'" style="text-decoration: underline;">Click here</a> to see your personalized twitter trends';

  $special_interest = array(
    array("Your trends", $user_message),
    array("<a href='http://inagist.com/japan/live'>Japan</a>", "Summary of tweets from Japan"),
    array("<a href='http://libya.inagist.com/'>Libyan Uprising</a>", "Libyan Uprising as seen on Twitter"),
    array("<a href='http://ladygaga.inagist.com/'>Lady Gaga</a>","Twitter fan page of Lady Gaga"),
    array("<a href='http://inagist.com/japan'>Japan</a>", "Summary of tweets from Japan"),
    array("<a href='http://inagist.com/apple/live'>Apple News</a>","Apple news live from Twitter"),
    array("<a href='http://inagist.com/yemen/live'>Yemen</a>","Monitoring the situation in Yemen"),
    array("<a href='http://inagist.com/bahrain/live'>Bahrain</a>","Monitoring the situation in Bahrain")
  );
  $special_interest_index = array_rand($special_interest, 1);
?>
<table class="channel" align="center" cellpadding="5" >
	<tr>
	<td class="channelname" valign="middle" style="vertical-align: middle; padding-bottom: 0px;">
		<?=$special_interest[$special_interest_index][0]?>
	</td>
	<td class="homepagename" style="width:600px; padding-bottom: 0px; text-align: left; vertical-align: middle; text-transform: none;">
		<?=$special_interest[$special_interest_index][1]?>
	</td>
	</tr>
</table>

<div style="clear:both;height:2em;width: 100%"></div>

		<div class="trending" style="border-top: 1px  solid #333; width:100%; float:left;" id="channel_tick1">
  		<h2 style="color:#fff; font-size: 16px; float:left; padding:12px 10px 4px 15px; margin:0px; font-weight:normal;"><a href="/all" style="color:#666;">more channels ></a></h2>
		<div id="scrollingText" class="inner" style="width:80%;">
			<ul style="left: 0px; margin-top:12px; padding-left:0px;">		
 			<?php
 			$category_portal=array();
 			foreach ($portal_map as $domain => $portal_details)
 				$category_portal[$portal_details['category_id']]=$portal_details["category_name"];
 			ksort($category_portal);	
 			foreach ($category_portal as $category_id => $category_name)
 			{	
 				?>
					<li style="float:left; font-size:12px;"><a href="http://inagist.com/all?cat=<?=$category_id?>" style="text-transform: uppercase;color:#666;"><?=$category_name?></a></li>
			<?php 
			}	 
 			?>
 			</ul> 
		</div>
		</div>

