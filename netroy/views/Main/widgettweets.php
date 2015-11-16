<?
      global $portal_map;
      $last_tweet_id = '';
		
      if ($widgettweets['noautorefresh'])
      {?>
		<script language="javascript">
	  	$(function(){
			  window.noautorefresh = true;
	  	});
		</script>
	<?php
		unset($widgettweets['noautorefresh']); 
     }
     if ($widgettweets['last_tweet_id']!='' && $widgettweets['last_tweet_id']!=null){
     	$last_tweet_id=$widgettweets['last_tweet_id'];
     	unset($widgettweets['last_tweet_id']);
     } 
     ?>
<div id="tweets">
<?
  $i=0;
  foreach($widgettweets as $tweet){
  	if (!isset($tweet["raw_text"]))
  		$tweet["raw_text"]=Utils::removeSpecialChar($tweet["text"]);
  ?>
<div class="body <?=$tweet["type"]?> <?=$tweet['cluster']?>" id="tw<?=$tweet["id"]?>">
<table cellspacing="0" cellpadding="0" border="0" class="tweets">
  <tr>
    <td class="text" colspan="3">
      <span class="favorite">
      <!-- a title="favorite this tweet" class="fav" id="star_<?=$tweet["id"]?>"> </a-->
      	<a title="reply to this" href="#"><span class="replybt"> </span></a><br/>
      	<a title="retweet this" href="#"><span class="retweetbt"> </span></a>
      </span>
      <div>
      	<a href="http://twitter.com/<?=$tweet['user']['screen_name']?>/status/<?=$tweet['id']?>" target="_blank" rel="nofollow" 
           onClick="_gaq.push(['_trackEvent', 'PartnerTweetAction', 'UserProfileClick', 
           '<?=$tweet['user']['screen_name']?>'],['b._trackEvent', 'PartnerTweetAction', 'UserProfileClick', 
           '<?=$tweet['user']['screen_name']?>']);">
        	<img src="<?=$tweet["user"]["profile_image_url"]?>" alt="<?=$tweet['user']['screen_name']?>" class="tweetuser" align="left"/>
      	</a>
        <a href="http://twitter.com/<?=$tweet['user']['screen_name']?>" target="_blank" rel="nofollow" 
         style="text-decoration: none;" onClick="_gaq.push(['_trackEvent', 'PartnerTweetAction', 'UserIDProfileClick', 
           '<?=$tweet['user']['screen_name']?>'],['b._trackEvent', 'PartnerTweetAction', 'UserIDProfileClick', 
           '<?=$tweet['user']['screen_name']?>']);">
        	<span class="user" style="padding-right: 0px;"><?=$tweet["user"]["screen_name"]?></span>
      	</a>: <?=$tweet["text"]?></div>
      <a href="#" class="hidden url"></a>
    </td>
  </tr><tr class="meta">
    <td class="meta" colspan="3" >
      <!--a href="http://twitter.com/<?=$tweet['user']['screen_name']?>" target="_blank" rel="nofollow">
        <span class="user" style="padding-right: 0px;"><?=$tweet["user"]["screen_name"]?></span>
      </a-->            
      <?php
      $tweeturl="";
      if ($tweet["url"]!='') 
      	$tweeturl="?url=".$tweet["url"];
      ?>
      <a href="http://twitter.com/<?=$tweet['user']['screen_name']?>/status/<?=$tweet['id']?>" target="_blank"
       onClick="_gaq.push(['_trackEvent', 'PartnerTweetAction', 'TweetIdClick', 
           '<?=$tweet['id']?>'],['b._trackEvent', 'PartnerTweetAction', 'TweetIdClick', 
           '<?=$tweet['id']?>']);">
        <span class="time"><?=$tweet["created_at"]?></span>
      </a>
      <!-- &nbsp;&nbsp;
        <a title="<?=$replies?><?=$retweets?>" href="http://twitter.com/?status=@<?=$tweet['user']['screen_name']?>%20&in_reply_to_status_id=<?=$tweet['id']?>&in_reply_to=<?=$tweet['user']['screen_name']?>" target="_blank">
        	<span class="comments">reply</span>
        </a>-->              	
      <?php
      if (($tweet["mentions"]+$tweet["retweets"])!='' && ($tweet["mentions"]+$tweet["retweets"])!=0 ) {
      	$replies="";$retweets="";
      	if ($tweet["mentions"]!='' && $tweet["mentions"]!=0)
      		$replies = $tweet["mentions"] . " Replies | ";
      	if ($tweet["retweets"]!='' && $tweet["retweets"]!=0)
      		$retweets = $tweet["retweets"] . " Retweets";	 
      ?>
      	&nbsp;&nbsp;
      	<a title="<?=$replies?><?=$retweets?>" href="http://inagist.com/<?=$tweet['user']['screen_name']?>/<?=$tweet['id']?>/?utm_source=partner&utm_medium=widget" target="_blank"
           onClick="_gaq.push(['_trackEvent', 'PartnerTweetAction', 'ReactionsClick', 
           '<?=$tweet['id']?>'],['b._trackEvent', 'PartnerTweetAction', 'ReactionsClick', 
           '<?=$tweet['id']?>']);">
        	<span class="comments"><?=($tweet["mentions"]+$tweet["retweets"])?> reactions</span>
        </a>
      <?php }?>
      &nbsp;&nbsp;
      <a title="more related tweets" href="http://inagist.com/<?=$tweet['user']['screen_name']?>/<?=$tweet['id']?>/?utm_source=partner&utm_medium=widget" target="_blank"
         onClick="_gaq.push(['_trackEvent', 'PartnerTweetAction', 'MoreDetailsClick', 
         '<?=$tweet['id']?>'],['b._trackEvent', 'PartnerTweetAction', 'MoreDetailsClick', 
         '<?=$tweet['id']?>']);">
        <span class="comments" style="float:right; margin-right: 5px;">more &raquo;</span>
      </a>
    </td>
    <!--td class="label"></td-->
    <!-- td colspan="2" align="right" class="action">
    <?php
      	if (($tweet['cluster']=='parent')&&($tweet['childcnt']!=0))
      		echo "<a href='#' title='".$tweet['childcnt']." more from ".$tweet['user']['screen_name']."' class='moreofuser' show='more' cnt='".$tweet['childcnt']."'>".$tweet['childcnt']." more&gt;&gt;&nbsp;&nbsp;</a>"; 
      ?>
      <a title="reply to this" href="#"><span class="replybt"> </span></a>
      <a title="retweet this" href="#"><span class="retweetbt"> </span></a>
      <? if($tweet["type"]=="url"){ ?>
        <a title="preview" href="#"><span class="prevbt" id="prev_<?=$tweet["id"]?>"></span></a>
      <? }else{ ?>
        <a title="replies" href="#"><span class="respbt" id="resp_<?=$tweet["id"]?>"></span></a>
      <? } ?>
    </td-->
  </tr>
