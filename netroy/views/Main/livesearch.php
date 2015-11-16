  <style type="text/css"> 
  div.preview div.msg  {line-height: 17px;font-size: 12px; }
  div.preview div.stats {display:none;}
  div.preview div.msg span.time,div.preview div.replystats {font-size:9px;}
  span.searchtext {font-size: 14px; padding-left: 12px;}
  li.notify {background-color: #0086B0;}
  </style>
	<div class="tweetcontent">
		<div id="tweetlabel">
    Live News on - <span id="searchtext" class="searchtext"></span>
		</div>
		<br/>
		<div id="search" class="preview url round-corner status-tab" style="display: block;" rel="done">
		</div>
	</div>

<script src="/netroy/js/pretty.js" type="text/javascript"></script>
<script>

/* Copyright (c) 2006 Mathias Bank (http://www.mathias-bank.de)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) 
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 * 
 * Thanks to Hinnerk Ruemenapf - http://hinnerk.ruemenapf.de/ for bug reporting and fixing.
 */
jQuery.extend({
/**
* Returns get parameters.
*
* If the desired param does not exist, null will be returned
*
* @example value = $.getURLParam("paramName");
*/ 
 getURLParam: function(strParamName){
    var strReturn = "";
    var strHref = window.location.href;
    var bFound=false;
    
    var cmpstring = strParamName + "=";
    var cmplen = cmpstring.length;

    if ( strHref.indexOf("?") > -1 ){
      var strQueryString = strHref.substr(strHref.indexOf("?")+1);
      var aQueryString = strQueryString.split("&");
      for ( var iParam = 0; iParam < aQueryString.length; iParam++ ){
        if (aQueryString[iParam].substr(0,cmplen)==cmpstring){
          var aParam = aQueryString[iParam].split("=");
          strReturn = aParam[1];
          bFound=true;
          break;
        }
        
      }
    }
    if (bFound==false) return null;
    return strReturn;
  }
});


function deleteLast(divid, count, refcheck) {
  if (count > refcheck) {
    $("#" + divid + " div:last-child").remove();
  }
}

var ws;
$(document).ready(function(){
  var counters = {tweets:0, archives:0, links:0, trends:0, interesting:0, search:0};
  
  if ("WebSocket" in window) {
    ws = new WebSocket("ws://websockets.inagist.com:18010/websockets_stream");
    var userid = "<?=$user?>";
    
    ws.onopen = function() {
      // Web Socket is connected. You can send data by send() method.
      $("#q").parent().removeAttr("onsubmit");
      $("#q").parent().submit(function() {
        if (document.getElementById("q").value!='')
          ws.send("search " + document.getElementById("q").value);
        $("#search").empty();
        $("#searchtext").text(document.getElementById("q").value);
        return false;
      });
      if ($.getURLParam("q")) {
        document.getElementById("q").value = $.getURLParam("q");
        $("#q").parent().submit();
      }
    };

    ws.onmessage = function (evt)
    {
      var data = evt.data;
      var eventObj = JSON.parse(data);
      if (eventObj.error){
        debug("Error " + eventObj.error);
      } else if (eventObj.status) {
        console.log(eventObj.status);
      } else if (eventObj.lookedup_tweet) {
        var tweet = eventObj.lookedup_tweet;
        var displayDiv = "#search";
        var displayTab = ".tab-search";
        var divPrefix = "search_" + tweet.id_str;
        counters.search++;
        deleteLast("archives", counters.search, 100);
        
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
          var matchText = "";
          if (tweet.corelation)
            matchText = "matches " + tweet.corelation;

          var tweetDiv = jQuery("<div id='" + divPrefix + 
                                    "' class='msg even'> <a class='left' rel='nofollow' " +
                                    "target='_blank' href='http://inagist.com/" +
                                    tweet.user.screen_name + "/" + tweet.id_str +
                                    "'><img height='20' width='20' src='" + 
                                    tweet.user.profile_image_url + "'/></a> " + 
                                    "<a target='_blank' href='http://twitter.com/" + tweet.user.screen_name +
                                    "'><span class='user'>" + tweet.user.screen_name +
                                    "</span></a> : " +
                                    linkify(tweet.text) + " <span class='time' title='"+tweet.created_at+"'>" + 
                                    prettyDate(tweet.created_at) + "</span> "+
                                    matchText + "</div>");
          var tweetStats = jQuery("<div class='replystats'></div>");
          if (tweet.mentions)
            tweetStats.append(jQuery("<span class='replies'>" + tweet.mentions + " replies | </span>"));
          if (tweet.retweets)
            tweetStats.append(jQuery("<span class='mentions'>" + tweet.retweets + " retweets</span>"));
          
          $(displayDiv).prepend(tweetDiv.append(tweetStats));
        }
      } else if (eventObj.search_result) {
        if (eventObj.search_result.ids) {
          for (var i in eventObj.search_result.ids) {
            ws.send("tweet " + eventObj.search_result.ids[i] + " search");
          }
        } else {
          ws.send("tweet " + eventObj.search_result.id_str + " " + eventObj.search_result.text);
        } 
      } else {
        console.log("Unhandled data " + data);
      }
    };

    ws.onclose = function()
    {
      debug(" socket closed");
    };
  } else {
    alert("You have no web sockets");
  };

  setInterval(function(){ $(".time").prettyDate(); }, 30000);

  function debug(str){
    console.log(str);
  };
});
</script>
