// Generated by CoffeeScript 1.3.3
var BirthdayArrange,
  __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

BirthdayArrange = (function(_super) {

  __extends(BirthdayArrange, _super);

  function BirthdayArrange(planId) {
    BirthdayArrange.__super__.constructor.call(this);
    this.planId = planId;
  }

  BirthdayArrange.prototype.upload = function() {
    var url,
      _this = this;
    url = this.makeUrl();
    return this.setBackgroundImages().then(function() {
      var saveData;
      saveData = _this.getImageData();
      return $.ajax({
        url: url,
        type: "POST",
        data: {
          "img_file": saveData
        },
        success: function(res) {
          return location.href = "/mypage";
        }
      });
    });
  };

  BirthdayArrange.prototype.promiseImage = function(src) {
    var df, img,
      _this = this;
    df = $.Deferred();
    img = new Image();
    img.src = src;
    img.onload = function() {
      return df.resolve(img);
    };
    img.onerror = function(err) {
      return df.reject(err);
    };
    return df.promise();
  };

  BirthdayArrange.prototype.createFixedImageComponent = function(src) {
    var ic;
    ic = new ImageComponent(src, 'none');
    ic.focus = function() {
      return this.flag.focus = false;
    };
    ic.rangeImageInCheck = function() {
      return false;
    };
    ic.rangeFocusInCheck = function() {
      return false;
    };
    ic.focusSize.width = 0;
    ic.focusSize.height = 0;
    ic.draw = function(ctx) {
      return ctx.drawImage(this.img, 0, 0, this.img.width, this.img.height, this.coords.left, this.coords.top, this.size.width, this.size.height);
    };
    return ic;
  };

  BirthdayArrange.prototype.setImages = function() {
    var bdeco, self;
    self = this;
    bdeco = new BirthdayDeco(self.planId);
    $.when(this.promiseImage("/img/card-bg.png"), this.promiseImage("/img/cover.png"), bdeco.load()).then(function(img, covImg, decoImg) {
      var ic, imageUrl, profileIC, textC, width;
      ic = self.createFixedImageComponent(img.src);
      ic.size.width = self.canvas.width;
      ic.size.height = self.canvas.height;
      self.pushImage(ic);
      imageUrl = bdeco.profileImage.src;
      profileIC = self.createFixedImageComponent(imageUrl);
      profileIC.size.width = 100;
      profileIC.size.height = 100;
      profileIC.coords.left = (self.canvas.width - profileIC.size.width) * 0.5;
      profileIC.coords.top = (self.canvas.height - profileIC.size.height) * 0.5;
      self.pushImage(profileIC);
      ic = self.createFixedImageComponent(covImg.src);
      ic.size.width = 200;
      ic.size.height = 200;
      ic.coords.top = 140;
      ic.coords.left = 150;
      self.pushImage(ic);
      textC = new TextComponent(bdeco.username, "bold 12px sans-serif");
      width = textC.getWidth(self.ctx);
      textC.left = self.canvas.width * 0.5 - width * 0.5;
      textC.top = self.canvas.height * 0.5 + profileIC.size.height * 0.5 + 28;
      return self.pushComponent(textC);
    });
    return self.getImages();
  };

  BirthdayArrange.prototype.getImageData = function() {
    var data, type;
    this.reDraw(false);
    type = "image/png";
    data = this.canvas.toDataURL(type);
    data = data.replace('data:image/png;base64,', '');
    return data;
  };

  BirthdayArrange.prototype.getImages = function() {
    var collaboratorList, self, url;
    self = this;
    url = "/get_collaborators/" + this.planId;
    collaboratorList = [];
    return $.ajax({
      url: url,
      type: "GET",
      dataType: "json",
      success: function(res) {
        var c, element, _i, _len, _results;
        _results = [];
        for (_i = 0, _len = res.length; _i < _len; _i++) {
          element = res[_i];
          c = element.Collaborator;
          _results.push((function(coll) {
            var uid;
            uid = coll.uid;
            collaboratorList.push(coll);
            return $.ajax({
              url: '/user/' + uid,
              type: "GET",
              dataType: "json",
              success: function(res) {
                var furl, uurl;
                coll.user = res.User;
                uurl = '/facebook/' + coll.user.fb_id + '/picture';
                $("<a href='javascript:void(0)'><img src='" + uurl + "' style='width: 120px'></a>").appendTo("#imageList").click(function() {
                  return self.inImage(uurl);
                });
                furl = self.fetchImage(coll.photo_id);
                return $("#imageList").append("<a onclick='drawing.inImage(\"" + furl + "\")' href='javascript:void(0)'><img src='" + furl + "' style='width: 120px'></a>");
              }
            });
          })(c));
        }
        return _results;
      }
    });
  };

  BirthdayArrange.prototype.fetchImage = function(id) {
    var url;
    url = "/img/collabo-photo/" + id + ".png";
    return url;
  };

  BirthdayArrange.prototype.makeUrl = function() {
    return "/plan/" + this.planId + "/photo";
  };

  return BirthdayArrange;

})(CanvasImages);
