<?php
class APIUtils{
  static $APIBASE = "http://inagist.com/api/v1";
  public static function search($userid, $text){
    $searchurl = self::$APIBASE."/search?userid=$userid&text=".urlencode($text);
    $searchdata = json_decode(file_get_contents($searchurl));
    return $searchdata;
  }

  public static function getTrendTweets($userid, $trend){
    $trendsurl = self::$APIBASE."/get_top_trends?type=phrase&summarize=0&userid=$userid&key=".urlencode($trend);
    $trendsdata = json_decode(file_get_contents($trendsurl));
    $results = array();
    foreach ($trendsdata as $trendKey => $trendKeyData){
      foreach ($trendKeyData as $trend => $trendData){
        $results = array_merge($results, $trendData);
      }
    }
    return $results;
  }

  public static function mergeTimeLine($userid, $urllimit, $trendlimit) {
    $trendsurl = self::$APIBASE."/get_top_trends?type=phrase&summarize=0&userid=$userid&limit=$trendlimit";
    $topurls = self::$APIBASE."/get_top_urls?rate_tweets=1&userid=$userid&limit=$trendlimit";
    $trendsdata = json_decode(file_get_contents($trendsurl));
    $urlsdata = json_decode(file_get_contents($topurls));
    $selectedTrends = self::selectTrends($trendsdata);
    return self::amerge($urlsdata, $selectedTrends);
  }

  public static function selectTrends($trendsJSON){
    $selected = array();
    foreach ($trendsJSON as $trendKey => $trendKeyData){
      $selectedTrend = "";
      foreach ($trendKeyData as $trend => $trendData) {
        if (strlen($trend) > strlen($selectedTrend))
          $selectedTrend = $trend;
      }
      $trendData = $trendKeyData->$selectedTrend;
      $selectedTweet = null;
      $scount = -1;
      foreach ($trendData as $trendDetails) {
        if ($trendDetails->count > $scount) {
          $scount = $trendDetails->count;
          $selectedTweet = $trendDetails;
        }
      }
      //$trendDetails->phrase = array("text" => $trend, "count" => sizeof($trendData));
      $selectedTweet->phrase->text = $trend;
      $selectedTweet->phrase->count = sizeof($trendData);
      array_push($selected, $selectedTweet);
    }
    return $selected;
  }

  private static function amerge($urls, $trends){
    $trend_ids = array();
    foreach ($trends as $trend) {
      array_push($trend_ids, $trend->id);
    }
    $uniqueurls = array();
    foreach ($urls as $url){
      if (!in_array($url->id, $trend_ids))
        array_push($uniqueurls, $url);
    }
    return array_merge($uniqueurls, $trends);
  }
}
?>
