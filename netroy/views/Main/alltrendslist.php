<?php
global $portal_tweets,$user_map,$portal_map;
?>
<div id="all_trends" style="width:300px;">
<?php 
  
  $col1="";$col2="";$col3="";$i=0;
  foreach ($portal_map as $domain => $portal)
  {
  	$selected = "";
  	if (in_array($portal['id'],$selChannels))
  		$selected = " checked='checked' ";
  		
  	$op=" <div style='padding:3px;'><input type='checkbox' name='channels[]' value='".$portal["id"]."' id='$domain' $selected onclick='showtrends(this);' /><label for='$domain'>".$portal["name"]."</label></div>";
  	if (($i%2)==0)
  		$col1.=$op;
  	else	
  		$col2.=$op;
  	$i++;
  }
  
?>
<div class="left columns" style="margin-right:10px; font-size:14px;"><?=$col1?></div>
<div class="left columns" style="margin-right:20px; font-size:14px;"><?=$col2?></div>
</div>
<div style="margin-left:300px;">
<?php
global $portal_tweets,$user_map,$portal_map;
foreach ($portal_map as $domain => $channel){
  $home_trends_map = xcache_get("ig_home_trends_map_".$channel['handle']);
  $categoryPortal = array();
  foreach ($home_trends_map as $domain => $usertrends)
  {
    $categoryid = $portal_map[$domain]['category_id'];
    $sortorder = $portal_map[$domain]['sortorder'];
    $categoryname = $portal_map[$domain]['category_name'];
    $channelname = $portal_map[$domain]['name'];
    $portalid = $portal_map[$domain]['id'];
    $categoryPortal[$sortorder][$categoryid]['name']=$categoryname;
    $i=1;
    if ($usertrends!='' && !empty($usertrends))
    {
    ?>
      <div class='left' style="margin:0px 10px 20px 10px; width:200px; height: 280px; border: 1px solid #eee; display: none;font-size:14px;" id="<?=$portalid?>_trends" disp="false">
      <div align="center" style="padding:10px 0px 10px 0px;border-bottom: 1px solid #eee;margin-bottom:5px; font-size:16px;"><a href="http://<?=$domain?>" style="color:#FFD800; "><?=$channelname?></a></div>
      <?php 
      foreach ($usertrends as $trend)
      {
        $encodedPhrase = urlencode(strtolower($trend["phrase"]));
        echo "<div style='padding:3px;'><a href='http://".$domain."/trends/$encodedPhrase/'>$i. ".$trend["phrase"]."</a> &nbsp;&nbsp;<a rel='nofollow' href='http://inagist.com/search?q=$encodedPhrase'>&raquo;</a></div>";
        $i++; 
      }
      ?>
      </div>
    <?php
    } 
  }
}
?>
</div>
<script language="javascript">
function showtrends(e)
{
	var id = '#'+ e.value + "_trends";
	if ($(id).attr("disp")=="false")
	{
		$(id).effect("highlight", {color:"#333333",mode:"show"}, 300);
		$(id).attr("disp","true");
	}
	else
	{
		$(id).effect("highlight", {color:"#333333",mode:"hide"}, 300);
		$(id).attr("disp","false");
	}	
}
</script>
