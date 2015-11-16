<div class="tweetcontent status" id="emessage_container">
	<div class="error-text">	
		<?=$message?>
	</div>	
</div>
<?php if(isset($tweetid)) { ?>
<script type="text/javascript">
  var tweetTemplate = '<div class="tweetcontent" id="tw{tweet.id_str}"> <div class="status"> <div class="left"> <a href="http://twitter.com/intent/user?screen_name={tweet.user.screen_name}" target="_blank" title="{tweet.user.name}"> <img alt="{tweet.user.name} as @{tweet.user.screen_name}" src="{tweet.user.profile_image_url}" style="width:75px; height:75px;"> </a>	</div> <div class="update-text">		<h1 style="clear: none;">{formattedText.text}</h1> </div>	 <span class="meta">			  <a href="http://twitter.com/intent/user?screen_name={tweet.user.screen_name}" target="_blank" title="{tweet.user.name}">        		<span class="user">{tweet.user.screen_name}</span>      		  </a> 			<a target="_blank" href="http://twitter.com/{tweet.user.screen_name}/status/{tweet.id_str}"><time class="timestamp" data-timestamp="{tweet.created_at}"></time></a>		 			<span class="action">				<a title="reply to this" href="#"><span class="replybt"> </span></a>      			<a title="retweet this" href="#"><span class="retweetbt"> </span></a>			</span>						<span class="right"></span>		</span>	    	</div> </div>';

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

/*  var tweetId = '<?=$tweetid?>';
  $(function(){
    $.ajax({
      url: "http://api.twitter.com/1/statuses/show/"+tweetId+".json?include_entities=true",
      dataType: "jsonp",
      success: function(data){
        var markup = render(tweetTemplate, {"tweet":data, "formattedText":{"text":twitterlib.ify.clean(data.text)}});
        $("#emessage_container").replaceWith(markup);
        $(".time, .timestamp").prettyDate();
      }
    });
  });
*/
</script>
<?php } ?>
