<div id="tweetlabel">
  Choose your favorite channels
</div>
<div id="settings">
<form action="" method="POST">
<input type="hidden" name="r" value="main/settings"/>
<?php 
  global $portal_map,$domain;
  if(isset($_SESSION['user_id']) && $_SESSION['user_sel_channels']==''){
  	$db = new DB();
  	$rows = $db->query("SELECT channels from user_custom where user_id='".$_SESSION['user_id']."'");
  	$selChannels=explode(",",$rows[0]['channels']);
  	$rows = $db->query("SELECT notification_id from credentials where user_id='".$_SESSION['user_id']."'");
  	$mailid = $rows[0]['notification_id'];
  }
  else{
  	$selChannels=explode(",",$_SESSION['user_sel_channels']);
  	$db = new DB();
  	$rows = $db->query("SELECT notification_id from credentials where user_id='".$_SESSION['user_id']."'");
  	$mailid = $rows[0]['notification_id'];
  }		
  
  $col1="";$col2="";$col3="";
  $categoryPortal = array();
  $i=0;
  foreach ($portal_map as $domain => $portal)
  {
  	$selected = "";
  	if (in_array($portal['id'],$selChannels))
  		$selected = " checked='checked' ";
  	$op=" <input type='checkbox' name='channels[]' value='".$portal["id"]."' id='$domain' $selected/><label for='$domain'>".$portal["name"]."</label><br/> ";
  	$categoryPortal[$portal['category_id']]['channels'].=$op;
  	$categoryPortal[$portal['category_id']]['name']=$portal['category_name'];
  	$i++;    
  }
  echo "<ul style='margin-left:0px;'>";
  foreach ($categoryPortal as $categoryid => $channel)
  {
  	echo '<li style="display:inline-block;vertical-align:top;width:180px;"><div class="columns">
  		  <div class="categoryname">'.$channel['name'].'</div>
  		  <div class="channels">	
  		  '.$channel['channels'].'
  		  </div></div></li>';
  }
  echo "</ul>";
?> 
<div class="clear"></div>
<div class="channels" style="padding-left:10px;" >
	Enter your Jabber/Gmail/Notify.io id : <input type="text" maxlength="300" name="mailid" size="30" value="<?=$mailid?>"/>
</div>
<div id="controls">
	<input type="submit" name="subBtn" value="Save"/>
	<input type="reset" name="resetBtn" value="Reset"/>
</div> 
</form>
</div>
