<?php

  class Main extends Controller{

    // Home Page
    public function Index($params=null){

      global $api_base,$portal_map;
       	 
      $portals = $this->Portals($params);
	  $i = 0;
      
      $noOfChannels = 5; //No. of channels to be shown.
      
      $display_portals=array();
	  if(isset($_SESSION['user_id']) && $_SESSION['user_sel_channels']==''){  	
		$db = new DB();
	    $rows = $db->query("SELECT channels from user_custom where user_id='".$_SESSION['user_id']."'");
	    $selChannels=explode(",",$rows[0]['channels']);
      $_SESSION['user_sel_channels']=$rows[0]['channels'];      	
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
	  
	  $default_portals = array("worldnews.inagist.com","worldbiz.inagist.com",
    	"geek.inagist.com","scitech.inagist.com","soccer.inagist.com","tech.inagist.com","india.inagist.com",
        "sports.inagit.com");
	  // if the user is not logged in or not customised channels then show default channels
	  if ($display_portals=='' || empty($display_portals))
	  {
	  	$display_portals = $default_portals;  		
	  }
	  else if (count($display_portals)<$noOfChannels){
	  	$i=0;	  		  	
	  	while (count($display_portals)<=$noOfChannels)
	  	{
	  		//echo "$i<pre>";echo count($display_portals);print_r($display_portals);echo "<br/>$default_portals[$i]</pre>";
	  		if (!in_array($default_portals[$i],$display_portals))
	  		{
	  				array_push($display_portals,$default_portals[$i]);
	  		}
	  		$i++;
	  	}
	  }
      
	  // ip to geo country
      if ($_SESSION['ipcountry']=='' || $_SESSION['ipcountry']==null || $_GET['ip']!='')
  	  {
  		$gi = geoip_open("config/GeoIP.dat",GEOIP_STANDARD);
  		$ip = (isset($_GET['ip']))?$_GET['ip']:$_SERVER['REMOTE_ADDR'];
  		$_SESSION['ipcountry']=strtolower(geoip_country_name_by_addr($gi, $ip).".inagist.com");
  	  }
  	  $ipcountry = $_SESSION['ipcountry'];
  	  
  	  $trendData = array();  	  
  	        
  	  $content = array();
  	  $data = array();
  	  $otherTrends = array();
      foreach($portals as $id=>$tweet){
      	$home_trends_map = xcache_get("ig_home_trends_map_".$tweet['handle']);
      	if(($i<$noOfChannels)&&
      			((in_array($tweet['portal'],$display_portals)) || ($tweet['portal']==$ipcountry)))
      	{      		
        	$tweet["i"] = $i;
        	$content[$id]=$tweet;
        	$i++;
      	}
      	if ((in_array($tweet['portal'],$display_portals)) || ($tweet['portal']==$ipcountry))
			$data[$tweet['portal']]=$home_trends_map[$tweet['portal']];      	
      	else
      		$otherTrends[$tweet['portal']]=$home_trends_map[$tweet['portal']];
      }      	
      
      //for signed users show trends from their customised channels only
      if(!isset($_SESSION['user_id'])){
      	$data=array_merge($data,$otherTrends);
      }
      
      $keywords='';
      foreach ($data as $domain => $usertrends)
      {
 		foreach ($usertrends as $trend)	
		{
			$keywords.=','.strtolower($trend["phrase"]);	
		}
      }	
      return array(
        "user"=>$user,
        "title"=>"All your news,updates and trends... In-A-Gist",
      	"keywords"=>"Inagist.com,trends,top stories,news,updates".$keywords,
        "description"=>"Get the latest news & updates around the world as it happens realtime on inagist.com",
      	"content"=>MVC::renderView("Main/homepage",array("portals"=>$content, "all_portals" => true,"trenddata"=>$data)),        
        "template"=>"mainpage"
      );
    }


    public function Indexnew($params=null){
      global $api_base,$portal_map;
      $portals = $this->Portals($params);
      $i = 0;
      $noOfChannels = 4; //No. of channels to be shown.
      $display_portals=array();
      if(isset($_SESSION['user_id']) && $_SESSION['user_sel_channels']==''){  	
        $db = new DB();
        $rows = $db->query("SELECT channels from user_custom where user_id='".$_SESSION['user_id']."'");
        $selChannels=explode(",",$rows[0]['channels']);
        $_SESSION['user_sel_channels']=$rows[0]['channels'];      	
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
	  
      $default_portals = array("worldnews.inagist.com","worldbiz.inagist.com",
        "soccer.inagist.com", "tech.inagist.com","india.inagist.com",
        "sports.inagist.com", "health.inagist.com","pinoy.inagist.com","indonesia.inagist.com",
        "london.inagist.com", "bayarea.inagist.com", "uk.inagist.com", "canada.inagist.com",
        "france.inagist.com", "singapore.inagist.com", "bargains.inagist.com",
        "nyc.inagist.com", "cricket.inagist.com",  "australia.inagist.com",
        "airports.inagist.com", "airlines.inagist.com", "travel.inagist.com");
	  // if the user is not logged in or not customised channels then show default channels
	  if ($display_portals=='' || empty($display_portals))
	  {
	  	$display_portals = $default_portals;  		
	  }
	  else if (count($display_portals)<$noOfChannels){
	  	$i=0;	  		  	
	  	while (count($display_portals)<=$noOfChannels)
	  	{
	  		//echo "$i<pre>";echo count($display_portals);print_r($display_portals);echo "<br/>$default_portals[$i]</pre>";
	  		if (!in_array($default_portals[$i],$display_portals))
	  		{
	  				array_push($display_portals,$default_portals[$i]);
	  		}
	  		$i++;
	  	}
	  }
      
	  // ip to geo country
      if ($_SESSION['ipcountry']=='' || $_SESSION['ipcountry']==null || $_GET['ip']!='')
  	  {
  		$gi = geoip_open("config/GeoIP.dat",GEOIP_STANDARD);
  		$ip = (isset($_GET['ip']))?$_GET['ip']:$_SERVER['REMOTE_ADDR'];
  		$_SESSION['ipcountry']=strtolower(geoip_country_name_by_addr($gi, $ip).".inagist.com");
  	  }
  	  $ipcountry = $_SESSION['ipcountry'];
  	  
  	  $trendData = array();  	  
  	    	        
  	  $content = array();
  	  $data = array();
  	  $otherTrends = array();
      foreach($portals as $id=>$tweet){
      	$home_trends_map = xcache_get("ig_home_trends_map_".$tweet['handle']);
      	if(($i<$noOfChannels)&&
      			((in_array($tweet['portal'],$display_portals)) || ($tweet['portal']==$ipcountry)))
      	{      		
        	$tweet["i"] = $i;
        	$content[$id]=$tweet;
        	$i++;
        	$data[$tweet['portal']]=$home_trends_map[$tweet['portal']];
      	}
      	else
      		$otherTrends[$tweet['portal']]=$home_trends_map[$tweet['portal']];
      }      	
      
      //for signed users show trends from their customised channels only
      if(!isset($_SESSION['user_id'])){
      	$data=array_merge($data,$otherTrends);
      }
      $specialChannels = xcache_get("ig_home_special_channels");
      $latestTrends = xcache_get("ig_latest_trends");
      $popularTweets = xcache_get("ig_popular_tweets");

      return array(
        "user"=>$user,
        "title"=>"All your news,updates and trends... In-A-Gist",
        //"trends"=>MVC::renderView("Main/trends",array("trenddata"=>$data)),
      	"content"=>MVC::renderView("Main/homepagenew",
          array("display_portals"=>$display_portals, 
                "all_portals" => true,
                "trenddata"=>$data, 
                "portals" => $portals, 
                "specialchannels"=>$specialChannels,
                "latest_trends" => $latestTrends,
                "popular_tweets" => $popularTweets)
        ),
        "hideheader"=>true,
        "template"=>"mainpage_vd15"
      );
    }
    
    public function demo($params=null)
    {
	  global $portal_map,$user_map;
	  $enable_stream=false; 	
      
	  if (isset($params['cricket']))
	  {
		  $params['user'] = $user ="cricketgist";
		  $params['list']="/cricketgist/cricketers";
	      $right = MVC::renderView("Main/demotweets",array("tweets"=>$this->Tweets($params),"labeloveride"=>"Cricketers"));
	      $params['list']="/cricketgist/cricket-news";
	      $left = MVC::renderView("Main/demotweets",array("tweets"=>$this->Tweets($params),"labeloveride"=>"Cricket News"));
	      
	      $home_trends_map = xcache_get("ig_home_trends_map_".$user);
	  
	      if ($home_trends_map[$user_map[$user]]!='' && $home_trends_map[$user_map[$user]]!=null)
	  		$trendData=$home_trends_map[$user_map[$user]];
	  	  else	
	  		$trendData = $this->getTrends(array("user"=>$user));
	  	$channelName = 'Cricket';	
	  }
      else if (isset($params['bollywood']))
	  {
		  $params['user'] = $user ="bollygist";
		  $params['list']="/bollygist/bolly-celebs";
	      $right = MVC::renderView("Main/demotweets",array("tweets"=>$this->Tweets($params),"labeloveride"=>"Bollywood Stars"));
	      $params['list']="/bollygist/bolly-news";
	      $left = MVC::renderView("Main/demotweets",array("tweets"=>$this->Tweets($params),"labeloveride"=>"Bollywood News"));
	      
	      $home_trends_map = xcache_get("ig_home_trends_map_".$user);
	      
	      if ($home_trends_map[$user_map[$user]]!='' && $home_trends_map[$user_map[$user]]!=null)
	  		$trendData=$home_trends_map[$user_map[$user]];
	  	  else	
	  		$trendData = $this->getTrends(array("user"=>$user));	
	  	$channelName = 'Bollywood';
	  }
      else if (isset($params['worldnews']))
	  {
		  $params['user'] = $user ="worldnewsgist";
		  $params['list']="/worldnewsgist/global-opeds";
	      $right = MVC::renderView("Main/demotweets",array("tweets"=>$this->Tweets($params),"labeloveride"=>"Analysis / OPED"));
	      $params['list']="/worldnewsgist/global-news";
	      $left = MVC::renderView("Main/demotweets",array("tweets"=>$this->Tweets($params),"labeloveride"=>"World News"));
	      
	      $home_trends_map = xcache_get("ig_home_trends_map_".$user);
	      
	      if ($home_trends_map[$user_map[$user]]!='' && $home_trends_map[$user_map[$user]]!=null)
	  		$trendData=$home_trends_map[$user_map[$user]];
	  	  else	
	  		$trendData = $this->getTrends(array("user"=>$user));	
	  	$channelName = 'World news';
	  }
      else if (isset($params['fashion']))
	  {
		  $params['user'] = $user ="fashiongist";
		  $params['list']="/fashiongist/fashion-mags";
	      $right = MVC::renderView("Main/demotweets",array("tweets"=>$this->Tweets($params),"labeloveride"=>"Fashion News"));
	      $params['list']="/fashiongist/fashion-brands";
	      $left = MVC::renderView("Main/demotweets",array("tweets"=>$this->Tweets($params),"labeloveride"=>"Fashion Brands"));
	      
	      $home_trends_map = xcache_get("ig_home_trends_map_".$user);
	      
	      if ($home_trends_map[$user_map[$user]]!='' && $home_trends_map[$user_map[$user]]!=null)
	  		$trendData=$home_trends_map[$user_map[$user]];
	  	  else	
	  		$trendData = $this->getTrends(array("user"=>$user));	
	  	$channelName = 'Fashion';
	  }
      else if (isset($params['takshashila']))
	  {
		  $params['user'] = $user ="takshashilagist";
		  $params['list']="/takshashilagist/ini-bloggers";
	      $right = MVC::renderView("Main/demotweets",array("tweets"=>$this->Tweets($params),"labeloveride"=>"Takshashila bloggers"));
	      $params['list']="";
	      $left = MVC::renderView("Main/demotweets",array("tweets"=>$this->Tweets($params),"labeloveride"=>"Takshashila"));
	      
	      $home_trends_map = xcache_get("ig_home_trends_map_".$user);
	      
	      if ($home_trends_map[$user_map[$user]]!='' && $home_trends_map[$user_map[$user]]!=null)
	  		$trendData=$home_trends_map[$user_map[$user]];
	  	  else	
	  		$trendData = $this->getTrends(array("user"=>$user));	
	  	$channelName = 'Takshashila';
	  	$enable_stream = true;
	  	
	  }	  
	  else
	  {
	  	  $params['user'] = $user ="soccergist";
		  $params['list']="/soccergist/soccer-stars";
	      $right = MVC::renderView("Main/demotweets",array("tweets"=>$this->Tweets($params),"labeloveride"=>"Soccer Players"));
	      $params['list']=null;
	      $left = MVC::renderView("Main/demotweets",array("tweets"=>$this->Tweets($params),"labeloveride"=>"Soccer News"));
	      
	      $home_trends_map = xcache_get("ig_home_trends_map_".$user);
	      
	      if ($home_trends_map[$user_map[$user]]!='' && $home_trends_map[$user_map[$user]]!=null)
	  		$trendData=$home_trends_map[$user_map[$user]];
	  	  else	
	  		$trendData = $this->getTrends(array("user"=>$user));
	  	$channelName = 'Soccer';	
      }    
      
	  $trendData = Utils::picksTop($trendData,10);      
      
	  // trends as Keywords for SEO purpose
      $keywords = "";
      foreach ($trendData as $trend)
      	$keywords.=",".$trend['phrase'];
      	
	  $arr = array(
        "user"=>$user,
	    "list"=>$selectedList,
	    "hours"=>$params['hours'],
        "title"=>"Demo Page : inagist.com ",
	    "left"=>$left,
        "right"=>$right,
	    "trends"=>$trendData,
	    "channelname"=>$channelName,
	  	"enable_stream"=>$enable_stream,
	    "template"=>"demo"
      );

      if(isset($_REQUEST["title"])) $arr["title"] = $_REQUEST["title"];
      if(isset($_REQUEST["keywords"])) $arr["keywords"] = $_REQUEST["keywords"];
      if(isset($_REQUEST["description"])) $arr["description"] = $_REQUEST["description"];
      
      $arr["keywords"].=$keywords;
      
      return $arr;
    	
    }
    
    public function prefill($params=null)
    {
		global $api_base,$lists_map;
      	$user = (!isset($params['user']))?"chjeeves":$params['user'];

      	$query = (!isset($params['query']))?"india":$params['query'];	
	    $urls = array();
	    $data = array();
	    $urls['twit']="http://search.twitter.com/search.json?q=$query&rpp=10&paginate=false";
	    foreach($urls as $type=>$url)
	    {
	    	try{
	          $content = file_get_contents($url);
	          $tweets = json_decode($content,true);
	          foreach($tweets['results'] as $tweet){
	          	$tweet["type"] = $type;
	          	$tweet["created_at_act"] = $tweet["created_at"];
	            $tweet["created_at"] = Utils::timeAgo($tweet["created_at"],1);
              $tweet["actual_text"] = $tweet["text"];
	            $tweet["raw_text"] = Utils::removeSpecialChar(Utils::removeUrls($tweet["text"]));
	            $tweet["text"] = Utils::linkify($tweet["text"]);
	            $tweet["user"]["id"] = $tweet["id"];
	            $tweet["user"]["id_str"] = $tweet["id_str"];
	            $tweet["user"]["screen_name"] =$tweet["from_user"];
	            $tweet["user"]["profile_image_url"] = $tweet["profile_image_url"];
	            $data[$tweet["id"]] = $tweet;
	        }
	        }catch(Exception $e){
	          var_dump($e);exit;
	        }
	 	}
	 	return $data;
    }

    public function microportal($params = null){
      $arr = array(
        "template" => "business"
      );
      return $arr;
    }
    
    public function ui_v3($params = null){
      $arr = array(
        "template" => "ui_v3"
      );
      return $arr;
    }
    
    public function tweementry($params = null){
    	$user = (!isset($params['user']))?"dlfipl":$params['user'];
    	$limit = (!isset($params['limit']))?2:$params['limit'];
      $suffix = implode("&",array("userid=$user","limit=$limit","rate_tweets=1"));
	    $url = "http://inagist.com/api/v1/get_top_retweets?$suffix";
      $search_url = "http://inagist.com/api/v1/search?userid=$user";
      $content = "{'status': 'Initial load failed'}";	
      $match_details = "{'status': 'Not Available'}";
      $data = array();
      try{
        $content = file_get_contents($url);
        $tweets = json_decode($content,true);
        foreach($tweets as $tweet){
          $data[$tweet["id_str"]] = $tweet;
        }
        if (count($data) < 15){
          $content = file_get_contents($search_url);
          $tweets = json_decode($content,true);
          foreach($tweets as $tweet){
            $data[$tweet["id_str"]] = $tweet;
          }
        }
        ksort($data);
        $content = json_encode($data);
        $match_details = file_get_contents("http://inagist.com/api/v1/tweementry_feed");
      }catch(Exception $e){
        var_dump($e);
      }

      $arr = array(
        "template" => "tweementry",
        "content" => $content,
        "match_details" => $match_details
      );
      return $arr;
    }
    
  	public function eventspage($params=null)
    {
	  global $portal_map,$user_map,$api_base;

	  $user = $params['user'];
	  $right = MVC::renderView("Main/demotweets",array("tweets"=>$this->Tweets($params),"labeloveride"=>$params['right_label']));
      $params['list']="";
      $params['limit']=0;
      $left = MVC::renderView("Main/demotweets",array("tweets"=>$this->Tweets($params),"labeloveride"=>$params['left_label']));
      $prefill = MVC::renderView("Main/demotweets",array("tweets"=>$this->prefill($params),"donotshowlabel"=>true));
  		$trendData = $this->getSimpleTrends(array("user"=>$user));
  	  if (empty($trendData) || $trendData=='' || $trendData==null || count($trendData)<4)
  	  {
  	  	$trendData=array();
        $args = array("userid=$user","limit=0","hours=24");
      	$suffix = implode("&",$args);
      	$url = "$api_base/get_top_phrases?$suffix";
      	
      	try{
	        $trendcontent = file_get_contents($url);
	        $trends = json_decode($trendcontent,true);
	        foreach($trends as $t)
	        {
	        	$single_trend=array();
	        	$single_trend['phrase']=$t['tag'];
	        	$trendData[]=$single_trend;
	        }
      	}	
	    catch(Exception $e){
	      	//var_dump($e);exit;
	    }    	
  	  }	
	  $channelName = $params['name'];
  	  $enable_stream = true;
	  
	  $trendData = Utils::picksTop($trendData,20);      
      
	  // trends as Keywords for SEO purpose
      $keywords = "";
      foreach ($trendData as $trend)
      	$keywords.=",".$trend['phrase'];
      	
	  $arr = array(
        "user"=>$user,
        "partner_id" => Ptwitter::getPartnerId($params['client']),
	    "list"=>$selectedList,
	    "title"=>$params['name']." : powered by inagist.com ",
		"keywords"=>"Inagist.com, ".$params['name'],
	    "description"=>"Live stream of tweets of ".$params['name'].". Powered by inagist.com",		  
	    "left"=>$left,
        "right"=>$right,
	  	"prefill"=>$prefill,
	    "trends"=>$trendData,
	    "channelname"=>$channelName,
	  	"enable_stream"=>$enable_stream,
	  	"params"=>$params,
	    "template"=>"events"
      );

      if(isset($_REQUEST["title"])) $arr["title"] = $_REQUEST["title"];
      if(isset($_REQUEST["keywords"])) $arr["keywords"] = $_REQUEST["keywords"];
      if(isset($_REQUEST["description"])) $arr["description"] = $_REQUEST["description"];
      $arr["keywords"].=$keywords;
      return $arr;
    }
    
    public function sitemap($params=null)
    {
    	global $portal_map;
    	
    	foreach ($portal_map as $domain => $channel)    	
    	{
    		echo "<url><loc>http://inagist.com/".$channel['handle']."</loc><priority>0.5</priority><changefreq>hourly</changefreq></url>\n";
    			
    	}
    }
    	
    // Reload the trends map using cron 
    // This function is called by Cron
    public function reloadTrends($params=null)
    {
    	global $portal_map, $api_base;
    	
      $url = "$api_base/get_top_channels";
      $specialChannels=array();
      try{
        $specialChannelsJson = file_get_contents($url);
        $specialChannels = json_decode($specialChannelsJson,true);
        xcache_set("ig_home_special_channels", $specialChannels);
      }	
      catch(Exception $e){
        //var_dump($e);exit;
      }

    	$nooftrends =(isset($params['nooftrends']))?$params['nooftrends']:8;
    	foreach ($portal_map as $domain => $channel)    	
    	{
    		$home_trends_map = xcache_get("ig_home_trends_map_".$channel['handle']);
    		if ($home_trends_map == '' || $home_trends_map == null)
    			$home_trends_map = array();
    			
    		$trendData = $this->getTrends(array("user"=>$channel['handle'],"curl"=>true, "archive" => false));
    		//$trendData = array_merge($trendData,$home_trends_map[$domain]);
    		$trendData = Utils::arrayUniqeBySubKey($trendData,'phrase');
    		$trendData = Utils::picksTop($trendData,$nooftrends);
    		$home_trends_map[$domain]=$trendData;    	
    		
    		if (!empty($home_trends_map) && $home_trends_map!=null && $home_trends_map!=''){
    			$home_trends_map['updatedtime']=time();
    			xcache_set("ig_home_trends_map_".$channel['handle'],$home_trends_map);
    		}		
    	}
    		
    }
    
    public function getSimpleTrends($params=null) {
    	global $api_base;
    	$user=$params['user'];
      $args = array("userid=$user","limit=0","summarize=1", "type=phrase");
      $suffix = implode("&",$args);
      $url = "$api_base/get_top_trends?$suffix";
      
      $trendData=array();
      try{
        $trendcontent = file_get_contents($url);
        $trends = json_decode($trendcontent,true);
        foreach($trends as $description => $phrasesArr){
          $single_trend=array('phrase' => "", 'count' => 0);
          foreach($phrasesArr as $phrase => $count) {
            if ($count > $single_trend['count']){
              $single_trend['phrase']=$phrase;
              $single_trend['count'] = $count;
            }
          }
          $trendData[]=$single_trend;
        }
      }	
      catch(Exception $e){
        //var_dump($e);exit;
      }
      return $trendData;
    }
    	
    //getTrends Using API and sort it according to score
    public function getTrends($params=null)
    {
    	global $api_base;
    	
    	$data = array();
    	$user=$params['user'];
      $args = array("userid=$user","limit=1","type=phrase","summarize=0");
      if (isset($params['rate_tweets']) && !$params['rate_tweets'])
        $args[] = "rate_tweets=0";
      else
        $args[] = "rate_tweets=1";

      if (isset($params['archive']) && !$params['archive'])
        $args[] = "archive=0";
      else
        $args[] = "archive=1";

      $suffix = implode("&",$args);
      $url = "$api_base/get_top_trends?$suffix";
		
      try{
	        $trendcontent = file_get_contents($url);
	        $trends = json_decode($trendcontent,true);
	        
	        foreach($trends as $description => $phrasesArr){
	        	$trendArr = Utils::pickBestTrend($phrasesArr);		        	
	        	foreach($trendArr as $phrase => $count){
	        		$item = array();
	        		$item['desc']=$description;
	        		$item['phrase']=$phrase;
	        		$item['preview']=null;
	        		$item['preview_bck']=null;
	        		// if $count is array then its tweets of the trend
	        		if (is_array($count)){
	        			$item['trend_tweets']=Utils::picksTop($count,2);
	        			$i=0;
	        			foreach ($count as $currentTweet)
	        			{
	        				if ($i==0)
		        				$currentTweet['tweeturl']=Utils::extractUrls($currentTweet['text']);
	        				$i++;    
                }
	        		}
	        		else	
	        			$item['count']=$count;	        			        		
	        		$data[]=$item;
	        	}//foreach	          
	       	}//foreach
      }	
	    catch(Exception $e){
	      	//var_dump($e);exit;
	    }
      usort($data,compare);
      return $data;    
    }
    
    // show all trends as Tag cloud
    public function AllTrends($params=null)
    {
		global $api_base,$portal_map;
		
      	$content=MVC::renderView("Main/alltrendslist");
  	  	return array(
	        "user"=>$user,
	        "title"=>"All trends : ..:: inagist ::.. ",
	        "content"=>"<br/>".$content,
	      	"template"=>"mainpagenew"
      	);  
    }
    
    //About Us Page
    public function About($params=null){
		$about = MVC::renderView("Main/about",array());
		return array("title"=>"All your news,updates and trends... In-A-Gist","content"=>$about,"template"=>"mainpage");
    }
    
    //Services Page
    public function services($params=null){
		$about = MVC::renderView("Main/services",array());
		return array(
      "title"=>"Create Custom Twitter Micro Sites - In-A-Gist",
      "keywords" => "twitter streaming portal, real time event portal, real time tweet dashboard",
      "description" => "Conducting an event or a campaign and would like to have a single destination where you can aggregate all the buzz? Let us help you out. We offer you a hosted microsite to follow the conversations around your event.",
      "content"=>$about,
      "template"=>"mainpage");
    }
    
    //TOS Page
    public function terms($params=null){
		$about = MVC::renderView("Main/terms",array());
		return array(
      "title"=>"In-A-Gist - Terms Of Service",
      "keywords" => "inagist",
      "description" => "In-A-Gist - Terms of Service",
      "content"=>$about,
      "template"=>"mainpage");
    }
    
    // All Channels page
    public function All($params=null){
	  global $api_base,$portal_map;	
      $portals = $this->Portals($params);
      $cloud = new wordCloud();
      $defaultSelection = "";
  	  foreach($portals as $id=>$tweet){
  	  	$portal = $portal_map[$tweet["portal"]];
  	  	if ($portal["name"]!='')
  	  	{
  	  		if ($defaultSelection=='')
  	  		{
  	  			$defaultSelection=$tweet["handle"];
  	  			$cloud->addWord(array('word' => $portal["name"], 'size' => 1+(($tweet['retweets']+$tweet['mention']+$tweet['count'])%4),'url'=>"#",'portaluser'=>$tweet['handle'],'selected'=>' selected'));
  	  		}
  	  		else	
  	  			$cloud->addWord(array('word' => $portal["name"], 'size' => 1+(($tweet['retweets']+$tweet['mention']+$tweet['count'])%4),'url'=>"#",'portaluser'=>$tweet['handle']));
  	  	}	
  	  }
  	  $myCloud = $cloud->showCloud('array');
  	  
  	  $content=MVC::renderView("Main/allportals1",array("allportals"=>$this->AllPortals(array("user"=>$defaultSelection)),"portal"=>$defaultSelection));
  	  return array(
        "user"=>$user,
        "title"=>"All channels : ..:: inagist ::.. ",
        "content"=>"<br/>".$content,
      	"template"=>"mainpagenew"
      	);
    }
    
  	// All Languages Channels page
    public function langall($params=null){
	  global $api_base,$portal_map;	
      $content=MVC::renderView("Main/alllang");
  	  return array(
        "user"=>$user,
        "title"=>"All channels : ..:: inagist ::.. ",
        "content"=>"<br/>".$content,
      	"template"=>"mainpagenew"
      	);
    }
    
    // For all chanellpage
    public function AllPortals($params=null){
		global $api_base;
    	$user = (!isset($params['user']))?"chjeeves":$params['user'];
      	$urls = array();
    	$limit = (!isset($params['limit']))?3:$params['limit'];
      	$suffix = implode("&",array("userid=$user","limit=$limit","rate_tweets=1"));
	    $urls["url"] = "$api_base/get_top_urls?$suffix";
	    $urls["tweet"] = "$api_base/get_top_tweets?$suffix";
	
	    // Populate the data my merging the two feeds
	    $data = array();
	    foreach($urls as $type=>$url){
	        try{
	          $content = file_get_contents($url);
	          $tweets = json_decode($content,true);
	          foreach($tweets as $tweet){
	          	$tweet['portalhandle']=$user;
	            $tweet["type"] = $type;
	            $tweet["created_at"] = Utils::timeAgo($tweet["created_at"],1);
              $tweet["actual_text"] = $tweet["text"];
	            $tweet["raw_text"] = Utils::removeSpecialChar(Utils::removeUrls($tweet["text"]));
	            $tweet["text"] = Utils::linkify($tweet["text"]);
	            $data[(string)$tweet["id"]] = $tweet;
	          }
	        }catch(Exception $e){
	          var_dump($e);exit;
	        }
	    }
	    krsort($data);
	    return Utils::picksTop($data,5);
    }

    //Search
    public function SearchTweets($params=null){
    	global $api_base,$lists_map,$portal_map,$user_map;
    	
    	$user = $params['user'];
    	
    	$limit = (!isset($params['limit']))?1:$params['limit'];
    	if (isset($params['q']))
    		$key=urlencode($params['q']);
    	else	
    		$key=urlencode($params['key']);
    	
    	if ($key=='' || $key==null)
    		return null;
    	else
    		$key = str_replace("_","%20",$key);
    	
      $args = array("limit=$limit","rate_tweets=1","type=phrase","summarize=0","text=$key","count=50","split=,");
      if (isset($params['start']) && $params['start']!='')
        $args[]="start=".$params['start'];
      $suffix = implode("&",$args);
      $genericsearch = "$api_base/search?$suffix";
      	
      if (isset($_SESSION['user_id']) && (!isset($params['more'])))
    		$urls["friends"] = "$api_base/search?text=$key&count=10&popular=1&split=,&userid=".$_SESSION['user_id'];
    				
      $backup_search=false;
    	
    	if ($user!='' && !isset($params['more']))
    	{	
        $args = array("userid=$user","limit=$limit","rate_tweets=1","type=phrase","summarize=0","text=$key","split=,");
        $suffix = implode("&",$args);
    		$urls["url"] = "$api_base/search?$suffix";
        $backup_search=true;	
    	}	
    	
    	if (!$backup_search)
    		$urls["url"]=$genericsearch;
    
	    $data = array();
	    $friendsdata = array();
      $keywords = array(urldecode($key));
	    foreach($urls as $type=>$url){
	      try{
	          $content = file_get_contents($url);
	          // try with userid first if it return empty then try without userid
	          if ($content=='""' && $backup_search && $type=='url')
              $content = file_get_contents($genericsearch);
	          $json = json_decode($content,true);
	          foreach($json as $tweet){
	            $tweet["type"] = $type;
	            $tweet["created_at"] = Utils::timeAgo($tweet["created_at"],1);
              $tweet["actual_text"] = $tweet["text"];
	            $tweet["raw_text"] = Utils::removeSpecialChar(Utils::removeUrls($tweet["text"]));
	            $tweet["text"] = Utils::linkify(Utils::highlightText($tweet["text"],array(urldecode($key))));
              if (is_array($tweet['key_phrases'])) 
                $keywords = array_merge($keywords, $tweet['key_phrases']);
	            if ($type=='friends')
                $friendsdata[$tweet["id"]] = $tweet;       
              else	 
	            	$data[$tweet["id"]] = $tweet;
	          }
	      }catch(Exception $e){
	    	     var_dump($e);exit;
	      }
	    }	
      usort($data,compare);
      usort($friendsdata,compare);
      $last_trend_tweet_id=Utils::minTweetid($data);    	

      //$userlists = $this->UserLists(array("user"=>$user));
      $userlists = array();
      $userTrends = $this->UserTrends($params);
      $userTrendsBalaji = array();
      
      $friendstweets='';
      if (!empty($friendsdata))
      	$friendstweets=MVC::renderView("Main/tweets",array("tweets"=>$friendsdata,"user"=>$user,"labeloveride"=>urldecode($key)." - Search Results - From Your Friends","trendkey"=>$key,"last_trend_tweet_id"=>Utils::minTweetid($friendsdata)));
      if ($backup_search)
      	$tweets = MVC::renderView("Main/tweets",array("tweets"=>$data,"user"=>$user,"labeloveride"=>urldecode($key)." - Search Results","trendkey"=>$key,"showmoreofnouser"=>true));
      else if (isset($params['json']))
      	$tweets = MVC::renderView("Main/tweets",array("tweets"=>$data,"user"=>$user,"trendkey"=>$key,"last_trend_tweet_id"=>$last_trend_tweet_id,"donotshowlabel"=>true));
      else
      	$tweets = MVC::renderView("Main/tweets",array("tweets"=>$data,"user"=>$user,"labeloveride"=>urldecode($key)." - Search Results","trendkey"=>$key,"last_trend_tweet_id"=>$last_trend_tweet_id));
      		
      $portallinks = MVC::renderView("Main/portallinks",array("portals"=>$this->Portals($params),"userlist"=>$userlists,"selectedtrend"=>urldecode($key),"user"=>$params['user'],"usertrends"=>$userTrends,"usertrendsbalaji"=>$userTrendsBalaji,"notimeline"=>true, "show_ads"=>true));
      
      $home_trends_map = xcache_get("ig_home_trends_map_".$user);
      if ($home_trends_map[$user_map[$user]]!='' && $home_trends_map[$user_map[$user]]!=null)
        $trendData=$home_trends_map[$user_map[$user]];
      else
      	$trendData = $this->getTrends(array("user"=>$user, "rate_tweets"=>false, "archive" =>false ));
      
      $trendData = Utils::picksTop($trendData,10);      
      if(array_key_exists($_SERVER["SERVER_NAME"],$portal_map)) 
      	$channelName = $portal_map[$_SERVER["SERVER_NAME"]]["name"];
      else
      	$channelName = '';
      
      $show_second_unit = true;
      if (empty($data) && empty($friendsdata)){
        $tweetList = MVC::renderView("Main/listerror",
          array("message" => "No data found. Please Try After Sometime.",
                "key" => $key));
        $show_second_unit = false;
      } else { 
        if (count($data) + count($friendsdata) < 5)
          $show_second_unit = false;
        $tweetList = $friendstweets.$tweets;
      }

      $arr = array(
        "user"=>$user,
        "list"=>$selectedList,
        "hours"=>$params['hours'],
        "noautorefresh"=>true,
        "title"=>urldecode($key)." - In-A-Gist Search",
        "keywords"=>implode(",", array_unique($keywords)),
        "navigation"=>$portallinks,
        "left"=>$tweetList,
        "right"=>$portals,
      	"trenddata"=>$trendData,
        "channelname"=>$channelName,
        "show_ads"=> true,
        "show_second_unit" => $show_second_unit,
        "show_alternate" => true,
        "noindex" => true,
        "template"=>"template"
      );
      
      if (isset($params['json'])) {
      	echo $tweets;
      	exit;
      }	
      	
      if ($params['newspaper']!='' || $params['newspaper']!=null)
      	return $data;
      else	
      	return $arr;
    }
    
    
    //Get the Trends for the user 
    public function UserTrendTweets($params=null){
    	global $api_base,$lists_map,$portal_map,$user_map;
    	
    	$user = $params['user'];
    	$limit = (!isset($params['limit']))?1:$params['limit'];
    	if (isset($params['t']))
    		$key=urlencode($params['t']);
    	else	
    		$key=urlencode($params['key']);
    	
    	if ($key=='' || $key==null)
    		return null;
    	else
    		$key = str_replace("_","%20",$key);
    	
      $searchQuery = null;
      $referringPage = parse_url($_SERVER['HTTP_REFERER']);
      if (stristr($referringPage['host'], 'google.') || 
          stristr($referringPage['host'], 'yahoo.') || 
          stristr($referringPage['host'], 'inagist.') || 
          stristr($referringPage['host'], 'bing.'))
      {
        parse_str($referringPage['query'], $queryVars);
        $searchQuery = $queryVars['q']; // This is the search term used
        $searchQuery = $searchQuery ? $searchQuery : $queryVars['p']; //yahoo sends as p
      }
    	
      if (isset($_SESSION['user_id']) && (!isset($params['more'])))
    		$urls["friends"] = "$api_base/search?text=$key&count=10&popular=1&split=,&userid=".$_SESSION['user_id'];	
      elseif (isset($searchQuery))
    		$urls["search"] = "$api_base/search?text=".urlencode($searchQuery)."&count=10&popular=1";	

      $args = array("limit=$limit","rate_tweets=1","type=phrase","summarize=0","key=$key","count=20","split=,");
      if (isset($params['start']) && $params['start']!='')
        $args[]="start=".$params['start'];
      $suffix = implode("&",$args);
			$genericsearch = "$api_base/get_top_trends?$suffix";
			
      $backup_search=false;
    	
    	if ($user!='' && !isset($params['more']))
    	{		
      		$args = array("userid=$user",
                        "limit=$limit",
                        "rate_tweets=1",
                        "type=phrase",
                        "summarize=0",
                        "key=$key", 
                        "split=,");
      		$suffix = implode("&",$args);
        	$urls["url"] = "$api_base/get_top_trends?$suffix";
        	$backup_search=true;	
    	}	
    	
    	if (!$backup_search)
    		$urls["url"]=$genericsearch;
    
	    $data = array();
	    $friendsdata = array();
      $searchData = array();
      $keywords = array(urldecode($key));
	    foreach($urls as $type=>$url){
	      try{
	          $content = file_get_contents($url);
	          // try with userid first if it return empty then try without userid
	          if ($content=='""' && $backup_search && $type=='url')
              $content = file_get_contents($genericsearch);
	          $json = json_decode($content,true);
	          if($type=='friends' || $type == 'search'){
	          	foreach($json as $tweet){
                $tweet["type"] = $type;
                $tweet["created_at"] = Utils::timeAgo($tweet["created_at"],1);
                $tweet["actual_text"] = $tweet["text"];
                $tweet["raw_text"] = Utils::removeSpecialChar(Utils::removeUrls($tweet["text"]));
                $tweet["text"] = Utils::linkify(Utils::highlightText($tweet["text"],array(urldecode($key))));
                if ($type == 'friends')
                  $friendsdata[$tweet["id"]] = $tweet;
                elseif ($type == 'search')
                  $searchData[$tweet["id"]] = $tweet;
                if (is_array($tweet['key_phrases']))
                  $keywords = array_merge($keywords, $tweet['key_phrases']);
	          	}	
	          }
	          else{
	          foreach($json as $twtsummary => $twtdata){
	          	foreach($twtdata as $twtkey => $tweets){
	          		foreach($tweets as $tweet){
			            $tweet["type"] = $type;
			            $tweet["created_at"] = Utils::timeAgo($tweet["created_at"],1);
                  $tweet["actual_text"] = $tweet["text"];
			            $tweet["raw_text"] = Utils::removeSpecialChar(Utils::removeUrls($tweet["text"]));
			            $tweet["text"] = Utils::linkify(Utils::highlightText($tweet["text"],array(urldecode($key))));
			            $data[$tweet["id"]] = $tweet;
                  if (is_array($tweet['key_phrases']))
                    $keywords = array_merge($keywords, $tweet['key_phrases']);
	          		}
	          	}
	          }
	          }
	      }catch(Exception $e){
	    	   //  var_dump($e);exit;
	      }
	    }	
      usort($data,compare);
      usort($friendsdata,compare);
      $last_trend_tweet_id=Utils::minTweetid($data);    	

      //$userlists = $this->UserLists(array("user"=>$user));
      $userlists = array();
      $userTrends = $this->UserTrends($params);
      $userTrendsBalaji = array();
      
      $friendstweets='';
      if ($friendsdata!='' and !empty($friendsdata))
      	$friendstweets=MVC::renderView("Main/tweets",
          array("tweets"=>$friendsdata,
                "user"=>$user,
                "labeloveride"=>urldecode($key)." - Top Stories - From Your Friends",
                "trendkey"=>$key,
                "source"=>"trends",
                "last_trend_tweet_id"=>Utils::minTweetid($friendsdata)));
     
      $searchTweets = '';
      if ($searchData!='' and !empty($searchData))
      	$searchTweets=MVC::renderView("Main/tweets",
          array("tweets"=>$searchData,
                "labeloveride"=>urldecode($searchQuery)." - Top Stories",
                "trendkey"=>$searchQuery,
                "source"=>"trends",
                "last_trend_tweet_id"=>Utils::minTweetid($searchData)));
      
      if ($backup_search)
      	$tweets = MVC::renderView("Main/tweets",
          array("tweets"=>$data,
                "user"=>$user,
                "labeloveride"=>urldecode($key)." - Top Stories",
                "trendkey"=>$key,
                "source"=>"trends",
                "showmoreofnouser"=>true));
      else if (isset($params['json']))
      	$tweets = MVC::renderView("Main/tweets",
          array("tweets"=>$data,
                "user"=>$user,
                "trendkey"=>$key,
                "source"=>"trends",
                "last_trend_tweet_id"=>$last_trend_tweet_id,
                "donotshowlabel"=>true));
      else
      	$tweets = MVC::renderView("Main/tweets",
          array("tweets"=>$data,
                "user"=>$user,
                "labeloveride"=>urldecode($key)." - Top Stories",
                "trendkey"=>$key,
                "source"=>"trends",
                "last_trend_tweet_id"=>$last_trend_tweet_id));
      		
      $portallinks = MVC::renderView("Main/portallinks",
        array("portals"=>$this->Portals($params),
              "userlist"=>$userlists,
              "selectedtrend"=>urldecode($key),
              "user"=>$params['user'],
              "usertrends"=>$userTrends,
              "usertrendsbalaji"=>$userTrendsBalaji,
              "notimeline"=>true, 
              "show_ads"=>true));
      
      $home_trends_map = xcache_get("ig_home_trends_map_".$user);
	  if ($home_trends_map[$user_map[$user]]!='' && $home_trends_map[$user_map[$user]]!=null)
	  	$trendData=$home_trends_map[$user_map[$user]];
	  else
      	$trendData = $this->getTrends(array("user"=>$user, "rate_tweets"=>false, "archive" =>false ));
      	
      $trendData = Utils::picksTop($trendData,10);      
        
      if(array_key_exists($_SERVER["SERVER_NAME"],$portal_map)){
      	$channelName = $portal_map[$_SERVER["SERVER_NAME"]]["name"];
        $canonicalURL = "http://".$_SERVER["SERVER_NAME"]."/trends/".urldecode($key)."/";
      } else {
      	$channelName = '';
        $canonicalURL = "http://inagist.com/$user/trends/".urldecode($key)."/";
      }
      
      $show_second_unit = true;
      $no_cache = false;
      if (empty($data) && empty($friendsdata)){
        $tweetList = MVC::renderView("Main/listerror",
          array("message" => "No data found. Please Try After Sometime.",
                "key" => $key));
        $show_second_unit = false;
        $no_cache = true;
      } else {
        if (count($data) + count($friendsdata) < 5)
          $show_second_unit = false;
        $tweetList = $searchTweets.$tweets.$friendstweets;
      }

      $arr = array(
        "user"=>$user,
        "list"=>$selectedList,
        "hours"=>$params['hours'],
        "noautorefresh"=>true,
        "title"=>urldecode($key)." - ".Utils::removeUrls($twtsummary),
        "keywords"=>implode(",", array_unique($keywords)),
        "description"=>urldecode($key)." : ".$tweet['raw_text'],
        "navigation"=>$portallinks,
        "left"=>$tweetList,
        "right"=>$portals,
      	"trenddata"=>$trendData,
        "channelname"=>$channelName,
        "show_ads"=> true,
        "set_expire"=> true,
        "show_second_unit" => $show_second_unit,
        "show_alternate" => true,
        "no_cache"=>$no_cache,
        "canonical_url" => $canonicalURL,
        "noindex" => 'follow',
        "template"=>"template"
      );
      
      if (isset($params['json']))
      {
      	echo $tweets;
      	exit;
      }	
      	
      if ($params['newspaper']!='' || $params['newspaper']!=null)
      	return $data;
      else	
      	return $arr;
    }

    //Get the Trends for the user 
    public function UserTrends($params=null){
    	global $api_base,$lists_map,$portal_map;

    	$user = (!isset($params['user']))?$_SESSION['user_id']:$params['user'];
    	$limit = (!isset($params['limit']))?1:$params['limit'];
      	$args = array("userid=$user","limit=1","rate_tweets=1","type=phrase","summarize=1","archive=0");
      	
    	$isPortal = false;
		foreach ($portal_map as $portal)
    	{
    		if ($portal['handle']==$user)
    			$isPortal = true;
    	}    	
    		        
        $suffix = implode("&",$args);
        $urls["url"] = "$api_base/get_top_trends?$suffix";
        //$urls["tweet"] = "$api_base/get_top_tweets?$suffix";
        // Populate the data my merging the two feeds
	    $data = array();
	    foreach($urls as $type=>$url){
		    try{
		        $content = file_get_contents($url);
		        $trends = json_decode($content,true);
		        foreach($trends as $description => $phrasesArr){
		        	$trendArr = Utils::pickBestTrend($phrasesArr);
		        	foreach($trendArr as $phrase => $count){
		        		$item = array();
		        		$item['desc']=$description;
		        		$item['phrase']=$phrase;
		        		$item['count']=$count;	        		
		        		$data[]=$item;	        		
		        	}	          
		       	}
		   }	
	       catch(Exception $e){
	     	    var_dump($e);exit;
	       }	
    	}
    	usort($data,compare);
    	return $data;
    }		

    //Get user trends by Balaji
	public function UserTrendsBalaji($params=null){
    	global $api_base,$portal_map;

    	$hours_interval_map = array (""=>"now", "1"=>"hour","4"=>"4hrs","8"=>"8hrs","24"=>"today","72"=>"3days","168"=>"week");
    	$user = (!isset($params['user']))?$_SESSION['user_id']:$params['user'];
    	    	
    	if (!$_SESSION['user_id'])
    		return null;
    	
        $urls["url"] = "http://67.23.46.234/testjsons/$user.".$hours_interval_map[$params['hours']].".json";
        //$urls["tweet"] = "$api_base/get_top_tweets?$suffix";
        // Populate the data my merging the two feeds
	    $data = array();
	    foreach($urls as $type=>$url){
		    try{
		        $content = file_get_contents($url);
		        $trends = json_decode($content,true);
		        foreach($trends['trends'] as $phrasesArr){
		        	foreach($phrasesArr as $phrase => $count){
		        		$item = array();
		        		$item['phrase']=$phrase;
		        		$item['count']=$count;	        		
		        		$data[]=$item;	        		
		        	}	          
		       	}
		   }	
	       catch(Exception $e){
	     	    var_dump($e);exit;
	       }	
    	}
    	usort($data,compare);
    	return $data;
    }		
    
    
    //Get the Lists of user using twitter api
    public function UserLists($params=null){
    	global $twitter_oauth, $twitter_api, $twitter_key, $twitter_sec,$twitter_auth_return, $domain,$api_base,$portal_map,$portal_list_map;
    	$user = (!isset($params['user']))?$_SESSION['user_id']:$params['user'];
    	// commenting for  :lists for portals 
    	foreach ($portal_map as $portal)
    	{
    		if ($portal['handle']==$user)
    			return $portal_list_map[$user];
    	}
    	$userListsArray = array();
	 	if ($_SESSION[$user]['lists']!=null && $_SESSION[$user]['lists']!='')
	 		return $_SESSION[$user]['lists'];
	 	else{
	 		define("TWITTER_USER_LISTS_API","http://api.twitter.com/1/".$user."/lists.json");	 		
	 		define("TWITTER_USER_FOLLOW_LISTS_API","http://api.twitter.com/1/".$user."/lists/subscriptions.json");	
    		try {
    			$oauth = new OAuth($twitter_key, $twitter_sec, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI); 
		    	$oauth->setToken($_SESSION['twitter_token'],$_SESSION['twitter_secret']);
		    	$oauth->fetch(TWITTER_USER_LISTS_API);
		    	$json[] = json_decode($oauth->getLastResponse(),true);
				$oauth->fetch(TWITTER_USER_FOLLOW_LISTS_API);
		    	$json[] = json_decode($oauth->getLastResponse(),true);
		    	
	          	global $lists_map;
	          	$lists_map=array();
	          	$db = new DB();
	          	$rows_lists = $db->query("select `list_id`,`list_name`,`limit` from lists_limit;");
    			foreach ($rows_lists as $record){
					$lists_map[$record['list_id']]=$record['limit'];
					$lists_map[$record['list_name']]=$record['limit'];
				}
	          	foreach ($json as $jsonop){
	 	        foreach ($jsonop['lists'] as $list)
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
		    	$_SESSION[$user]['lists']=$userListsArray;		    	
			} catch(OAuthException $E) {
		    	//print_r($E);
			}
	 	}
	 	return $userListsArray;	
    }

    
    // get widget snippet
    public function getWidgetSnippet($params=null){
      global $api_base;	
      
	  $portallinks = MVC::renderView("Main/portallinks",array("portals"=>$this->Portals($params)));

	  $widget = MVC::renderView("Main/generatewidget"); 
	  return array(
        "title"=>" Inagist.com :: Get Your Widget Code",
	    "navigation"=>$portallinks,
        "left"=>$widget,
        "template"=>"template"
      );
    }
    
    
    // Settings for the page
    public function Settings($params=null){
      global $api_base;	
      if (!isset($_SESSION['user_id']))
      	return null;
      $user=$_SESSION['user_id'];
      
      // save the settings
      if ($params['subBtn']=='Save')
      {
      	$email = $params['mailid'];
      	$selChannels = mysql_real_escape_string(implode(",",$params['channels']));
      	$db = new DB();
      	$rows = $db->query("REPLACE INTO user_custom (user_id,channels,updatedon) values ('$user','$selChannels',now())");
      	if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email) || trim($email)=='') 
      	{   
      		if (trim($email)=='')
      		   	$rows = $db->query("UPDATE credentials set notification_id=null where user_id='".mysql_real_escape_string($user)."'");
      		else   	
      			$rows = $db->query("UPDATE credentials set notification_id='".mysql_real_escape_string($email)."' where user_id='".mysql_real_escape_string($user)."'");
      		$data=file_get_contents("$api_base/handle_user?userid=$user&action=refresh_preferences");
      	}	
      	$_SESSION['user_sel_channels']=$selChannels;      	
      	header("Location: http://inagist.com/$user");
      	exit;
      }
	  
	  $portallinks = MVC::renderView("Main/portallinks",array("portals"=>$this->Portals($params)));

	  $settings = MVC::renderView("Main/settings"); 
	  return array(
        "title"=>ucwords($user)." : ..:: inagist ::.. ",
	    "navigation"=>$portallinks,
        "left"=>$settings,
        "template"=>"template"
      );
    }
    	
    // Listing for the page
    public function Listing($params=null){
    	
      global $portal_map,$user_map; 	
      $special_channels = xcache_get("ig_home_special_channels");
      $special_channels = is_array($special_channels) ? $special_channels : array();

      $timer_stats = array(microtime(true));
      $params['user'] = $user = (!isset($params['user']))?"":$params['user'];
      $selectedList = $params["list"];
	  
      $toolbar = MVC::renderView("Main/toolbar",array());

      $left = null;
      if(isset($_SESSION["authorization_time"])){
        if((time()-$_SESSION["authorization_time"]) < 360){
          $left = MVC::renderView("Main/wait",array());
        }else{
          unset($SESSION["authorization_time"]);
        }
      }
      
      //$userlists = $this->UserLists(array("user"=>$user));
      $userlists = array();
      //$userTrends = $this->UserTrends($params);
      $userTrendsBalaji = array();
      $timer_stats[] = microtime(true);
      $myTweets = $this->Tweets($params);
      $tweets = ($left!=null)?$left:MVC::renderView("Main/tweets",array("tweets"=>$myTweets));
      $timer_stats[] = microtime(true);
      //$portals = MVC::renderView("Main/portals",array("portals"=>$this->Portals($params),"count"=>03));
      $portallinks = MVC::renderView("Main/portallinks",array("portals"=>$this->Portals($params),"userlist"=>$userlists,"selectedlist"=>$selectedList,"user"=>$params['user'],"usertrends"=>$userTrends,"usertrendsbalaji"=>$userTrendsBalaji, "show_ads"=>true));
      
      $home_trends_map = xcache_get("ig_home_trends_map_".$user);
      $timer_stats[] = microtime(true);
      if ($home_trends_map[$user_map[$user]]!='' && $home_trends_map[$user_map[$user]]!=null)
        $trendData=$home_trends_map[$user_map[$user]];
      else	
        $trendData = $this->getTrends(array("user"=>$user, "rate_tweets"=>false, "archive" =>false ));
      $timer_stats[] = microtime(true);
      
	  $trendData = Utils::picksTop($trendData,10);      
	  if(array_key_exists($_SERVER["SERVER_NAME"],$portal_map)) 
      $channelName = $portal_map[$_SERVER["SERVER_NAME"]]["name"];
    else
      $channelName = '';
    
    // trends as Keywords for SEO purpose
    $keywords = "";
    foreach ($trendData as $trend)
      $keywords.=",".$trend['phrase'];

    $keyphrases = array();
    foreach ($myTweets as $myTweet){
      if (is_array($myTweet['key_phrases']))
        $keyphrases = array_merge($keyphrases, $myTweet['key_phrases']);
    }
    $keywords .= ",".implode(",", array_unique($keyphrases));

    $timer_stats[] = microtime(true);
      	
	  $arr = array(
        "user"=>$user,
        "list"=>$selectedList,
        "hours"=>$params['hours'],
        "title"=>ucwords($user)." : ..:: inagist ::.. ",
        "navigation"=>$portallinks,
        "left"=>$tweets,
        "right"=>$portals,
        "trenddata"=>$trendData,
        "channelname"=>$channelName,
        "noautorefresh"=>true,
        //"show_ads"=> !isset($_SESSION['user_id']),
        "show_ads"=> true,
        "template"=>"template"
      );

      if(isset($_REQUEST["title"])) $arr["title"] = $_REQUEST["title"];
      if(isset($_REQUEST["keywords"])) $arr["keywords"] = $_REQUEST["keywords"];
      if(isset($_REQUEST["description"])) $arr["description"] = $_REQUEST["description"];
      if(isset($_REQUEST["lang"])) 
        $arr["noindex"] = true; 
      else
        if(!(array_key_exists($user,$user_map) || array_key_exists($user, $special_channels))) 
          $arr["noindex"] = 'follow';
      
      $arr["keywords"].=$keywords;
      if (isset($_REQUEST['debug_time']))
        echo("<!-- ".print_r($timer_stats, true). " -->");
      
      return $arr;
    }

    // Listing for the page
    public function ListingNew($params=null){
      global $portal_map,$user_map,$api_base; 	
      $params['user'] = $user = (!isset($params['user']))?"":$params['user'];
      $selectedList = $params["list"];
      $params['skip_user_merge'] = true;
	  
      $myTweets = $this->Tweets($params);
      $home_trends_map = xcache_get("ig_home_trends_map_".$user);
      if ($home_trends_map[$user_map[$user]]!='' && $home_trends_map[$user_map[$user]]!=null)
        $trendData=$home_trends_map[$user_map[$user]];
      else	
        $trendData = $this->getTrends(array("user"=>$user, "rate_tweets"=>false, "archive" =>false ));
      
      $trendData = Utils::picksTop($trendData,10);      
      if(array_key_exists($_SERVER["SERVER_NAME"],$portal_map)) 
      	$channelName = $portal_map[$_SERVER["SERVER_NAME"]]["name"];
      else
      	$channelName = '';
      
      // trends as Keywords for SEO purpose
      $keywords = "";
      foreach ($trendData as $trend)
      	$keywords.=",".$trend['phrase'];

      $keyphrases = array();
      foreach ($myTweets as $myTweet){
        if (is_array($myTweet['key_phrases']))
          $keyphrases = array_merge($keyphrases, $myTweet['key_phrases']);
      }
      $keywords .= ",".implode(",", array_unique($keyphrases));
      $specialChannels = xcache_get("ig_home_special_channels");
      $latestTrends = xcache_get("ig_latest_trends");
      $popularTweets = xcache_get("ig_popular_tweets");

      // other trends
      $portals = $this->Portals($params);

      $arr = array(
        "user"=>$user,
      	"content"=>MVC::renderView("Main/channelpagenew",
          array("all_portals" => true,
                "user"=>$user,
                "trenddata"=>$trendData, 
                "portals" => $portals,
                "tweets"=>$myTweets,
                "channelname"=>$channelName,
                "show_alternate"=>true,
                "specialchannels"=>$specialChannels,
                "latest_trends" => $latestTrends,
                "popular_tweets" => $popularTweets)
        ),
        "show_ads"=> true,
        "hideheader"=>true,
        "template"=>"mainpage_vd15"
      );

      if(isset($_REQUEST["title"])) $arr["title"] = $_REQUEST["title"];
      if(isset($_REQUEST["keywords"])) $arr["keywords"] = $_REQUEST["keywords"];
      if(isset($_REQUEST["description"])) $arr["description"] = $_REQUEST["description"];
      
      $arr["keywords"].=$keywords;
      return $arr;
    }

    // Return portals data
    public function Portals($params=null){
      $portals = new Portal();
      $portals = $portals->GetCache($params);
      return $portals;
    }

    // Return tweets data
    public function Tweets($params=null){
      global $api_base,$lists_map;
      $user = (!isset($params['user']))?"chjeeves":$params['user'];

      $urls = array();
      $is_archived = false;
      // Add extra params here
      if(isset($params["hours"]) && intval($params["hours"]) > 0){
        $limit = (!isset($params['alimit']))?30:$params['alimit'];
        $count = (!isset($params['acount']))?0:$params['acount'];
        $hours = intval($params["hours"]);
        $args = array("userid=$user","limit=$limit","hours=$hours","count=$count","sort=timestamp","rate_tweets=1");
        if(isset($params["list"])){
          $args[] = "list=".$params["list"];
        }
      	if(isset($params["start"])){
          $args[] = "start=".$params["start"];
        }
        $suffix = implode("&",$args);
        //$urls["url"] = "$api_base/get_archived_stories?$suffix";
        //$urls["tweet"] = "$api_base/get_archived_tweets?$suffix";
        $urls["tweet"] = "$api_base/get_archives?$suffix";

        $is_archived = true;
      }
      else if ($params['lang']!='')
      {
      	$lang=$params['lang'];
      	$args = array("language=$lang","rate_tweets=1","count=50");
      	$suffix = implode("&",$args);
        $urls["tweets"] = "$api_base/search?$suffix";
      }
      else{      	
      	$limit = (!isset($params['limit']))?5:$params['limit'];
      	$args = array("userid=$user","limit=$limit","rate_tweets=1");
        if((isset($params["list"]))&&($params["list"]!='')){          
          if ($lists_map[$params["list"]]!='')
          		$limit=$lists_map[$params["list"]];	
          $args = array("userid=$user","limit=$limit","rate_tweets=1");  	
          $args[] = "list=".$params["list"];
        }        
        $suffix = implode("&",$args);
        $urls["url"] = "$api_base/get_top_urls?$suffix";
        $urls["tweet"] = "$api_base/get_top_tweets?$suffix";
      }

      // Populate the data my merging the two feeds
      $data = array();
      foreach($urls as $type=>$url){
        try{
          $content = file_get_contents($url);
          $tweets = json_decode($content,true);
          foreach($tweets as $tweet){
          	$relatedCount=Utils::getRelatedTweetCount($tweet['related']);
          	//$tweet["retweets"]+=$relatedCount['retweets'];
          	//$tweet["mentions"]+=$relatedCount['mentions'];
          	$tweet["related_tweets"]=$relatedCount['related'];
            $tweet["type"] = $type;
            $tweet["created_at_act"]=$tweet["created_at"];
            $tweet["created_at"] = Utils::timeAgo($tweet["created_at"],1);
            $tweet["actual_text"] = $tweet["text"];
            $tweet["raw_text"] = Utils::removeSpecialChar(Utils::removeUrls($tweet["text"]));
            $tweet["text"] = Utils::linkify($tweet["text"]);
            $data[$tweet["id"]] = $tweet;
          }
        }catch(Exception $e){
          //var_dump($e);exit;
        }
      }

      if ($params['sort']!=null)
     	  usort($data,compare);
      else
       	krsort($data);

      if (isset($params['skip_user_merge']))
        return $data;
      $uniq_users = array();
      $sorted_data = array();
      //if its from archived then no autorefresh
      if ($is_archived)
      { 
      	$sorted_data['noautorefresh']=true;
      	$sorted_data['last_tweet_id']=Utils::minTweetid($data);
      }	
      foreach ($data as $tweet)
      {
      	if (!in_array($tweet['user']['id'],$uniq_users))
      		array_push($uniq_users,$tweet['user']['id']);
      }
      //ranking signed in user's tweets to the top
      $signed_user_location = Utils::indexOf($_SESSION['twitter_id'],$uniq_users);
      if ($signed_user_location!=-1)
      {
      	unset($uniq_users[$signed_user_location]);
      	array_unshift($uniq_users,$_SESSION['twitter_id']);
      }	
      foreach ($uniq_users as $userid)
      {
      	$i=0;
      	$cntChild = 0;
      	$tmpParentTwt="";
      	foreach ($data as $tweet)
      	{
      		if ($tweet['user']['id']==$userid)
      		{
      			if ($i!=0)
      			{      				
      				$tweet['cluster']='child';
      				$cntChild++;
      			}
      			else
      			{
      				$tweet['cluster']='parent';
      				$tmpParentTwt = $tweet;
      			}
      			array_push($sorted_data,$tweet);
      			$i++;
      		}
      	}
      	$index = Utils::indexOf($tmpParentTwt,$sorted_data);
      	if ($index!=-1)
      	{
      		$sorted_data[$index]['childcnt']=$cntChild;
      	}
      }
      return $sorted_data;
    }
    
    public function LookupTweetFromTwitter($id){
      $url = "http://api.twitter.com/1/statuses/show/$id.json";
      $data = "";
      try{
        $content = file_get_contents($url);
        $json = json_decode($content,true);
        if ($json['retweet_count'] > 0)
          $json['retweets'] = $json['retweet_count'];
        if (isset($json['id']))
          $data = $json;
      }catch(Exception $e){
        //var_dump($e);exit;
      }
      return $data;
    }

    public function Lookup($params=null){
      global $display_mode,$api_base;
      $data = array();
      $data['prominent']=0;//default
      $data['userid']="";//default
      if(!isset($_GET["id"])) return;
      $id = $_GET["id"];
      $data['source_tweetid']=$id;
      $reply_count = ($display_mode=="mobile")?4:10;
      if(array_key_exists("reply_count",$params))
      {
      	if ($display_mode!="mobile")
      		$reply_count=$params['reply_count'];
      	unset($params['reply_count']);	
      }
      if(array_key_exists("enable_more_btn",$params))
      {
      	$data['enable_more_btn']=1;
      	unset($params['enable_more_btn']);	
      }
      

      $urls = array();
      $urls["conversation"] = "$api_base/handle_conversation_lookup?limit=$reply_count&id=$id&rate_tweets=1";
      if(array_key_exists("source",$params))
      	$urls["conversation"] .= "&source=".$params["source"];
      if(array_key_exists("start",$params))
      	$urls["conversation"] .= "&start=".$params["start"];
      if(array_key_exists("prominent",$params))
      {
      	$urls["conversation"] .= "&prominent=".$params["prominent"];
      	$data['prominent']=$params["prominent"];
      }
      if(array_key_exists("userid",$params))
      {
      	$urls["conversation"] .= "&userid=".$params["userid"];
      	$data['userid']=',"userid":"'.$params["userid"].'"';      	
      }
      	
      if(isset($_GET["url"])){
        $url = urlencode($_GET["url"]);
        //$urls["url_details"] = "$api_base/get_url_details?url=$url";
      }
      
      foreach($urls as $tag=>$url){
        try{
          $content = trim(file_get_contents($url));
          if(strlen($content)<3) continue;
          $json = json_decode($content,true);
          if(count($json)==0) continue;
          $data[$tag] = $json;
        }catch(Exception $e){
          var_dump($e);exit;
        }
      }
      return $data;
    }

    public function LookupRelated($params=null){
      global $api_base;
      if(!isset($_GET["q"]) || $_GET["q"] == "") return;
      $count = 12;
      $start = 0;
      if(array_key_exists("start",$params))
      	$start="-".$params['start'];

      $relatedSearchTerms = Utils::removeSpecialChar(implode("|", array_slice(explode(",", $_GET["q"]), 0, 4)));
      $relatedTweets = $this->WidgetTweets(
        array(
          "keyw"=> $relatedSearchTerms,
          "limitcount"=>12,
          "widget" => false,
          "skip_formatting"=>true,
          "donotsort" => true,
          "count" => 12,
          "start" => $start,
          "debug" => $_GET['debug']
        )
      );
      unset($relatedTweets['noautorefresh']);
      unset($relatedTweets[$id]);
      $baseTime = time();
      $relatedStoriesInTimeSpan = 0;
      $linkedUrls = array();
      foreach ($relatedTweets as $rtid => $relatedTweet){
        if (is_array($relatedTweet['long_urls'])) 
          $linkedUrls = array_merge($linkedUrls, $relatedTweet['long_urls']);
        if (($baseTime - strtotime($relatedTweet['created_at'])) < 90 * 60)
          $relatedStoriesInTimeSpan++;
      }
      $linkedUrls = array_unique($linkedUrls);

      if (count($linkedUrls) > 0){
        try{
          $requestJSON = json_encode(array("url"=>array_values($linkedUrls)));
          $content = $this->curlCallData("$api_base/get_url_details", $requestJSON);
          $currentTweetURLDetails = json_decode($content, true);
          $urlDetails = $currentTweetURLDetails;
        } catch(Exception $e){}
      }
      if (is_array($relatedTweets) && count($relatedTweets) > 0){
        $relatedTweetsCount = count($relatedTweets);
        $relatedTweets = MVC::renderView("Main/lookup", 
          array("lookup" => array("conversation"=>$relatedTweets), 
                "source" => "relatedTweets", 
                "nolink" => false, 
                "urlDetails" => $urlDetails));
      } else {
        $relatedTweetsCount = 0;
        $relatedTweets = "";
      }
      
      return array("html" => $relatedTweets, 
                   "count" => $relatedTweetsCount, 
                   "developing" => $relatedStoriesInTimeSpan);
    }

    private function curlCallData($url, $postData){
      $ch = curl_init();
      $arr = array();
      array_push($arr, 'Content-Type: application/json; charset=utf-8');

      curl_setopt($ch, CURLOPT_HTTPHEADER, $arr);
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

      $result = curl_exec($ch);
      curl_close($ch);
      return $result;
    }
    
    //Single Tweet
    public function Tweetdetail($params=null){
    	global $display_mode,$api_base;
      $timer_stats = array("a" => microtime(true));
    	//$data=MVC::renderView("Main/lookup",array("lookup"=>$this->Lookup(array("source"=>1))));
    	if(!isset($_GET["id"])) return;
      	$id = $_GET["id"];	

      $searchQuery = null;
      $referringPage = parse_url($_SERVER['HTTP_REFERER']);
      if (stristr($referringPage['host'], 'google.') || 
          stristr($referringPage['host'], 'yahoo.') || 
          stristr($referringPage['host'], 'inagist.') || 
          stristr($referringPage['host'], 'bing.'))
      {
        parse_str($referringPage['query'], $queryVars);
        $searchQuery = $queryVars['q'] ? $queryVars['q'] : $queryVars['t']; // This is the search term used
        $searchQuery = $searchQuery ? $searchQuery : $queryVars['p']; //yahoo sends as p
      }

    	$data=$this->Lookup(array("source"=>1,"reply_count"=>0,"enable_more_btn"=>1));
      $timer_stats["b"] = microtime(true);
      $urlDetails = array();
    	if (isset($data['conversation']))
    	{
    		$currentTweet=$data['conversation'][$id];
    		$currentTweet['tweetid']=$id;
    		$currentTweet['tweeturl']=null;
    		unset($data['conversation'][$id]);   		
    	}

      $relatedTweets = array();
      $linkedUrls = array();
			$description = $currentTweet['text']." by ".$currentTweet['user']['screen_name'];
      if (is_array($currentTweet['long_urls'])) 
        $linkedUrls = $currentTweet['long_urls'];

    	if ($currentTweet==null || $currentTweet==''){
        //$currentTweet = $this->LookupTweetFromTwitter($id);
        $currentTweet = null;
      } else {
        $keyPhrases = is_array($currentTweet['key_phrases']) ? $currentTweet['key_phrases'] : array();
        if ($searchQuery)
          $keyPhrases[] = strtolower($searchQuery);
        if (count($keyPhrases) < 2){
          if (isset($currentTweet['user']['screen_name']))
            $keyPhrases[] = strtolower($currentTweet['user']['screen_name']);
          if (isset($currentTweet['user']['name']))
            $keyPhrases[] = strtolower($currentTweet['user']['name']);
        }
        $keyPhrases = array_unique($keyPhrases);
        usort($keyPhrases, function($a, $b){
          return(strlen($b)-strlen($a));
        });

        $relatedSearchTerms = Utils::removeSpecialChar(implode("|", array_slice($keyPhrases, 0, 4)));
        $relatedTweets = $this->WidgetTweets(
          array(
            "keyw"=> $relatedSearchTerms,
            "widget" => false,
            "limitcount"=>12,
            "skip_formatting"=>true,
            "donotsort" => true,
            "count" => 12,
            "debug" => $_GET['debug']
          )
        );
        $timer_stats["c"] = microtime(true);
        unset($relatedTweets['noautorefresh']);
        unset($relatedTweets[$id]);
        $currentTweet['key_phrases'] = array_unique($currentTweet['key_phrases']);
        $relatedStoriesInTimeSpan = 0;
        $baseTime = time();
        foreach ($relatedTweets as $rtid => $relatedTweet){
          $description .= ". " . Utils::removeUrls($relatedTweet['text']);
          if (is_array($relatedTweet['long_urls'])) 
            $linkedUrls = array_merge($linkedUrls, $relatedTweet['long_urls']);
          if (($baseTime - strtotime($relatedTweet['created_at'])) < 90 * 60)
            $relatedStoriesInTimeSpan++;
        }
        // detect a developing story
        // have atleast 5 related stories within a span of 90 minutes
      }
      $linkedUrls = array_unique($linkedUrls);
      $description = str_replace("\"", "", $description);
      $odescription = str_replace("\"", "", $currentTweet['text']." by ".$currentTweet['user']['screen_name']);

      if (count($linkedUrls) > 0){
        try{
          $requestJSON = json_encode(array("url"=>array_values($linkedUrls)));
          $content = $this->curlCallData("$api_base/get_url_details", $requestJSON);
          $timer_stats["d"] = microtime(true);

          $currentTweetURLDetails = json_decode($content, true);
          $urlDetails = $currentTweetURLDetails;
        } catch(Exception $e){}
      }
      if (is_array($relatedTweets) && count($relatedTweets) > 0){
        $relatedTweetsCount = count($relatedTweets);
        $relatedTweets = MVC::renderView("Main/lookup", 
          array("lookup" => array("conversation"=>$relatedTweets), 
                "source" => "relatedTweets", 
                "nolink" => false, 
                "urlDetails" => $urlDetails));
      } else
        $relatedTweetsCount = 0;

    	$renderedData=MVC::renderView("Main/lookup",
        //array("lookup"=>$data, "showRepliesButton" => isset($currentTweet['mentions']), "id" => $id));
        array("lookup"=>$data, "showRepliesButton" => false, "id" => $id));
    	$returnArray = array(
			"title"=>Utils::removeSpecialChar(Utils::removeUrls($currentTweet['text']))." : ".$currentTweet['user']['screen_name'],
			"keywords"=>implode(",", $keyPhrases),
			"description"=>$description." ".$id,
			"odescription"=>$odescription." ".$id,
			"content"=>$renderedData,
			"rawdata"=>$data,
			"currentTweet"=>$currentTweet,
      "urlDetails" => $urlDetails,
      "relatedTweets"=>$relatedTweets,
      "relatedTweetsCount"=>$relatedTweetsCount,
      "relatedSearchTerms"=>$relatedSearchTerms,
      "keyPhrases"=>$keyPhrases,
      "searchQuery"=>$searchQuery,
      "relatedStoriesInTimeSpan" => $relatedStoriesInTimeSpan,
      "show_ads"=> true,
      "linkedUrls"=>array_unique($linkedUrls),
			"template"=>"tweetdetail"); 
    	if ($currentTweet==null || $currentTweet=='') {
    		$errorMessage=MVC::renderView("Main/error",
          array("message"=>"Data Not Available Currently.", "tweetid"=>$id));
    		$returnArray = array(
				"title"=>"Inagist.com : Data not available",
				"content"=>$errorMessage,
        "no_cache"=>true,
        "show_ads"=> true,
				"template"=>"mainpage");
    	}
      $timer_stats["e"] = microtime(true);
      $start_timer = $timer_stats["a"];
      $timerVals = "";
      foreach ($timer_stats as $timer_index => $timer_value){
        $timediff = $timer_value - $start_timer;
        $start_timer = $timer_value;
        $timerVals .= "$timer_index-$timediff,";
      }
      header("x-tload-stat: $timerVals");
      if (isset($_GET['debug'])){
        echo ("<!-- $timerVals -->");
      }
      return $returnArray; 
    }
    
	//Trends
    public function Newspaper($params=null){
    	$user = (!isset($params['user']))?$_SESSION['user_id']:$params['user'];
    	$userTrends = $this->UserTrends($params);
    	
    	return array(
        "user"=>$user,
        "title"=>"Live Trends",
      	"content"=>MVC::renderView("Main/newspaper",array("usertrends"=>$userTrends,"user"=>$user)),        
        "template"=>"mainpage"
      );
    }
        
    //Live stream using websockets
    public function Livestream($params=null){
    	$user = (!isset($params['user']))?$_SESSION['user_id']:$params['user'];
    	return array(
        "user"=>$user,
        "title"=>"Live twitter stream of $user by In-A-Gist",
      	"content"=>MVC::renderView("Main/livestream",array("user"=>$user)),        
        "template"=>"mainpage"
      );
    }
    
    //Live search using websockets
    public function Livesearch($params=null){
    	return array(
        "title"=>"Live search by In-A-Gist",
      	"content"=>MVC::renderView("Main/livesearch",array()),        
        "template"=>"mainpage"
      );
    }
    
    // Top 3 tweets for each channel
    public function Top($params=null){
      global $api_base,$portal_map;	
      $portals = $this->Portals($params);
      $cloud = new wordCloud();
      $defaultSelection = "";
  	  foreach($portals as $id=>$tweet){
  	  	$portal = $portal_map[$tweet["portal"]];
  	  	if ($portal["name"]!='')
  	  	{
  	  		if ($defaultSelection=='')
  	  		{
  	  			$defaultSelection=$tweet["handle"];
  	  			$cloud->addWord(array('word' => $portal["name"], 'size' => 1+(($tweet['retweets']+$tweet['mention']+$tweet['count'])%4),'url'=>"#",'portaluser'=>$tweet['handle'],'selected'=>' selected'));
  	  		}
  	  		else	
  	  			$cloud->addWord(array('word' => $portal["name"], 'size' => 1+(($tweet['retweets']+$tweet['mention']+$tweet['count'])%4),'url'=>"#",'portaluser'=>$tweet['handle']));
  	  	}	
  	  }
  	  $myCloud = $cloud->showCloud('array');
  	  
  	  $defaultPortal=MVC::renderView("Main/allportals",array("allportals"=>$this->TopPortals(array("user"=>$defaultSelection)),"portal"=>$defaultSelection));
  	  return array(
        "user"=>$user,
        "title"=>"All channels : ..:: inagist ::.. ",
        "content"=>MVC::renderView("Main/tagcloud",array("mycloud"=>$myCloud,"defaultportal"=>$defaultPortal,"top"=>1)),
      	"template"=>"mainpage"
      	);
    }
    
    public function TopPortals($params=null){
      global $api_base,$portal_map;	
      $portals = $this->Portals($params);  
          $hours = (!isset($params['hours']))?12:$params['hours'];
      $portal=$params['user'];
      $users = array();
	  $users[] = $portal;
	  if ($portal=='indiagist')
	  	$users[] = 'indiangist';	        
      $urls = array();
      $limit = (!isset($params['alimit']))?10:$params['alimit'];
      $count = (!isset($params['acount']))?0:$params['acount'];
      $hours = intval($hours);
      $data = array();$datanonurl=array();$dataurl=array();
      foreach ($users as $user)
      {
      	$args = array("userid=$user","limit=$limit","hours=$hours","count=$count","sort=count","rate_tweets=1");
      	if(isset($params["list"])){
        	 $args[] = "list=".$params["list"];
      	}
      	$suffix = implode("&",$args);      
      	$urls["tweet"] = "$api_base/get_archived_tweets?$suffix";
      	$urls["url"] = "$api_base/get_archived_stories?$suffix";
        // Populate the data my merging the two feeds
        	foreach($urls as $type=>$url){
          try{
          $content = file_get_contents($url);
          $tweets = json_decode($content,true);
          $j=0;$k=3;
          if (($type=='url')&&(empty($data)))
          	$k=5;
          foreach($tweets as $tweet){          	
          	if (($type!='url')&&($j>=2))
          		break;	
          	if (($type=='url')&&($j>=$k))
          		break;	
          	$tweet['portalhandle']=$users[0];	
            $tweet["type"] = $type;
            $tweet["created_at"] = Utils::timeAgo($tweet["created_at"],1);
            $tweet["actual_text"] = $tweet["text"];
            $tweet["raw_text"] = Utils::removeSpecialChar(Utils::removeUrls($tweet["text"]));
            $tweet["text"] = Utils::linkify($tweet["text"]);
            $data[$tweet["id"]] = $tweet;
            if ($type=='url')
            	$dataurl[$tweet["id"]]=$tweet;
            else
            	$datanonurl[$tweet["id"]]=$tweet;
            $j++;
          }
          }catch(Exception $e){
          	var_dump($e);exit;
          	}
          }
      }
      if (count($users)>1)
      {
      	usort($dataurl,compare);
      	usort($datanonurl,compare);
      	$dataurl = Utils::picksTop($dataurl,3);
      	$datanonurl = Utils::picksTop($datanonurl,2);
      	$data = array_merge($dataurl,$datanonurl);
      }
	  shuffle($data);
	  $portal=$data;	      
      return $portal;
    }   
    
    //Widget trends json
  	public function getTrendsWidget($params=null){
  		global $publisher_map,$user_map,$portal_map;
  		
  		$home_trends_map = xcache_get("ig_home_trends_map_".$user);
  		
  		if (!isset($params['pubid']) || !is_array($publisher_map[$params['pubid']]))
  			return null;
  			
		$publisherid=$params['pubid'];
  		$channels = explode(',',$publisher_map[$publisherid]['channels']);
  		
  		$trendsArray = array();
  		
  		foreach($portal_map as $domain => $portal_details)
  		{
  			if (in_array($portal_details['id'],$channels))
  			{
  				$trends=$home_trends_map[$domain];
  				foreach ($trends as $trend)
  				{
  					array_push($trendsArray,$trend['phrase']);
  				}				
  			}
  		}
  		
  		return $trendsArray;
  	}
    
    //Widget
  	public function Widget($params=null){
      $params['user'] = $user = (!isset($params['user']))?"indiagist":$params['user'];
      $params['widget'] = true;
      $params['count'] = 15;

      $tweets = MVC::renderView("Main/widgettweets",array("widgettweets"=>$this->WidgetTweets($params)));
      
      $params['title'] = (!isset($params['title']))?"$user - Trends":$params['title'];
      
      $arr = array(
        "user"=>$user,
        "partner_id"=>Ptwitter::getPartnerId($params['client']),
        "list"=>$params['list'],
        "title"=>ucwords($user)." : ..:: inagist ::.. ",
        "left"=>$tweets,
        "width"=>$params['w'],
        "height"=>$params['h'],
        "css"=>$params['css'],
        "reply"=>isset($params['reply'])?$params['reply']:0,
        "twtcnt"=>isset($params['twtcnt'])?$params['twtcnt']:-1,
        "bgcolor"=>$params['tbgcolor'],
        "tcolor"=>$params['ttcolor'],
        "lcolor"=>$params['tlcolor'],
        "bcolor"=>$params['tbcolor'],
        "widgettitle"=>$params['title'],
      	"googleanalyticsid"=>$params['gaid'],
        "template"=>"widget"
      );

      if(isset($_REQUEST["title"])) $arr["title"] = $_REQUEST["title"];
      if(isset($_REQUEST["keywords"])) $arr["keywords"] = $_REQUEST["keywords"];
      if(isset($_REQUEST["description"])) $arr["description"] = $_REQUEST["description"];
      
      return $arr;
    }
  
  //Widgettweets
    public function WidgetTweets($params=null){
      global $api_base;
      $donotsort=false;
      $user = (!isset($params['user']))?"indiagist":$params['user'];
      $top_trends = $params['top_trends'];
      
      $keywords ='';
      if (isset($params['keyw']) && $params['keyw'] !='')
      	$keywords = str_replace('%7C','|',urlencode(strtolower($params['keyw'])));
      
      $urls = array();
      $is_archived = false;
      // Trends widget
      if ($top_trends==1)
      {
      	$limit = (!isset($params['limit']))?1:$params['limit'];
      	$args = array("userid=$user","limit=$limit","rate_tweets=1","type=phrase","summarize=0","key=$keywords");
      	$suffix = implode("&",$args);
        $url= "$api_base/get_top_trends?$suffix";
        $data = array();
      	try{
          $content = file_get_contents($url);
          $json = json_decode($content,true);
          foreach($json as $twtsummary => $twtdata){
            foreach($twtdata as $twtkey => $tweets){
              foreach($tweets as $tweet){
		            $tweet["type"] = $type;
                if (!isset($params['skip_formatting'])){
                  $tweet["created_at"] = Utils::timeAgo($tweet["created_at"],1);
                  $tweet["raw_text"] = Utils::removeSpecialChar(Utils::removeUrls($tweet["text"]));
                  $tweet["text"] = Utils::linkify(Utils::highlightText($tweet["text"],array(urldecode($key))));
                }
		            $data[$tweet["id"]] = $tweet;
	          	}
	          }
          }   
      	}//try
      	catch(Exception $e){
          	var_dump($e);exit;
        }
        $data['noautorefresh']=true;
      	return $data;
      }
      else if ($keywords!='')
      {
      	$args = array("text=$keywords","rate_tweets=1","split=|");
      	if (isset($params['widget']) && !$params['widget'])
      		$args[]="widget=0";
        else
      		$args[]="widget=1";
      	if ($params['user']!='')
      		$args[]="userid=".$params['user'];
      	if (isset($params['count']))
      		$args[]="count=".$params['count'];
      	if (isset($params['debug']))
      		$args[]="debug=".$params['debug'];
      	if (isset($params['start']))
      		$args[]="start=".$params['start'];
        $suffix = implode("&",$args);
        $urls["trend"] = "$api_base/search?$suffix";
        $params['sort'] = "count";
        $donotsort=(!isset($params['donotsort'])) ? false : $params['donotsort'];
      }
      else if(isset($params["hours"]) && intval($params["hours"]) > 0){
        $limit = (!isset($params['alimit']))?30:$params['alimit'];
        $count = (!isset($params['acount']))?0:$params['acount'];
        $hours = intval($params["hours"]);
        $args = array("userid=$user","limit=$limit","hours=$hours","count=$count","sort=timestamp","rate_tweets=1");
        if(isset($params["list"])){
          $args[] = "list=".$params["list"];
        }
        $suffix = implode("&",$args);
        $urls["url"] = "$api_base/get_archived_stories?$suffix";
        $urls["tweet"] = "$api_base/get_archived_tweets?$suffix";

        $is_archived = true;
      }else{
      	$limit = (!isset($params['limit']))?3:$params['limit'];
      	$args = array("userid=$user","limit=$limit","rate_tweets=1");
        if((isset($params["list"]))&&($params["list"]!='')){          
          if ($lists_map[$params["list"]]!='')
          		$limit=$lists_map[$params["list"]];	
          $args = array("userid=$user","limit=$limit","rate_tweets=1");  	
          $args[] = "list=".$params["list"];
        }        
        $suffix = implode("&",$args);
        $urls["url"] = "$api_base/get_top_urls?$suffix";
        $urls["tweet"] = "$api_base/get_top_tweets?$suffix";
      }

      // Populate the data my merging the two feeds
      $data = array();
      foreach($urls as $type=>$url){
        try{
          if (isset($_GET['debug']))
            echo ("<!-- $url -->");
          $content = file_get_contents($url);
          $json = json_decode($content,true);
          foreach($json as $tweet){
            $tweet["type"] = $type;
            if (!isset($params['skip_formatting'])){
              $tweet["created_at"] = Utils::timeAgo($tweet["created_at"],1);
              $tweet["raw_text"] = Utils::removeSpecialChar(Utils::removeUrls($tweet["text"]));
              $tweet["text"] = Utils::linkify($tweet["text"]);
            }
            $data[$tweet["id"]] = $tweet;
          }    
        }catch(Exception $e){
          var_dump($e);exit;
        }
      }

     if (!$donotsort)
     { 
	     if ($params['sort']!=null)
	     	usort($data,compare);
	     else 
        krsort($data);
     }  	
     if ($params['twtcnt']!='' && $params['twtcnt']>0)
        $data=Utils::picksTop($data,$params['twtcnt']);
     if ($params['limitcount']!='' && $params['limitcount']>0)
        $data=array_slice($data, 0, $params['limitcount'], true);
     if ($keywords!='')
      	$data['noautorefresh']=true;
      return $data;
    }
    
    public function Debug($params=null){
    	global $portal_map,$user_map,$publisher_list_map;
    	echo "SERVER ADDR : " .$_SERVER['SERVER_ADDR']."<br/>";
		echo "<pre>";
		echo "<h3>Portal Map</h3>";
		print_r($portal_map);
		echo "<h3>User Map</h3>";
		print_r($user_map);
		echo "<h3>Special Channels</h3>";
		print_r(xcache_get("ig_home_special_channels"));
		echo "<h3>Latest Trends</h3>";
		print_r(xcache_get("ig_latest_trends"));
		echo "<h3>Latest Trends Text</h3>";
		print_r(xcache_get("ig_latest_trends_text"));
		echo "<h3>Popular Tweets</h3>";
		print_r(xcache_get("ig_popular_tweets"));
		echo "<h3>Popular Tweets Text</h3>";
		print_r(xcache_get("ig_popular_tweets_text"));
		echo "<h3>Portal Tweets</h3>";
    $portal_tweets = xcache_get("ig_portals_3_tweets");
		print_r(count($portal_tweets). "<br/>");
		print_r($portal_tweets);
		echo "<h3>Portal Top</h3>";
    $portals_top = xcache_get("ig_portals_tweets");
		print_r(count($portals_top). "<br/>");
		print_r($portals_top);
		echo "<h3>Publisher List Map</h3>";
		print_r(count($publisher_list_map). "<br/>");
		print_r($publisher_list_map);
		echo "<h3>Trends Map</h3>";
		foreach ($portal_map as $domain => $channel)    	
    	{
    		$home_trends_map = xcache_get("ig_home_trends_map_".$channel['handle']);
    		print_r($home_trends_map);
    	}	
		exit;    	
    }

  }
  
// comparing element to be sorted on relevance DESC ORDER
  	function compare($x, $y)
	{
 		if ( $x['count'] == $y['count'] )
        if ($x['id'] > $y['id'])
          return -1;
        else
  			  return 1;
 		else if ( $x['count'] > $y['count'] )
  			return -1;
 		else
  			return 1;
	}
?>
