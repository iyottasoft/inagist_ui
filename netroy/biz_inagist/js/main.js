var tweethash={}, iSocket;
var mouseOverDisplay = false;

// Set URL of your WebSocketMain.swf here:
WEB_SOCKET_SWF_LOCATION = "/netroy/live_ui/flash/WebSocketMain.swf";
// Set this to dump debug message from Flash to console.log:
WEB_SOCKET_DEBUG = false;


function animateScroll(div, matcher, height){
  $(div).animate({scrollTop : height}, 1500, function(){
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

function getOembedCallback(eTweet){
  return function(oembed){
    oembed.url = oembed.url || oembed.thumbnail_url || oembed.provider_url;
    if ((typeof oembed.thumbnail_url != 'undefined') && ($("#oembed_" + generateID(oembed.url)).length == 0)){
      $("#media1").append(jQuery("<div class='oembedTweet'><img src='"+oembed.thumbnail_url+
                  "' title=\"" + eTweet.text.replace(/\"/g,"&quot;") + " - @" + eTweet.user.screen_name + 
                  "\" /></div>").data("oembed", oembed).data("tweet", eTweet).
                  attr("id", "oembed_" + generateID(oembed.url)));
    }
    $("#" + eTweet.id_str + ", #head_" + eTweet.id_str + ", #search_" + eTweet.id_str + ", #l_" + eTweet.id_str).
      addClass("oembed_show").data("oembed", oembed);
  }
}

function formatTweet(tweet){
  var retweeter = undefined;
  var fromFollow = tweet.from_follow ? "highlight_follow" : "";
  if (typeof tweet.retweeted_status == 'object'){
    retweeter = tweet.user;
    tweet = tweet.retweeted_status;
  }
  var tweetText = "<div class='actionbar'><span class='action retweet'></span>";
  tweetText += "<span class='action reply'></span><span class='action favorite'></span></div>";
  tweetText += "<span class='story_text'>" + twitterlib.ify.clean(tweet.text) + "</span><div class='clear'></div>";
  tweetText += "<div class='story_footer "+fromFollow+"'><span class='story_icon'><a href='http://twitter.com/intent/user?screen_name=";
  tweetText += tweet.user.screen_name + "' target='_blank'";
  tweetText += "><img src='"+tweet.user.profile_image_url+"' alt='"+tweet.user.screen_name+"'></a></span>";
  tweetText += "<span class='story_info'><a href='http://twitter.com/intent/user?screen_name="+tweet.user.screen_name+"' target='_blank' ";
  tweetText += "class='story_user'>" +tweet.user.screen_name + "</a>";
  tweetText += "<div><a href='http://inagist.com/" + tweet.user.screen_name+"/";
  tweetText += tweet.id_str +"' target='_blank' title='"+tweet.created_at+"' class='createdTime'>";
  tweetText += prettyDate(tweet.created_at) + "</a><span class='story_stats'>";
  if (tweet.retweets)
    tweetText += " ♻" + tweet.retweets;
  else if (tweet.retweet_count)
    tweetText += " ♻" + tweet.retweet_count;
  if (tweet.mentions)
    tweetText += ", ↶" + tweet.mentions;
  if (retweeter){
    tweetText += " <span class='retweeter_icon'><a href='http://twitter.com/";
    tweetText += retweeter.screen_name + "' target='_blank'";
    tweetText += "><img src='"+retweeter.profile_image_url+"' alt='"+retweeter.screen_name+"'></a></span>";
  }
  tweetText += "</span></div></span></div>";

  return tweetText;
}

function appendFBLike(Url, Div){
  Div.append("<fb:like layout='button_count'"+
             " href='" + Url + "' "+ 
             " show_faces='false' width='150' colorscheme='dark'></fb:like>");
  FB.XFBML.parse(Div[0]||Div);
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
  $("#media_preview").append(jQuery("<div class='text'>"+formatTweet(tweet)+"</div>").data("tweet", tweet));
  $("#media_preview").append("<div class='clear'></div><span class='footer'><a href='"+oembed.url+
                             "' target='_blank'>"+oembed.provider_name+"</a></span>");
  appendFBLike(oembed.url, $("#media_preview"));
  $("#media_preview_container").removeClass("none");
}

function getDisplayDiv(data){
  if (jQuery.inArray(data.user.screen_name, official_accounts) >= 0)
    return $("#tweet1");
  else if (data.retweets && data.retweets > level * 2)
    if (official_accounts[0] == '')
      return $("#tweet" + ((rand(11) % 2) + 1));
    else
      return $("#tweet2");
  else
    return $("#influential_tweets");
 }

function checkAndPrependDiv(Container, TargetDiv){
  TargetDiv.height(120);
  if ($(".tweet_box", Container).length < 2)
    Container.prepend(TargetDiv);
  else if (Container.attr("id") == "influential_tweets")
    Container.prepend(TargetDiv.height(0).animate({height:100}, 300));
  else
    $(".tweet_box:nth-child(2)", Container).after(TargetDiv);
}

function showNextHeadline() {
  var elem = undefined;
  for (var i in nextHeadlines) {
    delete nextHeadlines[i];
    if (typeof tbars.elements[i] != 'undefined'){
      elem = tbars.elements[i];
      break;
    }
  }
  if (typeof elem != 'undefined')
    headlineCount = tbars.elements[i].position;
  else
    headlineCount = ++headlineCount % tbars.data.length;
  showHeadlines(headlineCount);
}

function showPrevHeadline() {
  headlineCount = --headlineCount < 0 ? tbars.data.length - 1 : headlineCount;
  showHeadlines(headlineCount);
}

function showHeadlines(index){
  var text = tbars.data[index].key;
  iSocket.search($.extend({'trend_lookup': true}, 
                          {'text': text, 'userid': userid, 
                            'callback': function(searchResult){
    $("#headlines_container_head").html(text);
    var results = [];
    if (searchResult.ids.length > 5){
      results[4] = searchResult.ids.pop();
      results[3] = searchResult.ids.pop();
      results[2] = searchResult.ids.pop();
      results[1] = searchResult.ids.pop();
      results[0] = searchResult.ids.pop();
    } else 
      results = searchResult.ids;

    for (var tweet_index in results){
      if ($("#hold_" + results[tweet_index]).length > 0)
        $("#headlines_container_body").prepend($("#hold_" + results[tweet_index]));
      else if ($("#head_" + results[tweet_index]).length > 0)
        $("#headlines_container_body").prepend($("#head_" + results[tweet_index]));
      else if ($("#" + results[tweet_index]).length == 0){
        $("#headlines_container_body").prepend("<div class='tweet_box' id= 'hold_"+results[tweet_index]+"'></div>");
        iSocket.lookuptweet(results[tweet_index], function(data){
          displayTweetOembedData(data);
          var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                                "id", "head_" + data.id_str).data("tweet", data);
          $("#hold_" + data.id_str).replaceWith(tweetDiv);
        });
      } else {
        var data = $("#" + results[tweet_index]).data("tweet");
        var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                              "id", "head_" + data.id_str).data("tweet", data);
        $("#headlines_container_body").prepend(tweetDiv);
      }
    }
    $("#headlines_container").removeClass("none");
  }}));
}

function doSearch(Text, Options, Callback){
  iSocket.search($.extend(Options, {'text': Text, 'userid': userid, 'callback': function(searchResult){
    $("#search_results").html("");
    for (var tweet_index in searchResult.ids){
      if ($("#" + searchResult.ids[tweet_index]).length == 0){
        $("#search_results").prepend("<div id='shold_"+searchResult.ids[tweet_index]+"'></div>");
        iSocket.lookuptweet(searchResult.ids[tweet_index], function(data){
            var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                                  "id", "search_" + data.id_str).data("tweet", data);
            $("#shold_" + data.id_str).replaceWith(tweetDiv);
        });
      } else {
        var data = $("#" + searchResult.ids[tweet_index]).data("tweet");
        var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                              "id", "search_" + data.id_str).data("tweet", data);
        $("#search_results").prepend(tweetDiv);
      }
    }
    //$("#search_results").append("<div class='clear'></div>"+
    //"<fb:comments href=\"http://inagist.com/"+userid+"/trends?t="+escape(Text)+
    //"\" num_posts=\"5\" width=\"330\"></fb:comments>")
    //FB.XFBML.parse($("#search_results")[0]);
    $("#search_tab").html(Text);
    iSocket.live_off();
    $("#col_right_live").addClass("none");
    $("#col_right_search").removeClass("none");
    if (typeof Callback == 'function')
      Callback();
  }}));
}

