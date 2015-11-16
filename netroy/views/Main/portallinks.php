<?php
  global $portal_map,$domain;
  $isPortal = false;
  foreach ($portal_map as $portal)
  {
  	if ($portal['handle']==$user)
    	$isPortal = true;
  }
?>
<div id="sidebar">
<?php 
	$selected = "";
    if($_GET['user']==$_SESSION['user_id'] && $_GET['user']!='')
    	$selected=" selected ";
?> 
<?php if ($_SESSION['user_id']!='') { ?>	
<ul class="navigation">
	<li class="nav-items <?=$selected?>">    	
		<span><a href="http://inagist.com/<?=$_SESSION['user_id']?>">Your Trending Tweets</a></span>
	</li>
	<li class="nav-items" id="livestream_link" style="display:none;">    	
		<span><a href="http://inagist.com/<?=$_SESSION['user_id']?>/live" target="_blank">Live Stream</a></span>
	</li>
</ul>
<?php }
/* Start trends */
if ($usertrends!='' && !empty($usertrends) && isset($_GET['showtrends'])){?>
<div style="margin-bottom:10px; padding-top:8px;">Hot Topics <span id="ex_trend" class="expandcollapse"></span></div>
<ul class="navigation" id="trend_navigation">
	<?php
	foreach ($usertrends as $trend)	
	{
		$selected="";
		if($selectedtrend==$trend["phrase"])
    		$selected=" selected ";
		?>		
		<li class="nav-items <?=$selected?>">
			<?php if ($isPortal){?>
			<span><a href="/trends/<?=urlencode(strtolower($trend["phrase"]))?>?showtrends"><?=$trend["phrase"]?></a></span>			
			<?php }else{?>
			<span><a href="/<?=$user?>/trends/<?=urlencode(strtolower($trend["phrase"]))?>?showtrends"><?=$trend["phrase"]?></a></span>
			<?php }?>				
		</li>
	<?php 
	}
?>
</ul>
<?php }

/* End Trends */
?>

<?php
/* Start Lists */ 
if ($userlist!='' && !empty($userlist)){?>
<div style="margin-bottom:10px; padding-top:8px;">Lists <span id="ex_list" class="collapseexpand"></span></div>
<ul class="navigation" id="list_navigation" style="display: none;">
	<?php
	$listViewed=false;
	foreach ($userlist as $list)	
	{
    	$selected = "";$limit="";
    	if ($isPortal){
			if($selectedlist==("/".$user."/".$list["slug"]))
			{
	    		$selected=" selected ";
	    		$listViewed=true;
			}	
	    	?>
			<li class="nav-items <?=$selected?>">
				<span><a href="/list/<?=$list["slug"]?>" user="<?=$list['user']?>"><?=$list["name"]?></a></span>				
			</li>
		<?php 
    	}
    	else{
			if($selectedlist==("/".$list['user']."/".$list["slug"]))
			{
	    		$selected=" selected ";
	    		$listViewed=true;
			}	
	    	if ($list['limit']!='')
	    		$limit="?limit=".$list['limit'];	
			?>
			<li class="nav-items <?=$selected?>">
				<span><a href="http://<?=$domain?>/<?=$user?>/list/<?=($list['user']."/".$list["slug"]).$limit?>" user="<?=$list['user']?>"><?=$list["name"]?></a></span>
			</li>
		<?php 
    	}
	}	
	?>
</ul>

<?php 	
	if ($listViewed || $isPortal){
		//If list is being viewed then do not collapse the list
		echo '
		<script language="javascript">
		$(function(){
  			$("#ex_list").removeClass("collapseexpand");
			$("#ex_list").addClass("expandcollapse");
			$("#list_navigation").show();
		});
  		</script>
  		';	
	}
}
/* End Lists */
?>

