(window.modal = function modal(){
  // if modal stuff missing.. add it
  if($("div#mCont").length==0) $(document.body).append('<div id="mCont"><div class="overlay opac60"></div><div class="loader"></div><div class="info"></div><div class="dialog"><div class="title"></div><div class="content"></div></div></div>');
  
  // change position fixed to absolute for ie6
  if($.browser.msie && $.browser.version == "6.0") $("div#mCont,div#mCont div.overlay,div#mCont div.loader,div#mCont div.dialog").css("position","absolute");
  
  function resizeOverlay(){
    var cont = $("div#mCont");
    $("div.overlay",cont).css({height:$(document.body).height()+"px",width:$(document.body).width()+"px"});

    var loader = $("div.loader",cont);
    loader.css("left",Math.floor(($(document.body).width()-loader.outerWidth())/2));

    var info = $("div.info",cont);
    info.css("left",Math.floor(($(document.body).width()-info.outerWidth())/2));

    var dialog = $("div.dialog",cont);
    dialog.css("left",Math.floor(($(document.body).width()-dialog.outerWidth())/2));
    dialog.css("top",Math.floor(($(window).height()-dialog.outerHeight())/3));
  }
  $(window).resize(resizeOverlay);
  resizeOverlay();

  modal.loader = {
    show:function(msg){
      msg = msg||"loading";
      //$("div#mCont div.dialog").hide();
      $("div#mCont div.loader").html(msg);
      $("div#mCont div.overlay,div#mCont div.loader").show();
      return modal.loader;
    },hide:function(){
      $("div#mCont div.overlay,div#mCont div.loader").hide();
    }
  };

  modal.info = function(msg,lag){
    if(typeof msg != "string") return;
    lag = lag||3000;
    $("div#mCont div.info").html(msg).show().stop().delay(lag).slideUp('fast');
  }

  modal.promote = function(msg, closeCallback){
    if(typeof msg != "string") return;
    closeCallback = (typeof closeCallback == 'function')?closeCallback:(new Function);
    $("div#mCont div.info").html(msg).slideDown('slow');
    var closeDiv = jQuery("<div style='float: right; text-decoration : underline;'>close [x]</div>").
      click(function() {
        $("div#mCont div.info").slideUp('fast');
        closeCallback();
      });
    $("div#mCont div.info").append(closeDiv);
  }

  modal.prompt = function(title,pretext,callback){
    title = title || "input text";
    pretext = pretext || "";
    callback = (typeof callback == 'function')?callback:(new Function);

    var dialog = $("div#mCont div.dialog");
    $("div.title",dialog).html(title);
    $("div.content",dialog).html("<div><textarea>"+pretext+"</textarea></div><span>char left : <b>"+(140-pretext.length)+"</b></span><button value='ok'>ok</button><button>cancel</button>");

    modal.loader.hide();
    $("div#mCont div.overlay,div#mCont div.dialog").show();

    // on typing calculate chars
    var ibox = $("div.content textarea",dialog);
    ibox.focus().keyup(function(e){
      var counter = $("div.content span b");
      var l = 140-$(this).val().length;
      counter.html(l);
      if(l>10) counter.attr("style","");
      else if(l>=0) counter.attr("style","color:#CB8D00;");
      else counter.attr("style","color:#EB1500;");
    });

    // on button click
    $("div.content button",dialog).click(function(){
      if($(this).attr("value").toLowerCase()=='ok'){
        var val = ibox.val();
        if(val.length>140){
          modal.info("text too long");
          return;
        }
        callback.call(window,val);
      }
      $("div#mCont div.overlay,div#mCont div.dialog").hide();
    });
  }

  modal.confirm = function(msg,callback){
    msg = msg || "are you sure";
    callback = (typeof callback == 'function')?callback:(new Function);

    var dialog = $("div#mCont div.dialog");
    $("div.title",dialog).html("Retweet");
    $("div.content",dialog).html("<p>"+msg+"</p><button value='ok'>yes</button><button>no</button>");
    $("div.content button",dialog).click(function(){
      if($(this).attr("value").toLowerCase()=='ok') callback.call(window);
      $("div#mCont div.overlay,div#mCont div.dialog").hide();
    });
    modal.loader.hide();
    $("div#mCont div.overlay,div#mCont div.dialog").show();    
  }
})();