function doLiveSearch(Text, Options, Callback){
  iSocket.search($.extend(Options, {'text': Text, 'live': true, 'callback': function(searchResult){
    $("#search_results").html("");
    for (var tweet_index in searchResult.ids){
      if ($("#" + searchResult.ids[tweet_index]).length == 0){
        $("#search_results").prepend("<div id='shold_"+searchResult.ids[tweet_index]+"'></div>");
        iSocket.lookuptweet(searchResult.ids[tweet_index], function(data){
            var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                                  "id", "search_" + data.id_str).data("tweet", data);
            $("#shold_" + data.id_str).replaceWith(tweetDiv);
        });
      } else {
        var data = $("#" + searchResult.ids[tweet_index]).data("tweet");
        var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                              "id", "search_" + data.id_str).data("tweet", data);
        $("#search_results").prepend(tweetDiv);
      }
    }
    //$("#search_results").append("<div class='clear'></div>"+
    //"<fb:comments href=\"http://inagist.com/"+userid+"/trends?t="+escape(Text)+
    //"\" num_posts=\"5\" width=\"330\"></fb:comments>")
    //FB.XFBML.parse($("#search_results")[0]);
    $("#search_tab").html(Text);
    iSocket.live_off();
    $("#col_right_live").addClass("none");
    $("#col_right_search").removeClass("none");
    if (typeof Callback == 'function')
      Callback();
  }}));
}

