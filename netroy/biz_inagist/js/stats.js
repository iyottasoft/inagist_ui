Raphael.fn.drawGrid = function (x, y, w, h, wv, hv, color) {
    color = color || "#000";
    var path = ["M", Math.round(x) + .5, Math.round(y) + .5, "L", Math.round(x + w) + .5, Math.round(y) + .5, Math.round(x + w) + .5, Math.round(y + h) + .5, Math.round(x) + .5, Math.round(y + h) + .5, Math.round(x) + .5, Math.round(y) + .5],
        rowHeight = h / hv,
        columnWidth = w / wv;
    for (var i = 1; i < hv; i++) {
        path = path.concat(["M", Math.round(x) + .5, Math.round(y + i * rowHeight) + .5, "H", Math.round(x + w) + .5]);
    }
    for (i = 1; i < wv; i++) {
        path = path.concat(["M", Math.round(x + i * columnWidth) + .5, Math.round(y) + .5, "V", Math.round(y + h) + .5]);
    }
    return this.path(path.join(",")).attr({stroke: color});
};

function getAnchors(p1x, p1y, p2x, p2y, p3x, p3y) {
    var l1 = (p2x - p1x) / 2,
        l2 = (p3x - p2x) / 2,
        a = Math.atan((p2x - p1x) / Math.abs(p2y - p1y)),
        b = Math.atan((p3x - p2x) / Math.abs(p2y - p3y));
    a = p1y < p2y ? Math.PI - a : a;
    b = p3y < p2y ? Math.PI - b : b;
    var alpha = Math.PI / 2 - ((a + b) % (Math.PI * 2)) / 2,
        dx1 = l1 * Math.sin(alpha + a),
        dy1 = l1 * Math.cos(alpha + a),
        dx2 = l2 * Math.sin(alpha + b),
        dy2 = l2 * Math.cos(alpha + b);
    return {
        x1: p2x - dx1,
        y1: p2y + dy1,
        x2: p2x + dx2,
        y2: p2y + dy2
    };
}

