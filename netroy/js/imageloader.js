
/**
 * @license The imageloader.js library is licensed under GNU Lesser General Public License v3 or later
 */
(function(){
/**
 * TwitPic Image Loader
 * Example: ldr = new TwitPicLoader("http://twitpic.com/asdf123");
 * @param {string} URL of the TwitPic page. Eg. http://twitpic.com/asdf123
 * @constructor
 */
TwitPicLoader = function(url){
  this.url = url.replace(/^http:/, "https:");
};
/**
 * Get the large image URL from TwitPic
 * Note that TwitPic API doesn't offer JSONP
 * @param {Function} Callback function. Will be called with first argument is the image's URL and second argument is the description
 */
TwitPicLoader.prototype.getURL = function(cb){
  d = this.url.match(/twitpic\.com\/([0-9a-zA-Z]*).*/i);
  cb("<img src='http://twitpic.com/show/full/"+d[1]+".jpg' />", "");
  return true;
};
/**
 * Plixi Image Loader
 * Example: ldr = new PlixiLoader("https://plixi.com/p/asdf");
 * @param {string} URL of the Plixi page. Eg. https://plixi.com/p/asdf
 * @constructor
 */
PlixiLoader = function(url){
  this.url = url.replace(/^https:/, "http:");
};
/**
 * Get the image URL from Plixi API
 * @param {Function} Callback function. Will be called with first argument is the image's URL and second argument is the description
 */
PlixiLoader.prototype.getURL = function(cb){
  $.getJSON("https://api.plixi.com/api/tpapi.svc/jsonp/metadatafromurl?callback=?", {"url": this.url}, function(d){
    cb("<img src='"+d['BigImageUrl']+"' />", d['Message']);
  });
};
/**
 * VimeoLoader Loader
 * Example: ldr = new PlixiLoader("http://vimeo.com/27260633");
 * @param {string} URL of the Vimeo page. Eg. http://vimeo.com/27260633
 * @constructor
 */
VimeoLoader = function(url){
  this.url = url.replace(/^https:/, "http:");
};
VimeoLoader.prototype.getURL = function(cb){
  $.getJSON("http://vimeo.com/api/oembed.json?callback=?", {"url": this.url}, function(d){
    cb(d["html"], d['title']);
  });
};
HuluLoader = function(url){
  this.url = url.replace(/^https:/, "http:");
};
HuluLoader.prototype.getURL = function(cb){
  $.getJSON("http://www.hulu.com/api/oembed.json?callback=?", {"url": this.url}, function(d){
    cb(d["html"], d['title']);
  });
};
/**
 * OEmbed Image Loader
 * @param {string} Image URL
 * @param {string} OEmbed endpoint for that URL
 * @constructor
 */
OEmbedLoader = function(url, endpoint){
  this.url = url;
  this.endpoint = endpoint;
}
/**
 * Get the image URL from OEmbed endpoint
 * @param {Function} Callback function. Will be called with first argument is the image's URL and second argument is the description
 */
OEmbedLoader.prototype.getURL = function(cb){
  join = this.endpoint.indexOf("?") == -1 ? "?" : "&"
  $.getJSON(this.endpoint+join+"callback=?", {"url": this.url}, function(d){
    var embedCode = (typeof(d['html']) == 'string') ? d['html'] : "<img src='"+ d['url'] +"' />";
    cb(embedCode, d['title']);
  })
}
/**
 * picplz Image Loader
 * Example: ldr = new PicPlzLoader("https://picplz.com/Jn22");
 * @param {string} URL of the PicPlz page.
 * @constructor
 */
PicPlzLoader = function(url){
  if(url.match(/http[s]{0,1}:\/\/picplz\.com\/user\/[^\/]+\/pic\/([^\/]+)/)){
    this.urlType = "longurl";
  }else{
    this.urlType = "shorturl";
  }
  this.code = url.match(/\/([^\/]+)[\/]{0,1}$/)[1];
  this.url = url;
};
/**
 * Get the image URL from Plixi API
 * @param {Function} Callback function. Will be called with first argument is the image's URL and second argument is the description
 */
PicPlzLoader.prototype.getURL = function(cb){
  data = {};
  data[this.urlType+"_id"] = this.code;
  $.getJSON("https://api.picplz.com/api/v2/pic.json?callback=?", data, function(d){
    pic = d['value']['pics'][0];
    cb("<img src='"+pic['pic_files']['640r']['img_url']+"' />", pic['caption']);
  });
};
/**
 * twitgoo Image Loader
 * Note: No HTTPS support.
 * Example: ldr = new TwitGooLoader("http://twitgoo.com/a05dh");
 * @param {string} URL of the twitgoo page.
 * @constructor
 */
TwitGooLoader = function(url){
  this.code = url.match(/^http:\/\/twitgoo\.com\/([0-9a-zA-Z]+)/)[1];
  this.url = url;
};
/**
 * Get the image URL from twitgoo API
 * @param {Function} Callback function. Will be called with first argument is the image's URL and second argument is the description
 */
TwitGooLoader.prototype.getURL = function(cb){
  $.getJSON("http://twitgoo.com/api/message/info/"+this.code+"?format=json&callback=?", function(d){
    cb("<img src='"+d['imageurl']+"' />", d['text']);
  });
};

/* Main class */
window['ImageLoader'] = {
  /**
   * Get the image provider class from specified URL
   * @todo Make it extensible
   * @param {String} The image URL
   */
  "getProvider": function(url){
    if(url.match(/^http[s]{0,1}:\/\/twitqic\.com\/([0-9a-zA-Z]+)/)){
      // oohEmbed doesn't offer title
      return new TwitPicLoader(url);
    }else if(url.match(/^http[s]{0,1}:\/\/plixi\.com\/p\/([0-9]+)/)||
        url.match(/^http[s]{0,1}:\/\/lockerz\.com\/s\/([0-9]+)/)||
        url.match(/^http[s]{0,1}:\/\/tweetphoto\.com\/([0-9]+)/)){
      return new PlixiLoader(url);
    }else if(url.match(/^http[s]{0,1}:\/\/upic\.me\/(show\/([0-9]+)|e[^\/]+)/)){
      return new OEmbedLoader(url.replace(/^https:/, "http:"), "http://upic.me/api/oembed");
    }else if(url.match(/^http[s]{0,1}:\/\/instagr\.am\/p\/([^\/]+)/)){
      return new OEmbedLoader(url, "https://api.instagram.com/oembed");
    }else if(url.match(/^http[s]{0,1}:\/\/picplz\.com\/([^\/]+)/) ||
        url.match(/http[s]{0,1}:\/\/picplz\.com\/user\/[^\/]+\/pic\/([^\/]+)/)){
      return new PicPlzLoader(url);
    }else if(url.match(/^http[s]{0,1}:\/\/(www\.|)xkcd\.com\/([0-9]+)/)){
      return new OEmbedLoader(url.replace(/^https:/, "http:"), "http://api.embed.ly/1/oembed");
    }else if(url.match(/^http:\/\/twitgoo\.com\/([0-9a-zA-Z]+)/)){
      // no HTTPS support
      return new TwitGooLoader(url);
    }else if(url.match(/vimeo\.com\//)){
      return new VimeoLoader(url);
    }else if(url.match(/hulu\.com\/watch/)){
      return new HuluLoader(url);
    }else if(url.match(/(youtube|yfrog|flickr)/)){
      return new OEmbedLoader(url, "http://inagist.com/oembed");
    }
  },
  "viewer": {
    "container": function(url, container){
      provider = ImageLoader['getProvider'](url);
      if (typeof provider == 'object'){
        container.parent().show();
        provider.getURL(function(html, desc){
          container.replaceWith(html + "<br/>" +
            "<span class='provider_info'>Embedded from <a href='"+provider.url+"' target='_blank'>"+
            provider.url+"</a></span>");
        });
      }
    }
  }
};
})();

if(!$) alert("jQuery is required!")
