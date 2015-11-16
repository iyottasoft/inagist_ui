<?
      global $portal_map;
      $last_tweet_id = '';
		
      if ($tweets['noautorefresh'])
      {?>
		<script language="javascript">
	  	$(function(){
			  window.noautorefresh = true;
	  	});
		</script>
	<?php
		unset($tweets['noautorefresh']); 
     }
     if ($tweets['last_tweet_id']!='' && $tweets['last_tweet_id']!=null){
     	$last_tweet_id=$tweets['last_tweet_id'];
     	unset($tweets['last_tweet_id']);
     }
     ?>
<div id="tweets">
<? 
   if($_GET['list']!='')
		$listlabel = " - ".$_GET['list'];
   if(array_key_exists($_SERVER["SERVER_NAME"],$portal_map)) 
		$label=$portal_map[$_SERVER["SERVER_NAME"]]["name"]." News & Trends";
   elseif ($_GET['user']==$_SESSION['user_id'])
   		$label="Your$listlabel Trending Tweets";
   elseif ($_GET['name']!='')
   		$label=$_GET['name']." News & Trends";		
   elseif ($_GET['user']!='')
   		$label=$_GET['user'].$listlabel." - Trending Tweets";		
   else
   		$label="";
   if ($labeloveride!='' && $labeloveride!=null)
   		$label = $labeloveride;
   if ($donotshowlabel)
   		$label="";				
   if ($label!=''){		
?>
<div id="tweetlabel">
  <h1><?=$label?></h1>
</div>
<? } ?>
<ul class="tweetslist">
<?
  $i=0;
  foreach($tweets as $tweet){
  	if (!isset($tweet["raw_text"]))
  		$tweet["raw_text"]=Utils::removeSpecialChar($tweet["text"]);
    $nofollow = ($tweet["retweets"] >= 3) ? "": "rel='nofollow'";
  ?>
<li class="body <?=$tweet["type"]?> <?=$tweet['cluster']?>" id="tw<?=$tweet["id"]?>">
<table cellspacing="0" cellpadding="0" border="0" class="tweets">
  <tr>
    <td rowspan="2" class="pic">
      <a href="http://twitter.com/intent/user?screen_name=<?=$tweet['user']['screen_name']?>" data-trackAction="TweetUserProfileClick" data-trackLabel='<?=$tweet["user"]["screen_name"]?>'>
        <img src="<?=$tweet["user"]["profile_image_url"]?>" alt="<?=$tweet['user']['screen_name']?>" />
      </a>
    </td>
    <td class="text" colspan="3">
      <span class="favorite">
      <!-- a title="favorite this tweet" class="fav" id="star_<?=$tweet["id"]?>"> </a-->
      	<a title="reply to this" href="#"><span class="replybt"> </span></a><br/>
      	<a title="retweet this" href="#"><span class="retweetbt"> </span></a>
      </span>
      <div>
        <a href="http://twitter.com/intent/user?screen_name=<?=$tweet['user']['screen_name']?>" style="text-decoration: none;" data-trackAction='TweetUserProfileClick' data-trackLabel='<?=$tweet["user"]["screen_name"]?>'>
        	<span class="user" style="padding-right: 0px;"><?=$tweet["user"]["screen_name"]?></span>
      	</a>: <a href="http://inagist.com/<?=$tweet['user']['screen_name']?>/<?=$tweet['id']?>/" target="_blank" style="color: #333;" <?=$nofollow?>><?=$tweet["actual_text"]?></a></div>
    </td>
  </tr><tr class="meta">
    <td class="meta">
      <?php
      if (($tweet["mentions"]+$tweet["retweets"])!='' && ($tweet["mentions"]+$tweet["retweets"])!=0 ) {
      	$replies="";$retweets="";
      	if ($tweet["mentions"]!='' && $tweet["mentions"]!=0)
      		$replies = $tweet["mentions"] . " Replies | ";
      	if ($tweet["retweets"]!='' && $tweet["retweets"]!=0)
      		$retweets = $tweet["retweets"] . " Retweets";	 
      ?>              	
      <span class="time"><?=$tweet["created_at"]?></span>
      <span class="time"><?=$replies?><?=$retweets?></span>
      <?php } else { 
        $related = "";
      	if ($tweet["related_tweets"]!='' && $tweet["related_tweets"]!=0){
      		$related = $tweet["related_tweets"] . " Related";
        }
      ?>
      <span class="time"><?=$tweet["created_at"]?></span>
      <span class="time"><?=$related?></span>
      <?php } ?>
    </td>
    <!--td class="label"></td-->
    <td colspan="2" align="right" class="action">
    <?php
      	if (($tweet['cluster']=='parent')&&($tweet['childcnt']!=0))
      		echo "<a href='#' title='".$tweet['childcnt']." more from ".$tweet['user']['screen_name']."' class='moreofuser' show='more' cnt='".$tweet['childcnt']."'>".$tweet['childcnt']." more&gt;&gt;&nbsp;&nbsp;</a>"; 
      ?>
    </td>
  </tr>
</table>
<div class="preview <?=$tweet["type"]?>" id="pre<?=$tweet["id"]?>">
  <div class="loader opac90"> </div>
</div>
</li>
<?
  $i++;
  }
