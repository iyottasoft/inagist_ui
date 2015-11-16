function fetchInagistTrends(channelId){
  _gaq.push(['_trackEvent', 'InAGist API Call', 'Fetch Trends', channelId]);
  $.ajax({
    url: "http://inagist.com/api/v1/get_top_trends?show_tweets=0&userid="+channelId,
    dataType: 'jsonp',
    success: function(data){
      $("#inagist_trends_list").empty();
      for (var x in data){
        var trendItem = jQuery("<li class='trend'>"+x+"</li>").data('trend', x);
        $("#inagist_trends_list").append(trendItem);
      }
    },
  });
}

function searchInagist(trend){
  _gaq.push(['_trackEvent', 'InAGist API Call', 'Search', trend]);
  $.ajax({
    url: "http://inagist.com/api/v1/search?text="+trend.replace("#",""),
    dataType: 'jsonp',
    success: function(data){
      $("#inagist_tweets_viewer").empty();
      $("#inagist_trend_header").html(trend);
      for (var x in data){
        var tweet = data[x];
        var tweetItem = jQuery("<li class='tweet'><div style='clear:both;'><img class='story_icon' src='"+tweet.user.profile_image_url+"' /><span class='selectable'></span>@"+tweet.user.screen_name+" - "+tweet.text+"</div></li>").data('tweet', tweet).data('trend', trend).data('text', "@"+tweet.user.screen_name+" - "+tweet.text);
        $("#inagist_tweets_viewer").append(tweetItem);
      }
    },
  });
}

function searchTwitter(trend){
  _gaq.push(['_trackEvent', 'Twitter API Call', 'Search', trend]);
  $.ajax({
    url: "http://search.twitter.com/search.json?result_type=mixed&rpp=20&q="+escape(trend),
    dataType: 'jsonp',
    success: function(data){
      $("#twitter_tweets_viewer").empty();
      $("#twitter_trend_header").html(trend);
      for (var x in data.results){
        var tweet = data.results[x];
        var tweetItem = jQuery("<li class='tweet'><div style='clear: both;'><img class='story_icon' src='"+tweet.profile_image_url+"'/><span class='selectable'></span>@"+tweet.from_user+" - "+tweet.text+"</div></li>").data('tweet', tweet).data('trend', trend).data('text', "@"+tweet.from_user+" - "+tweet.text);
        $("#twitter_tweets_viewer").append(tweetItem);
      }
    },
  });
}

function fetchTwitterTrends(woeId){
  _gaq.push(['_trackEvent', 'Twitter API Call', 'Trends Fetch', woeId]);
  $.ajax({
    url: "http://api.twitter.com/1/trends/"+woeId+".json",
    dataType: 'jsonp',
    success: function(data){
      $("#twitter_trends_list").empty();
      var trendsResponse = data[0];
      for (var x in trendsResponse.trends){
        var trend = trendsResponse.trends[x];
        var trendItem = jQuery("<li class='trend'>"+trend.name+"</li>").data('trend', trend.name);
        $("#twitter_trends_list").append(trendItem);
      }
    },
  });
}

function getKeyVals(Key, Callback){
  $.ajax({
    url: "http://inagist.com/partners/utv/feed.php?key="+escape(Key),
    dataType: 'jsonp',
    success: function(response){
      if (typeof(Callback) == 'function')
        Callback(response);
    },
  });
}

function storeKeyVal(Key, Val, Callback){
  var requestObject = {};
  requestObject[Key] = Val;
  $.ajax({
    type: "PUT",
    url: "http://inagist.com/partners/utv/feed.php",
    data: JSON.stringify(requestObject),
    dataType: 'jsonp',
    contentType: "application/json",
    success: function(response){
      if (typeof(Callback) == 'function'){
        Callback(response);
      }
    },
  });
}

function deleteKeyVal(Key, Id, Callback){
  $.ajax({
    type: "DELETE",
    url: "http://inagist.com/partners/utv/feed.php?id="+Id+"&key="+escape(Key),
    dataType: 'jsonp',
    success: function(response){
      if (typeof(Callback) == 'function'){
        Callback(response);
      }
    },
  });
}

function deleteAllKeyVal(Key, Callback){
  $.ajax({
    type: "DELETE",
    url: "http://inagist.com/partners/utv/feed.php?key="+escape(Key),
    dataType: 'jsonp',
    success: function(response){
      if (typeof(Callback) == 'function'){
        Callback(response);
      }
    },
  });
}

