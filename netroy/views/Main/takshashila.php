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
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/takshashila.css" />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/reset.css" />
  <script type="text/javascript" src="<?=$cdn_base?>js/jquery.min.js"></script>
  <script type="text/javascript" src="<?=$cdn_base?>js/jquery.oembed.min.js"></script>
  <script type="text/javascript">window.baseurl="<?=$baseurl?>";<? if(isset($user)) echo "window.user='$user';"; if(isset($list)) echo "window.list='$list';"; if(isset($hours)) echo "window.hours='$hours';";?></script>
</head>
<body>
<div id="supercontainer">

<div id="topsection">
	<div class="innertube" style="width: 1000px; margin: 0 auto; padding-top:20px;">
		<div class="left">
			<h1><a href="http://takshashila.org.in/">The Takshashila Institution</a></h1>
		</div>	
		<div class="right">
  			<a href="http://inagist.com/" title="In-A-Gist">
    			<img border="0" alt="inagist" src="<?=$cdn_base?>/images/logo.png"/>
  			</a>  
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
		<?php 
		$i=1;
		foreach ($trends as $trend)	
		 {	
		 	echo "<br/><a href='http://inagist.com/trends?t=".urlencode(strtolower($trend["phrase"]))."' style='font-size:18px;'>".$trend["phrase"]."</a><br/>";
		 	$i++;	 
		 }
		?>  
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
		 <div id="tweets" class="tweetstream"  style="display: block;" rel="done">
		 	<?=$prefill?>
		 </div>
	 </div>
	<div id="nowebsockets" style="display: none;">
		<?=$left?>
	</div>
	 	<script>
	 	
		function deleteLast(divid, count, refcheck) {
		  if (count > refcheck) {
		    $("#" + divid + " div:last-child").remove();
		  }
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
		  var counters = {tweets:0, archives:0, links:0, trends:0, interesting:0, search:0};
		  
		  if ("WebSocket" in window) {
		    ws = new WebSocket("ws://websockets.inagist.com:18010/websockets_stream");
		    var userid = "<?=$user?>";
		    
		    ws.onopen = function() {
		      // Web Socket is connected. You can send data by send() method.
		      ws.send("stream " + userid);
		      
		    };
		
		    ws.onmessage = function (evt)
		    {
		      var data = evt.data;
		      var eventObj = JSON.parse(data);
		      if (eventObj.error){
		        debug("Error " + eventObj.error);
		      } else if (eventObj.lookedup_tweetstat) {
		        var tweet = eventObj.lookedup_tweetstat;
		        if ($("#atweet_" + tweet.id_str).length > 0) {
		          var displayedTweet = $("#atweet_" + tweet.id_str).detach();
		          var displayedTweetStats = $(".replystats", displayedTweet);
		          if (displayedTweetStats.length == 0) {
		            displayedTweetStats = jQuery("<div class='replystats'></div>");
		            displayedTweetStats.appendTo(displayedTweet);
		          }
		          
		          if (tweet.mentions)
		            if ($(".replystats .replies", displayedTweet).length > 0)
		              $(".replystats .replies", displayedTweet).text(tweet.mentions + " replies | ");
		            else
		              displayedTweetStats.prepend(jQuery("<span class='replies'>" + tweet.mentions + " replies | </span>"));
		          if (tweet.retweets)
		            if ($(".replystats .mentions", displayedTweet).length > 0)
		              $(".replystats .mentions", displayedTweet).text(tweet.retweets + " retweets");
		            else
		              displayedTweetStats.append(jQuery("<span class='mentions'>" + tweet.retweets + " retweets</span>"));
		          
		          displayedTweet.prependTo("#archives");
		        } 
		      } else {
		        counters.tweets++;
		        deleteLast("tweetstream", counters.tweets, 100);
		        var tweet = eventObj;
		        if ($(".tweetstream:visible").length == 0)
		          $(".tab-stream .talert").show();

		        $(".tweetstream").prepend("<div class='body' id='tw" + tweet.id_str+"'>" + 
				        "<table cellspacing='0' cellpadding='0' border='0' class='tweets'> " +
		          		"<tr>" +
		            	"<td rowspan='2' class='pic'> " +
		              	"	<a href='http://twitter.com/"+tweet.user.screen_name+" target='_blank' rel='nofollow' > " +
		                "		<img src='"+tweet.user.profile_image_url+"' /> " +
		              	"	</a> "+
		            	"</td> "+
		            	"<td class='text' colspan='3'> "+
		              	"<div style='word-wrap: break-word; width:300px;'>"+
		                "	<a href='http://twitter.com/"+tweet.user.screen_name+"' target='_blank' rel='nofollow' style='text-decoration: none;'><span class='user' style='padding-right: 0px;'>"+tweet.user.screen_name+"</span></a>: "+linkify(tweet.text)+
		                "</div>"+
		              	"</td>"+
		          		"</tr>"+
		          		"<tr class='meta'>"+
		          	    "<td class='meta'>"+
		          	  	" <span class='time'>" + tweet.created_at + "</span>"+
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
		    console.log(str);
		  };
		
		  $(".tweetstream").show();
		  
		  $(".tab-button").click(function(){
		    $(".status-tab:visible").hide();
		  });
		  $(".tab-stream").click(function(){
			$(".tweetstream").show();
		    $(".tab-stream .talert").hide();
		  });
		
		
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
    <script type="text/javascript" src="<?=$cdn_base?>js/main.min.js"></script>
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
</body>
</html>