function sortByFreshness(Selector, Container){
  var stories = $(Selector);
  stories.sort(function(a,b){
    var ai = $(a).attr("id");
    var bi = $(b).attr("id");
    if (ai > bi)
      return -1;
    else
      return 1;
  });
  for (i=0;i<stories.length;i++){
    $(Container).append(stories[i]);
  }
}

function generateID(Key){
  if (typeof Key == 'string')
    return escape(Key.replace(/[ \/\.]/g, "_")).replace(/%/g, "_");
  else
    return Key;
}

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

function initTrendyBars(data){
  if (data.length == 0) return;
  if (typeof tbars == 'undefined'){
    tbars = new TrendyBars(data, {element_id: 'trends_chart', max_count: 40, height: 960, width: 220,
      callback: function(Key){
        doSearch(Key, {'trend_lookup': true}, function(){
          return;
        });
      }});
    headlineCount = 0;
    showHeadlines(0);
  } else
    for (var i in data) {
      tbars.update(data[i]);
    }
}

function trackEvent(P1, P2, P3){
  _gaq.push(['_trackEvent', userid, P1, P2, P3]);
}

$(function(){
  iSocket = new Instaket(userid, {retweets: false, trackwords_with_stat: true, toplinks: true, debug: false,
                                  toptweets: true, level: level, live: false,
                                  url: "ws://websockets.inagist.com:18010/websockets_stream"});
  iSocket.connect();
  $(iSocket).bind('custom_event', function(event, e){
    console.log(e);
  });
  $(iSocket).bind('trending_personal_phrase', function(event, e){
      var phrase = e.phrase;
      e.key = e.phrase;
      var newTrend = jQuery("<li class='trend'><a href='http://inagist.com/"+userid+"/trends?t="+phrase+
                            "' target='_blank'>"+phrase+"</a></li>").
        attr("id", "t_" + generateID(phrase)).
        data("phrase", phrase).data("user", userid).
        data("level", e.level).data("rank", e.rank);
    if (e.level > 5)
      nextHeadlines[e.phrase] = undefined;
    initTrendyBars([e]);
  });
  
  $(iSocket).bind('trending_phrase', function(event, e){
    e.key = e.phrase;
    if ($("#t_" + generateID(e.phrase)).length > 0){
      $("#t_" + generateID(e.phrase)).data("level", e.level).data("rank", e.rank);
    }
    if (e.level > 5)
      nextHeadlines[e.phrase] = undefined;
    initTrendyBars([e]);
  });
  
  $(iSocket).bind('toplinks', function(event, links){
    for (var tweet in links){
      if ($("#" + tweet).length == 0){
        iSocket.lookuptweet(tweet, function(data){
          if ($("#oembed_" + generateID(links[data.id_str])).length == 0)
            $.embedly(links[data.id_str], {}, getOembedCallback(data));

          var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                                "id", data.id_str).data("tweet", data).data("url", links[data.id_str]);
          getDisplayDiv(data).append(tweetDiv);
        });
      }
    }

    if ($("#media1 .oembedTweet").length < 8) {
      iSocket.search({'text': "yfrog,youtube,vimeo,flickr,twitpic,instagr,youtu,flic,edition,justin,ustream,picasaweb,imgur,twitvid", 
                      'userid': userid, 'callback': function(searchResult){
        for (var tweet_index in searchResult.ids){
          if ($("#" + searchResult.ids[tweet_index]).length == 0){
            iSocket.lookuptweet(searchResult.ids[tweet_index], function(data){
              displayTweetOembedData(data);
              var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                                    "id", data.id_str).data("tweet", data);
              getDisplayDiv(data).append(tweetDiv);
            });
          }
      }}});
    }
  });
  $(iSocket).bind('toptweets', function(event, tweets){
    for (var tweet in tweets){
      if ($("#" + tweet).length == 0){
        iSocket.lookuptweet(tweet, function(data){
          displayTweetOembedData(data);
          var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                                "id", data.id_str).data("tweet", data);

          getDisplayDiv(data).append(tweetDiv);
        });
      }
    }
  });
  $(iSocket).bind('trackwordsstat', function(){
    var i = 0;
    var trends = [];
    while (++i < arguments.length){
      var trackword = arguments[i];
      if (typeof trackword["key"] != 'undefined'){
        trends.push(trackword);
        var newTrend = jQuery("<li class='trend'><a href='http://inagist.com/"+userid+"/trends?t="+trackword["key"]+
                              "' target='_blank'>"+trackword["key"]+"</a></li>").
          attr("id", "t_" + generateID(trackword["key"])).
          data('phrase', trackword["key"]).data('user', userid).
          data("level", trackword["level"]).data("rank", trackword["rank"]);
      }
    }
    initTrendyBars(trends);
    $("#live_trends").hide();
  });
  $(iSocket).bind('tweet_archived', function(event, tweet_archived){
    if (tweet_archived.level > 5 && typeof tweet_archived.url != 'undefined') {
      if ($("#" + tweet_archived.id_str).length == 0){
        iSocket.lookuptweet(tweet_archived.id_str, function(data){
          var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                                "id", data.id_str).data("tweet", data);
          if (typeof tweet_archived.url == 'string'){
            if ($("#oembed_" + generateID(tweet_archived.url)).length == 0)
              $.embedly(tweet_archived.url, {}, getOembedCallback(data));
            tweetDiv.data('url', tweet_archived.url);
          }

          if ($("#" + tweet_archived.id_str).length == 0)
            checkAndPrependDiv(getDisplayDiv(data), tweetDiv);
        });
      } else {
        if ($("#influential_tweets #" + tweet_archived.id_str).length > 0) 
          checkAndPrependDiv($("#tweet2"), $("#influential_tweets #" + tweet_archived.id_str));
        else if (($("#tweet1 #" + tweet_archived.id_str).length > 0) && 
                 ($("#tweet1 .tweet_box:first-child").attr("id") != tweet_archived.id_str))
          checkAndPrependDiv($("#tweet1"), $("#tweet1 #" + tweet_archived.id_str));
        else if (($("#tweet2 #" + tweet_archived.id_str).length > 0) &&
                 ($("#tweet2 .tweet_box:first-child").attr("id") != tweet_archived.id_str))
          checkAndPrependDiv($("#tweet2"), $("#tweet2 #" + tweet_archived.id_str));
      }
    }
  });
  $(iSocket).bind('trending_tweet', function(event, tweet_archived){
    if (tweet_archived.level > 0) {
      if ($("#" + tweet_archived.id_str).length == 0){
        iSocket.lookuptweet(tweet_archived.id_str, function(data){
          displayTweetOembedData(data);
          var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                                "id", data.id_str).data("tweet", data);
          if ($("#" + tweet_archived.id_str).length == 0)
            checkAndPrependDiv(getDisplayDiv(data), tweetDiv);
        });
      } else {
        if ($("#influential_tweets #" + tweet_archived.id_str).length > 0) {
          if (tweet_archived.level > 2)
            checkAndPrependDiv($("#tweet2"), $("#influential_tweets #" + tweet_archived.id_str));
          else
            checkAndPrependDiv($("#influential_tweets"), $("#influential_tweets #" + tweet_archived.id_str));
        } else if (($("#tweet1 #" + tweet_archived.id_str).length > 0) &&
                   ($("#tweet1 .tweet_box:first-child").attr("id") != tweet_archived.id_str))
          checkAndPrependDiv($("#tweet1"), $("#tweet1 #" + tweet_archived.id_str));
        else if (($("#tweet2 #" + tweet_archived.id_str).length > 0) &&
                   ($("#tweet2 .tweet_box:first-child").attr("id") != tweet_archived.id_str))
          checkAndPrependDiv($("#tweet2"), $("#tweet2 #" + tweet_archived.id_str));
      }
    }
  });

  $(iSocket).bind('tweet retweeted_tweet external_retweeted_tweet replied_tweet external_replied_tweet favourite_tweet', 
    function(event, e){
    var data = e;
    var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                          "id", "l_" + data.id_str).data("id", data.id_str).data("tweet", data);
    if (data.from_follow){
      var tid = data.retweeted_status ? data.retweeted_status.id_str : data.id_str;
      if ($("#l_"+tid).length == 0){
        $("#live_tweets").prepend(tweetDiv.height(0).animate({height:100}, 200));
        displayTweetOembedData(data);
        if (noFocus) noFocusCount++;
      }
    } else if (jQuery.inArray(data.user.screen_name, official_accounts) >= 0){
      $("#tweet1 .tweet_box:nth-child(2)").after(tweetDiv);
      displayTweetOembedData(data);
    } else
      $("#live_tweets").prepend(tweetDiv.height(0).animate({height:100}, 200));
    var tweet_counter = $("#tweet_counter").data("counter");
    $("#tweet_counter").html(++tweet_counter).data("counter", tweet_counter);
  });

  $(iSocket).bind('search_result', function(event, search_result){
    if (typeof search_result.tweet == 'object'){
      var data = search_result.tweet;
      var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                            "id", "search_" + data.id_str).data("tweet", data);
      $("#search_results").prepend(tweetDiv);
    } else {
      $("#search_results").prepend("<div id='shold_"+search_result.id_str+"'></div>");
      iSocket.lookuptweet(search_result.id_str, function(data){
          var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                                "id", "search_" + data.id_str).data("tweet", data);
          $("#shold_" + data.id_str).replaceWith(tweetDiv);
      });
    }
  });

  $(iSocket).bind('info_message', function(event, info_message){
    $("#tweet_counter").html(info_message.tweet_count).data("counter", info_message.tweet_count);
  });
  
  $(iSocket).bind('nosocket', function(event, e){
    $("#nochrome").removeClass("hidden");
  });
  
  $(iSocket).bind('connection_opened', function(event, e){
    if (typeof logged_in_user == 'object'){
      trackEvent("Connected", logged_in_user.user_id);
      if (logged_in_user.user_id != userid)
        iSocket.search({'live': true, 'cross_user_stream': [logged_in_user.user_id, userid], 
        'callback': function(searchResult){
        for (var tweet_index in searchResult.ids){
          if ($("#" + searchResult.ids[tweet_index]).length == 0){
            $("#live_tweets").prepend("<div id='lhold_"+searchResult.ids[tweet_index]+"'></div>");
            iSocket.lookuptweet(searchResult.ids[tweet_index], function(data){
                var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                                      "id", "l_" + data.id_str).data("tweet", data);
                $("#lhold_" + data.id_str).replaceWith(tweetDiv);
                displayTweetOembedData(data);
            });
          } else {
            var data = $("#" + searchResult.ids[tweet_index]).data("tweet");
            var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                                  "id", "l_" + data.id_str).data("tweet", data);
            $("#live_tweets").prepend(tweetDiv);
          }
        }}});
      window.onblur = function(){noFocus = true;};
      window.onfocus = function(){noFocus = false; noFocusCount = 0; document.title = documentTitle;};
    }
  });
  
  $("#chromebox").removeClass("hidden");
  $(".retweet").live('click', function(e){
    trackEvent("Retweet",  logged_in_user.user_id);
    if ($(e.target).parent().parent().data('tweet') && typeof logged_in_user != 'undefined')
      retweet($(e.target).parent().parent().data('tweet'), $(e.target).parent().parent().data('url'));
  });
  $(".reply").live('click', function(e){
    trackEvent("Reply",  logged_in_user.user_id);
    if ($(e.target).parent().parent().data('tweet') && typeof logged_in_user != 'undefined')
      reply($(e.target).parent().parent().data('tweet'));
  });
  $(".favorite").live('click', function(e){
    trackEvent("Favorite",  logged_in_user.user_id);
    if ($(e.target).parent().parent().data('tweet') && typeof logged_in_user != 'undefined')
      favorite($(e.target).parent().parent().data('tweet'), $(e.target).parent().parent().data('url'));
  });
  $("#tweet_now_button").live('click', function(e){
    trackEvent("Tweet",  logged_in_user.user_id);
    tweet(typeof official_hashtag != 'undefined' ? official_hashtag : undefined);
  });
  $("a").live('click', function(e){
    if ($(e.target).attr("href") != "#"){
      $(e.target).attr("target", "_blank");
      trackEvent("LinkClick",  $(e.target).attr("href"));
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
  $("#replyText, #tweetText").live('keyup', function(e){
    $("#textCounter").html(140 - e.target.value.length);
  });
  $(".trend").live('click', function(e){
    e.preventDefault();
    if (!($(e.target).hasClass("selected_trend"))) {
      trackEvent("TrendSearch",  $(e.target).text());
      doSearch($(e.target).text(), {'trend_lookup': true}, function(){
        $(".selected_trend").removeClass("selected_trend");
        $(e.target).addClass("selected_trend");
      });
    } else 
      $("#search_tab").click(); 
  });
  $("#searchform").submit(function(e){
    e.preventDefault();
    trackEvent("Search",  $("#search_box")[0].value);
    doSearch($("#search_box")[0].value, {}, undefined);
  });

  $("#media_preview").click(function(e){
    $("#media_preview_container").addClass("none");
  });
  $("#trends_tab").click(function(e){
    drawTrendStats(tbars.data);
    $("#media_preview_container").removeClass("none");
  });
  $("#influential_tab").click(function(e){
    sortByFreshness("#influential_tweets .tweet_box", "#influential_tweets");
  });
  $("#headlines_container_head").click(function(e){
    doSearch($(e.target).text(), {'trend_lookup': true}, function(){
      return;
    });
  });
  $("#headlines_container_prev").click(function(){
    showPrevHeadline();
  });
  $("#headlines_container_next").click(function(){
    showNextHeadline();
  });
  $("#live_tab").click(function(e){
    if ($("#live_tab").hasClass("live_off")){
      iSocket.live_on();
      if ($("#live_tweets .tweet_box").length == 0){
        iSocket.search({'search_ops':'backfill_tweets', 'userid': userid, 
          'callback': function(searchResult){
          for (var tweet_index in searchResult.ids){
            if ($("#" + searchResult.ids[tweet_index]).length == 0){
              $("#live_tweets").prepend("<div id='lhold_"+searchResult.ids[tweet_index]+"'></div>");
              iSocket.lookuptweet(searchResult.ids[tweet_index], function(data){
                  var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                                        "id", "l_" + data.id_str).data("tweet", data);
                  $("#lhold_" + data.id_str).replaceWith(tweetDiv);
                  displayTweetOembedData(data);
              });
            } else {
              var data = $("#" + searchResult.ids[tweet_index]).data("tweet");
              var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(data)+"</div>").attr(
                                    "id", "l_" + data.id_str).data("tweet", data);
              $("#live_tweets").prepend(tweetDiv);
            }
          }}});
      }
      $("#live_tab").removeClass("live_off");
    } else {
      iSocket.live_off();
      $("#live_tab").addClass("live_off");
    }
  });
  $("#search_tab").live('click', function(e){
    if (!$("#live_tab").hasClass("live_off"))
      iSocket.live_on();
    $("#col_right_search").addClass("none");
    $(".selected_trend").removeClass("selected_trend");
    $("#col_right_live").removeClass("none");
  });
  setInterval(function(){
    $(".createdTime").prettyDate();
    if ($("#tweet1 .tweet_box").length > 1)
      animateScroll("#tweet1", "#tweet1 .tweet_box:first-child", 130);
    if ($("#tweet2 .tweet_box").length > 1)
      animateScroll("#tweet2", "#tweet2 .tweet_box:first-child", 130);
    if (typeof tbars != 'undefined')
      showNextHeadline();
    if ($("#media1 .oembedTweet").length > 1)
      animateLeft("#media1", "#media1 .oembedTweet:first-child");
  },30000);
  setInterval(function(){
    while ($("#live_tweets .tweet_box").length > 32)
      $("#live_tweets .tweet_box:last-child").remove();
    while ($("#influential_tweets .tweet_box").length > 32)
      $("#influential_tweets .tweet_box:last-child").remove();
    while ($("#headlines_container_body .tweet_box").length > 210)
      $("#headlines_container_body .tweet_box:last-child").remove();
    if (noFocus && noFocusCount > 0) document.title = "(" + noFocusCount + ") " + documentTitle;
  }, 5000);

  // bind some keyboard shortcuts
  $(document).keyup(function(e){
    if (e.which == 27){
      $("#dialog").remove();
      $("#media_preview_container").addClass("none");
    }
  });

  nextHeadlines = {};
  noFocus = false;
  noFocusCount = 0;
  documentTitle = document.title;
  
  // prepopulate official account
  if (official_accounts[0] != '')
    twitter_timeline_url = "http://api.twitter.com/1/statuses/user_timeline.json?include_entities=1&screen_name=" + 
                            official_accounts[0];
  if (typeof official_list != 'undefined')
    twitter_timeline_url = "http://api.twitter.com/1/"+official_list+"/statuses.json?include_entities=1";
  if (typeof twitter_timeline_url != 'undefined')
    $.ajax({
      url: twitter_timeline_url,
      dataType: 'jsonp',
      success:function(timeline){
        for (i in timeline){
          var tweet = timeline[i];
          var tweetDiv = jQuery("<div class='tweet_box'>"+formatTweet(tweet)+"</div>").attr(
                                "id", tweet.id_str).data("tweet", tweet);
          $("#tweet1").append(tweetDiv);
          displayTweetOembedData(tweet);
        }
      }
    });
});
