var tweethash={}, iSocket;
var mouseOverDisplay = false;
    
// Set URL of your WebSocketMain.swf here:
WEB_SOCKET_SWF_LOCATION = "/netroy/live_ui/flash/WebSocketMain.swf";
// Set this to dump debug message from Flash to console.log:
WEB_SOCKET_DEBUG = false;
logged_in_user = undefined;

function displayTweetInBox(tweetbox, text, created_at, user){
  tweetbox.html(text);
  tweetbox.append((parseInt(((new Date()).getTime() - Date.parse(created_at)) / 1000) + " secs ago").sub());
  tweetbox.append(user.screen_name.bold());
}

function formatTweet(tweet){
  var tweetText = "<img src='"+tweet.user.profile_image_url+"'><b>";
  tweetText += tweet.user.screen_name+"</b> "+tweet.text;
  tweetText += "<br/><span class='createdTime' title='"+tweet.created_at+"'>"+prettyDate(tweet.created_at)+"</span>";
  if (tweet.retweets)
    tweetText += "<span class='stats retweetStat'>"+tweet.retweets+" RT</span>";
  if (tweet.mentions)
    tweetText += " <span class='stats replyStat'>"+tweet.mentions+" Reply</span>";
  return tweetText;
}

function animateTrends(){
  $("#trends").animate({scrollTop: 54}, 2000, function(){
    $("#trends").append($("#trends li:first-child"));
    $("#trends").scrollTop(27);
  });
}

function animateMedia(){
  $("#media").animate({scrollTop : 210}, 3000, function(){
    $("#media").append($("#media div:first-child"));
    $("#media").scrollTop(105);
  });
}

function addActionsDiv(target){
  var actionsBar = "<span class='actionbar'>"+
                   "<span class='action retweet' title='Retweet'></span>" +
                   "<span class='action reply' title='Reply'></span>" +
                   "<span class='action favorite' title='Favorite'></span>"+
                   "</span>";
  target.append(jQuery(actionsBar));
}

function retweet(tweetid, user){
  $(document.body).append("<div id='dialog'> <img src='"+user.profile_image_url+"' title='"+user.user_id+"'/>"+
                          " Retweet this? <button id='okbutton'>"+
                          " OK </button> <button id='cancelbutton'> Cancel </button> </div>");
  $("#okbutton").click(function(){
    $.ajax({
      type: 'POST',
      url:"http://inagist.com/retweet.php?tweet_id=" + tweetid,
      success: function(){ 
        $("#dialog").html("Retweeted"); 
        setTimeout(function(){
          $("#dialog").remove();
        }, 3000)
      },
      error: function() {showLogin();}
    });
  });
  $("#cancelbutton").click(function(){
    $("#dialog").remove();
  });
}

function reply(tweetid, user, screenName){
  $(document.body).append("<div id='dialog' style='width: 95%; left: 2px; top: 0px;'>"+
    "<img src='"+user.profile_image_url+"' title='"+user.user_id+"'/>"+
    "<textarea id='replyText' rows='3' cols='38'>@" + screenName + "</textarea>" +
    " <button id='okbutton'> OK </button> <button id='cancelbutton'> Cancel </button> </div>");
  $("#okbutton").click(function(){
    $.ajax({
      type: 'POST',
      url:"http://inagist.com/reply.php?tweet_id=" + tweetid + "&tweet_text="+ $("#replyText").text(),
      success: function(){ 
        $("#dialog").html("Reply sent"); 
        setTimeout(function(){
          $("#dialog").remove();
        }, 3000)
      },
      error: function() {showLogin();}
    });
  });
  $("#cancelbutton").click(function(){
    $("#dialog").remove();
  });
}

function favorite(tweetid, user){
  $(document.body).append("<div id='dialog'> <img src='"+user.profile_image_url+"' title='"+user.user_id+"' />"+
                          " Favorite this? <button id='okbutton'>"+
                          " OK </button> <button id='cancelbutton'> Cancel </button> </div>");
  $("#okbutton").click(function(){
    $.ajax({
      type: 'POST',
      url:"http://inagist.com/favorite.php?tweet_id=" + tweetid,
      success: function(){ 
        $("#dialog").html("Favorited"); 
        setTimeout(function(){
          $("#dialog").remove();
        }, 3000)
      },
      error: function() {showLogin();}
    });
  });
  $("#cancelbutton").click(function(){
    $("#dialog").remove();
  });
}

