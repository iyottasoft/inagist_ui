<?
class RSS
{
	public function RSS()
	{
		require_once ('mysql_connect.php');
		require_once ('Utils.php');
	}
	
	public function GetFeed($params)
	{
		 $this->getDetails($params);
	}
	
	private function dbConnect()
	{
		DEFINE ('LINK', mysql_connect (DB_HOST, DB_USER, DB_PASSWORD));
	}
	
	private function getDetails($params)
	{
		$data = $this->getTweets($params);
		
		$details = '<?xml version="1.0" encoding="UTF-8" ?>
					<rss version="2.0"
						xmlns:content="http://purl.org/rss/1.0/modules/content/"
						xmlns:wfw="http://wellformedweb.org/CommentAPI/"
						xmlns:dc="http://purl.org/dc/elements/1.1/"
						xmlns:atom="http://www.w3.org/2005/Atom">
            <link rel="hub" href="http://pubsubhubbub.appspot.com"/>
            <link rel="self" href="http://inagist.com/rss/'. $params['user'] .'"/>
						<channel>
						<title>'. $params['user'] .' News, Updates and Trends </title>
						<link>http://inagist.com/'. $params['user'] .'</link>
						<description>'. $params['user'] .' News, Updates and Trends </description>
						<lastBuildDate>'.date(DATE_RSS).'</lastBuildDate>
				    <generator>Inagist.com</generator>\r\n';
		echo $details;
		$this->getTrends($params);
		$this->getItems($data); 
		//$details .= $items;
		$details = '</channel>
			 		</rss>';
		echo $details;
		//return $details;		
	}

	private function getTweets($params)
	{	
	    $api_base = "http://inagist.com/api/v1"; 
      $user = $params['user'];
      if ($user == 'all')
        return $this->getAllPortalTweets($params);
      $urls = array();
      // Add extra params here
      $limit = (!isset($params['limit']))?5:$params['limit'];
      $suffix = implode("&",array("userid=$user","limit=$limit","rate_tweets=0"));
      //$urls["url"] = "$api_base/get_top_urls?$suffix";
      $urls["tweet"] = "$api_base/get_top_retweets?$suffix";

      // Populate the data my merging the two feeds
      $data = array();
      foreach($urls as $type=>$url){
        try{
          $content = file_get_contents($url);
          $tweets = json_decode($content,true);
          foreach($tweets as $tweet){
            $tweet["type"] = $type;
            $tweet["created_at"] = date(DATE_RSS, strtotime($tweet["created_at"]));
            $tweet["plain_text"] = $tweet["text"];
            $tweet["raw_text"] = Utils::removeSpecialChar(Utils::removeUrls($tweet["text"]));
            $tweet["text"] = Utils::linkify($tweet["text"]);
            $data[$tweet["id"]] = $tweet;
          }
        }catch(Exception $e){
          var_dump($e);exit;
        }
      }
      krsort($data);
      return $data;
	}
	
	private function getTrends($params)
	{	
	  $api_base = "http://inagist.com/api/v1"; 
    $user = $params['user'];
    if ($user == 'all') return;
    $limit = (!isset($params['limit']))?3:$params['limit'];
    $suffix = implode("&",array("userid=$user","limit=$limit","show_tweets=1", "type=phrase", "archive=0"));
    $url = "$api_base/get_top_trends?$suffix";

    // Populate the data my merging the two feeds
    try{
      $content = file_get_contents($url);
      $trends = json_decode($content,true);
      foreach($trends as $text => $keys){
        foreach($keys as $key => $val){
          $item = '<item>
            <title><![CDATA[ Trending '. $key .' in '.$user.']]></title>
            <link>http://inagist.com/'. $user .'/trends/'.urlencode($key).'/?utm_source=inagist&amp;utm_medium=rss</link>
            <description><![CDATA['. $key . ' - '. $text .']]></description>
            <pubDate>'.date(DATE_RSS).'</pubDate>
            <guid>http://inagist.com/'. $user .'/trends/'.urlencode($key).'/</guid>
          </item>\r\n';
          echo $item;
        }
      }
    }catch(Exception $e){
      var_dump($e);exit;
    }
	}
	
	private function getItems($tweets)
	{	
		$items="";
		foreach ($tweets as $tweet)
		{
$items = '<item>
	<title><![CDATA[@'.$tweet['user']['screen_name'].' : '.$tweet["plain_text"] .']]></title>
	<link>http://inagist.com/'. $tweet["user"]["screen_name"] .'/'.$tweet["id"].'/?utm_source=inagist&amp;utm_medium=rss</link>
	<description><![CDATA[<img align="left" height="48" width="48" style="margin-right:5px;" src="'.
  $tweet["user"]["profile_image_url"].
  '" alt="'.$tweet['user']['screen_name'].'" />  @'.$tweet["user"]["screen_name"].' : '. $tweet["text"] .']]>
  </description>
	<pubDate>'.$tweet["created_at"].'</pubDate>
	<guid>http://inagist.com/'. $tweet["user"]["screen_name"] .'/'.$tweet["id"].'/</guid>
</item>\r\n';
			echo $items;
		}
		//return $items;
	}

  private function getAllPortalTweets($params){
    $api_base = "http://inagist.com/api/v1"; 
    //$portals = new Portal($params);
    //$portals->GetCache();
    $url = "$api_base/get_latest_trends?type=tweets&limit=50&time=30&sort=id";
    //$url = "$api_base/get_latest_trends?type=tweets&limit=50&time=30&sort=time_slice_follow";
    //$url = "$api_base/get_latest_trends?type=tweets&limit=50&time=30&sort=flink_ratio";
    //$url = "$api_base/get_latest_trends?type=mixed&limit=50&time=30";
    $data = array();
    $type = $params['user'];
    try{
      $content = file_get_contents($url);
      $tweets = json_decode($content,true);
      foreach($tweets as $tweet){
        $tweet = $tweet['tweet'];
        if (is_array($tweet)){
          $tweet["type"] = $type;
          $tweet["created_at"] = date(DATE_RSS, strtotime($tweet["created_at"]));
          $tweet["plain_text"] = $tweet["text"];
          $tweet["raw_text"] = Utils::removeSpecialChar(Utils::removeUrls($tweet["text"]));
          $tweet["text"] = Utils::linkify($tweet["text"]);
          $data[$tweet["id"]] = $tweet;
        }
      }
    }catch(Exception $e){
      var_dump($e);exit;
    }
    krsort($data);
    return $data;
  }
}
?>
