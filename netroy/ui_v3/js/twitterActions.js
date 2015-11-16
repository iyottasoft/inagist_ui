function retweet(tweetObj, url){
  var tweetid = tweetObj.id_str;
  var text = tweetObj.text;
  var screen_name = tweetObj.user.screen_name;
  $("#dialog").remove();
  $(document.body).append("<div id='dialog'><div id='dialog_content'><div class='tweetConfirm'>" +
                          "</div><div class='clear'></div>"+
                          " Retweet this? <div class='clear'></div><button id='okbutton'>"+
                          " OK </button> <button id='cancelbutton'> Cancel </button>"+
                          " <button id='editbutton'> Edit </button>"+
                          "<div class='clear'></div></div></div>");
  var tweetDiv = formatTweet(tweetObj);
  $(".meta", tweetDiv).remove();
  $("#dialog_content .tweetConfirm").append(tweetDiv);
  if (url)
    appendFBLike(url, $("#dialog_content"));
  $("#okbutton").click(function(e){
    $(e.target).attr("disabled", true);
    $.ajax({
      type: 'POST',
      url:"/retweet.php?tweet_id=" + tweetid,
      success: function(){ 
        $("#dialog_content").html("Retweeted"); 
        setTimeout(function(){
          $("#dialog").remove();
        }, 3000);
      },
      error: function() {showLogin();}
    });
  });
  $("#cancelbutton").click(function(){
    $("#dialog").remove();
  });
  $("#editbutton").click(function(){
    tweet("RT @" + screen_name + " " +text); 
  });
}

function tweet(Text){
  $("#dialog").remove();
  $(document.body).append("<div id='dialog'><div id='dialog_content'> What's happening? "+
    "<div class='clear'></div><textarea id='tweetText' rows='5' cols='60'>" + 
    "</textarea><div class='clear'></div><button id='okbutton'> OK </button> "+
    "<button id='cancelbutton'> Cancel </button> <span id='textCounter'></span><div class='clear'></div></div></div>");
  if (Text)
    $("#tweetText")[0].value = Text;

  $("#textCounter").html(140 - $("#tweetText")[0].value.length);
  $("#okbutton").click(function(e){
    $(e.target).attr("disabled", true);
    $.ajax({
      type: 'POST',
      url:"/reply.php?tweet_text="+ escape($("#tweetText")[0].value),
      success: function(){ 
        $("#dialog_content").html("Tweet sent"); 
        setTimeout(function(){
          $("#dialog").remove();
        }, 3000);
      },
      error: function() {showLogin();}
    });
  });
  $("#cancelbutton").click(function(){
    $("#dialog").remove();
  });
}

function reply(tweet){
  tweetid = tweet.id_str;
  var additionals = typeof tweet.retweeted_status != 'undefined' ? " @" + tweet.retweeted_status.user.screen_name : "";
  additionals += typeof official_hashtag != 'undefined' ? " " + official_hashtag : "";
  $("#dialog").remove();
  $(document.body).append("<div id='dialog'><div id='dialog_content'><div class='tweetConfirm'>"+
    "</div><div class='clear'></div><textarea id='replyText' rows='5' cols='60'>@" + 
    tweet.user.screen_name + additionals + "</textarea>" +
    " <div class='clear'></div><button id='okbutton'> OK </button> "+
    "<button id='cancelbutton'> Cancel </button> <span id='textCounter'></span><div class='clear'></div> </div></div>");
  var tweetDiv = formatTweet(tweet);
  $(".meta", tweetDiv).remove();
  $("#dialog_content .tweetConfirm").append(tweetDiv);
  $("#textCounter").html(140 - $("#replyText")[0].value.length);
  $("#okbutton").click(function(e){
    $(e.target).attr("disabled", true);
    $.ajax({
      type: 'POST',
      url:"/reply.php?tweet_id=" + tweetid + "&tweet_text="+ escape($("#replyText")[0].value),
      success: function(){ 
        $("#dialog_content").html("Reply sent"); 
        setTimeout(function(){
          $("#dialog").remove();
        }, 3000);
      },
      error: function() {showLogin();}
    });
  });
  $("#cancelbutton").click(function(){
    $("#dialog").remove();
  });
}

function favorite(tweet, url){
  tweetid = tweet.id_str;
  $("#dialog").remove();
  $(document.body).append("<div id='dialog'> <div id='dialog_content'><div class='tweetConfirm'>"+
                          "</div><div class='clear'></div>"+
                          " Favorite this? <div class='clear'></div><button id='okbutton'>"+
                          " OK </button> <button id='cancelbutton'> Cancel </button>"+
                          "<div class='clear'></div> </div></div>");
  var tweetDiv = formatTweet(tweet);
  $(".meta", tweetDiv).remove();
  $("#dialog_content .tweetConfirm").append(tweetDiv);
  if (url)
    appendFBLike(url, $("#dialog_content"));
  $("#okbutton").click(function(e){
    $(e.target).attr("disabled", true);
    $.ajax({
      type: 'POST',
      url:"/favorite.php?tweet_id=" + tweetid,
      success: function(){ 
        $("#dialog_content").html("Favorited"); 
        setTimeout(function(){
          $("#dialog").remove();
        }, 3000);
      },
      error: function() {showLogin();}
    });
  });
  $("#cancelbutton").click(function(){
    $("#dialog").remove();
  });
}

function showLogin(){
  $("#dialog_content").html("Failed"); 
  setTimeout(function(){
    $("#dialog").remove();
  }, 3000);
}