<?php 
/*Friend's Channels*/
$display_portals=array();
if ($_SESSION['user_id']!=$user)
{
	if($_SESSION['user_frend_channel'][$user]=='')
	{
		$db = new DB();
    	$rows = $db->query("SELECT channels from user_custom where user_id='".$user."'");
    	$selChannels=explode(",",$rows[0]['channels']);
	}
	else{
		$selChannels=explode(",",$_SESSION['user_frend_channel'][$user]);	
	}
	
	foreach ($selChannels as $portalid)
	{
	  	foreach ($portal_map as $portaldomain => $portaldetails)
	   	{
	  		if ($portaldetails['id']==$portalid)
	   		{
	   			array_push($display_portals,$portaldomain);
	   			break;
	   		}	
	   	}
	}

	if ($display_portals!='' && !empty($display_portals))
  	{
		?>
		<div style="margin-bottom:10px; padding-top:8px;"><?=$user?>'s Channels <span id="ex_frchannel" class="expandcollapse"></span></div>
		<ul class="navigation" id="frchannel_navigation">		
		<?php
		foreach($portal_map as $portaldomain => $portal)
		{
			if ($portal["name"]!='' && in_array($portaldomain, $display_portals))
			{?>
				<li class="nav-items <?=$selected?>">    	
					<a href="http://<?=$portaldomain?>/"><span><?=$portal["name"]?></span></a>
				</li>
			<?php 	
			}
		}?>
		</ul>
		<?php			   		
  	}
}
?>

<div style="margin-bottom:10px; padding-top:8px;">Special Channels</div>
<ul class="navigation" id="spchannel_navigation">
<?	
  $special_channels = xcache_get("ig_home_special_channels");
  foreach($special_channels as $channel_id => $channel_description){	
	?>
    <li class="nav-items">    	
		<a href="http://inagist.com/<?=$channel_id?>" name="<?=$channel_id?>" title="<?=$channel_description["long_description"]?>"><span><?=$channel_description["short_description"]?></span></a>
	  </li>
<? } ?>
</ul>

<div style="margin-bottom:10px; padding-top:8px;">Channels <span id="ex_channel" class="expandcollapse"></span></div>
<ul class="navigation" id="channel_navigation">
<?	
  $display_portals=array();
  if(isset($_SESSION['user_id']) && $_SESSION['user_sel_channels']==''){  	
	$db = new DB();
    $rows = $db->query("SELECT channels from user_custom where user_id='".$_SESSION['user_id']."'");
    $selChannels=explode(",",$rows[0]['channels']);
  }
  else{
  	$selChannels=explode(",",$_SESSION['user_sel_channels']);
  }	
    
  foreach ($selChannels as $portalid)
  {
  	foreach ($portal_map as $portaldomain => $portaldetails)
   	{
  		if ($portaldetails['id']==$portalid)
   		{
   			array_push($display_portals,$portaldomain);
   			break;
   		}	
   	}
  }
  
  $i=0;
  if ($display_portals=='' || empty($display_portals))
  {
  	$display_portals = array("worldnews.inagist.com","worldbiz.inagist.com",
    	"geek.inagist.com","scitech.inagist.com","soccer.inagist.com");  		
  }
  	
	//Ip to Geo Location ..  
  	if ($_SESSION['ipcountry']=='' || $_SESSION['ipcountry']==null || $_GET['ip']!='')
  	{
  		$gi = geoip_open("config/GeoIP.dat",GEOIP_STANDARD);
  		$ip = (isset($_GET['ip']))?$_GET['ip']:$_SERVER['REMOTE_ADDR'];
  		$_SESSION['ipcountry']=strtolower(geoip_country_name_by_addr($gi, $ip).".inagist.com");
  	}
  	$ipcountry = $_SESSION['ipcountry'];
  
  foreach($portal_map as $portaldomain => $portal){	  
    $selected = "";
    if($portaldomain==$_SERVER["SERVER_NAME"] || $_GET['user']==$portal["handle"])
    	$selected=" selected ";
    if (($portal["name"]!='' && in_array($portaldomain, $display_portals))||($selected!='' || $portaldomain==$ipcountry)){
	?>
    <li class="nav-items <?=$selected?>">    	
		<a href="http://<?=$portaldomain?>/" user="<?=$portal["handle"]?>" name="<?=$portal["name"]?>"><span><?=$portal["name"]?></span></a>
	</li>
  <?php 
    }
  }
  ?>	
</ul>
<div>
<a href="http://<?=$domain?>/all">View All Channels</a>
</div>
</div>
<script language="javascript">
$(function(){
	if ("WebSocket" in window) {
		$("#livestream_link").show();
	}	
});
</script>
