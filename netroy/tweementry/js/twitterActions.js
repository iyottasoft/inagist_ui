function retweet(tweetObj, url){
  var tweetid = tweetObj.id_str;
  var text = tweetObj.text;
  var screen_name = tweetObj.user.screen_name;
  $("#dialog").remove();
  $(document.body).append("<div id='dialog'><div id='dialog_content'><div class='navy_bluebg'>"+
    "<img src='/netroy/tweementry/images/tweementary_title.jpg' style='float:left;' />"+
    "<div class='dialog_user_details'><img src='"+logged_in_user.profile_image_url+"' />"+logged_in_user.name+"</div>"+
    "</div><div class='dialog_action_text'>Retweet to your followers?</div><div class='tweetConfirm'>" +
                          "</div><div class='clear'></div>"+
                          " <button id='okbutton'>"+
                          " OK </button> <button id='cancelbutton'> Cancel </button>"+
                          " <button id='editbutton'> Edit </button>"+
                          "<div class='clear'></div></div></div>");
  var tweetDiv = formatTweet(tweetObj);
  $(".icons", tweetDiv).remove();
  $("#dialog_content .tweetConfirm").append(tweetDiv);
  //if (url)
  //  appendFBLike(url, $("#dialog_content"));
  $("#okbutton").click(function(e){
    $(e.target).attr("disabled", true);
    $.ajax({
      type: 'POST',
      url:"/partner/retweet?partner=tweementry&tweet_id=" + tweetid,
      success: function(){ 
        showAlert("Retweeted");
      },
      error: function() {showAlert("Failed");}
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
  $(document.body).append("<div id='dialog'><div id='dialog_content'><div class='navy_bluebg'>"+
    "<img src='/netroy/tweementry/images/tweementary_title.jpg' style='float:left;' />"+
    "<div class='dialog_user_details'><img src='"+logged_in_user.profile_image_url+"' />"+logged_in_user.name+"</div>"+
    "</div><div class='dialog_action_text'>Whats Happening?</div> "+
    "<div class='clear'></div><textarea id='tweetText' rows='5' cols='45'>" + 
    "</textarea><div class='clear'></div><button id='okbutton'> OK </button> "+
    "<button id='cancelbutton'> Cancel </button> <span id='textCounter'></span><div class='clear'></div></div></div>");
  if (Text)
    $("#tweetText")[0].value = Text;

  $("#textCounter").html(140 - $("#tweetText")[0].value.length);
  $("#okbutton").click(function(e){
    $(e.target).attr("disabled", true);
    $.ajax({
      type: 'POST',
      url:"/partner/reply?partner=tweementry&tweet_text="+ escape($("#tweetText")[0].value),
      success: function(){ 
        showAlert("Tweet Sent");
      },
      error: function() {showAlert("Failed");}
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
  $(document.body).append("<div id='dialog'><div id='dialog_content'><div class='navy_bluebg'>"+
    "<img src='/netroy/tweementry/images/tweementary_title.jpg' style='float:left;' />"+
    "<div class='dialog_user_details'><img src='"+logged_in_user.profile_image_url+"' />"+logged_in_user.name+"</div>"+
    "</div><div class='dialog_action_text'>Reply to this Tweet?</div><div class='tweetConfirm'>"+
    "</div><div class='clear'></div><textarea id='replyText' rows='5' cols='45'>@" + 
    tweet.user.screen_name + additionals + "</textarea>" +
    " <div class='clear'></div><button id='okbutton'> OK </button> "+
    "<button id='cancelbutton'> Cancel </button> <span id='textCounter'></span><div class='clear'></div> </div></div>");
  var tweetDiv = formatTweet(tweet);
  $(".icons", tweetDiv).remove();
  $("#dialog_content .tweetConfirm").append(tweetDiv);
  $("#textCounter").html(140 - $("#replyText")[0].value.length);
  $("#okbutton").click(function(e){
    $(e.target).attr("disabled", true);
    $.ajax({
      type: 'POST',
      url:"/partner/reply?partner=tweementry&tweet_id=" + tweetid + "&tweet_text="+ escape($("#replyText")[0].value),
      success: function(){ 
        showAlert("Reply Sent");
      },
      error: function() {showAlert("Failed");}
    });
  });
  $("#cancelbutton").click(function(){
    $("#dialog").remove();
  });
}

function favorite(tweet, url){
  tweetid = tweet.id_str;
  $("#dialog").remove();
  $(document.body).append("<div id='dialog'> <div id='dialog_content'><div class='navy_bluebg'>"+
    "<img src='/netroy/tweementry/images/tweementary_title.jpg' style='float:left;' />"+
    "<div class='dialog_user_details'><img src='"+logged_in_user.profile_image_url+"' />"+logged_in_user.name+"</div>"+
    "</div><div class='dialog_action_text'>Favorite this Tweet?</div><div class='tweetConfirm'>"+
                          "</div><div class='clear'></div>"+
                          "<button id='okbutton'>"+
                          " OK </button> <button id='cancelbutton'> Cancel </button>"+
                          "<div class='clear'></div> </div></div>");
  var tweetDiv = formatTweet(tweet);
  $(".icons", tweetDiv).remove();
  $("#dialog_content .tweetConfirm").append(tweetDiv);
  //if (url)
  //  appendFBLike(url, $("#dialog_content"));
  $("#okbutton").click(function(e){
    $(e.target).attr("disabled", true);
    $.ajax({
      type: 'POST',
      url:"/partner/favorite?partner=tweementry&tweet_id=" + tweetid,
      success: function(){ 
        showAlert("Favorited");
      },
      error: function() {showAlert("Failed");}
    });
  });
  $("#cancelbutton").click(function(){
    $("#dialog").remove();
  });
}

function showAlert(Message){
  $("#dialog").remove();
  $(document.body).append("<div id='dialog'> <div id='dialog_content'>"+Message+"</div></div>");
  setTimeout(function(){
    $("#dialog").remove();
  }, 3000);
}
