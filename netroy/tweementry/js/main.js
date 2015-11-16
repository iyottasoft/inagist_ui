// Set URL of your WebSocketMain.swf here:
WEB_SOCKET_SWF_LOCATION = "/netroy/live_ui/flash/WebSocketMain.swf";
// Set this to dump debug message from Flash to console.log:
WEB_SOCKET_DEBUG = false;

var tweethash={}, iSocket;
var mouseOverDisplay = false, userid = "dlfipl", level = 2, trendDirection = 1;

function rand(base){
  return Math.floor(Math.random()*base);
}

function appendTweet(tweetDiv, id){
  if ($("#tweet_loader").length > 0)
    $("#tweet_loader").remove();
  if ($("#live_comments #"+id).length == 0)
    $("#live_comments").prepend(tweetDiv);
}

function formatTweet(tweet){
  var retweeter = undefined;
  var fromFollow = tweet.from_follow ? " from_follow" : "";
  if (typeof tweet.retweeted_status == 'object'){
    retweeter = tweet.user;
    tweet = tweet.retweeted_status;
  }
 
  var tweetText = '<div class="comment_grey_bg tweet" id="'+tweet.id_str+'"><div class="white_bg"><div class="padding6">';
  tweetText += '<div class="photo"><img src="'+tweet.user.profile_image_url+'" /></div>';
  tweetText += '<div class="username"><a href="http://twitter.com/intent/user?screen_name='+tweet.user.screen_name+'">';
  tweetText += tweet.user.screen_name+'</a></div>';
  tweetText += '<div class="user_comment">'+twitterlib.ify.clean(tweet.text)+'</div><div class="tweet_footer">';
  tweetText += '<div class="comment_time"><a href="http://inagist.com/'+tweet.user.screen_name+'/'+tweet.id_str+'" title="'+tweet.created_at+'" target="_blank" class="createdTime">'+prettyDate(tweet.created_at)+'</a></div>';
  tweetText += '<div class="icons replybt" align="center"><img src="/netroy/tweementry/images/ico-reply.jpg" border="0" title="Reply"/></div>';
  tweetText += '<div class="icons retweetbt" align="center"><img src="/netroy/tweementry/images/ico-retweet.jpg" border="0" title="Retweet"/></div>';
  tweetText += '<div class="icons favoritebt" align="center"><img src="/netroy/tweementry/images/ico-addtofavourite.jpg" border="0" title="Add to Favorites"/></div>';
  tweetText += '</div></div></div></div>';

  return jQuery(tweetText).data("tweet", tweet);
}

function extractTrendStrength(val){
  return parseInt($(val).attr("rel"));
}

function extractTweetId(val){
  return $(val).attr("id");
}

function extractTweetStrength(val){
  return $(val).data("tweet").retweets;
}

