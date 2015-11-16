var tweethash={}, iSocket;
var mouseOverDisplay = false;

// Set URL of your WebSocketMain.swf here:
WEB_SOCKET_SWF_LOCATION = "/netroy/live_ui/flash/WebSocketMain.swf";
// Set this to dump debug message from Flash to console.log:
WEB_SOCKET_DEBUG = false;


function animateScroll(div, matcher, height){
  $(div).animate({scrollTop : height}, 3000, function(){
    $(div).append($(matcher));
    $(div).scrollTop(0);
  });
}

function animateLeft(div, matcher){
  $(matcher).hide(function(){
    elem = $(matcher);
    $(div).append(elem);
    elem.show();
  });
}

function rand(base){
  return Math.floor(Math.random()*base);
}

function formatTweet(tweet){
  var tweetText = "<div class='actionbar'><span class='action retweet'></span>";
  tweetText += "<span class='action reply'></span><span class='action favorite'></span></div>";
  tweetText += "<span class='story_text'>" + twitterlib.ify.clean(tweet.text) + "</span><div class='clear'></div>";
  tweetText += "<div class='story_footer'><span class='story_icon'><a href='http://twitter.com/" + tweet.user.screen_name + "' target='_blank'";
  tweetText += "><img src='"+tweet.user.profile_image_url+"' alt='"+tweet.user.screen_name+"'></a></span>";
  tweetText += "<span class='story_info'><a href='http://twitter.com/"+tweet.user.screen_name+"' target='_blank' ";
  tweetText += "class='story_user'>" +tweet.user.screen_name + "</a>";
  tweetText += "<div><a href='http://twitter.com/" + tweet.user.screen_name+"/status/";
  tweetText += tweet.id_str +"' target='_blank' title='"+tweet.created_at+"' class='createdTime'>";
  tweetText += prettyDate(tweet.created_at) + "</a>";
  if (tweet.retweets)
    tweetText += " ♻" + tweet.retweets;
  else if (tweet.retweet_count)
    tweetText += " ♻" + tweet.retweet_count;
  if (tweet.mentions)
    tweetText += ", ↶" + tweet.mentions;
  tweetText += "</div></span><span class='fbshare'> . Share</span><span class='fblike'> . Like </span></div>";

  return tweetText;
}

function displayOembedData(oembed, tweet){
  $("#media_preview").html("");
  if (typeof oembed.code != 'undefined')
    $("#media_preview").html(oembed.code);
  else if (typeof oembed.html != 'undefined')
    $("#media_preview").html(oembed.html);
  else
    return false;
  $("#media_preview").append("<div class='clear'></div><div class='text'>"+formatTweet(tweet)+"</span>");
  $("#media_preview").append("<div class='clear'></div><span class='footer'><a href='"+oembed.url+
                             "' target='_blank'>"+oembed.provider_name+"</a></span>");
  $("#media_preview").removeClass("none");
}

