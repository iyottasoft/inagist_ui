<?php
  global $portal_map,$domain;
  if(isset($portals)){
    if(isset($_GET['count']) && !isset($count)) $count = intval($_GET['count']);
?>
<div id="portals">
<? /*if(isset($count)){ ?>
<div class="label"><a href="http://<?=$domain?>/all">channels</a></div>
<? }*/ ?>
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
<div class="portal <?=$tweet["type"]?> pr<?=($i%7)?>">
  <div class="heading">
    <a href="http://<?=$tweet["portal"]?>/">
      <span class="label"><?=$portal["name"]?></span>
    </a>
  </div>
  <div class="heading more">
    <a href="http://<?=$tweet["portal"]?>/">
      <span class="toggle">more</span>
    </a><span class="toggle"> &#187;</span>
  </div>
  
  <div class="body">
  <table cellspacing="0" cellpadding="0" border="0" class="tweets">
    <tr>
      <td rowspan="2" class="pic">
        <a href="http://twitter.com/<?=$tweet['user']['screen_name']?>" target="_blank" rel="nofollow">
          <img src="<?=$tweet["user"]["profile_image_url"]?>" alt="<?=$tweet['user']['screen_name']?>" />
        </a>
      </td>
      <td class="text"><div><?=$tweet["text"]?></div></td>
    </tr><tr height="14">
      <td class="meta">
        <a href="http://twitter.com/<?=$tweet['user']['screen_name']?>" target="_blank" rel="nofollow">
          <span class="user"><?=$tweet["user"]["screen_name"]?></span>
        </a>   
      	<a href="http://inagist.com/<?=$tweet['user']['screen_name']?>/<?=$tweet['id']?>/" target="_blank">  
        	<span class="time"><?=$tweet["created_at"]?></span>
      	</a>
      </td>
    </tr>
  </table>
  </div>
</div>
<?
  $i++;
  if(isset($count) && $i==$count) break;
  }
?>
<? if(isset($count) || isset($index) ){ ?>
<div class="label"><a href="http://<?=$domain?>/all">see all <!--<?=count($portal_map)?> -->channels</a></div>
<? } ?>
</div>
<? } ?>
