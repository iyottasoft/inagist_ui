$(function(){
  if (typeof modal.promote == 'function'){
    var promotions = ["Run a WordPress blog? Get the latest from Twitter on your blog post with our <a href='http://bit.ly/inagist_wordpress_plugin'>WordPress Widget</a>",
    "Like the content? Get a <a href='http://bit.ly/inagist_create_widget'>customized widget</a> for your self.",
    "Try a newsy version of this at <a href='http://nowwhat.in/'>nowwhat.in</a>"];
    var isChrome=navigator.userAgent.toLowerCase().indexOf('chrome')>-1;
    if (isChrome && !localStorage.extension_advice_blocked)
      modal.promote("Keep yourself updated with our <a href='https://chrome.google.com/extensions/detail/oangdphebgapkakpmiiceehanhopodgo'>"+
                    "Chrome extension</a>", function(){localStorage.extension_advice_blocked = true;});
    else if (Math.random() < 0.25){
      var promotion_index = Math.floor(Math.random() * promotions.length);
      modal.promote(promotions[promotion_index]);
    }
  }
});
