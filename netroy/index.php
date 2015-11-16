<?php
  require_once("config/config.inc.php");
  require_once("config/map.inc.php");
  require_once("config/wordcloud.class.php");
  require_once("config/geoip.inc.php");  

  // Test if its a portal and modify the request accordingly
  $languagArray = array("ar.inagist.com","arc.inagist.com","bn.inagist.com","bo.inagist.com","chr.inagist.com","cr.inagist.com","dv.inagist.com","el.inagist.com","en.inagist.com","gu.inagist.com","he.inagist.com","hi.inagist.com","hy.inagist.com","jp.inagist.com","ka.inagist.com","km.inagist.com","kn.inagist.com","ko.inagist.com","la.inagist.com","lo.inagist.com","ml.inagist.com","mn.inagist.com","my.inagist.com","ogam.inagist.com","or.inagist.com","pa.inagist.com","ru.inagist.com","runr.inagist.com","si.inagist.com","ta.inagist.com","te.inagist.com","th.inagist.com","ti.inagist.com","zh.inagist.com");
  $eventsArry=array("takshashila.inagist.com"=>array("name"=>"Takshashila","user"=>"takshashilagist","list"=>"/takshashilagist/all","query"=>"takshashila","logo_link"=>"http://takshashila.org.in/","logo_text"=>"The Takshashila Institution","logo_url"=>"http://takshashila.org.in/wp-content/themes/takshashila/img/common/takshashila_logo.gif","header_bg_url"=>"http://takshashila.org.in/wp-content/themes/takshashila/img/common/header.png","left_label"=>"Takshashila","right_label"=>"Takshashila Gist", "limit"=>2),
  					"dvcamp.inagist.com"=>array("name"=>"Digital Vidya","user"=>"dvcamp","query"=>"dvcamp","logo_link"=>"http://www.digitalvidya.com/","logo_text"=>"Digital Vidya","logo_url"=>"http://www.digitalvidya.com/images/digital-marketing-learning-digital-vidya-logo.jpg","header_bg_color"=>"#000","left_label"=>"Digital Vidya","right_label"=>"Digital Vidya Gist", "limit"=>1),
  					"superbowl.inagist.com"=>array("name"=>"Super Bowl XLV","user"=>"superbowl","query"=>"sb45+OR+superbowl+OR+super+bowl","logo_link"=>"http://www.nfl.com/superbowl/45","logo_text"=>"Super Bowl XLV","logo_url"=>"http://img.static.nfl.com/static/site/img/global/nfl-logo-footer.png","header_bg_color"=>"#000","left_label"=>"Super Bowl XLV","right_label"=>"Super Bowl XLV")
 ); 
  $microPortalsArray = array(
    "cwc.inagist.com" => array("userid" => "cwc", "_handler" => "main/microportal"),
    "libya.inagist.com" => array("userid" => "libya", "_handler" => "main/microportal"),
    "japan.inagist.com" => array("userid" => "japan", "_handler" => "main/microportal"),
    "bahrain.inagist.com" => array("userid" => "bahrain", "_handler" => "main/microportal"),
    "oscar.inagist.com" => array("userid" => "oscar", "_handler" => "main/microportal"),
    "grammy.inagist.com" => array("userid" => "grammy", "_handler" => "main/microportal"),
    "wikileaks.inagist.com" => array("userid" => "wikileaks", "_handler" => "main/microportal"),
    "marchmadness.inagist.com" => array("userid" => "marchmadness", "_handler" => "main/microportal"),
    "justinbieber.inagist.com" => array("userid" => "justinbieber", "_handler" => "main/microportal"),
    "americanidol.inagist.com" => array("userid" => "americanidol", "_handler" => "main/microportal"),
    "sxsw.inagist.com" => array("userid" => "sxsw", "_handler" => "main/microportal"),
    "nyknicks.inagist.com" => array("userid" => "nyknicks", "_handler" => "main/microportal"),
    "sales20.inagist.com" => array("userid" => "sales20", "_handler" => "main/microportal"),
    "jerseyshore.inagist.com" => array("userid" => "jerseyshore", "_handler" => "main/microportal"),
    "ladygaga.inagist.com" => array("userid" => "ladygaga", "_handler" => "main/microportal"),
    "ipl.inagist.com" => array("userid" => "dlfipl", "_handler" => "main/ui_v3"),
    "royalwedding.inagist.com" => array("userid" => "royalwedding", "_handler" => "main/ui_v3"),
    "tiecon.inagist.com" => array("userid" => "tiecon", "_handler" => "main/ui_v3"),
    "tweementry.inagist.com" => array("userid" => "dlfipl", "_handler" => "main/tweementry"),
    "tweementary.com" => array("userid" => "dlfipl", "_handler" => "main/tweementry"),
    "www.tweementary.com" => array("userid" => "dlfipl", "_handler" => "main/tweementry")
  );
  $portal_map_vd15 = array("nyc.inagist.com", "london.inagist.com", "worldbiz.inagist.com",
                           "food.inagist.com", "bargains.inagist.com", "books.inagist.com",
                           "green.inagist.com", "travel.inagist.com", "airlines.inagist.com",
                           "france.inagist.com", "singapore.inagist.com", "itgist.inagist.com");
  if(array_key_exists($_SERVER["SERVER_NAME"],$microPortalsArray)){
  	$microPortal=$microPortalsArray[$_SERVER["SERVER_NAME"]];
  	$_REQUEST=array_merge($_REQUEST, $microPortal);
    if (!isset($_GET["r"])) $_GET["r"]=$microPortal["_handler"];
  } elseif(array_key_exists($_SERVER["SERVER_NAME"],$eventsArry)){
  	$event=$eventsArry[$_SERVER["SERVER_NAME"]];
  	$_REQUEST=$event;
  	$_GET["r"]="main/eventspage";
  }
  else if(array_key_exists($_SERVER["SERVER_NAME"],$portal_map)) {
    if(!isset($_GET["r"])) {
      in_array($_SERVER["SERVER_NAME"], $portal_map_vd15)? 
        $_GET["r"]="main/listingnew" : $_GET["r"]="main/listing";
    };
    $portal = $portal_map[$_SERVER["SERVER_NAME"]];

    if((isset($_GET["list"]))&&($_GET["list"]!=''))
    {
    	if (substr($_GET['list'],1,strlen($portal["handle"]))!=$portal["handle"]) 
    		$_REQUEST["list"] = "/".$portal["handle"]."/".$_GET["list"];
    	else	
    		$_REQUEST["list"] = $_GET["list"];
    }
    $_REQUEST["user"] = $portal["handle"];
    if(!isset($_REQUEST["limit"])) $_REQUEST["limit"] = $portal["limit"];

    $_REQUEST["title"] = $portal["title"];
    $_REQUEST["keywords"] = $portal["keywords"];
    $_REQUEST["description"] = $portal["description"];

    // TODO: add theme and bg image after image admin is done

  }
  else if (in_array($_SERVER["SERVER_NAME"],$languagArray))
  {

  	if(!isset($_GET["r"])) $_GET["r"]="main/listing";
    $portal = $portal_map[$_SERVER["SERVER_NAME"]];
    $_REQUEST["lang"] = substr($_SERVER["SERVER_NAME"],0,strpos($_SERVER["SERVER_NAME"],"."));
    
  }else if($_SERVER["SERVER_NAME"]!=$domain){
    // For other domains redirect to the baseurl
    header("Location: http://$domain/");
    exit;
  }else if(isset($_GET["user"]) && !isset($_GET["r"])){
    // For inagist.com/<twitter_handle> pages disable all other controllers
    $_GET["r"]="main/listing";
    $user=$_GET['user'];
  }

  // Populate the common stuff
  $header = MVC::renderView("header",array("cdn_base"=>$cdn_base,"domain"=>$domain,"user"=>$user));
  $footer = MVC::renderView("footer",array("cdn_base"=>$cdn_base,"domain"=>$domain,"enable_analytics"=>($config_mode!="local")));
  $params = array(
    "baseurl"=>$baseurl,
    "domain"=>$domain,
    "cdn_base"=>$cdn_base,
    "header"=>$header,
    "footer"=>$footer,
    "keywords"=>"",
    "description"=>""
  );

  // Do the MVC stuff [:)]
  $r = (isset($_GET['r']))?$_GET['r']:"";
  $r = explode("/",$r);
  $controller = (count($r)>0 && class_exists($c = ucwords(strtolower($r[0])))) ? $c : "Main";
  foreach (array("t","q","key", "keyw", "limit", "user", "title") as $validated_key){
    if (isset($_REQUEST[$validated_key]))
      $_REQUEST[$validated_key] = strip_tags($_REQUEST[$validated_key]);
  }
  $inst = new $controller($_REQUEST);
  $method = (count($r) > 1 && method_exists($inst,($m = strtolower($r[1])))) ? $m : "index";
  $data = $inst->$method($_REQUEST);

  // Merge populated data with common stuff
  $params = array_merge($params,$data);

  // And render the appropriate view
  if($data != null){
    if(array_key_exists("template",$data)){
      MVC::renderView("$controller/".$data["template"],$params,true);    
    }else{
      MVC::renderView("$controller/$method",array("$method"=>$data),true);
    }
  }
?>