</table>
</div>
<div class="preview <?=$tweet["type"]?>" id="pre<?=$tweet["id"]?>">
  <div class="loader opac90"> </div>
</div>
<?
  $i++;
  }
  if ($i == 0 && isset($_REQUEST['keyw']) && $_REQUEST['keyw'] !=''){
    $keywords = str_replace('%7C',' OR ',urlencode(substr_replace($_REQUEST['keyw'], '', -1)));
?>
    <script language="javascript">
      function backfill_tweets(queryText) {
        $.ajax({
          url: 'http://search.twitter.com/search.json?rpp=5&paginate=false&q=' + queryText,
          dataType: "jsonp",
          success:function(data) {
            for ( i in data.results){
              var tweet = data.results[i];
              $("#tweets").append("<div class='body' id='tw" + tweet.id_str+"'>" + 
                  "<table cellspacing='0' cellpadding='0' border='0' class='tweets'> " +
                    "<tr>" +
                    "<td rowspan='2' class='pic'> " +
                      "	<a href='http://twitter.com/"+tweet.from_user+"/status/"+tweet.id_str+
                      "' target='_blank' rel='nofollow' > " +
                      "		<img src='"+tweet.profile_image_url+"' /> " +
                      "	</a> "+
                    "</td> "+
                    "<td class='text' colspan='3'> "+
                      "<div>"+
                      "	<a href='http://twitter.com/"+tweet.from_user+
                      "' target='_blank' rel='nofollow' style='text-decoration: none;'>"+
                      "<span class='user' style='padding-right: 0px;'>"+
                      tweet.from_user+"</span></a>: "+linkify(tweet.text)+
                      "</div>"+
                      "</td>"+
                    "</tr>"+
                    "<tr class='meta'>"+
                      "<td class='meta'>"+
                      " <span class='time uptime' title='" +tweet.created_at + "'>" + 
                      prettyDate(tweet.created_at) + "</span>"+
                      "</td>"+
                    "</tr>"+
                    "</table>"+
                  "</div>");
            }
          }
        });
      };

      backfill_tweets("<?=$keywords?>");
    </script>
<?
  }
?>
</div>
