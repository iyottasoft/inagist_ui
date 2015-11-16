<?php
  class Utils{
    public static function timeAgo($date,$gran=2) {
      $date = strtotime($date);
      $difference = time() - $date;
      $periods = array(
        'month' => 2628000,
        'day' => 86400,
        'hour' => 3600,
        'min' => 60
      );

      $ago = array();
      if($difference < 60) $ago[]="less than a min";
      else{
        foreach ($periods as $key => $value) {
          if ($difference >= $value) {
            $time = floor($difference/$value);
            $difference %= $value;
            $ago[]= "$time ".(($time > 1) ? $key.'s' : $key);
            $gran--;
          }
          if($gran==0) break;
        }
      }
      $ago[]="ago";
      return implode(" ",$ago);      
    }
    public static function linkify($text){
      // Mark RT
      $text = preg_replace("/(^|[\s,\.])RT ?@([A-Za-z0-9_]+)/","$1 <span class='retweet-icon'></span> <b> @$2 </b>",$text);
      
      // Mark Urls
      // TODO: change to preg_match_all an then iterate and replace individually
      $text = preg_replace(
        "#(^|[^\">])(https?://)([\w]+[^ \"\n\r\t< ]*)#",
        "\\1<a href='\\2\\3' target='_blank' rel='nofollow'>\\2\\3</a>",
        $text
      );
      // ones without http.. starting with www or ftp
      $text = preg_replace(
        "#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#",
        "\\1<a href='http://\\2' target='_blank' rel='nofollow'>\\2</a>",
        $text
      );

      // Mark users
      $text = preg_replace(
        "/(^|[\s,\.\]\[\(\)])@([A-Za-z0-9_]+)/",
        "$1@<a href='http://twitter.com/$2' class='user' target='_blank' rel='nofollow'>$2</a>",
        $text
      );

      // Mark Hash Tags
      $text = preg_replace(
        "/(^|[\s,\.\]\[\(\)])#([A-Za-z0-9_]+)/",
        "$1#<a href=\"http://search.twitter.com/search?had_popular=true&amp;q=$2\" class='tag' target='_blank' rel='nofollow'>$2</a>",
        $text
      );

      return $text;
    }

    public static function flush_buffers($msg=null,$islast=false){
      if($msg!=null) echo $msg."\n";
      ob_end_flush();
      ob_flush();
      flush();
      if(!$islast) ob_start();
    }

    public static function urlifyTweetText($text, $removeUrls=true){
      //if ($removeUrls)
      //  $text = Utils::removeSpecialChar(Utils::removeUrls($text));
      //return urlencode(substr(str_replace(' ','_',$text),0,80));
      return "";
    }

    public static function removeUrls($text){
      
      // Replace urls with blank space      
      $text = preg_replace(
        "#(^|[^\">])(https?://)([\w]+[^ \"\n\r\t< ]*)#",
        "",
        $text
      );
      // ones without http.. starting with www or ftp..Replace urls with blank space
      $text = preg_replace(
        "#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#",
        "",
        $text
      );

      return $text;
    }
    
  	public static function removeSpecialChar($text){
  	  $arrSpecChar=array("%", "&", "?", "'", "\"", "#","/","\n","\r");	
  	  foreach ($arrSpecChar as $char)
  	  	$text=str_replace($char,'',$text);
      return $text;
    }
    
    public static function extractUrls($text){

      //match for urls      
      preg_match(
        "#(^|[^\">])(https?://)([\w]+[^ \"\n\r\t< ]*)#",
        $text,
        $matches
      );
      // ones without http.. starting with www or ftp..Replace urls with blank space
      if (empty($matches)){
      	preg_replace(
        "#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#",
        $text,
        $matches
      	);
      }	
      return $matches[0];
    }
    
    // indexOf function.  Searches an array for
    // a value and returns the index of the *first* occurance
    public static function indexOf($needle, $haystack) {                
        for ($i=0;$i<count($haystack);$i++) {         
                if ($haystack[$i] == $needle) {       
                        return $i;                    
                }
        }
        return -1;
	}
	
	// Pick top tweets alone
	public static function picksTop($data,$count=2) {
		$topdata = array();
		$i=0;
		foreach ($data as $element)
		{
			if	($i<$count)
				array_push($topdata,$element);
			$i++;	
		}
		return $topdata;
	}
    
  }
  
?>