function sortOnVal(Selector, Container, Extractor, Direction){
  var stories = $(Selector);
  stories.sort(function(a,b){
    var ai = Extractor(a);
    var bi = Extractor(b);
    if (ai > bi)
      return -1 * Direction;
    else
      return 1 * Direction;
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

function trackEvent(P1, P2, P3){
  _gaq.push(['_trackEvent', userid, P1, P2, P3]);
}

function translateTeamName(Team){
  var TeamShort = Team.split(" ")[0];
  switch (TeamShort) {
    case "Kings":
      return "Punjab";
    case "Royal":
      return "Bangalore";
    case "Deccan":
      return "Hyderabad";
    default:
      return TeamShort;
  }
}

function updateMatchDetails(MatchDetails){
  if (typeof MatchDetails.Awayteam != 'undefined')
    $("#away_team").html(translateTeamName(MatchDetails.Awayteam));
  if (typeof MatchDetails.Hometeam != 'undefined')
    $("#home_team").html(translateTeamName(MatchDetails.Hometeam));
  if (typeof MatchDetails.SecondInnings != 'undefined'){
    MatchDetails.Equation = MatchDetails.SecondInnings.SIEquation;
    MatchDetails.OverDetail = MatchDetails.SecondInnings.SIOverDetail;
    $("#batting_team").html(translateTeamName(MatchDetails.SecondInnings.Battingteam));
  } else if (typeof MatchDetails.FirstInnings != 'undefined'){
    MatchDetails.Equation = MatchDetails.FirstInnings.FIEquation;
    MatchDetails.OverDetail = MatchDetails.FirstInnings.FIOverDetail;
    $("#batting_team").html(translateTeamName(MatchDetails.FirstInnings.Battingteam));
  }
  if (typeof MatchDetails.Equation == 'undefined')
    return;

  if (typeof MatchDetails.MatchResult != 'undefined' && MatchDetails.MatchResult != '')
    $("#match_status").html(MatchDetails.MatchResult);
  else if (typeof MatchDetails.equation != 'undefined' && MatchDetails.equation != '')
    $("#match_status").html(MatchDetails.equation);
  else if (typeof MatchDetails.Status != 'undefined' && MatchDetails.Status != '')
    $("#match_status").html(MatchDetails.Status);
  $("#current_score").html(MatchDetails.Equation.Total + "/" + MatchDetails.Equation.Wickets);
  $("#current_overs").html(MatchDetails.Equation.Overs);
  $("#current_run_rate").html(MatchDetails.Equation.Runrate);
}

function convertToInt(x){
  var y = -1;
  if ((y = parseInt(x)) >= 0) 
    return y; 
  else 
    return x;
}

function updateScoreDetails(ScoreDetails){
  if (typeof ScoreDetails.ThisOver == 'string' && ScoreDetails.ThisOver != '')
    ScoreDetails.ThisOver = ScoreDetails.ThisOver.split(",");
  else
    ScoreDetails.ThisOver = [];

  if (typeof ScoreDetails.BatDetails == 'string' && ScoreDetails.BatDetails != '')
    ScoreDetails.BatDetails = ScoreDetails.BatDetails.split("|").map(convertToInt);
  if (typeof ScoreDetails.NonStrBatDetails == 'string' && ScoreDetails.NonStrBatDetails != '')
    ScoreDetails.NonStrBatDetails = ScoreDetails.NonStrBatDetails.split("|").map(convertToInt);

  if (typeof ScoreDetails.BatDetails == 'object')
    $("#striker_batsman").html(ScoreDetails.BatDetails[0] + "* " + 
                               ScoreDetails.BatDetails[1] + " (" + 
                               ScoreDetails.BatDetails[2] + ")");
  else
    $("#striker_batsman").html("");
  if (typeof ScoreDetails.NonStrBatDetails == 'object')
    $("#non_striker_batsman").html(ScoreDetails.NonStrBatDetails[0] + " " + 
                               ScoreDetails.NonStrBatDetails[1] + " (" + 
                               ScoreDetails.NonStrBatDetails[2] + ")");
  else
    $("#non_striker_batsman").html("");
  $("#over_details").empty();
  for (var ballScore in ScoreDetails.ThisOver){
    if (ScoreDetails.ThisOver[ballScore] != ' '){
      var thisBallScore = ScoreDetails.ThisOver[ballScore].split("(")[0];
      $("#over_details").append('<span class="ball_details">'+thisBallScore+'</span>');
    }
  }
  if (typeof ScoreDetails.Commentary == 'string')
    $("#last_ball_comment").html(ScoreDetails.Commentary);
  else
    $("#last_ball_comment").html("");
}

function updateScoreCard(ScoreCard){
  updateMatchDetails(ScoreCard.match_details);
  updateScoreDetails(ScoreCard.node_details);
}

function doSearch(Text, Options, Callback){
  iSocket.search($.extend(Options, {'text': Text, 'userid': userid, 'callback': function(searchResult){
    $("#search_results").html("");
    for (var tweet_index in searchResult.ids){
      if ($("#" + searchResult.ids[tweet_index]).length == 0){
        $("#search_results").prepend("<div id='shold_"+searchResult.ids[tweet_index]+"'></div>");
        iSocket.lookuptweet(searchResult.ids[tweet_index], function(data){
            var tweetDiv = formatTweet(data);
            $("#shold_" + data.id_str).replaceWith(tweetDiv);
        });
      } else {
        var data = $("#" + searchResult.ids[tweet_index]).data("tweet");
        var tweetDiv = formatTweet(data);
        $("#search_results").prepend(tweetDiv);
      }
    }
    $("#trends_selected_header").empty().
      append("<div class='search_results_header'>" + Text + "<span>(x)</span></div>");
    if (typeof Callback == 'function')
      Callback();
  }}));
}

$(function(){
  if (typeof logged_in_user == 'object')
    trackEvent("Signed In", logged_in_user.user_id);

  if (typeof initial_data == 'object'){
    for (var x in initial_data){
      var data = initial_data[x];
      appendTweet(formatTweet(data), data.id_str);
    }
  }
  if (typeof match_details == 'object'){
    updateScoreCard(match_details.score_update);
  }

  iSocket = new Instaket(userid, {retweets: false, trackwords_with_stat: true, toplinks: false, debug: false,
                                  toptweets: false, level: level, live: false,
                                  url: "ws://websockets.inagist.com:18010/websockets_stream"});
  iSocket.connect();

  $(iSocket).bind('trending_personal_phrase trending_phrase', function(event, e){
    var phrase = e.phrase;
    var id = generateID(phrase);
    if (e.rank == -1)
      $(".players_score_box #t_"+id).remove();
    else if ($(".players_score_box #t_"+id).length == 0){
      if (e.rank < 3) return;
      var newTrend = jQuery("<a href='#' target='_blank' class='trend'>"+phrase+"</a>").
        attr("id", "t_" + generateID(phrase)).
        data("phrase", phrase).data("level", e.level).attr("rel", e.rank);
      $(".players_score_box").append(newTrend);
    } else 
      $(".players_score_box #t_"+id).attr("rel", e.rank);
    sortOnVal(".players_score_box .trend", ".players_score_box", extractTrendStrength, trendDirection);
  });
  
  $(iSocket).bind('trackwordsstat', function(){
    var i = 0;
    var trends = [];
    while (++i < arguments.length){
      var trackword = arguments[i];
      if (typeof trackword["key"] != 'undefined'){
        var newTrend = jQuery("<a href='#' target='_blank' class='trend'>"+trackword["key"]+"</a>").
          attr("id", "t_" + generateID(trackword["key"])).
          data("phrase", trackword["key"]).data("level", trackword["level"]).attr("rel", trackword["rank"]);
        $(".players_score_box").append(newTrend);
      }
    }
    sortOnVal(".players_score_box .trend", ".players_score_box", extractTrendStrength, trendDirection);
  });
 
  $(iSocket).bind('toplinks', function(event, links){
    for (var tweet in links){
      if ($("#" + tweet).length == 0){
        iSocket.lookuptweet(tweet, function(data){
          appendTweet(formatTweet(data).data("url", links[data.id_str]), data.id_str);
        });
      }
    }
  });

  $(iSocket).bind('toptweets', function(event, tweets){
    for (var tweet in tweets){
      if ($("#" + tweet).length == 0){
        iSocket.lookuptweet(tweet, function(data){
          appendTweet(formatTweet(data), data.id_str);
        });
      }
    }
  });
  $(iSocket).bind('tweet_archived', function(event, tweet_archived){
    if (tweet_archived.level > 5 && typeof tweet_archived.url != 'undefined') {
      if ($("#" + tweet_archived.id_str).length == 0){
        iSocket.lookuptweet(tweet_archived.id_str, function(data){
          appendTweet(formatTweet(data), data.id_str);
        });
      }
    }
  });
  $(iSocket).bind('trending_tweet', function(event, tweet_archived){
    if (tweet_archived.level > 0) {
      if ($("#" + tweet_archived.id_str).length == 0){
        iSocket.lookuptweet(tweet_archived.id_str, function(data){
          var tweetDiv = formatTweet(data);
          appendTweet(tweetDiv, data.id_str);
        });
      }
    }
  });
  $(iSocket).bind('tweet retweeted_tweet external_retweeted_tweet replied_tweet external_replied_tweet favourite_tweet', 
    function(event, e){
    var data = e;
    var tweetDiv = formatTweet(data);
    appendTweet(tweetDiv, data.id_str);
  });
  $(iSocket).bind('info_message', function(event, info_message){
    return true;
  });

  $(iSocket).bind('score_update', function(event, score_update){
    updateScoreCard(score_update);
  });

  $(iSocket).bind('connection_opened', function(event, e){
    trackEvent("Connected", typeof logged_in_user == 'object' ? logged_in_user.user_id : "default");
  });

  $(iSocket).bind('nosocket', function(event, e){
    $("#nochrome").removeClass("hidden");
  });
  
  setInterval(function(){
    $(".createdTime").prettyDate();
    while ($("#live_comments .tweet").length > 60)
      $("#live_comments .tweet:last").remove();
  },30000);

  $(".retweetbt,.replybt,.favoritebt").live('click', function(e){
    var node = $(e.target).parents(".tweet");
    var tweet = node.data('tweet');
    var url = node.data('url');
    if (tweet && typeof logged_in_user != 'undefined'){
      if($(e.target).parent().hasClass("retweetbt")){
        trackEvent("ReTweet",  logged_in_user);
        retweet(tweet, url);
      } else if($(e.target).parent().hasClass("replybt")){
        trackEvent("Reply",  logged_in_user);
        reply(tweet,url);
      } else if($(e.target).parent().hasClass("favoritebt")){
        trackEvent("Favorite",  logged_in_user);
        favorite(tweet,url);
      }
    }
  });

  $("#login_button").live('click', function(e){
    trackEvent("Login",  null);
    $.oauthpopup({path: '/partner/login?partner=tweementry',callback: function(){
          window.location.reload();
        }
    });
  });

  $("#replyText, #tweetText").live('keyup', function(e){
    $("#textCounter").html(140 - e.target.value.length);
  });
  $(".search_results_header span").live('click', function(){
    $("#search_results").empty();
    $("#trends_selected_header").empty();
  });
  $(".players_score_box a").live('click', function(e){
    e.preventDefault(); 
    var phrase = $(e.target).data("phrase");
    trackEvent("TrendClick",  typeof logged_in_user == 'object' ?logged_in_user.user_id:"not_logged_in", phrase);
    doSearch(phrase, {"trend_lookup": true}, undefined);
  });
  $("a").live('click', function(e){
    trackEvent("LinkClick",  $(e.target).attr("href"));
  });

  // bind some keyboard shortcuts
  $(document).keyup(function(e){
    if (e.which == 27){
      $("#dialog").remove();
    }
  });

  $("#popular.sortable_selectable").live('click', function(){
    sortOnVal("#comment_container .tweet", "#comment_container", extractTweetStrength, 1); 
    $("#popular").removeClass("sortable_selectable"); 
    $("#latest").addClass("sortable_selectable");
  });

  $("#latest.sortable_selectable").live('click', function(){
    sortOnVal("#comment_container .tweet", "#comment_container", extractTweetId, 1); 
    $("#latest").removeClass("sortable_selectable"); 
    $("#popular").addClass("sortable_selectable");
  });

  $("#submit_button").click(function(){$("#form1").submit();});
  $("#form1").submit(function(event){
    event.preventDefault();
    if (typeof logged_in_user == 'object'){
      trackEvent("Tweet",  logged_in_user.user_id);
      var form_data = $("#form1").serialize();
      $.ajax({
        type: "POST",
        url: "/partner/reply?partner=tweementry",
        data: form_data,
        success: function(){ $("#tweet_text")[0].value='#ipl '; showAlert("Tweet Sent");},
        error: function(){ showAlert("Failed");}
      });
    }
  });
});