function drawTrendStats(trends){
  var data = [],
      labels = [];
  if (typeof trends != 'undefined' && trends.length > 0)
    for (var i=0; i < trends.length; i++){
      data.push(trends[i].level);
      labels.push(trends[i].key);
    }
  else {
    data = [100, 200, 300, 500, 40, 30];
    labels= ["wah wah", "blah blah", "doh doh", "so so", "rest", "redt"];
  }

  $("#media_preview").html("");
  var width = 800,
      height = 400,
      leftgutter = 30,
      bottomgutter = 100,
      topgutter = 20,
      colorhue = .6 || Math.random(),
      color = "hsb(" + [colorhue, .5, 1] + ")",
      txt = {font: '12px Helvetica, Arial', fill: "#fff"},
      txt3 = {font: '10px Courier', fill: "#fff", 'text-anchor': 'end'},
      txt1 = {font: '10px Helvetica, Arial', fill: "#fff"},
      txt2 = {font: '12px Helvetica, Arial', fill: "#000"},
      X = (width - leftgutter) / labels.length,
      max = Math.max.apply(Math, data),
      min = Math.min.apply(Math, data),
      Y = (height - bottomgutter - topgutter) / max;
  r = Raphael("media_preview", width, height);

  r.drawGrid(leftgutter + X * .5 + .5, topgutter + .5, width - leftgutter - X, 
      height - topgutter - bottomgutter, 10, 10, "#666");
  path = r.path().attr({stroke: color, "stroke-width": 4, "stroke-linejoin": "round"});
  bgp = r.path().attr({stroke: "none", opacity: .3, fill: color});
  var label = r.set(),
      is_label_visible = false,
      leave_timer,
      blanket = r.set();
  label.push(r.text(60, 12, "0").attr(txt));
  label.push(r.text(60, 27, "0").attr(txt1).attr({fill: color}));
  label.hide();
  var frame = r.popup(100, 100, label, "right").attr({fill: "#000", stroke: "#666", "stroke-width": 2, "fill-opacity": .7}).hide();

  //var p, bgpp;
  for (var i = 0, ii = labels.length; i < ii; i++) {
      var y = Math.round(height - bottomgutter - Y * data[i]),
          x = Math.round(leftgutter + X * (i + .5)),
          lheight = height - bottomgutter + 3,
          t = r.text(x - 10, lheight , labels[i]).attr(txt3).toBack();
          t.rotate(-45, x, lheight);
      if (!i) {
          p = ["M", x, y, "C", x, y];
          bgpp = ["M", leftgutter + X * .5, height - bottomgutter, "L", x, y, "C", x, y];
      }
      if (i && i < ii - 1) {
          var Y0 = Math.round(height - bottomgutter - Y * data[i - 1]),
              X0 = Math.round(leftgutter + X * (i - .5)),
              Y2 = Math.round(height - bottomgutter - Y * data[i + 1]),
              X2 = Math.round(leftgutter + X * (i + 1.5));
          var a = getAnchors(X0, Y0, x, y, X2, Y2);
          p = p.concat([a.x1, a.y1, x, y, a.x2, a.y2]);
          bgpp = bgpp.concat([a.x1, a.y1, x, y, a.x2, a.y2]);
      }
      var dot = r.circle(x, y, 4).attr({fill: "#000", stroke: color, "stroke-width": 2});
      blanket.push(r.rect(leftgutter + X * i, 0, X, height - bottomgutter).
        attr({stroke: "none", fill: "#fff", opacity: 0}));
      var rect = blanket[blanket.length - 1];
      (function (x, y, data, lbl, dot) {
          var timer, i = 0;
          rect.hover(function () {
              clearTimeout(leave_timer);
              var side = "right";
              if (x + frame.getBBox().width > width) {
                  side = "left";
              }
              var ppp = r.popup(x, y, label, side, 1);
              frame.show().stop().animate({path: ppp.path}, 200 * is_label_visible);
              label[0].attr({text: data}).show().stop().
                animateWith(frame, {translation: [ppp.dx, ppp.dy]}, 200 * is_label_visible);
              label[1].attr({text: lbl}).show().stop().
                animateWith(frame, {translation: [ppp.dx, ppp.dy]}, 200 * is_label_visible);
              dot.attr("r", 6);
              is_label_visible = true;
          }, function () {
              dot.attr("r", 4);
              leave_timer = setTimeout(function () {
                  frame.hide();
                  label[0].hide();
                  label[1].hide();
                  is_label_visible = false;
              }, 1);
          });
      })(x, y, data[i], labels[i], dot);
  }
  p = p.concat([x, y, x, y]);
  bgpp = bgpp.concat([x, y, x, y, "L", x, height - bottomgutter, "z"]);
  path.attr({path: p});
  bgp.attr({path: bgpp});
  frame.toFront();
  label[0].toFront();
  label[1].toFront();
  blanket.toFront();
}

var TrendyBars = function(data, options){
  this.defaults = {
    background: "rgba(12, 12, 12, 0.8)",
    max_count: 20,
    bar_width: 10,
    element_id : "trendy_bars",
    width: 200,
    height: 400,
    left_gutter: 10,
    right_gutter: 10,
    top_gutter : 10,
    bottom_gutter: 10,
    animate_easing: 'bounce',
    animate_time: 1500,
    text_style : {font: '12px Helvetica, Arial', fill: "#fff", 'text-anchor':'end'},
    callback : function(e) { console.log(e);}
  };
  this.settings = $.extend(this.defaults, options);
  this.elements = {};
  this.data = [];
  this.max = 0;
  this.min = 0;
  this.factor = 0;
  this.gwidth = this.settings.width - this.settings.left_gutter - this.settings.right_gutter;
  this.gheight = this.settings.height - this.settings.top_gutter - this.settings.bottom_gutter;
  this.raphael = Raphael(this.settings.element_id, this.settings.width, this.settings.height)
  this.init(data);
};

