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
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/reset.css" />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/common.css?v=3" />
  <link rel="stylesheet" type="text/css" href="<?=$cdn_base?>css/main.css" />
  <script type="text/javascript" src="<?=$cdn_base?>js/jquery.min.js"></script>
  <script type="text/javascript" src="<?=$cdn_base?>js/jquery.oembed.min.js"></script>
  <script type="text/javascript">window.baseurl="<?=$baseurl?>";<? if(isset($user)) echo "window.user='$user';"; if(isset($list)) echo "window.list='$list';"; if(isset($hours)) echo "window.hours='$hours';";?></script>
<style type="text/css">
body{
margin:0;
padding:0;
line-height: 1.5em;
}

b{font-size: 110%;}
em{color: red;}

#maincontainer{
width: 1000px; /*Width of main container*/
margin: 0 auto; /*Center container on page*/
}


#topsection h1{
margin: 0;
}

#contentwrapper{
float: left;
width: 100%;
}

#contentcolumn{
margin-left: 800px; /*Margin for content column. Should be (RightColumnWidth + LeftColumnWidth)*/
}

#leftcolumn{
float: left;
width: 400px; /*Width of left column in pixel*/
margin-left: -1000px; /*Set left margin to -(MainContainerWidth)*/
border-right:1px solid #eee;
}

#rightcolumn{
float: left;
width: 400px; /*Width of right column in pixels*/
margin-left: -600px; /*Set right margin to -(MainContainerWidth - LeftColumnWidth)*/
border-right:1px solid #eee;
}

#footer{
clear: left;
width: 100%;
background: black;
color: #FFF;
text-align: center;
padding: 4px 0;
}

#footer a{
color: #FFFF80;
}

.innertube{
margin: 10px; /*Margins for inner DIV inside each column (to provide padding)*/
margin-top: 0;
}

</style>


</head>
<body>
<div id="maincontainer">

<div id="topsection">
	<div class="innertube"><?=$header?></div>
</div>

<div id="contentwrapper">
<div id="contentcolumn">
<div class="innertube">
		<div id="tweetlabel">
  			<?=$channelname?> Trends
		</div>
		<?php 
		$i=1;
		foreach ($trends as $trend)	
		 {	
		 	echo "<br/>&nbsp;&nbsp;<a href='http://inagist.com/trends?t=".urlencode(strtolower($trend["phrase"]))."' style='font-size:18px;'>".$trend["phrase"]."</a><br/>";
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
		      } else if (eventObj.status == "connected") {
		        $("#q").parent().removeAttr("onsubmit");
		        $("#q").parent().removeAttr("onclick");
		        document.getElementById("q").value = '';
		        $("#q").parent().submit(function() {
		          if (document.getElementById("q").value!='')
		            ws.send("search " + document.getElementById("q").value);
		          $("#search").empty();
		          return false;
		        });
		      } else if (eventObj.status) {
		        console.log(eventObj.status);
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
		              	"<div>"+
		                "	<a href='http://twitter.com/"+tweet.user.screen_name+"' target='_blank' rel='nofollow' style='text-decoration: none;'> "+
		                "		<span class='user' style='padding-right: 0px;'>"+tweet.user.screen_name+"</span> " +
		              	"	</a>: "+linkify(tweet.text)+"</div>"+
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
		    alert(str);
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
<div id="scriptContainer">
    <script type="text/javascript" src="<?=$cdn_base?>js/common.js"></script>
    <script type="text/javascript" src="<?=$cdn_base?>js/modal.js"></script>
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