$(function(){
  iSocket = new Instaket(userid, {retweets: false, trackwords: true, toplinks: true, debug: false,
                                  toptweets: true, level: level, 
                                  url: "wss://websockets.inagist.com/websockets_stream"});
  iSocket.connect();
  $(iSocket).bind('custom_event', function(event, e){
    console.log(e);
  });
  $(iSocket).bind('trending_personal_phrase', function(event, e){
      var phrase = e.phrase;
      var newTrend = jQuery("<li class='trend'><a href='http://inagist.com/"+userid+"/trends?t="+phrase+
                            "' target='_blank'>"+phrase+"</a></li>").
        data('phrase', phrase).data('user', userid);
    $("#live_trends").prepend(newTrend);
  });
  $(iSocket).bind('toplinks', function(event, links){
    for (var tweet in links){
      iSocket.lookuptweet(tweet, function(data){
        $.embedly(links[data.id_str], {maxWidth: 400}, function(oembed){
          if ((typeof oembed.thumbnail_url != 'undefined') && ($("#media1 #oembed_" + data.id_str).length == 0)){
            $("#media1").append(jQuery("<div class='oembedTweet' id='oembed_"+data.id_str+"'><img src='"+oembed.thumbnail_url+
                        "' /></div>").data("oembed", oembed).data("tweet", data));
          }
          $("#" + data.id_str).addClass("oembed_show").data("oembed", oembed);
        });
 
        var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                              "id", data.id_str).data("tweet", data);
        if ((jQuery.inArray(data.user.screen_name, official_accounts) >= 0) || (data.retweets && data.retweets > level * 2)){
          $("#tweet1").append(tweetDiv);
        }else
          $("#influential_tweets").append(tweetDiv);
      });
    }
  });
  $(iSocket).bind('toptweets', function(event, tweets){
    for (var tweet in tweets){
      iSocket.lookuptweet(tweet, function(data){
        var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                              "id", data.id_str).data("tweet", data);

        if ((jQuery.inArray(data.user.screen_name, official_accounts) >= 0) || (data.retweets && data.retweets > level * 2)){
          $("#tweet1").append(tweetDiv);
        }else
          $("#influential_tweets").append(tweetDiv);
      });
    }
  });
  $(iSocket).bind('trackwords', function(event, trackwords){
    for (trackword in trackwords){
      var newTrend = jQuery("<li class='trend'><a href='http://inagist.com/"+userid+"/trends?t="+trackwords[trackword]+
                            "' target='_blank'>"+trackwords[trackword]+"</a></li>").
        data('phrase', trackwords[trackword]).data('user', userid);
      $("#live_trends").append(newTrend);
    }
  });
  $(iSocket).bind('tweet_archived', function(event, tweet_archived){
    if ($("#" + tweet_archived.id_str).length == 0){
      iSocket.lookuptweet(tweet_archived.id_str, function(data){
        if (typeof tweet_archived.url != 'undefined'){
          $.embedly(tweet_archived.url, {maxWidth: 400}, function(oembed){
            if ((typeof oembed.thumbnail_url != 'undefined') && ($("#media1 #oembed_" + tweet_archived.id_str).length == 0)){
              $("#media1").prepend(jQuery("<div class='oembedTweet' id='oembed_"+data.id_str+
                "'> <img src='"+oembed.thumbnail_url+"' /></div>").
                data("oembed", oembed).data("tweet", data));
            }
            $("#" + data.id_str).addClass("oembed_show").data("oembed", oembed);
          });
        }
        var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                              "id", data.id_str).data("tweet", data);
        if ((jQuery.inArray(data.user.screen_name, official_accounts) >= 0) || (data.retweets && data.retweets > level * 2)){
          if ($("#tweet1 .tweet_box").length > 1)
            $("#tweet1 .tweet_box:first-child").after(tweetDiv);
          else
            $("#tweet1").append(tweetDiv);
        }else
          $("#influential_tweets").prepend(tweetDiv);
      });
    } else {
      if ($("#influential_tweets #" + tweet_archived.id_str).length > 0)
        $("#influential_tweets").prepend($("#influential_tweets #" + tweet_archived.id_str));
      else if ($("#tweet1 #" + tweet_archived.id_str).length > 0)
        $("#tweet1").prepend($("#tweet1 #" + tweet_archived.id_str));
      if ($("#oembed_" + tweet_archived.id_str).length > 0)
        $("#media1").prepend($("#oembed_" + tweet_archived.id_str));
    }
  });
  $(iSocket).bind('tweet retweeted_tweet external_retweeted_tweet', function(event, e){
    var data = e;
    if ($("#l_"+data.id_str).length == 0){
      var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                            "id", "l_" + data.id_str).data("id", data.id_str).data("tweet", data);
      if (jQuery.inArray(data.user.screen_name, official_accounts) >= 0)
        $("#tweet1 .tweet_box:first-child").after(tweetDiv);
      else
        $("#live_tweets").prepend(tweetDiv);
    }
  });
  $(iSocket).bind('nosocket', function(event, e){
    $("#nochrome").removeClass("hidden");
  });
  
  $("#chromebox").removeClass("hidden");
  setInterval(function(){
    $(".createdTime").prettyDate();
  },30000);
  $(".retweet").live('click', function(e){
    if ($(e.target).parent().parent().data('id') && typeof logged_in_user != 'undefined')
      retweet($(e.target).parent().parent().data('id'), logged_in_user);
  });
  $(".reply").live('click', function(e){
    if ($(e.target).parent().parent().data('id') && typeof logged_in_user != 'undefined')
      reply($(e.target).parent().parent().data('id'), logged_in_user, logged_in_user.name);
  });
  $(".favorite").live('click', function(e){
    if ($(e.target).parent().parent().data('id') && typeof logged_in_user != 'undefined')
      favorite($(e.target).parent().parent().data('id'), logged_in_user);
  });
 
  $(".oembed_show a, .oembedTweet a").live('click', function(e){
    e.preventDefault();
    displayOembedData($(e.target).parent().parent().data("oembed"), $(e.target).parent().parent().data("tweet"));
  });
  $(".oembedTweet img").live('click', function(e){
    e.preventDefault();
    displayOembedData($(e.target).parent().data("oembed"), $(e.target).parent().data("tweet"));
  });
  $("#media_preview").click(function(e){
    $("#media_preview").addClass("none");
  });
 
  $("#influential_tab").click(function(){
    $("#col_right").addClass("none");
    $("#col_left").addClass("none");
    $("#col_center").removeClass("none");
    $("#influential_tab").removeClass("disabled");
    $("#trends_tab").addClass("disabled");
    $("#live_tab").addClass("disabled");
  });
  $("#live_tab").click(function(){
    $("#col_center").addClass("none");
    $("#col_left").addClass("none");
    $("#col_right").removeClass("none");
    $("#live_tab").removeClass("disabled");
    $("#trends_tab").addClass("disabled");
    $("#influential_tab").addClass("disabled");
  });
  $("#trends_tab").click(function(){
    $("#col_center").addClass("none");
    $("#col_right").addClass("none");
    $("#col_left").removeClass("none");
    $("#live_tab").addClass("disabled");
    $("#influential_tab").addClass("disabled");
    $("#trends_tab").removeClass("disabled");
  });

  $(".fbshare").live('click', function(e){
    tweetData = $(e.target).parent().parent().data("tweet");
    FB.ui(
       {
         method: 'feed',
         display: 'popup',
         name: tweetData.text,
         link: tweetData.share_url,
         message: tweetData.text + " via @" + tweetData.user.screen_name
       }
     );
    });

  setInterval(function(){
    if ($("#media1 .oembedTweet").length > 1)
      animateLeft("#media1", "#media1 .oembedTweet:first-child");
  },30000);
  setInterval(function(){
    if ($("#tweet1 .tweet_box").length > 1)
      animateScroll("#tweet1", "#tweet1 .tweet_box:first-child", 115);
  },20000);
  setInterval(function(){
    while ($("#live_tweets .tweet_box").length > 20)
      $("#live_tweets .tweet_box:last-child").remove();
    while ($("#influential_tweets .tweet_box").length > 20)
      $("#influential_tweets .tweet_box:last-child").remove();
  }, 1000);
  setInterval(function(){
    $(".createdTime").prettyDate();
  },30000);

  // prepopulate official account
  var twitter_timeline_url = "http://api.twitter.com/1/statuses/user_timeline.json?include_entities=1&screen_name=" + official_accounts[0];
  if (typeof official_list != 'undefined')
    twitter_timeline_url = "http://api.twitter.com/1/"+official_list+"/statuses.json?include_entities=1";
  $.ajax({
    url: twitter_timeline_url,
    dataType: 'jsonp',
    success:function(timeline){
      for (i in timeline){
        var tweet = timeline[i];
        var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(tweet)+"</div>").attr(
                              "id", tweet.id_str).data("tweet", tweet);
        $("#tweet1").append(tweetDiv);
      }
    }
  });

  $.ajax({
    url:"http://inagist.com/getuser.php",
    success: function(e){
      if (typeof e.user_id != 'undefined'){
        logged_in_user = e;
        $("#notloggedin").remove();
        var sdiv = "<div id='loggedin'><img class='profile_pic' src='"+e.profile_image_url+"' /> " + e.name + "<br/>sign out</div>";
        $("#promobar").append(sdiv);
      }
    },
  });
});