TrendyBars.prototype = {
  getCallback: function (Data, Callback){
    return function(){
      Callback(Data);
    }
  },

  init: function(data){
    this.elements = {};
    this.yfactor = this.gheight / this.settings.max_count;
    data = data.sort(function(a, b){ return b.rank - a.rank == 0 ? b.level - a.level : b.rank - a.rank;}).
      slice(0, this.settings.max_count);
    this.data = data;
    this.max = data[0].rank;
    this.min = data[data.length -1].rank;
    this.xfactor = this.gwidth / this.max;
    var j = 1;
    for (var i in this.data){
      var hue = this.data[i].rank / this.max;
      var y = this.yfactor * j - this.settings.bar_width/2;
      var radius = this.settings.bar_width/2 + 2;
      var bar = this.raphael.rect(this.settings.left_gutter, y, 
                                  this.xfactor * this.data[i].rank, 
                                  this.settings.bar_width, this.settings.bar_width/2).
                attr({stroke: "hsla(0.9, 0.9, 0.9, 0.7)", 
                      fill: "hsla(" + hue + ","+hue*0.8+","+hue * 0.9+", 0.8)",
                      'stroke-width': 2}).
                toFront();
      var bcircle = this.raphael.circle(this.settings.left_gutter + radius, y + this.settings.bar_width/2, 
                      radius).
                    attr({stroke: "hsla(0.9, 0.9, 0.9, 0.7)", 
                          fill: "hsla(" + hue + ","+hue*0.9+","+hue * 0.8+", 0.9)",
                          'stroke-width': 2}).
                    toFront();
      var bset = this.raphael.set();
      var text = this.raphael.text(this.settings.width - this.settings.right_gutter, y - this.settings.bar_width/4, 
                  this.data[i].key).
                 attr(this.settings.text_style);
      var tbox = text.getBBox();
      var ttbox = this.raphael.rect(tbox.x - 3, tbox.y + 2, tbox.width + 5, tbox.height, 3).
                  attr({fill: "#222", opacity: 0.75});
      ttbox.toFront();
      text.toFront();
      bset.push(bar, bcircle, text, ttbox);
      bset.click(this.getCallback(this.data[i].key, this.settings.callback));
      bset.attr({cursor: 'pointer'});
      this.elements[this.data[i].key] = {bset : bset, position: (j - 1)};
      j++;
    }
  },
  
  delete_trend: function(trend){
    if (typeof this.elements[trend.key] != 'undefined'){
      var pos = this.elements[trend.key].position;
      this.elements[trend.key].bset.remove();
      delete this.elements[trend.key];
      while (pos < this.data.length-1){
        this.data[pos] = this.data[pos+1];
        this.elements[this.data[pos].key].position--;
        this.elements[this.data[pos].key].bset.animate({translation: '0 ' + -(this.yfactor)}, 
          this.settings.animate_time, this.settings.animate_easing);
        pos++;
      }
      this.data.pop();
      if (this.data[0].rank < this.max){
        this.max = this.data[0].rank;
        this.xfactor = this.gwidth / this.max;
        this.redraw();
      } 
    }
  },

  update: function(trend){
    if (trend.rank == -1) return this.delete_trend(trend);
    if (trend.rank <= this.min && this.data.length >= this.settings.max_count) return;
    if (typeof this.elements[trend.key] != 'undefined'){
       var pos = this.elements[trend.key].position;
       this.data[pos] = trend;
       var bset = this.elements[trend.key].bset;
       while ( pos > 0 && 
          (this.data[pos].rank > this.data[pos-1].rank ||
            (this.data[pos].rank == this.data[pos-1].rank &&
             this.data[pos].level > this.data[pos-1].level))){
         this.data[pos] = this.data[pos-1];
         this.elements[this.data[pos].key].position++;
         this.elements[this.data[pos].key].bset.animate({translation: '0 ' + this.yfactor}, 
          this.settings.animate_time, this.settings.animate_easing);
         this.data[pos-1] = trend;
         this.elements[trend.key].position--;
         this.elements[trend.key].bset.animate({translation: '0 ' + -(this.yfactor)}, 
          this.settings.animate_time, this.settings.animate_easing);
         pos--;
       }
       while ( pos < this.data.length-2 && 
         (this.data[pos+1].rank > this.data[pos].rank ||
            (this.data[pos+1].rank == this.data[pos].rank &&
             this.data[pos+1].level > this.data[pos].level))){
         this.data[pos] = this.data[pos+1];
         this.elements[this.data[pos].key].position--;
         this.elements[this.data[pos].key].bset.animate({translation: '0 ' + -(this.yfactor)}, 
          this.settings.animate_time, this.settings.animate_easing);
         this.data[pos+1] = trend;
         this.elements[trend.key].position++;
         this.elements[trend.key].bset.animate({translation: '0 ' + this.yfactor}, 
          this.settings.animate_time, this.settings.animate_easing);
         pos++;
       }
       if (trend.rank > this.max){
          this.max = trend.rank;
          this.xfactor = this.gwidth / this.max;
          this.redraw();
       } else 
         bset[0].animate({width: this.xfactor * trend.rank}, this.settings.animate_time, this.settings.animate_easing);
    } else {
      // slot it in
      if (this.data.length >= this.settings.max_count){
        // remove the last one
        var last = this.data.pop();
        var lelem = this.elements[last.key];
        delete this.elements[last.key];
        lelem.bset.remove();
      }
      for (var i = this.data.length - 1; i>=0; i--){
         if ((this.data[i].rank < trend.rank) ||
              (this.data[i].rank == trend.rank &&
               this.data[i].level < trend.level)){
           // move it down
           this.data[i+1] = this.data[i];
           this.elements[this.data[i].key].position++;
           this.elements[this.data[i].key].bset.animate({translation: '0 '+ this.yfactor}, 
            this.settings.animate_time, this.settings.animate_easing);
         } else
          break;
      }
      this.data[++i] = trend;
      this.max = this.data[0].rank;
      this.min = this.data[this.data.length - 1].rank;
      this.xfactor = this.gwidth / this.max;
      var hue = trend.rank / this.max;
      var y = this.yfactor * (i+1) - this.settings.bar_width/2;
      var radius = this.settings.bar_width/2 + 2;
      var bar = this.raphael.rect(this.settings.left_gutter, y, 
                                  this.xfactor * trend.rank, 
                                  this.settings.bar_width, this.settings.bar_width/2).
                attr({stroke: "hsla(0.9, 0.9, 0.9, 0.7)", 
                      fill: "hsla(" + hue + ","+hue*0.8+","+hue * 0.9+", 0.8)",
                      'stroke-width': 2}).
                toFront();
      var bcircle = this.raphael.circle(this.settings.left_gutter + radius, y + this.settings.bar_width/2, 
                      radius).
                    attr({stroke: "hsla(0.9, 0.9, 0.9, 0.7)", 
                          fill: "hsla(" + hue + ","+hue*0.9+","+hue * 0.8+", 0.9)",
                          'stroke-width': 2}).
                    toFront();
      var bset = this.raphael.set();
      var text = this.raphael.text(this.settings.width - this.settings.right_gutter, y - this.settings.bar_width/4, 
                  trend.key).
                 attr(this.settings.text_style);
      var tbox = text.getBBox();
      var ttbox = this.raphael.rect(tbox.x - 3, tbox.y + 2, tbox.width + 5, tbox.height, 3).
                  attr({fill: "#222", opacity: 0.75});
      ttbox.toFront();
      text.toFront();
      bset.push(bar, bcircle, text, ttbox);
      bset.click(this.getCallback(trend.key, this.settings.callback));
      bset.attr({cursor: 'pointer'});
      this.elements[trend.key] = {bset : bset, position: i};
      if (i == 0 || i == this.settings.max_count)
        this.redraw();
      return;
    }
  },
  redraw: function(){
    for (var i in this.data){
     var bset = this.elements[this.data[i].key].bset;
     bset[0].animate({width : this.xfactor * this.data[i].rank}, 
      this.settings.animate_time, this.settings.animate_easing);
    }
  }
};