$(function(){
  var tweetbox = $("#tweet");
  var boxes = $("#container a").live("mouseover",function(e){
    var id = $(this).attr("id");
    if(typeof tweethash[id] == 'undefined') return;
    mouseOverDisplay = true;
    displayTweetInBox(tweetbox, tweethash[id].text, tweethash[id].created_at, tweethash[id].user);
  }).live("mouseout",function(e){
    tweetbox.empty();
    mouseOverDisplay = false;
  });

  var count = boxes.length;
  var userid = USERID?USERID:$.getURLParam('userid')?$.getURLParam('userid'):"grandslam";
  var level = $.getURLParam('level')?$.getURLParam('level'):3;
  iSocket = new Instaket(userid, {retweets: true, trackwords: true, toplinks: true, debug: false,
                                  toptweets: true, level: level, 
                                  url: "wss://websockets.inagist.com/websockets_stream"});
  iSocket.connect();
  $(iSocket).bind('custom_event', function(event, e){
    console.log(e);
  });
  $(iSocket).bind('trending_phrase', function(event, e){
      var level = e.level;
      var phrase = e.phrase;
      var newTrend = jQuery("<li rel='"+level+"' class='trend'>"+phrase+"</li>").data('phrase', phrase).data('user', userid);
      newTrend.addClass("new");
      setTimeout(function(){
        newTrend.removeClass("new");
      },3000);
    $("#trends").removeClass("hidden");
    $("#trends li:nth-child(2)").after(newTrend);
  });
  $(iSocket).bind('trending_personal_phrase', function(event, e){
      var level = e.level;
      var phrase = e.phrase;
      var newTrend = jQuery("<li rel='"+level+"' class='trend'>"+phrase+"</li>").data('phrase', phrase).data('user', userid);
      newTrend.addClass("new");
      setTimeout(function(){
        newTrend.removeClass("new");
      },3000);
    $("#trends").removeClass("hidden");
    $("#trends li:nth-child(2)").after(newTrend);
  });
  $(iSocket).bind('trending_channel_phrase', function(event, e){
      var level = e.level;
      var phrase = e.phrase;
      var newTrend = jQuery("<li rel='"+level+"' class='trend'>"+phrase+"</li>").data('phrase', phrase).data('user', e.channel);
      newTrend.addClass("new");
      setTimeout(function(){
        newTrend.removeClass("new");
      },3000);
    $("#trends").removeClass("hidden");
    $("#trends li:nth-child(2)").after(newTrend);
  });
  $(iSocket).bind('toplinks', function(event, links){
    for (var tweet in links){
      iSocket.lookuptweet(tweet, function(data){
        $.embedly(links[data.id_str], {maxWidth: 150}, function(oembed){
          var oembedDiv = jQuery("<span class='oembedText'>"+oembed.title + " " + data.text + "</span>");
          $("#media").append(jQuery("<div class='oembedTweet'></div>").html(
            "<a href='" +oembed.url+ "'><img src='" + oembed.thumbnail_url + "'/></a>").append(oembedDiv));
        });
        var trendDiv = jQuery("<div class='tweet'>"+formatTweet(data)+"</div>").attr("id", data.id_str).data("id", data.id_str);
        addActionsDiv(trendDiv);
        $("#media").append(trendDiv);
      });
    }
    $("#media").scrollTop(105);
  });
  $(iSocket).bind('toptweets', function(event, tweets){
    for (var tweet in tweets){
      iSocket.lookuptweet(tweet, function(data){
        var trendDiv = jQuery("<div class='tweet'>"+formatTweet(data)+"</div>").attr("id", data.id_str).data("id", data.id_str);
        addActionsDiv(trendDiv);
        $("#media").append(trendDiv);
      });
    }
    $("#media").scrollTop(105);
  });
  $(iSocket).bind('trackwords', function(event, trackwords){
    for (trackword in trackwords){
      var newTrend = jQuery("<li class='trend'>"+trackwords[trackword]+"</li>").
        data('phrase', trackwords[trackword]).data('user', userid);
      $("#trends").removeClass("hidden").append(newTrend);
    }
    $("#trends").scrollTop(27);
  });
  $(iSocket).bind('tweet_archived', function(event, tweet_archived){
    if ($("#media #" + tweet_archived.id_str).length == 0){
      iSocket.lookuptweet(tweet_archived.id_str, function(data){
        if (typeof tweet_archived.url != 'undefined'){
          $.embedly(tweet_archived.url, {maxWidth: 150}, function(oembed){
            var oembedDiv = jQuery("<span class='oembedText'>"+oembed.title + " " + data.text + "</span>");
            $("#media div:nth-child(3)").after(jQuery("<div class='oembedTweet' id='oembed"+data.id_str+"'></div>").html(
              "<a href='" +oembed.url+ "'><img src='" + oembed.thumbnail_url + "'/></a>").append(oembedDiv));
          });
        }
        var trendDiv = jQuery("<div class='tweet'>"+formatTweet(data)+"</div>").attr("id", data.id_str).data("id", data.id_str);
        addActionsDiv(trendDiv);
        $("#media div:nth-child(3)").after(trendDiv);
      });
    } else {
      $("#media div:nth-child(3)").after($("#media #" + tweet_archived.id_str));
      if ($("#oembed" + tweet_archived.id_str).length > 0)
        $("#media div:nth-child(4)").after($("#oembed" + tweet_archived.id_str));

    }
  });
  $(iSocket).bind('tweet retweeted_tweet external_retweeted_tweet', function(event, e){
    var box;
    (new Image()).src=e.user.profile_image_url; // prelaod image

    if(!(box=document.getElementById(e.id_str))){
      tweethash[e.id_str] = e;
      box = $(boxes[parseInt(Math.random()*count)]);
      box.html("<img src='"+e.user.profile_image_url+"' />");
      box.attr("id",e.id_str).attr("title",e.text).attr("href",
        "http://inagist.com/"+e.user.screen_name+"/"+e.id_str).addClass("tweet");
      if (!mouseOverDisplay)
        displayTweetInBox($("#tweet"), e.text, e.created_at, e.user);
      box.data("id", e.id_str);
//      addActionsDiv(box);
      setTimeout(function(){
        box.removeClass("old");
      },5000);
      box.addClass("new");
      setTimeout(function(){
        box.removeClass("new").addClass("old");
      },1000);
    }
  });
  $(iSocket).bind('nosocket', function(event, e){
    $("#nochrome").removeClass("hidden");
  });
  $(iSocket).bind('connection_opened', function(event, e){
    $("#chromebox").removeClass("hidden");
  });
  $(".trend").live('click', function(e){
    if ($(e.target).data('phrase')){
      $.ajax({
        url: "http://inagist.com/api/v1/get_top_trends?type=phrase&summarize=0&show_tweets=1&userid="+
          $(e.target).data('user')+"&key="+$(e.target).data('phrase'),
        dataType: 'jsonp',
        success: function(data){
          $("#infobox .results").empty();
          $("#infobox").slideDown('slow');
          $("#infobox .boxchrome").html($(e.target).data('phrase'));
          for (var x in data){
            for (var y in data[x]){
              var results = data[x][y];
              for (var i in results){
                var trendDiv = jQuery("<div>"+formatTweet(results[i])+"</div>");
                $("#infobox .results").prepend(trendDiv);
              }
            }
          }
        },
      });
    }
  });
  $("#chromebox").removeClass("hidden");
  $(".boxchrome").click(function(e){
    $(e.target).parent().slideUp('slow');
  });
  setInterval(function(){
    animateTrends();
  },10000);
  setInterval(function(){
    animateMedia();
  },10000);
  setInterval(function(){
    $(".createdTime").prettyDate();
  },30000);
  $("#container a").hover(function(e){
    if ($("img", $(e.target)).length > 0)
      addActionsDiv($(e.target));
  }, function(e){
    $(".actionbar", $(e.target)).remove();
  });
  $(".retweet").live('click', function(e){
    if ($($(e.target).parent().parent()).data('id') && typeof logged_in_user != 'undefined')
      retweet($($(e.target).parent().parent()).data('id'), logged_in_user);
  });
  $(".reply").live('click', function(e){
    if ($($(e.target).parent().parent()).data('id') && typeof logged_in_user != 'undefined')
      reply($($(e.target).parent().parent()).data('id'), logged_in_user, logged_in_user.name);
  });
  $(".favorite").live('click', function(e){
    if ($($(e.target).parent().parent()).data('id') && typeof logged_in_user != 'undefined')
      favorite($($(e.target).parent().parent()).data('id'), logged_in_user);
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
