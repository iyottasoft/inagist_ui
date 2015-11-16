<?php
global $portal_map,$domain;
 $label="";$morelink="#";
 foreach ($portal_map as $portalurl => $portaldetails)
 {
 	if ($portal==$portaldetails['handle'] || $portaldetails['handle']==$data['allportals'][0]['portalhandle'])
 	{
 		$label="more ".$portaldetails['name']." news & trends >>";
 		$morelink="http://".$portalurl;
 		$tittlelabel=$portaldetails['name']." news & trends";
 	}
 }
  if ($tittlelabel!=''){?> 
	<div id="tweetlabel">
  		<?=$tittlelabel?>
	</div>
  <?php } ?>
<?php
  
  
  foreach($data['allportals'] as $toptweet) {
  ?>
  <div class="body">
  <table cellspacing="0" cellpadding="0" border="0" class="tweets">
    <tr>
      <td rowspan="2" class="pic">
        <a href="http://twitter.com/<?=$toptweet['user']['screen_name']?>" target="_blank" rel="nofollow">
          <img src="<?=$toptweet["user"]["profile_image_url"]?>" alt="<?=$toptweet['user']['screen_name']?>" />
        </a>
      </td>
      <td class="text">
      <div>
        <a href="http://twitter.com/<?=$toptweet['user']['screen_name']?>" target="_blank" rel="nofollow">
          <span class="user" style="padding-right: 0px; color:#FFEB00;"><?=$toptweet["user"]["screen_name"]?></span>
        </a> : <?=$toptweet["text"]?></div>
      </td>
    </tr><tr height="14">
      <td class="meta">
      	<span class="time"><?=$toptweet["created_at"]?></span>
      	
      	<?php
      	if (($toptweet["mentions"]+$toptweet["retweets"])!='' && ($toptweet["mentions"]+$toptweet["retweets"])!=0 ) {
      		$replies="";$retweets="";
      		if ($toptweet["mentions"]!='' && $toptweet["mentions"]!=0)
      			$replies = $toptweet["mentions"] . " Replies | ";
      		if ($toptweet["retweets"]!='' && $toptweet["retweets"]!=0)
      			$retweets = $toptweet["retweets"] . " Retweets";	 
      ?>              	
      	&nbsp;&nbsp;&nbsp;
      	<a title="<?=$replies?><?=$retweets?>" href="http://inagist.com/<?=$toptweet['user']['screen_name']?>/<?=$toptweet['id']?>/" target="_blank">
        	<img src="/netroy/images/commenticon.gif" class="cmticon"/><span class="time"><?=($toptweet["mentions"]+$toptweet["retweets"])?></span>
        </a>
      <?php 
      }?>
      </td>
    </tr>
  </table>
  </div>
  <?php
  //}
 }
?>

<div id="allportalmorelabel">
  <a href="<?=$morelink?>" style="text-decoration: underline;"><?=$label?></a>
</div>  
