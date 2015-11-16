<?
      global $portal_map;
?>
<div id="tweets">
<?
  $i=0;
  foreach($usertrendtweets as $tweet){
  	if ($i>=2)
  		break;
  	if (!isset($tweet["raw_text"]))
  		$tweet["raw_text"]=Utils::removeSpecialChar($tweet["text"]);
  ?>
<div class="body <?=$tweet["type"]?> <?=$tweet['cluster']?>" id="tw<?=$tweet["id"]?>">
<table cellspacing="0" cellpadding="0" border="0" class="tweets">
  <tr>
    <td rowspan="2" class="pic">
      <a href="http://twitter.com/<?=$tweet['user']['screen_name']?>" target="_blank" rel="nofollow">
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
        <a href="http://twitter.com/<?=$tweet['user']['screen_name']?>" target="_blank" rel="nofollow" style="text-decoration: none;">
        	<span class="user" style="padding-right: 0px;"><?=$tweet["user"]["screen_name"]?></span>
      	</a>: <?=$tweet["text"]?></div>
      <a href="<?=$tweet["url"]?>" class="hidden url"></a>
    </td>
  </tr><tr class="meta">
    <td class="meta">
      <!--a href="http://twitter.com/<?=$tweet['user']['screen_name']?>" target="_blank" rel="nofollow">
        <span class="user" style="padding-right: 0px;"><?=$tweet["user"]["screen_name"]?></span>
      </a-->            
      <?php
      $tweeturl="";
      if ($tweet["url"]!='') 
      	$tweeturl="?url=".$tweet["url"];
      ?>
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
    <!--td class="label"></td-->
    <td colspan="2" align="right" class="action">
    <?php
      	if (($tweet['cluster']=='parent')&&($tweet['childcnt']!=0))
      		echo "<a href='#' title='".$tweet['childcnt']." more from ".$tweet['user']['screen_name']."' class='moreofuser' show='more' cnt='".$tweet['childcnt']."'>".$tweet['childcnt']." more&gt;&gt;&nbsp;&nbsp;</a>"; 
      ?>
      <!-- a title="reply to this" href="#"><span class="replybt"> </span></a>
      <a title="retweet this" href="#"><span class="retweetbt"> </span></a-->
      <?/* if($tweet["type"]=="url"){ ?>
        <a title="preview" href="#"><span class="prevbt" id="prev_<?=$tweet["id"]?>"></span></a>
      <? }else{ ?>
        <a title="replies" href="#"><span class="respbt" id="resp_<?=$tweet["id"]?>"></span></a>
      <? }*/ ?>
    </td>
  </tr>
</table>
</div>
<div class="preview <?=$tweet["type"]?>" id="pre<?=$tweet["id"]?>">
  <div class="loader opac90"> </div>
</div>
<?
  $i++;
  }
?>
</div>
