<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
  <meta name="language" content="en" />
  <meta name="keywords" content="<?=$keywords?>" />
  <meta name="description" content="<?=$description?>" />
  <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW" />
  <title><?=$title?></title>
  <link rel="shortcut icon" href="<?=$cdn_base?>favicon.ico" />
  <link rel="alternate" title="<?=strtoupper($user)?> NEWS UPDATES TRENDS" href="http://inagist.com/rss/<?=$user?>" type="application/rss+xml" />  
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/events.css" />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/reset.css" />
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
  <script type="text/javascript" src="<?=$cdn_base?>js/jquery.oauth.js"></script>
  <script type="text/javascript" src="<?=$cdn_base?>js/events.min.js"></script>
  <script type="text/javascript" src="<?=$cdn_base?>js/jquery.oembed.min.js"></script>
  <script type="text/javascript" src="<?=$cdn_base?>js/pretty.js"></script>
  <script type="text/javascript" src="<?=$cdn_base?>js/modal.js"></script>
  <script type="text/javascript">window.baseurl="<?=$baseurl?>";<? if(isset($user)) echo "window.user='$user';"; if(isset($list)) echo "window.list='$list';"; if(isset($hours)) echo "window.hours='$hours';";?></script>
  <style type="text/css">
  /* Overridding defaults*/
  <?php 
  if ($params['user']=='bjpkaitcellgist')
  {?>
	 #topsection {background:url("<?=$params['header_bg_url']?>") no-repeat scroll 0 0 <?php echo (isset($params['header_bg_color']))? $params['header_bg_color']:"transparent"?>;}
	 #topsection {background-position: center;}
	 .inagistlogo {margin-right:55px;margin-bottom:93px;} 	
  <?php }
  else {
  ?>
  #topsection {background:url("<?=$params['header_bg_url']?>") repeat scroll 0 0 <?php echo (isset($params['header_bg_color']))? $params['header_bg_color']:"transparent"?>;}
  <?php
  }
  if ($params['logo_url']!='')
   echo " h1 a {background:url('".$params['logo_url']."') no-repeat scroll 0 0 transparent; ";
  else if ($params['user']!='bjpkaitcellgist')
   echo " h1 a {font-size:30px; text-indent:0px; height:67px; margin-top:8px; color:#FFF;} ";	 
  ?>
  
  </style>
</head>
<body>
<div id="supercontainer">

<div id="topsection">
	<div class="innertube" style="width: 1000px; margin: 0 auto; padding-top:20px;">
		<div class="left">
			<h1><a href="<?=$params['logo_link']?>" style="color:#FFF;"><?=$params['logo_text']?></a></h1>
		</div>	
    <div class="right inagistlogo">
    <a href="http://inagist.com/" title="In-A-Gist">
      <img border="0" alt="inagist" src="<?=$cdn_base?>/images/logo.png"/>
    </a>  
		</div>
		<div class="clear-right"> </div>
    <div class="right" style="font-size: 14px; ">
      <? if(isset($_SESSION[$partner_id.'_user_id'])) {
          $url = "http://twitter.com/".$_SESSION[$partner_id.'_user_id'];
          $name = (isset($_SESSION[$partner_id.'_name']))?$_SESSION[$partner_id.'_name']:$_SESSION[$partner_id.'_user_id'];
      ?>
        <div class="right">
        <? if(isset($_SESSION[$partner_id.'_profile_image_url'])){ ?>
          <div class="right">
            <a href="<?=$url?>"><img src="<?=$_SESSION[$partner_id.'_profile_image_url']?>" class="tweetuser" /></a>
          </div>
        <? } ?>
          <div class="right" align="right" style="text-align: right;padding-right: 10px;">
            <a href="<?=$url?>"><b><?=$name?></b></a>
            <br/><a href="http://<?=$domain?>/partner/logout?partner=<?=$partner_id?>">logout</a>
          </div>
          <script type="text/javascript">window.loggedinuser="<?=$_SESSION[$partner_id.'_user_id']?>";</script>
        </div>
      <? }else{?>
        <div id="notloggedin">
            <span id="loginBt"></span>
        </div>
      <? } ?>
    </div>
		<div class="clear"> </div>
	</div>
