// Generated by CoffeeScript 1.3.3
var GouseiComponent,
  __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
  __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

GouseiComponent = (function(_super) {

  __extends(GouseiComponent, _super);

  GouseiComponent.index = 0;

  GouseiComponent.defaultParams = {
    width: 250,
    height: 250,
    coords: {
      top: 0,
      left: 0
    },
    img: [
      {
        size: {
          width: 250,
          height: 250
        },
        coords: {
          x: 0,
          y: 0
        },
        priority: 1
      }, {
        size: {
          width: 250,
          height: 250
        },
        coords: {
          x: 100,
          y: 100
        },
        priority: 20
      }, {
        size: {
          width: 250,
          height: 250
        },
        coords: {
          x: 100,
          y: 50
        },
        priority: 20
      }
    ]
  };

  function GouseiComponent(srcList, params) {
    this.params = params != null ? params : GouseiComponent.defaultParams;
    this.onload = __bind(this.onload, this);

    this.focusSize = {
      width: 20,
      height: 20
    };
    this.flag = {
      focus: false
    };
    this.img = new Image();
    this.canvas = new _Canvas("canvas_gousei_" + GouseiComponent.index, true, this.params);
    GouseiComponent.index += 1;
    this.generate_image(srcList);
  }

  GouseiComponent.prototype.generate_image = function(srcList) {
    var _this = this;
    return $.when(this.onload(srcList[0]), this.onload(srcList[1]), this.onload('/img/hukidashi.jpeg')).then(function(img1, img2, balloon) {
      _this.canvas.drawImage(img1, _this.params.img[0].coords);
      _this.canvas.drawImage(balloon, _this.params.img[2].coords);
      _this.canvas.drawImage(img2, _this.params.img[1].coords);
      _this.img.src = _this.canvas.getImageData();
      return console.log(_this.img);
    });
  };

  GouseiComponent.prototype.onload = function(src) {
    var dfd, img,
      _this = this;
    dfd = $.Deferred();
    img = new Image();
    img.src = src;
    img.onload = function() {
      return dfd.resolve(img);
    };
    return dfd.promise();
  };

  return GouseiComponent;

})(ImageComponent);
