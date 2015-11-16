<?
  if(array_key_exists("url_details",$lookup)){
    extract($lookup["url_details"],EXTR_PREFIX_ALL,"l");
    $l_durl = preg_replace("/\/.*/","",preg_replace("/^http(s)?:\/\//","",$l_url));
?>
	<script type="text/javascript">
        $(document).ready(function() {
                $("#preview_container").oembed("<?=$l_url?>",{maxWidth: 330});
        });
  </script>
  <div id="preview_main_container">
  	<div id="preview_container"></div>
  </div>
  
  <h2 class="title"><?=$l_title?></h2>
  <div class="descr">
  <? if(isset($l_attributes) && array_key_exists("image_src",$l_attributes)){ ?>
    <img class="left post_load" data-src="<?=$l_attributes["image_src"]?>" />
  <? } ?>
  <?=$l_description?>
  </div>
  <a href="<?=$l_url?>" target="_blank" rel="nofollow" class="url"><?=$l_durl ?></a>
<?
  }if(array_key_exists("conversation",$lookup)){
    if(array_key_exists("url_details",$lookup)) echo "<hr/>\n";
?><div class="resp"><?
    $i=0;
    $displayedUrls = array();
    if (!isset($source))
      $source = "source=main";
    else
      $source = "source=$source";
    foreach($lookup["conversation"] as $id=>$conversation){
      extract($conversation,EXTR_PREFIX_ALL,"c");
      if(strlen($c_user['screen_name'])==0 || strlen($c_text)==0) continue;
      $cls = ($i%2==0)?"even":"odd";
?>
    <div class="msg <?=$cls?>" id="tw<?=$id?>">
      <a href="http://twitter.com/intent/user?screen_name=<?=$c_user['screen_name']?>" target="_blank" class="left" title="@<?=$c_user['screen_name']?>">
        <img alt="@<?=$c_user['screen_name']?>" class="post_load" data-src="<?=$c_user["profile_image_url"]?>" />
      </a>
      <a title="reply to this" href="#"><span class="replybt right"> </span></a>
      <a href="http://twitter.com/intent/user?screen_name=<?=$c_user["screen_name"]?>" target="_blank">
        <span class="user"><?=$c_user["screen_name"]?></span>
      </a> : 
      <?php
        $noFollowDetail = ($conversation["retweets"] >= 8 || $i < 0) ? "": "rel='nofollow'";
        if ($nolink){
        	echo "<a href='/".$c_user['screen_name']."/$id/' $noFollowDetail class='clink'>$c_text</a>";	
        } else {
          echo Utils::linkifyNoTrack($c_text);
        }
      ?>
      <a href="http://twitter.com/<?=$c_user['screen_name']?>/status/<?=$id?>">
        <span class="time" data-timestamp="<?=$c_created_at?>">
          <?=Utils::timeAgo($c_created_at)?>
        </span>
      </a>
      <div class="replystats more_link" style="margin-left: 5px;">
        <a href="/<?=$c_user['screen_name']?>/<?=$id?>/" title="<?=str_replace("\"", "", $c_text)?>" rel="nofollow">more &raquo;</a>
      </div>
        <?php
        $buffer = array();
        if ($conversation['mentions']!=null)
        	$buffer[]="<span class='replies'>".$conversation['mentions']." replies</span>";        	
        if ($conversation['retweets']!=null)
        	$buffer[]="<span class='mentions'>".$conversation['retweets']." retweets</span>";
        if ($conversation['retweets']!=null || $conversation['mentions']!=null)
        	echo '<div class="replystats">'.implode(" | ",$buffer).'</div>';	
        if (is_array($conversation['long_urls'])){
          foreach($conversation['long_urls'] as $c_url){
            $c_urlDetails = $urlDetails[$c_url];
            if (is_array($c_urlDetails)){
            $canonicalURL = $c_urlDetails['attributes']['canonical'] ? 
              $c_urlDetails['attributes']['canonical'] : $c_urlDetails['attributes']['location'];
            if (isset($c_urlDetails['title']) && 
                !in_array($c_urlDetails['url'], $displayedUrls) &&
                !in_array($canonicalURL, $displayedUrls)){
              $domain = parse_url($c_urlDetails['url']);
              $domain = $domain['host'];
              $c_imagePreview = $c_urlDetails['attributes']['image_src'] ? "<img class='post_load' data-src='".$c_urlDetails['attributes']['image_src']."' />" : "";
              echo("<div class='clear story_details'><h3 style='font-size: 14px; margin-bottom: 5px; font-weight: normal; color: #0D5575;'>".$c_urlDetails['title']."</h3>".$c_imagePreview."<blockquote>".strip_tags(substr($c_urlDetails['description'],0,1024))."</blockquote><a href='".$c_urlDetails['url']."' target='_blank' data-trackAction='TweetRelatedLinkClick' rel='nofollow'>".$domain."</a></div>");
              $displayedUrls[] = $c_urlDetails['url'];
              if (is_array($c_urlDetails["attributes"]) && isset($canonicalURL))
                $displayedUrls[] = $canonicalURL;
            }
            $c_urlDetails = null;
          }
          }
        }
        ?>              
    </div>
<? $i++; } ?>
</div>
<? }if(array_key_exists("stats",$lookup)){
  $buffer = array();
  foreach($lookup["stats"] as $stat=>$value){ 
    $buffer[] = "<span class='$stat'>$value $stat</span>";
  }
?><div class="stats"><?=implode(" | ",$buffer)?></div>
<? } ?>

<?php 
if(array_key_exists("enable_more_btn",$lookup) && $id!='' && $i>=19)
{?>
	<div id="more_updates<?=$id?>"></div>
	<div id="morebutton">
		<a	id="<?=$id?>" class="twtmore round-corner" title="More" href="#">More </a>
	</div>
<?php } elseif ($showRepliesButton && $id != '') {?>
	<div id="more_updates<?=$id?>"></div>
	<div id="morebutton">
		<a	id="0" class="twtmore round-corner" title="More" href="#">Show Replies</a>
	</div>
<?php } ?>
