$(function(){
    $("span.action a span.replybt").parent().click(replyTweet);
    $("span.action a span.retweetbt").parent().click(retweet);
    $("span.replybt").click(replyTweet);
    $(".time, .timestamp").prettyDate();
    $('a.oembed').embedly({maxWidth:720}).bind('embedly-oembed', function(e){
      $(this).parent().show('fast');
    });
  
    $("#prominentreply").click(function() {  
      $("#prominentreply").removeClass("tabnavnotselected");
      $("#prominentreply").addClass("tabnavselected");
      $("#relevantreply").removeClass("tabnavselected");
      $("#relevantreply").addClass("tabnavnotselected");
      $("#allreply").removeClass("tabnavselected");    		
      $("#allreply").addClass("tabnavnotselected");
      
      $("#pre" + currentTweetId).html("<span class='loading round-corner'>Loading</span>");  	  		   	
      var params = {"r":"main/lookup","id":currentTweetId,"reply_count":20,"enable_more_btn":1,"prominent":1};
      $.get(window.makeurl(params),function(data){
        $("#pre" + currentTweetId).html(data);  				  		    
      },"html");	
        return false;
    });
      $("#allreply").click(function() {
        $("#allreply").removeClass("tabnavnotselected");
      $("#allreply").addClass("tabnavselected");
      $("#relevantreply").removeClass("tabnavselected");
      $("#relevantreply").addClass("tabnavnotselected");
      $("#prominentreply").removeClass("tabnavselected");    		
      $("#prominentreply").addClass("tabnavnotselected");
        
      $("#pre" + currentTweetId).html("<span class='loading round-corner'>Loading</span>"); 	  		   	
      var params = {"r":"main/lookup","id":currentTweetId,"reply_count":20,"enable_more_btn":1,"prominent":0};
      $.get(window.makeurl(params),function(data){
        $("#pre" + currentTweetId).html(data);  				  		    
      },"html");	
        return false;
    });
      $("#relevantreply").click(function() {  
        $("#relevantreply").removeClass("tabnavnotselected");
      $("#relevantreply").addClass("tabnavselected");
      $("#allreply").removeClass("tabnavselected");
      $("#allreply").addClass("tabnavnotselected");
      $("#prominentreply").removeClass("tabnavselected");    		
      $("#prominentreply").addClass("tabnavnotselected");
      
      $("#pre" + currentTweetId).html("<span class='loading round-corner'>Loading</span>"); 	  		   	
      var params = {"r":"main/lookup","id":currentTweetId,"reply_count":20,"enable_more_btn":1,"userid":currentUserId};
      $.get(window.makeurl(params),function(data){
        $("#pre" + currentTweetId).html(data);  				  		    
      },"html");	
        return false;
    });   
    $(".twtmore").click(function() {
        var element = $(this);
      var msg = element.attr("id");  			   
      $("#morebutton").html('<div class="loading round-corner">loading</div>');
      var params = {"r":"main/lookup","id":currentTweetId,"start":msg,"reply_count":20,"enable_more_btn":1,"prominent":"Y" + currentUserId};
      $.get(window.makeurl(params),function(data){
        $("#morebutton").remove();
        $("#more_updates" + currentTweetId).append(data);  				  		    
      },"html");	
        return false;
    });   
});
