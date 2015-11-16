<?php
$_SERVER['FULL_URL'] = 'http';
$script_name = '';
if(isset($_SERVER['REQUEST_URI'])) {
    $script_name = $_SERVER['REQUEST_URI'];
} else {
    $script_name = $_SERVER['PHP_SELF'];
    if($_SERVER['QUERY_STRING']>' ') {
        $script_name .=  '?'.$_SERVER['QUERY_STRING'];
    }
}
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {
    $_SERVER['FULL_URL'] .=  's';
}
$_SERVER['FULL_URL'] .=  '://';
if($_SERVER['SERVER_PORT']!='80')  {
    $_SERVER['FULL_URL'] .= $_SERVER['HTTP_HOST'].$script_name;
} else {
    $_SERVER['FULL_URL'] .=  $_SERVER['HTTP_HOST'].$script_name;
}
if (isset($no_cache) && $no_cache) {
  header('HTTP/1.1 410 Gone');
  if(preg_match('/(googlebot|yahoo|bingbot|baidu|skipcache)/i', $_SERVER['HTTP_USER_AGENT'])){
    header("Cache-Control: no-cache");
    exit;
  }
}
$expireAge = 120;
header("Cache-Control: max-age=$expireAge");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + $expireAge) . " GMT");
$show_alternate_ad = false;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xml:lang="en" lang="en">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
  <meta name="language" content="en" />
  <meta name="keywords" content="<?=$keywords?>" />
  <meta name="description" content="<?=$description?>" />
  <meta property="fb:app_id" content="184217584951764" />
  <meta property="og:title" content="<?=$title?>" />
  <meta property="og:description" content="<?=$description?>" />
  <meta property="og:type" content="article" />
  <meta property="og:url" content="<?=$_SERVER['FULL_URL']?>" />
<?php if (isset($noindex)) { ?>
  <meta name="robots" content="noindex, <?=($noindex === true)?'nofollow':'follow'?>" />
<?php } ?>
  <title><?=$title?></title>
  <link rel="shortcut icon" href="<?=$cdn_base?>favicon.ico" />
  <link rel="publisher" href="https://plus.google.com/101948688477206152601" />
  <link rel="alternate" title="<?=strtoupper($user)?> NEWS UPDATES TRENDS" href="http://inagist.com/rss/<?=$user?>" type="application/rss+xml" />  
  <link href='http://fonts.googleapis.com/css?family=Cabin:500|Droid+Serif|Inconsolata&v2' 
    rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/reset.css" />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/common.css?v=1.9.91" />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/main.css?v=1.9.998" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
  <script type="text/javascript" src="<?=$cdn_base?>js/minified/jquery.embedly.min.js?v=2"></script>
  <script type='text/javascript' src='http://partner.googleadservices.com/gampad/google_service.js'>
  </script>
  <script type='text/javascript'>
    GS_googleAddAdSenseService("ca-pub-2412868093846820");GS_googleEnableAllServices();
  </script>
  <script type='text/javascript'>
    GA_googleAddSlot("ca-pub-2412868093846820", "Channel_Page_Bottom");GA_googleAddSlot("ca-pub-2412868093846820", "Channel_Page_TOP");GA_googleAddSlot("ca-pub-2412868093846820", "Detail_Pages_Large_Unit");
  </script>
  <script type='text/javascript'>
    GA_googleFetchAds();
  </script>
  <script type="text/javascript">
    window.baseurl="<?=$baseurl?>";
    <? if(isset($user)) echo "window.user='$user';"; 
    if(isset($list)) echo "window.list='$list';"; 
    if(isset($hours)) echo "window.hours='$hours';";?>
  </script>
  <style type="text/css">
	#sidebar{float:left;width:180px;text-align: right; padding-right:5px; font-size:14px; padding-top:45px;color: #0D5575; }
	#sidebar ul {margin:5px 0 1.5em 20px;}
	#sidebar ul li {list-style: none outside none;text-align: right; padding-top:2px; padding-bottom: 2px; padding-right: 10px; margin-bottom:3px;}
	#sidebar li a {color:#555; display:block;}
	#sidebar li a:hover,#sidebar li:hover {background-color:#C7C7C7;color:#000;}
	#sidebar li.selected,#sidebar li.selected a{background-color:#ccc; color: black;}
	span.user{text-decoration: none; }
  </style>  
