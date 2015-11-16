<?php
  
if(!defined("MAP_INITIALIZED")){
  $mapName = "ig_portals";
  $usermapName = "ig_usermap";
  $portalListMapName = "ig_portal_list_map";
  $publisherChannelMapName = "ig_publisher_list_map";

  $portal_map = xcache_get($mapName);
  $user_map = xcache_get($usermapName);
  $portal_list_map = xcache_get($portalListMapName);
  $publisher_list_map = xcache_get($publisherChannelMapName);
  
  if($publisher_list_map == null || empty($publisher_list_map) || isset($_GET['reloadmap'])){

  	$publisher_map = array();
  	
  	$db = new DB();
    $rows = $db->query(" SELECT `id` , `pubid` , `channels` , `updatedon` FROM `publisher_trends` LIMIT 0 , 30 ;");

    foreach($rows as $row)
    	$publisher_map[$row["pubid"]]=$row;
    
    xcache_set($publisher_list_map,$publisher_map);
  }
  
  if($portal_map == null || empty($portal_map) || isset($_GET['reloadmap'])){
    $portal_map = array();
    $user_map = array();

    $db = new DB();
    $rows = $db->query("select p.`id`,`name`,`subdomain`,`handle`,`limit`,`title`,`keywords`,`description`,`show_list`,`category_name`,`category_id`,`sortorder` from portals p ,portal_category pc where pc.id=p.category_id order by `name`;");

    foreach($rows as $row){
      $subdomain = $row["subdomain"];
      $name = ucwords($row["name"]);
      $handle = $row["handle"] = strtolower($row["handle"]);
      unset($row["subdomain"]);
      if($row["title"]==NULL) $row["title"] = "$name - news, updates and trends";
      if($row["keywords"]==NULL) $row["keywords"] = "$name,news,trends,updates,$handle";
      if($row["description"]==NULL) $row["description"] = "Latest $name news, trends, stories and updates in a gist @ inagist.com";
      $portal_map[$subdomain] = $row;
      $user_map[$handle] = $subdomain;
    }
    xcache_set($mapName,$portal_map);
    xcache_set($usermapName,$user_map);    
  }
  if($portal_list_map == null || empty($portal_list_map) || isset($_GET['reloadmap'])){
  	global $twitter_oauth, $twitter_api, $twitter_key, $twitter_sec,$twitter_auth_return;
  	$portal_list_map = array();
  	
  	/*$lists_map=array();
  	$db = new DB();
    $rows_lists = $db->query("select `list_id`,`list_name`,`limit` from lists_limit;");
    foreach ($rows_lists as $record){
      $lists_map[$record['list_id']]=$record['limit'];
      $lists_map[$record['list_name']]=$record['limit'];
    }
    $rows = $db->query("select c.`user_id`,`twitter_token`,`twitter_secret` from credentials c, portals p where p.`handle`=c.`user_id` and c.`account_type`='P' and p.`show_list`=1");
    foreach($rows as $row){
    	$json = array();
    	$twt_user_list_api="http://api.twitter.com/1/".$row['user_id']."/lists.json";	 		
	 	//$twt_user_follow_list_api="http://api.twitter.com/1/".$row['user_id']."/lists/subscriptions.json";	
    	try {
    		//$oauth = new TwitterOAuth($twitter_key, $twitter_sec);
    		$oauth = new OAuth($twitter_key, $twitter_sec, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI); 
	    	$oauth->setToken($row['twitter_token'],$row['twitter_secret']);
	    	$oauth->fetch($twt_user_list_api);
	    	$json[] = json_decode($oauth->getLastResponse(),true);
			//$oauth->fetch($twt_user_follow_list_api);
	    	//$json[] = json_decode($oauth->getLastResponse(),true);
	    	$userListsArray = array();
    		foreach ($json as $jsonop){
	 	        foreach ($jsonop['lists'] as $list)
		        {
		        	if ($list['mode']!='private')
		        	{
			        	$userList = array();
			        	$userList['name']=$list['name'];
			        	$userList['id']=$list['id'];
			        	$userList['uri']=$list['uri'];
			        	$userList['slug']=$list['slug'];
			        	$userList['user']=$list['user']['screen_name'];
			        	if ($lists_map[$list['id']]!='')
			        		$userList['limit']=$lists_map[$list['id']];
			        	array_push($userListsArray,$userList);
		        	}
		        }
          	} 
	    	$portal_list_map[$row['user_id']]=$userListsArray;		    	
		} catch(OAuthException $E) {
	    	//echo "<pre>";print_r($E);echo "</pre>";
		}
    }*/
    xcache_set($portalListMapName,$portal_list_map);    
  }
  define("MAP_INITIALIZED",true);
}
?>