</div>

<div id="maincontainer">
<div id="contentwrapper">
<div id="contentcolumn">
<div class="innertube">
		<div id="tweetlabel">
  			Trends 
		</div>
    <div id="trendstore" class="trendarchives">
		<?php 
		$i=1;
		foreach ($trends as $trend)	
		 {	
		 	echo "<br/><a href='http://inagist.com/".$user."/trends?t=".urlencode(strtolower($trend["phrase"]))."' target='_blank' style='font-size:18px;'>".$trend["phrase"]."</a><br/>";
		 	$i++;	 
		 }
		?> 
    </div>
</div>
</div>
</div>

<div id="leftcolumn">
<div class="innertube">
	<?php
	 if ($enable_stream)
	 {?>
	 <div class="tweetcontent">
		 <div id="tweetlabel">
	  		Live Stream
		 </div>
		 <div id="tweets_live" class="tweetstream"  style="display: block;" rel="done">
		 </div>
		 	<?=$prefill?>
	 </div>
	<div id="nowebsockets" style="display: none;">
		<?php 
		if ($params['user']=='bjpkaitcellgist'){
			echo '
			<div id="tweetlabel">
	  			Live Stream
		 	</div> ';	
	  		echo $prefill;
		}	
	  	else	
			echo $left;
		?>	
	</div>
	 	<script>
    $(document).ready(function(){
        $('#notloggedin').click(function(){
          $.oauthpopup({path: 'http://inagist.com/partner/login?partner=<?=$partner_id?>',callback: function(){
              window.location.reload();
            }
        });
        });	    
    });
	
		function deleteLast(divid, count, refcheck) {
		  //if (count > refcheck) {
		  //  $("." + divid + " div:last-child").remove();
		  //}
      return true;
		}
		
		function getLiveTrackSpan(trackWord) {
		  return jQuery("<span class='live-track-term'> track live</span>").
		    click(function(){
		      var currTerms = document.getElementById("q").value.split(",");
		      if ((trackWord) && ($.inArray(trackWord, currTerms) < 0)){
		        currTerms.push(trackWord);
		        document.getElementById("q").value = currTerms.join(",");
		        $("#q").parent().submit();
		      }
		    });
		};
		var ws;
		$(document).ready(function(){
		  var counters = {tweets:0, archives:0, trends:0};
		  
		  if ("WebSocket" in window) {
		    ws = new WebSocket("ws://websockets.inagist.com:18010/websockets_stream");
		    var userid = "<?=$user?>";
		    
		    ws.onopen = function() {
		      // Web Socket is connected. You can send data by send() method.
		      ws.send("stream " + userid);
		      ws.send("retweets_on");
		    };
		
		    ws.onmessage = function (evt)
		    {
		      var data = evt.data;
		      var eventObj = JSON.parse(data);
		      if (eventObj.error){
		        debug("Error " + eventObj.error);
		      } else if (eventObj.status) {
		        console.log(eventObj.status);
          } else if (eventObj.trending_personal_phrase) {
            var trendText = eventObj.trending_personal_phrase.phrase;
            counters.trends++;
            deleteLast("trendarchives", counters.trends, 60);
            var trackDiv = jQuery("<div class='msg even'><a rel='nofollow'"+
                                 " target='_blank' href='http://inagist.com/" + userid +
                                 "/trends?t=" + trendText.replace(/ /g, "+") + "'>"
                                 + trendText + "</a></div>");
              $(".trendarchives").prepend(trackDiv);
          } else if (eventObj.tweet_archived) {
            if ($("#atweet_" + eventObj.tweet_archived.id_str).length == 0) {
              ws.send("tweet " + eventObj.tweet_archived.id_str);
            }
          } else if (eventObj.lookedup_tweet) {
            var tweet = eventObj.lookedup_tweet;
            var displayDiv = ".tweetarchives";
            var divPrefix = "atweet_" + tweet.id_str;
            
            if ($("#" + divPrefix ).length == 0) {
              counters.archives++;
              deleteLast("tweetarchives", counters.archives, 100);
              $(displayDiv).prepend("<div class='body' id='" + divPrefix +"'>" + 
                  "<table cellspacing='0' cellpadding='0' border='0' class='tweets'> " +
                    "<tr>" +
                    "<td rowspan='2' class='pic'> " +
                      "	<a href='http://twitter.com/"+tweet.user.screen_name+"/status/"+tweet.id_str+
                      "' target='_blank' rel='nofollow' > " +
                      "		<img src='"+tweet.user.profile_image_url+"' /> " +
                      "	</a> "+
                    "</td> "+
                    "<td class='text' colspan='3'> "+
                      "<div style='word-wrap: break-word; width:300px;'>"+
                      "	<a href='http://twitter.com/"+tweet.user.screen_name+
                      "' target='_blank' rel='nofollow' style='text-decoration: none;'>"+
                      "<span class='user' style='padding-right: 0px;'>"+
                      tweet.user.screen_name+"</span></a>: "+linkify(tweet.text)+
                      "</div>"+
                      "</td>"+
                    "</tr>"+
                    "<tr class='meta'>"+
                      "<td class='meta'>"+
                      " <span class='time uptime' title='" +tweet.created_at + "'>" + 
                      prettyDate(tweet.created_at) + "</span>"+
                      "</td>"+
                    "</tr>"+
                    "</table>"+
                  "</div>");
            }
		      } else if ((eventObj.type == "tweet") || (eventObj.type == "external_retweeted_tweet")){
		        counters.tweets++;
            if (counters.tweets > 100)
		          $("#tweets_live .body:last").remove();
		        var tweet = eventObj;

		        $(".tweetstream").prepend("<div class='body' id='tw" + tweet.id_str+"'>" + 
				        "<table cellspacing='0' cellpadding='0' border='0' class='tweets'> " +
		          		"<tr>" +
		            	"<td rowspan='2' class='pic'> " +
		              	"	<a href='http://twitter.com/"+tweet.user.screen_name+"/status/"+tweet.id_str+
                    "' target='_blank' rel='nofollow' > " +
		                "		<img src='"+tweet.user.profile_image_url+"' /> " +
		              	"	</a> "+
		            	"</td> "+
		            	"<td class='text' colspan='3'> "+
		              	"<div style='word-wrap: break-word; width:300px;'>"+
		                "	<a href='http://twitter.com/"+tweet.user.screen_name+
                    "' target='_blank' rel='nofollow' style='text-decoration: none;'>"+
                    "<span class='user' style='padding-right: 0px;'>"+
                    tweet.user.screen_name+"</span></a>: "+linkify(tweet.text)+
		                "</div>"+
		              	"</td>"+
		          		"</tr>"+
		          		"<tr class='meta'>"+
		          	    "<td class='meta'>"+
		          	  	" <span class='time uptime' title='" +tweet.created_at + "'>" + 
                    prettyDate(tweet.created_at) + "</span>"+
		          	  	"</td>"+
		          		"</tr>"+
		          		"</table>"+
		        		"</div>");
		      }
		    };
		
		    ws.onclose = function()
		    {
		      debug(" socket closed");
		    };
		  } else {
			$(".tweetcontent").hide(); 
			$("#nowebsockets").show();
		  };
		
		  function debug(str){
		    alert(str);
		  };
		
		  setInterval(function(){ $(".uptime").prettyDate(); }, 30000);
		});
		</script>
	 	
	 
	 	
	 <?php 
	 }
	 else 
	 	echo $left;
	 ?>
</div>

</div>

<div id="rightcolumn">
<div class="innertube"><?=$right?></div>
</div>

</div>

	<div id="template_footer" style="padding-bottom: 5px; padding-top: 5px; clear: both;">
        <div align="center" style="padding-bottom: 5px; padding-top: 5px;">
        	Powered By <a href="http://twitter.com">Twitter</a>
        </div>
        <div class="clear"></div>
    </div>
</div>

<div id="scriptContainer">
    <script type="text/javascript" src="<?=$cdn_base?>js/common.js"></script>
    <script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16053252-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
    
</div>
<div id="mCont"><div class="overlay opac60"></div><div class="loader"></div><div class="info"></div><div class="dialog"><div class="title"></div><div class="content"></div></div></div>
</body>
</html>
