// Set URL of your WebSocketMain.swf here:
WEB_SOCKET_SWF_LOCATION = "/netroy/live_ui/flash/WebSocketMain.swf";
// Set this to dump debug message from Flash to console.log:
WEB_SOCKET_DEBUG = false;

$(function(){
  var tweethash={}, iSocket;
  var mouseOverDisplay = false;
  var maxNodes = 45;
  var maxRows = parseInt(maxNodes/5);
  var boxSize = 190;

  function render(template,data){
    return template.replace(/{[\w\.\(\)]+}/g,function(match){
      var token = match.replace(/[\{\}]/g,"");
      try{
      with(data){
          return eval(token);
        }
      }catch(e){
        return "";
      }
    });
  }

  function rand(base){
    return Math.floor(Math.random()*base);
  }

  function generateID(Key){
    if (typeof Key == 'string')
      return escape(Key.replace(/[ \/\.]/g, "_")).replace(/%/g, "_");
    else
      return Key;
  }

  function trackEvent(P1, P2, P3){
    if (typeof _gaq != 'undefined')
      _gaq.push(['_trackEvent', userid, P1, P2, P3]);
  }

  var oEmbedTemplate = '<li class="oembedTweet tweet anim"><img src="{oembed.thumbnail_url}" title="{eTweet.text} - @{eTweet.user.screen_name}" /></li>';
  var selectorTemplate = "#{eTweet.id_str}, #head_{eTweet.id_str}, #search_{eTweet.id_str}, #l_{eTweet.id_str}";
  function getOembedCallback(eTweet){
    return function(oembed){
      oembed.url = oembed.url || oembed.thumbnail_url || oembed.provider_url;
      if ((typeof oembed.thumbnail_url != 'undefined') && ($("#oembed_" + generateID(oembed.url)).length == 0)){
        var markup = render(oEmbedTemplate,{"oembed":oembed,"eTweet":eTweet});
        var markupObj = jQuery(markup).data("oembed", oembed).
          data("tweet", eTweet).attr("id", "oembed_" + generateID(oembed.url));
        tweetList.trigger("queue",[markupObj]);
      }
      $(render(selectorTemplate,{"eTweet":eTweet})).addClass("oembed_show").data("oembed", oembed);
    }
  }

  function displayOembedData(oembed, tweet){
    $("#media_preview").html("");
    if (typeof oembed.code != 'undefined')
      $("#media_preview").html(oembed.code);
    else if (typeof oembed.html != 'undefined')
      $("#media_preview").html(oembed.html);
    else
      return false;
    $("#media_preview").append("<div class='clear'></div>");
    $("#media_preview").append("<div class='clear'></div><span class='footer'><a href='"+oembed.url+
                               "' target='_blank'>"+oembed.provider_name+"</a></span>");
    appendFBLike(oembed.url, $("#media_preview"));
    $("#media_preview_container").removeClass("none");
  }

  function appendFBLike(Url, Div){
    Div.append("<fb:like layout='button_count'"+
               " href='" + Url + "' "+ 
               " show_faces='false' width='150' colorscheme='dark'></fb:like>");
    FB.XFBML.parse(Div[0]||Div);
  }

  var notableIDs = official_accounts;
  var notableList = $("#notable_tweet_list");
  for(var i=0,l=notableIDs.length;i<l;i++){
    notableList.append($(render("<div id='notable_{id}'></div>",{"id":notableIDs[i]})));
  }

  var tweetTemplate = '<li class="tweet anim {tweet.fromFollow}" data="{tweet.id_str}" id="t_{tweet.id_str}"><span class="text georgia anim">{twitterlib.ify.clean(tweet.text)}</span><a href="http://twitter.com/{tweet.user.screen_name}" target="_blank"><b class="name verdana">{tweet.user.screen_name}</b><img class="prof" src="{tweet.user.profile_image_url}" alt="{tweet.user.screen_name}"/></a><time datetime="{tweet.created_at}" class="verdana"><a href="http://twitter.com/{tweet.user.screen_name}/status/{tweet.id_str}" target="_blank" title="{tweet.created_at}" class="createdTime">{prettyDate(tweet.created_at)}</a></time><div class="meta clearfix anim"><div class="toolbar"><a title="reply to this" href="javascript:void(0);"><span class="replybt icon"> </span></a><a title="retweet this" href="javascript:void(0);"><span class="retweetbt icon"> </span></a><a title="favorite this" href="javascript:void(0);"><span class="favoritebt icon"> </span></a></div></div></li>';

  var tweetList = $("#tweet_list");
  var tweetQueue = [];
  var tweetIndex = 0;

  function formatTweet(tweet){
    tweet.fromFollow = tweet.from_follow ? " from_follow" : "";
    if (typeof tweet.retweeted_status == 'object'){
      tweet.retweeted_status.fromFollow = tweet.fromFollow;
      tweet = tweet.retweeted_status;
    }
    var tweetText = render(tweetTemplate,{"tweet":tweet});
    tweetText = $(tweetText);
    displayTweetOembedData(tweet);
    return tweetText.data("tweet", tweet);
  }
  window.formatTweet = formatTweet;

  function pushToQueue(e,tweet){
    var orig = $("#" + tweet.attr("id"));
    if (orig.length == 0)
      tweetQueue.push(tweet);
  }
  tweetList.bind("queue",pushToQueue);

  function cleanOldNodes(){
    $(".createdTime").prettyDate();
    var children = tweetList.children("li,br");
    var lastBR = $("br:last-of-type",tweetList);
    if(lastBR.length == 0 || children.length == 0 || tweetList.children("li").length <= maxNodes) return;
    var index = children.toArray().indexOf(lastBR[0]);
    for(var l=children.length;index<l;index++){
      $(children[index]).remove();
    }
  }
  setInterval(cleanOldNodes,10000);
  tweetList.height(maxRows*(boxSize+1));
	
  function insertTweet(length){
    if(tweetQueue.length == 0) return;
    for(var i = 0;i < length; i++){
      var tweet = tweetQueue.shift();
      var orig = $("#" + tweet.attr("id"));
      if (orig.length == 0){
        if(tweetList.children("li").length % 5 == 0)
          tweetList.prepend($("<br/>").css("clear","both"));
        tweet = tweet.height("1px");
        $("br:first-of-type",tweetList).before(tweet);
        tweet.animate({"height":"190px"},150);
        if(tweetIndex++ % 2 == 0) tweet.addClass("odd");
      }
    }
    cleanOldNodes();
  }
  setInterval(function(){
    var max = Math.floor(tweetQueue.length/5)*5;
    insertTweet((tweetQueue.length > 4)?max:1);
  },500);

  function displayTweetOembedData(data){
    if (data.entities && typeof data.entities.urls == 'object'){
      var tweetlinks = data.entities.urls;
      for (var i in tweetlinks){
        var url = tweetlinks[i].expanded_url || tweetlinks[i].url;
        if ($("#oembed_" + generateID(url)).length == 0)
          $.embedly(url, {}, getOembedCallback(data));
      }
    }
    if (data.entities && typeof data.entities.long_urls == 'object'){
      var tweetlinks = data.entities.long_urls;
      for (var i in tweetlinks){
        var url = tweetlinks[i];
        if ($("#oembed_" + generateID(url)).length == 0)
          $.embedly(url, {}, getOembedCallback(data));
      }
    }
  }

  iSocket = new Instaket(userid, {retweets: false, trackwords_with_stat: false, toplinks: true, debug: false,
                                  toptweets: true, level: level, live: false,
                                  url: "ws://websockets.inagist.com:18010/websockets_stream"});
  iSocket.connect();
  $(iSocket).bind('custom_event', function(event, e){
    console.log(e);
  });
  $(iSocket).bind('toplinks toptweets', function(event, results){
    for (var tweet in results){
      if ($("#t_" + tweet).length == 0){
        iSocket.lookuptweet(tweet, function(data){
          var tweetDiv = formatTweet(data);
          switch(event.type){
            case "toplinks":
              tweetDiv = tweetDiv.data("url", results[data.id_str]);
              if ($("#oembed_" + generateID(results[data.id_str])).length == 0)
                $.embedly(results[data.id_str], {}, getOembedCallback(data));
              break;
          }
          var userId = $("#notable_" + data.user.id_str);
          if(userId.length > 0 && (userId.is(":empty") || 
            userId.children("li:first-child").data("tweet").id_str < data.id_str))
             userId.empty().append(tweetDiv);
          else
            tweetList.trigger("queue",[tweetDiv]);
        });
      }
    }
    if (event.type == 'toplinks' && $(".oembedTweet").length < 8) {
      iSocket.search({'text': 
      "yfrog,youtube,vimeo,flickr,twitpic,instagr,youtu,flic,edition,justin,ustream,picasaweb,imgur,twitvid", 
                      'userid': userid, 'callback': function(searchResult){
        for (var tweet_index in searchResult.ids){
          if ($("#" + searchResult.ids[tweet_index]).length == 0){
            iSocket.lookuptweet(searchResult.ids[tweet_index], function(data){
              displayTweetOembedData(data);
              var tweetDiv = formatTweet(data);
              tweetList.trigger("queue",[tweetDiv]);
            });
          }
      }}});
    }

  });
  /*$(iSocket).bind('tweet_archived', function(event, tweet_archived){
    if (tweet_archived.level > 5 && typeof tweet_archived.url != 'undefined') {
      if ($("#t_" + tweet_archived.id_str).length == 0){
        iSocket.lookuptweet(tweet_archived.id_str, function(data){
          var tweetDiv = formatTweet(data);
          if (typeof tweet_archived.url == 'string'){
            if ($("#oembed_" + generateID(tweet_archived.url)).length == 0)
              $.embedly(tweet_archived.url, {}, getOembedCallback(data));
            tweetDiv.data('url', tweet_archived.url);
          }

          var userId = $("#notable_" + data.user.id_str);
          if(userId.length > 0 && (userId.is(":empty") || 
            userId.children("li:first-child").data("tweet").id_str < data.id_str))
             userId.empty().append(tweetDiv);
          else
            tweetList.trigger("queue",[tweetDiv]);
        });
      }
    }
  });*/
  $(iSocket).bind('trending_tweet', function(event, tweet_archived){
    if (tweet_archived.level > 0) {
      if ($("#t_" + tweet_archived.id_str).length == 0){
        iSocket.lookuptweet(tweet_archived.id_str, function(data){
          var tweetDiv = formatTweet(data);
          var userId = $("#notable_" + data.user.id_str);
          if(userId.length > 0 && (userId.is(":empty") || 
            userId.children("li:first-child").data("tweet").id_str < data.id_str))
             userId.empty().append(tweetDiv);
          else{ 
            tweetList.trigger("queue",[tweetDiv]);
	      }
        });
      }
    }
  });
  $(iSocket).bind('tweet retweeted_tweet external_retweeted_tweet replied_tweet external_replied_tweet favourite_tweet', function(event, e){
      var data = e;
      var tweetDiv = formatTweet(data);
      var userId = $("#notable_" + data.user.id_str);
      if (userId.length > 0) userId.empty().append(tweetDiv);
      else if (typeof data.retweeted_status == 'object'){
        var rUserId = $("#notable_" + data.retweeted_status.user.id_str);
        if (rUserId.length > 0 && (rUserId.is(":empty") || 
          rUserId.children("li:first-child").data("tweet").id_str < data.retweeted_status.id_str))
          userId.empty().append(tweetDiv);
        else
          tweetList.trigger("queue",[tweetDiv]);
      }else{		
        tweetList.trigger("queue",[tweetDiv]);
      }
  });
  $(iSocket).bind('info_message', function(event, info_message){
    return true;
  });
  $(iSocket).bind('nosocket', function(event, e){
    $("#nochrome").removeClass("hidden");
  });
  $(iSocket).bind('connection_opened', function(event, e){
    if (typeof logged_in_user == 'object'){
      if (logged_in_user.user_id != userid){
        iSocket.search({'live': true, 'cross_user_stream': [logged_in_user.user_id, userid], 'callback': function(searchResult){
        for (var tweet_index in searchResult.ids){
          if ($("#" + searchResult.ids[tweet_index]).length == 0){
            iSocket.lookuptweet(searchResult.ids[tweet_index], function(data){
              var tweetDiv = formatTweet(data);
              if ($("#notable_" + data.user.id_str).length > 0 &&
                 ($("#notable_" + data.user.id_str).is(":empty") ||
                  $("#notable_" + data.user.id_str + " li:first-child").data("tweet").id_str < data.id_str)){
                $("#notable_" + data.user.id_str).empty().append(tweetDiv);
              }else{
                tweetList.trigger("queue",[tweetDiv.addClass("from_follow")]);
              }
            });
          }
        }
        }});
      }
    }
  });

  $(".retweetbt,.replybt,.favoritebt").live('click', function(e){
    var node = $(e.target).parents(".tweet");
    var tweet = node.data('tweet');
    var url = node.data('url');
    if (tweet && typeof logged_in_user != 'undefined'){
      if($(e.target).hasClass("retweetbt")) retweet(tweet, url);
      else if($(e.target).hasClass("replybt")) reply(tweet,url);
      else if($(e.target).hasClass("favoritebt")) favorite(tweet,url);
    }
  });

  $(".oembed_show a, .oembedTweet a").live('click', function(e){
    e.preventDefault();
    trackEvent("MediaLinkPreview",  $(e.target).parent().parent().data("oembed").url);
    displayOembedData($(e.target).parent().parent().data("oembed"), $(e.target).parent().parent().data("tweet"));
  });
  $(".oembedTweet img").live('click', function(e){
    e.preventDefault();
    trackEvent("MediaPreview",  $(e.target).parent().data("oembed").url);
    displayOembedData($(e.target).parent().data("oembed"), $(e.target).parent().data("tweet"));
  });

  $("#tweet_now_button").live('click', function(e){
    tweet(typeof official_hashtag != 'undefined' ? official_hashtag : undefined);
  });

  $("#media_preview").click(function(e){
    $("#media_preview_container").addClass("none");
  });

  // bind some keyboard shortcuts
  $(document).keyup(function(e){
    if (e.which == 27){
      $("#dialog").remove();
      $("#media_preview_container").addClass("none");
    } else if (e.which == 78 && e.altKey == true)
      tweet(typeof official_hashtag != 'undefined' ? official_hashtag : undefined);
  });
  $("#replyText, #tweetText").live('keyup', function(e){
    $("#textCounter").html(140 - e.target.value.length);
  });

});
