// Generated by CoffeeScript 1.3.3
var Connect, debug,
  __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

debug = false;

Connect = (function() {

  function Connect() {
    this.eventSet = __bind(this.eventSet, this);

    this.getSuccess = __bind(this.getSuccess, this);

    var _this = this;
    $(".send-btn").click(function() {
      view["delete"](".owner");
      view["delete"](".card");
      view["delete"](".card-sample");
      return _this.post("/postcard", {});
    });
  }

  Connect.prototype.get = function(url, id) {
    var res;
    if (debug) {
      res = {};
      if (id === "user") {
        res = {
          "User": {
            "id": "1",
            "username": "imausr",
            "fb_picture": "https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-ash2\/368785_100000588696676_2051595305_q.jpg"
          }
        };
      }
      if (id === "plan") {
        res = {
          "Plan": {
            "id": "1",
            "from_id": "1",
            "to_id": "4294967295",
            "username": "satosghun1",
            "fb_picture": "https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-snc4\/49066_100002595002132_583854562_q.jpg",
            "memo": null,
            "created": null,
            "modified": null
          }
        };
      }
      if (id === "collaborators") {
        res = [
          {
            "Collaborator": {
              "id": "1",
              "plan_id": "1",
              "uid": "1",
              "comment": "hogehogehogehoge",
              "memo": null,
              "created": null,
              "modified": null
            }
          }, {
            "Collaborator": {
              "id": "3",
              "plan_id": "1",
              "uid": "3",
              "comment": "fugafugafugafuga",
              "memo": null,
              "created": null,
              "modified": null
            }
          }
        ];
      }
      if (id === "friend") {
        res = {
          "Friend": [
            {
              "name": "Xiaochen Su",
              "id": "316087",
              "fb_picture": "https:\/\/graph.facebook.com\/316087\/picture"
            }, {
              "name": "Takashi Nishimura",
              "id": "1955966",
              "fb_picture": "https:\/\/graph.facebook.com\/1955966\/picture"
            }, {
              "name": "Yuki Shiraji",
              "id": "4203608",
              "fb_picture": "https:\/\/graph.facebook.com\/4203608\/picture"
            }
          ]
        };
      }
      return this.getSuccess(res);
    } else {
      return $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: this.getSuccess
      });
    }
  };

  Connect.prototype.getSuccess = function(res) {
    var friend, friendInfo, selector, _i, _len, _ref,
      _this = this;
    if (debug) {
      console.log("getSuccess");
    }
    if (debug) {
      console.log(res);
    }
    if (res.User) {
      if (debug) {
        console.log("in owner");
      }
      if (debug) {
        console.log(res);
      }
      view.update({
        tplSelector: "#ownerTpl",
        appendSelector: ".content .owner",
        data: res
      });
      return $(".owner-right").bind('click', function() {
        if (debug) {
          console.log("call getFriends");
        }
        return _this.getFriends();
      });
    } else if (res.Plan) {
      if (debug) {
        console.log("in plan");
      }
      console.log(res);
      this.get("/get_collaborators/" + res.Plan.id, "collaborators");
      return view.update({
        tplSelector: "#planTpl",
        appendSelector: '.content .card-left',
        data: res
      });
    } else if (res.collaborators) {
      if (debug) {
        return console.log("in collaborators");
      }
    } else if (res.Friend) {
      view["delete"]('.content .owner');
      view["delete"]('.content .card');
      view["delete"]('.content .card-sample');
      view.update({
        tplSelector: "#selectFriendTpl",
        appendSelector: ".content"
      });
      friendInfo = res.Friend;
      _ref = res.Friend;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        friend = _ref[_i];
        view.update({
          tplSelector: "#friendInfoTpl",
          appendSelector: ".content .select-friend",
          data: friend
        });
      }
      selector = ".content .friend-info";
      return $(selector).click(function() {
        var index;
        index = $(".content .friend-info").index(this);
        view["delete"]('.content .select-friend');
        return connect.post("/insertPlan", friendInfo[index]);
      });
    } else {
      if (debug) {
        console.log("in else");
      }
      if (res.Friend) {
        $(".select-user").bind('click', function() {
          return _this.insertPlan();
        });
        return $('#content').html = this.setHtml(res);
      }
    }
  };

  Connect.prototype.eventSet = function(selector, func) {
    return $(selector).click(func);
  };

  Connect.prototype.getFriends = function() {
    return this.get('/get_friends', "friend");
  };

  Connect.prototype.insertPlan = function(data) {
    return this.post("/insertPlan", {
      to_id: "",
      fb_picture: "",
      username: ""
    });
  };

  Connect.prototype.post = function(url, data) {
    if (debug) {
      return this.postSuccess({
        Success: "true"
      });
    } else {
      return $.ajax({
        url: url,
        type: "POST",
        data: data,
        dataType: "json",
        success: this.postSuccess,
        error: this.postError
      });
    }
  };

  Connect.prototype.postSuccess = function(res) {
    if (res.Success === "true") {
      return view.update({
        tplSelector: "#insertPlanSuccessTpl",
        appendSelector: ".content"
      });
    } else {
      return view.update({
        tplSelector: "#insertPlanErrorTpl",
        appendSelector: ".content"
      });
    }
  };

  Connect.prototype.postError = function(res) {};

  Connect.prototype.sendButtonClick = function() {
    return this.contributor("http://yahoo.co.jp");
  };

  Connect.prototype.contributor = function(url) {
    return location.href = url;
  };

  return Connect;

})();
