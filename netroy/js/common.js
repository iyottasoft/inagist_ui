(function(){
  window.params = {};
  var arr = window.location.search.substr(1).split("&");
  for(var i=0,l=arr.length;i<l;i++){
    var a = arr[i];
    if(a.length==0) continue;
    var b = a.split("=");
    if(b.length==0 || b[0].length==0) continue;
    window.params[b[0]] = b[1]||null;
  }

  window.makeurl = function(arr){
    var bf = {}, df = [];
    for(var param in window.params) bf[param] = window.params[param];
    for(var param in arr) bf[param] = arr[param];
    for(var param in bf) df.push(param+(!!bf[param]?"="+bf[param]:""));
    return window.baseurl+"?"+df.join("&");
  }

  /*$.oldajax = $.ajax;
  $.ajax = function(){
    if(arguments[0].url.replace(/http:\/\//g,"").replace(/\/.*$/,"") != window.location.host)
      arguments[0].url = window.baseurl+"proxy.php?url="+escape(arguments[0].url);
    $.oldajax.apply(window,arguments);
  }*/
})();

$(function(){
  window.isHome = ($("#tweets").length==0);
  if (window.widgetUrl)
	  window.tweetUrl = window.makeurl({"r":"main/widgettweets","user":window.user,"list":window.list,"hours":window.hours});
  else if (window.noautorefresh)
  	  window.tweetUrl = "";
  else
	  window.tweetUrl = window.makeurl({"r":"main/tweets","user":window.user,"list":window.list,"hours":window.hours});
	  
  window.portalUrl = window.makeurl({"r":"main/portals","count":(isHome?3:3)});
});

function linkify(text){
  if(typeof text != 'string') return "";
  text = text.replace(/(https?:\/\/[^\s]+)/i,"<a href='$1' class='ext' target='_blank' rel='nofollow'>$1</a>");
  text = text.replace(/(^|[\s,\.])@([A-Za-z0-9_]+)/g, "$1@<a href=\"http://twitter.com/$2\" target='_blank'>$2</a>");
  text = text.replace(/(^|[\s,\.])#([A-Za-z0-9_]+)/g,
    "$1#<a href=\"http://search.twitter.com/search?had_popular=true&q=$2\" target='_blank'>$2</a>");
  return text;
}

function toggleTweetByID(id){
  // Hide other previews
  $("div.preview[id!=pre"+id+"]").hide()
  $("div.body[id!=tw"+id+"]").removeClass("selected");
  
  // Toggle selected
  var preview = $("div#pre"+id);
  $("div.loader",preview).show();

  return preview;
}

function postToggle(id,preview){
  var node = $("div#tw"+id);
  if(preview.css("display")=='none'){
    node.removeClass("selected");
  }else{
    node.addClass("selected");

    // Scroll to the preview box
    var p_top = $("#pre"+id).offset().top;
    var w_bot = $(document).scrollTop() + $(window).height()-20;
    if(p_top > w_bot) $('html,body').scrollTop(p_top-$(window).height()+$("#tw"+id).height());

    if(preview.attr("rel")=="done"){
      $("div.loader",preview).hide();
      return;
    }

    var params = {"r":"main/lookup","id":id};
    var url = $("td.text a.url",node).attr("href");
    if(url.length>0 && url.indexOf("http://")==0) params["url"] = escape(url);

    $.get(window.makeurl(params),function(data){
      $("div.loader",preview).hide();
      $(":not(:first-child)",preview).remove();
      preview.append(data);
      $("span.replybt",preview).click(replyTweet);
    },"html");

    preview.attr("rel","done");
  }
}

function reloadTweet(){
  $.get(tweetUrl,function(data){
    if(data.length > 10){
      $("#leftpane").html(data);
      initTweetUI();
    }
  },"html");
}

function reloadPortal(){
  $.get(portalUrl,function(data){
    if(data.length > 10){
      $("#rightpane").html(data);
      initPortalUI();
    }
  },"html");
}

function handleTap(e){
  if($(e.target)[0].tagName.toLowerCase()=="a" || $(e.target.parentNode)[0].tagName.toLowerCase()=="a"){
    if($(e.target).hasClass("ext")){
      e.preventDefault();
      var bd = $(e.target).parentsUntil("div.body")[0];
      $(bd).click();
    }else e.stopPropagation();
    return;
  }
  
  var id = $("span.prevbt,span.respbt",$(this)).attr("id").replace(/^(prev|resp)_/,"");
  var preview = toggleTweetByID(id)
  preview.toggle();
  postToggle(id,preview);
}

function replyTweet(e){
  e.preventDefault();
  if(typeof window.loggedinuser == 'undefined'){
    modal.info("not logged in... login with twitter to post reply and retweet");
    window.location = "/login";
    return false;
  }
  var parent = $(this).parentsUntil("div.body,div.msg,div.tweetcontent").last().parent();
  var id = parent.attr("id").substr(2);
  var usr= $("span.user",parent).html();
  modal.prompt("replying to "+usr,"@"+usr+" ",function(msg){
    modal.loader.show("posting your reply");
    $.post(baseurl+"reply.php",{tweet_id:id,tweet_text:msg},function(data){
      if(data.response==200) modal.info("reply posted");
      else if(data.response==400) modal.info("not logged in");
      else modal.info("some issue posting your reply");
      modal.loader.hide();
    });
  });
  return false;
}

function retweet(e){
  e.preventDefault();
  if(typeof window.loggedinuser == 'undefined'){
    modal.info("not logged in... login with twitter to post reply and retweet");
    window.location = "/login";
    return false;
  }
  var parent = $(this).parentsUntil("div.body,div.tweetcontent").last().parent();
  var id = parent.attr("id").substr(2);
  modal.confirm("do you wanna retweet this?",function(){
    modal.loader.show("posting the retweet");
    $.post(baseurl+"retweet.php?tweet_id="+id,function(data){
      if(data.response==200) modal.info("retweet posted");
      else if(data.response==400) modal.info("not logged in");
      else modal.info("some issue posting your reply");
      modal.loader.hide();
    });
  });
  return false;
}

function showmore(e)
{
	e.preventDefault();
	$("div.child").slideUp("slow");
	var allMoreOfUsers = $("a.moreofuser");
	for(var i=0;i<allMoreOfUsers.length;i++)
	{	
		var node = $(allMoreOfUsers)[i];
		if (this != node)
		{
			$(node).attr("show","more");
			var cnt = $(node).attr("cnt") + " more&gt;&gt;&nbsp;&nbsp;";
			$(node).html(cnt);
		}
	}	
	var siblings = $(this).parent().parent().parent().parent().parent().nextUntil("div.parent");
	var showattr = $(this).attr("show");
	for(var i=0;i<siblings.length;i++)
	{	
		var node = $(siblings)[i];		
		if ($(node).hasClass('child'))
		{			
			if (showattr == 'more')
				$(node).slideDown('slow');
			else
				$(node).slideUp('slow');
		}			
	}
	if (showattr == 'more')
	{	
		$(this).attr("show","less");
		$(this).html("less&lt;&lt;&nbsp;&nbsp;");
	}
	else
	{
		$(this).attr("show","more");
		var cnt = $(this).attr("cnt") + " more&gt;&gt;&nbsp;&nbsp;";
		$(this).html(cnt);
	}	
}