?>
</ul>
</div>
	<?php
	if (count($tweets)>=30 && $last_tweet_id!=''){
		$url = "?1"; 
		$skip_params = array ("r","user","list","realdata","start","1");
		foreach ($_GET as $key => $value)
		{
			if (!in_array($key,$skip_params))
				$url .= "&".$key."=".$value;		
		}
		 ?>
		<div id="morebutton">
			<a class="twtmore round-corner" rel="nofollow" title="More" href="<?=$url?>&start=<?=$last_tweet_id?>">More </a>
		</div>
	<?php
	}
	/* More button more more tweets for a trend topic*/
	if ($trendkey && count($tweets)>0 && $showmoreofnouser){
		$url = "?1"; 
		$skip_params = array ("r","user","list","realdata","start","1","key");
		foreach ($_GET as $key => $value)
		{
			if (!in_array($key,$skip_params))
				$url .= "&".$key."=".$value;		
		}		
		 ?>
		<div align="right" style="padding:5px; font-size:14px;">
			<a title="More" rel="nofollow" href="<?=$url?>&more" >More on <span style="">'<?=urldecode($trendkey)?>'</span></a>
		</div>
	<?php 	
	}
	else if ($last_trend_tweet_id!='' && count($tweets)>=18)
	{
		$url = "?1"; 
		$skip_params = array ("r","user","list","realdata","start","1");
		foreach ($_GET as $key => $value)
		{
			if (!in_array($key,$skip_params))
				$url .= "&".$key."=".$value;		
		}		
		 ?>
		<div id="more_updates<?=$last_trend_tweet_id?>"></div> 
		<div align="right" style="padding:5px; font-size:14px;" id="morebtn" >
			<a title="More" rel="nofollow" id="<?=$last_trend_tweet_id?>" class="trendsmore" href="#" >More on <span style="">'<?=urldecode($trendkey)?>'</span></a>
		</div>
		<script language="javascript">
		$(function(){
	  		$(".trendsmore").click(function() {
	  		   	var element = $(this);
	  			var msg = element.attr("id");  			   
	  			$("#morebtn").html('<span style="color:#EEE;">loading...</span>');
	  			var params = {"r":"main/UserTrendTweets","start":"<?=$last_trend_tweet_id?>","t":"<?=$trendkey?>","json":"true","realdata":"true"};
	  			$.get(window.makeurl(params),function(data){
	  				$("#morebtn").remove();
	  				$("#more_updates<?=$last_trend_tweet_id?>").append(data);  				  		    
	  			},"html");	
	  		    return false;
	  		}); 
        $(".tweets a").click(function(e){
          var target = e.target || e.srcElement;
          while (target && target.nodeName.toLowerCase() !== 'a') {
            target = target.parentNode;
          }
          var trackAction=$(target).attr("data-trackAction")?$(target).attr("data-trackAction"):"Action";
          var trackLabel=$(target).attr("data-trackLabel");
          trackLabel = trackLabel ? trackLabel : $(target).attr("href");
          _gaq.push(['_trackEvent', 'TweetAction', trackAction, trackLabel]);
        });
		});
		</script>
	<?php
	} 
	?>	