</head>
<body>
<div id="wrapper">
  <div id="header"><div id="headerInner"><?=$header?></div></div>
  <div id="content">
  <?php if (count($trenddata)>0){?>
  <div class="trending" style="width:100%; float:left;">
  	<h2 style="color:#0d5575; font-size: 16px; font-weight: bold; float:left; padding:10px 10px 4px 15px; width: 160px; text-align: right;">
  	Trends 
  	</h2>
	<div id="scrollingText" class="inner" style="width:550px; border-bottom: 1px dotted #888;">	
	<ul style="left: 0px;">		
	 <?php
		 foreach ($trenddata as $trend)	
		 {		 
		 	if ($channelname!=''){
			?>
				<li style="float:left; font-size:14px;"><a href="/trends/<?=urlencode(strtolower($trend["phrase"]))?>/" style="padding-top:10px;"><?=$trend["phrase"]?></a></li>
			<?php 
		 	}
		 	else
		 	{
		 	?>
				<li style="float:left; font-size:14px;"><a href="/<?=$user?>/trends/<?=urlencode(strtolower($trend["phrase"]))?>/" style="padding-top:10px;"><?=$trend["phrase"]?></a></li>
			<?php	
		 	}
		 }
	 ?>
 	</ul> 
	</div>
  </div>
  <div style="clear:both;"></div>
  <?php }?>
			
  <?=$navigation?>
  <div>
  <table cellpadding="0" cellspacing="0" border="0" style="width:auto; margin-left:180px;" >
  	<tr>
  		<td style="vertical-align: top;min-width: 550px; color: black;">
  		<div id="leftpane">
      <!-- google_ad_section_start -->
  		<?=$left?>
      <!-- google_ad_section_end -->
        <?php if ( isset($show_second_unit) && !$show_second_unit) { ?>
          <div class="ad_container" style="margin: 10px 0px 0px 96px;" id="tweetdetail_google_links_ad">
          <!-- Detail_Pages_Large_Unit -->
          <script type='text/javascript'>
            GA_googleFillSlot("Detail_Pages_Large_Unit");
          </script>
          </div>
        <?php } ?>
  		</div>
  		</td>
  		<td></td>
  		<td style="padding:10px 0px 0px 10px; width:350px;">
      <div style="height: 36px; width:250px; padding-left: 5px;">
        <div align="left" style="float: left; width: 65px;">
          <a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a>
        </div>
        <div align="left" style="float: left; width: 50px; overflow: hidden; margin-right: 5px;">
          <fb:like href="" send="false" layout="button_count" width="100" show_faces="false" 
            colorscheme="dark" font="arial"></fb:like>
        </div>
        <div align="left" style="float: left; width: 65px;">
          <g:plusone size="medium" count="false"></g:plusone>
        </div>
      </div>
  		<?php if ($show_ads){ ?>
        <div id="main_google_ad" class="ad_container" style="margin-bottom: 20px;">
      <?php
        if (false && (isset($_SESSION['twitter_id']) || isset($_REQUEST['140proof_demo']))) {
          $target_user = isset($_SESSION['twitter_id']) ? 
            $_SESSION['twitter_id'] : 
            (isset($_REQUEST['140proof_userid']) ? $_REQUEST['140proof_userid'] : $currentTweet['user']['id']);
      ?>
    <script type='text/javascript' src='http://api.140proof.com/javascripts/loader.js?hb=<?=$target_user?>&app_id=84&style=srec&width=250&height=250&ts=<?=time()?>'>
    </script>
        <?php } else {?>
          <!-- Channel_Page_TOP -->
          <script type='text/javascript'>
            GA_googleFillSlot("Channel_Page_TOP");
          </script>
        <?php } ?>
        </div>
        <?php if (!isset($show_second_unit) || $show_second_unit) { ?>
          <div id="main_google_ad1" class="ad_container">
        <?php if ($show_alternate) { ?>
          <fb:recommendations site="inagist.com" width="250" height="250" header="false" font="verdana" border_color="">
          </fb:recommendations>
        <?php } else { ?>
          <!-- Channel_Page_Bottom -->
          <script type='text/javascript'>
            GA_googleFillSlot("Channel_Page_Bottom");
          </script>
        <?php } ?>
          </div>
		<?php
        }
      }
    ?>
		</td>
  	</tr>
  </table>
  </div>    
    <div class="clear"> </div>
  </div>
  <div id="scriptContainer">
    <?php if ($noautorefresh){?>
	<script language="javascript">
  	$(function(){
		  window.noautorefresh = true;
  	});
	</script>
	<?php }?>
    <script type="text/javascript">
      (function() {
        var s = document.getElementsByTagName('script')[0];
        var twS = document.createElement('script'); twS.type = 'text/javascript'; twS.async = true;
        twS.src = '<?=$cdn_base?>js/minified/all.min.js?v=14';
        s.parentNode.insertBefore(twS, s);
      })();
    </script>
  </div>
  <div align="center" style="border-top: 2px solid #333;" id="footer"><?=$footer?></div>
</div>
<script language="javascript">
		
		var arTrending=$(".trending");
		if(arTrending.length>0)
		{
			arTrending.each(function(i){var $objList=arTrending.find("ul");
				$objList.animate({left:0},{duration:600});
				var $objInner=arTrending.find(".inner");
				var $arItems=$objList.find("li");
				var intItem=-1;
				var $nextItem,$intNext,tempId;
				var boolPause=false;
				function fGallery()
				{
					if(boolPause===true)
					{
						return false
					}
					clearInterval(tempId);
					if(intItem==($arItems.length-1))
					{
						intItem=0
					}
					else
					{
						intItem=intItem+1
					}
					$nextItem=$($arItems[intItem]);
					$intNext=$nextItem.width();
					$objList.animate({left:-$intNext+"px"},{duration:1000,complete:function(){$objList.css("left","0px");
																				$nextItem.remove().appendTo($objList);
																				tempId=setInterval(fGallery,3000)}})
				}
				$objInner.bind("mouseover",function(event){boolPause=true});
				$objInner.bind("mouseout",function(event){boolPause=false});
				tempId=setInterval(fGallery,3000)
			})
		}
</script>
</body>
</html>
