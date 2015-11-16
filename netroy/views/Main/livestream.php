  <style type="text/css"> 
  div.preview div.msg  {line-height: 17px;font-size: 12px; }
  div.preview div.stats {display:none;}
  div.preview div.msg span.time,div.preview div.replystats {font-size:9px;}
  li.notify {background-color: #0086B0;}
  span.live-track-term {font-size: 9px; color: #118611;};
  </style>
	<div class="tweetcontent">
		<br/>
		<div>
			<ul id="tabnav">
				<li class="tab-button tab-stream tab1"><a href="#" class="tabnavnotselected">Live Stream<span class='talert'> *</span></a></li> 
				<li class="tab-button tab-archives tab3"><a href="#" class="tabnavnotselected">In-A-Gist<span class='talert'> *</span></a></li> 
				<li class="tab-button tab-links tab4"><a href="#"  class="tabnavnotselected">URL Gist<span class='talert'> *</span></a></li>
				<li class="tab-button tab-trends tab2"><a href="#" class="tabnavnotselected">Trends<span class='talert'> *</span></a></li> 
				<li class="tab-button tab-interesting tab5"><a href="#"  class="tabnavnotselected">Suggested Reading<span class='talert'> *</span></a></li>
				<li class="tab-button tab-search tab6"><a href="#"  class="tabnavnotselected">Search Results<span class='talert'> *</span></a></li>
			</ul>
		</div>
		<div id="tweetstream" class="preview url round-corner status-tab" style="display: block;" rel="done">
		</div>
		<div id="archives" class="preview url round-corner status-tab" style="display: block;" rel="done">
		</div>
		<div id="links" class="preview url round-corner status-tab" style="display: block;" rel="done">
		</div>
		<div id="trends" class="preview url round-corner status-tab" style="display: block;" rel="done">
		</div>
		<div id="interesting" class="preview url round-corner status-tab" style="display: block;" rel="done">
		</div>
		<div id="search" class="preview url round-corner status-tab" style="display: block;" rel="done">
		</div>
	</div>


<div class="sharediv" style="left: 0px; width: 100px;-moz-border-radius:0 10px 10px 0;">
  <ul class="sharebutton">
    <li class="retweet-off notify">Retweets Off</li>
    <li class="retweet-on">Retweets On</li>
  </ul>
</div>
<script src="/netroy/js/pretty.js" type="text/javascript"></script>
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
      backfill_tweets(userid);
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
        ws.send("toptweets " + userid + " 5");
        ws.send("toplinks " + userid + " 5");
        ws.send("toptrends " + userid);
        ws.send("trackwords " + userid);
      } else if (eventObj.status) {
        console.log(eventObj.status);
      }else if (eventObj.toptweets){
        var toptweets = eventObj.toptweets;
        for (tweet in toptweets) {
          if ($("#atweet_" + tweet).length == 0)
            ws.send("tweet " + tweet);
        }
      } else if (eventObj.toplinks){
        var toptweets = eventObj.toplinks;
        for (tweet in toptweets) {
          if ($("#atweet_" + tweet).length == 0){
            ws.send("tweet " + tweet);
            ws.send("url " + toptweets[tweet]);
          }
        }
      } else if (eventObj.trackwords) {
        var trackwords = eventObj.trackwords;
        for (i in trackwords) {
          counters.trends++;
          if ($("#trends:visible").length == 0)
            $(".tab-trends .talert").show();
          deleteLast("trends", counters.trends, 60);
          var trackDiv = jQuery("<div class='msg even'><a rel='nofollow'"+
                             " target='_blank' href='http://inagist.com/" + userid +
                             "/trends?t=" + trackwords[i].replace(/ /g, "+") + "'><b>"
                             + trackwords[i] + "</b></a></div>").append(getLiveTrackSpan(trackwords[i]));
          $("#trends").prepend(trackDiv);
        }
      } else if (eventObj.toptrends) {
        var toptrends = eventObj.toptrends;
        for (trendid in toptrends) {
          var trendText = toptrends[trendid];
          counters.trends++;
          if ($("#trends:visible").length == 0)
            $(".tab-trends .talert").show();
          deleteLast("trends", counters.trends, 60);
          $("#trends").prepend("<div class='msg even'><a rel='nofollow'"+
                             " target='_blank' href='http://inagist.com/" + userid +
                             "/trends?t=" + trendText.replace(/ /g, "+") + "'>"
                             + trendText + "</a></div>");
        }
      } else if (eventObj.trending_channel_phrase) {
        var trendText = eventObj.trending_channel_phrase.phrase;
        var channelName = eventObj.trending_channel_phrase.channel;
        counters.trends++;
        if ($("#trends:visible").length == 0)
          $(".tab-trends .talert").show();
        deleteLast("trends", counters.trends, 60);
        var trackDiv = jQuery("<div class='msg even'><a rel='nofollow'"+
                             " target='_blank' href='http://inagist.com/" + channelName +
                             "/trends?t=" + trendText.replace(/ /g, "+") + "'>"
                             + trendText + "</a> - in " + channelName + "</div>").append(getLiveTrackSpan(trendText));
        $("#trends").prepend(trackDiv);
      } else if (eventObj.trending_personal_phrase) {
        var trendText = eventObj.trending_personal_phrase.phrase;
        var channelName = "you";
        counters.trends++;
        if ($("#trends:visible").length == 0)
          $(".tab-trends .talert").show();
        deleteLast("trends", counters.trends, 60);
        var trackDiv = jQuery("<div class='msg even'><a rel='nofollow'"+
                             " target='_blank' href='http://inagist.com/" + channelName +
                             "/trends?t=" + trendText.replace(/ /g, "+") + "'>"
                             + trendText + "</a> - in " + channelName + "</div>").append(getLiveTrackSpan(trendText));
        $("#trends").prepend(trackDiv);
      } else if (eventObj.trending_phrase) {
        var trendText = eventObj.trending_phrase.phrase;
        counters.trends++;
        if ($("#trends:visible").length == 0)
          $(".tab-trends .talert").show();
        deleteLast("trends", counters.trends, 60);
        var trackDiv = jQuery("<div class='msg even'><a rel='nofollow'"+
                             " target='_blank' href='http://inagist.com/" + userid +
                             "/trends?t=" + trendText.replace(/ /g, "+") + "'>"
                             + trendText + "</a></div>");
        $("#trends").prepend(trackDiv);
      } else if (eventObj.tweet_archived) {
        if ($("#atweet_" + eventObj.tweet_archived.id_str).length > 0) {
          var displayedTweet = $("#atweet_" + eventObj.tweet_archived.id_str).detach();
          displayedTweet.prependTo("#archives");
          ws.send("tweetstat " + eventObj.tweet_archived.id_str);
        } else {
          ws.send("tweet " + eventObj.tweet_archived.id_str);
          if (eventObj.tweet_archived.url)
            ws.send("url " + eventObj.tweet_archived.url)
        }
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
      } else if (eventObj.lookedup_url) {
        var url = eventObj.lookedup_url;
        if (url.title){
          counters.links++;
          if ($("#links:visible").length == 0)
            $(".tab-links .talert").show();
          deleteLast("links", counters.links, 100);
          var description = "";
          if (url.description)
            description = url.description;
					var image = "";
					if (url.attributes.image_src)
						image="<a class='left' rel='nofollow' target='_blank' href='" + url.url +
									"'><img src='" + url.attributes.image_src + "'></a>";
          $("#links").prepend("<div class='msg even'>" + image + 
                              "<a rel='nofollow' target='_blank' href='" + url.url + "'> " + 
                              url.title + "</a><br>" + description + "</div>");
        }
      } else if (eventObj.lookedup_tweet) {
        var tweet = eventObj.lookedup_tweet;
        var displayDiv = "#archives";
        var displayTab = ".tab-archives";
        var divPrefix = "atweet_" + tweet.id_str;
        var matchText = "";
        if (tweet.corelation) {
          displayDiv = "#search";
          divPrefix = "search_" + tweet.id_str;
          displayTab = ".tab-search";
          matchText = tweet.corelation;
        } else {
          counters.archives++;
          deleteLast("archives", counters.archives, 100);
        }
        
        if ($("#" + divPrefix + tweet.id_str).length > 0) {
          var displayedTweet = $("#" + divPrefix + tweet.id_str).detach();
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
          
          displayedTweet.prependTo(displayDiv);
        } else {
          if ($(displayDiv + ":visible").length == 0)
            $(displayTab + " .talert").show();
          var tweetDiv = jQuery("<div id='" + divPrefix + 
                                    "' class='msg even'> <a class='left' rel='nofollow' " +
                                    "target='_blank' href='http://inagist.com/" +
                                    tweet.user.screen_name + "/" + tweet.id_str +
                                    "'><img height='20' width='20' src='" + 
                                    tweet.user.profile_image_url + "'/></a> " + 
                                    "<a target='_blank' href='http://twitter.com/" + tweet.user.screen_name +
                                    "'><span class='user'>" + tweet.user.screen_name +
                                    "</span></a> : " +
                                    linkify(tweet.text) + " <span class='time' title='" +tweet.created_at+ "'>" + 
                                    prettyDate(tweet.created_at) + "</span> " + matchText + "</div>");
          var tweetStats = jQuery("<div class='replystats'></div>");
          if (tweet.mentions)
            tweetStats.append(jQuery("<span class='replies'>" + tweet.mentions + " replies | </span>"));
          if (tweet.retweets)
            tweetStats.append(jQuery("<span class='mentions'>" + tweet.retweets + " retweets</span>"));
          
          $(displayDiv).prepend(tweetDiv.append(tweetStats));
        }
      } else if (eventObj.type == "favourite_tweet") {
        if ($("#itweet_" + eventObj.id_str).length > 0) {
          var displayedTweet = $("#itweet_" + eventObj.id_str).detach();
          displayedTweet.attr("title", eventObj.match_string);
          displayedTweet.prependTo("#interesting");
        } else {
          counters.interesting++;
          deleteLast("interesting", counters.interesting, 100);
          var tweet = eventObj;
          if ($("#interesting:visible").length == 0)
            $(".tab-interesting .talert").show();
          $("#interesting").prepend("<div id='itweet_" + tweet.id_str + "' class='msg even'>"+
                                    " <a class='left' rel='nofollow' title='" + tweet.match_string + "'" +
                                    "target='_blank' href='http://inagist.com/" +
                                    tweet.user.screen_name + "/" + tweet.id_str +
                                    "'><img height='20' width='20' src='" + 
                                    tweet.user.profile_image_url + "'/></a> " + 
                                    "<a target='_blank' href='http://twitter.com/" + tweet.user.screen_name +
                                    "'><span class='user'>" + tweet.user.screen_name +
                                    "</span></a> : " +
                                    linkify(tweet.text) + " <span class='time' title='" +tweet.created_at+ "'>" + 
                                    prettyDate(tweet.created_at) + "</span></div>");
        }
      } else if (eventObj.search_result) {
        if (eventObj.search_result.ids) {
          for (var i in eventObj.search_result.ids) {
            ws.send("tweet " + eventObj.search_result.ids[i] + " matches search");
          }
        } else {
          ws.send("tweet " + eventObj.search_result.id_str + " matches " + eventObj.search_result.text);
        } 
      } else {
        counters.tweets++;
        deleteLast("tweetstream", counters.tweets, 100);
        var tweet = eventObj;
        if ($("#tweetstream:visible").length == 0)
          $(".tab-stream .talert").show();
        $("#tweetstream").prepend("<div class='msg even'><a class='left' rel='nofollow'"+
                                  " target='_blank' href='http://twitter.com/" +
                                  tweet.user.screen_name + "/status/" + tweet.id_str +
                                  "'><img height='20' width='20' src='" + 
                                  tweet.user.profile_image_url + "'/></a> " + 
                                  "<a target='_blank' href='http://twitter.com/" + tweet.user.screen_name +
                                  "'><span class='user'>" + tweet.user.screen_name +
                                  "</span></a> : " +
                                  linkify(tweet.text) + " <span class='time' title='"+tweet.created_at+"'>" + 
                                  prettyDate(tweet.created_at) + "</span></div>");
      }
    };

    ws.onclose = function()
    {
      debug(" socket closed");
    };
  } else {
    debug("You have no web sockets");
  };

  function debug(str){
    console.log(str);
  };

  function backfill_tweets(queryText) {
    $.oldajax({
      url: 'http://search.twitter.com/search.json?rpp=10&paginate=false&q=' + queryText,
      dataType: "jsonp",
      success:function(data) {
        for ( i in data.results){
          counters.tweets++;
          var tweet = data.results[i];
          $("#tweetstream").append("<div class='msg even'><a class='left' rel='nofollow'"+
                                  " target='_blank' href='http://twitter.com/" +
                                  tweet.from_user + "/status/" + tweet.id_str +
                                  "'><img height='20' width='20' src='" + 
                                  tweet.profile_image_url + "'/></a> " + 
                                  "<a target='_blank' href='http://twitter.com/" + tweet.from_user +
                                  "'><span class='user'>" + tweet.from_user +
                                  "</span></a> : " + linkify(tweet.text) + 
                                  " <span class='time' title='" +tweet.created_at + "'>" + 
                                  prettyDate(tweet.created_at) + "</span></div>");
        }
        $(".tab-stream").click();
      }
    });
  };

  $("#tabnav .talert").hide();
  $(".status-tab").hide();
  $("#archives").show();

  $(".tab-button").click(function(){
    $(".status-tab:visible").hide();
  });
  $(".tab-stream").click(function(){
    $("#tweetstream").show();
    $(".tab-stream .talert").hide();
  });
  $(".tab-archives").click(function(){
    $("#archives").show();
    $(".tab-archives .talert").hide();
  });
  $(".tab-links").click(function(){
    $("#links").show();
    $(".tab-links .talert").hide();
  });
  $(".tab-trends").click(function(){
    $("#trends").show();
    $(".tab-trends .talert").hide();
  });
  $(".tab-interesting").click(function(){
    $("#interesting").show();
    $(".tab-interesting .talert").hide();
  });
  $(".tab-search").click(function(){
    $("#search").show();
    $(".tab-search .talert").hide();
  });

  $(".retweet-off").click(function(){
    ws.send("retweets_off");
    $(".retweet-off").addClass("notify");
    $(".retweet-on").removeClass("notify");
  });
  $(".retweet-on").click(function(){
    ws.send("retweets_on");
    $(".retweet-off").removeClass("notify");
    $(".retweet-on").addClass("notify");
  });
  setInterval(function(){ $(".time").prettyDate(); }, 30000);

});
</script>
