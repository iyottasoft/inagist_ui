<?php

  class Portal extends DBController{

    public static $mapName = "ig_portals_tweets";
    public static $mapName_3tweets = "ig_portals_3_tweets";
    protected $table = "`portals`";
    protected $fields = "`id`,`name`,`subdomain`,`handle`,`limit`,`title`,`keywords`,`description`";
    
    public function Listing($params=null){
      $data = parent::Listing($params);
      return $data;
    }

    public function GetCache($params=null){
      global $portal_tweets;	
      $portals_top = xcache_get(self::$mapName);
      $portal_tweets = xcache_get(self::$mapName_3tweets);
      if($portals_top == null || isset($_GET["reloadportals"]) || $portal_tweets == null ){
        $portals_top = $this->ReloadCache($params);
      }
      return $portals_top;
    }

    public function ReloadCache($params=null){
      global $portal_map,$user_map,$api_base,$portal_tweets;
      $ccount = (!isset($params['ccount']))?0:$params['ccount'];
      $cmin = (!isset($params['cmin']))?1440:$params['cmin'];

      // Fetch data from API and decode the JSON
      //$url = "$api_base/get_portal_summary?minutes=$cmin&count=$ccount&rate_tweets=1&show_tweets=5&order=nourl";
      $url = "$api_base/get_portal_summary?minutes=$cmin&count=$ccount&rate_tweets=1&show_tweets=5";
      $content = file_get_contents($url);
      $portal_tweets = json_decode($content,true);
      
      //$url = "$api_base/get_portal_summary?minutes=$cmin&count=$ccount&user=$user&rate_tweets=1";
      //$content = file_get_contents($url);
      //$tweets = json_decode($content,true);
      $tweets = array();
      foreach($portal_tweets as $handle=>$tweet){
        if (count($tweet) < 2)
          unset($portal_tweets[$handle]);
        else
          $tweets[$handle] = $tweet[0];
      }

      // Init empty array for the map and a temporary arry for sorting and stuff
      $portals_top = array();

      // Run through, map
      foreach($tweets as $handle=>$tweet){
        $domain = $user_map[$handle];
        $tweet["portal"] = $domain;
        $tweet["handle"] = $handle;
        $portals_top[$tweet["id"]] = $tweet;
      }

      // , Sort
      krsort($portals_top);

      // , and Save in memory
      xcache_set(self::$mapName,$portals_top);
      xcache_set(self::$mapName_3tweets,$portal_tweets);

      // top tweets
      try{
        $latestTrendsContent = 
        //file_get_contents("$api_base/get_latest_trends?time=30&type=tweets&limit=24&sort=id");
        //file_get_contents("$api_base/get_latest_trends?time=30&type=tweets&limit=24&sort=flink_ratio");
        //file_get_contents("$api_base/get_latest_trends?time=30&type=tweets&limit=24&sort=time_slice_follow");
        file_get_contents("$api_base/get_latest_trends?time=20&type=tweets&limit=24&sort=fval_ratio");
        //file_get_contents("$api_base/get_latest_trends?time=30&limit=24");
        //file_get_contents("$api_base/get_latest_trends?type=mixed&time=15&limit=24");
        $latestTrends = json_decode($latestTrendsContent,true);
        xcache_set("ig_latest_trends",$latestTrends);
        if (count($latestTrends) > 0) {
          $latest_trends_text = "";
          for ($i=0;$i<5;$i++) {
            $latestTweet = $latestTrends[$i];
            if (is_array($latestTweet)){
              $tweet = $latestTweet['tweet'];
              $tweetUser = $tweet['user'];
              $latest_trends_text .= "<li class=\"atrend\" style=\"margin: 0 0 5px 0; clear: both;\"><a href=\"http://inagist.com/".$tweetUser['screen_name']."/".$tweet['id_str']."/".Utils::urlifyTweetText($tweet['text'], true)."\" class=\"clink\"><img style=\"float: left;\" class=\"post_load\" data-src=\"".$tweetUser['profile_image_url']."\"/> ".Utils::removeUrls($tweet['text'])."</a></li>";
            }
          }
          xcache_set("ig_latest_trends_text",$latest_trends_text);
        }
        //
        // fetch current popular tweets on inagist
        $popularTweetsContent = 
          file_get_contents("$api_base/get_latest_trends?type=popular&time=1&sort=score&limit=5");
        $popularTweets = json_decode($popularTweetsContent,true);
        xcache_set("ig_popular_tweets",$popularTweets);
        if (count($popularTweets) > 0) {
          $popular_tweets_text = "";
          foreach ($popularTweets as $tweetIndex => $popularTweet) {
            if (is_array($popularTweet['tweet'])){
              $tweet = $popularTweet['tweet'];
              $tweetUser = $tweet['user'];
              $popular_tweets_text .= "<li class=\"atrend\" style=\"margin: 0 0 5px 0; clear: both;\"><a href=\"http://inagist.com/".$tweetUser['screen_name']."/".$tweet['id_str']."/".Utils::urlifyTweetText($tweet['text'], true)."\" class=\"clink\"><img style=\"float: left;\" class=\"post_load\" data-src=\"".$tweetUser['profile_image_url']."\"/> ".Utils::removeUrls($tweet['text'])."</a></li>";
            }
          }
          xcache_set("ig_popular_tweets_text",$popular_tweets_text);
        }
      }catch(Exception $e){
        //var_dump($e);exit;
      }
      
      return $portals_top;
    }
  }

?>