function loadSelectedTrends(){
  getKeyVals("trends", function(response){ 
    $("#user_defined_list").empty();
    for (var x in response){
      var selectedTrend = response[x];
      var trendItem = jQuery("<li class='trend'><div style='clear: both;'><span class='deletable'></span>"+selectedTrend.hval+"</div></li>").
                        data('trend', selectedTrend.hval).
                        data('store_data', selectedTrend);
      $("#user_defined_list").append(trendItem);
    }
  });
}

function checkValInList(Val, SelectedList, Key){
  var rVal = false;
  SelectedList.each(function(){
    var data = $(this).data(Key);
    if (data == Val){
      rVal = true;
      return false;
    }
  });
  return rVal;
}

$(function() {
  $("#inagist_channels .trend_region").click(function(e){
    selectedChannel = $(this);
    fetchInagistTrends(selectedChannel.attr("data-channel-name"));
  });
  $("#twitter_woeids .trend_region").click(function(e){
    selectedWoeId = $(this);
    fetchTwitterTrends(selectedWoeId.attr("data-woeid"));
  });
  $(".trend").live('click', function(){
    selectedTrend = $(this);
    getKeyVals(selectedTrend.data('trend'), function(response){
      $("#selected_tweets_list").empty();
      $("#selected_trend_header").html(selectedTrend.data('trend'));
      $("#selected_trend_url").html("http://inagist.com/partners/utv/feed.php?customer=utv&q="+escape(selectedTrend.data('trend')));
      for (var x in response){
        var tweet = response[x];
        var tweetItem = jQuery("<li class='tweet'><div style='clear: both;'><span class='deletable'></span>"+tweet.hval+"</div></li>").data('selected_tweet', tweet).data('key', selectedTrend.data('trend'));
        $("#selected_tweets_list").append(tweetItem);
      }
    });
    searchInagist(selectedTrend.data('trend'));
    searchTwitter(selectedTrend.data('trend'));
  });
  $("#tweets_viewer .selectable").live('click', function(){
    selectedTweet = $(this).parent().parent();
    if (typeof(selectedTweet.data('trend')) == 'string'){
      var tweet = selectedTweet.data('tweet');
      storeKeyVal(selectedTweet.data('trend'), selectedTweet.data('text'), function(){
        getKeyVals(selectedTweet.data('trend'), function(response){
          $("#selected_tweets_list").empty();
          $("#selected_trend_header").html(selectedTrend.data('trend'));
          $("#selected_trend_url").html("http://inagist.com/partners/utv/feed.php?customer=utv&q="+escape(selectedTrend.data('trend')));
          for (var x in response){
            var tweet = response[x];
            var tweetItem = jQuery("<li class='tweet'><div style='clear: both;'><span class='deletable'></span>"+tweet.hval+"</div></li>").data('selected_tweet', tweet).data('key', selectedTweet.data('trend'));
            $("#selected_tweets_list").append(tweetItem);
          }
        });
      });
    }
  });
  $("#tweets_selected .deletable").live('click', function(event){
    event.preventDefault();
    selectedTweet = $(this).parent().parent();
    deleteKeyVal(selectedTweet.data('key'), selectedTweet.data('selected_tweet').id, function(response){
      selectedTweet.remove();
    });
  });
  $("#custom_trends .deletable").live('click', function(event){
    event.preventDefault();
    selectedTrend = $(this).parent().parent();
    deleteAllKeyVal(selectedTrend.data('trend'));
    deleteKeyVal('trends', selectedTrend.data('store_data').id, function(response){
      selectedTrend.remove();
    });
  });
  $("#custom_trend_field").keypress(function(e){
    if(e.which == 13){
      if (!checkValInList(this.value, $("#user_defined_list .trend"), "trend"))
        storeKeyVal("trends", this.value, loadSelectedTrends);
    }
  });
  $("#inagist_trends .trend, #twitter_trends .trend").live('click', function(e){
    selectedTrend = $(this);
    if (!checkValInList(selectedTrend.data('trend'), $("#user_defined_list .trend"), "trend"))
      storeKeyVal("trends", selectedTrend.data('trend'), loadSelectedTrends);
  });
  loadSelectedTrends();
});
