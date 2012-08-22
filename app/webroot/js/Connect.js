// Generated by CoffeeScript 1.3.3
var Connect,
  __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

Connect = (function() {

  function Connect() {
    this.postSuccess = __bind(this.postSuccess, this);

    this.eventSet = __bind(this.eventSet, this);

    this.getSuccess = __bind(this.getSuccess, this);
    this.data = {};
  }

  Connect.prototype.get = function(url, id) {
    var res;
    if (debug.flag) {
      res = debug.getData(id);
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

  Connect.prototype.getFriends = function() {
    return this.get('/get_friends', "friend");
  };

  Connect.prototype.getSuccess = function(res) {
    var friend, friendInfo, selector, _i, _len, _ref,
      _this = this;
    if (debug.flag) {
      console.log("getSuccess");
    }
    if (debug.flag) {
      console.log(res);
    }
    if (debug.alertflag) {
      alert("getSuccess");
    }
    if (res.User) {
      this.data.user = res.User;
      if (debug.alertflag) {
        alert("User processing");
      }
      if (debug.flag) {
        console.log("in owner");
      }
      if (debug.flag) {
        console.log(res);
      }
      view.update({
        tplSelector: "#ownerTpl",
        appendSelector: ".main_content",
        method: "prependTo",
        data: res
      });
      return $(".owner-right").bind('click', function() {
        if (debug.flag) {
          console.log("call getFriends");
        }
        return _this.getFriends();
      });
    } else if (res.Plan) {
      this.data.plan = res.Plan;
      if (debug.alertflag) {
        alert("Plan processing");
      }
      if (debug.flag) {
        console.log("in plan");
      }
      console.log(res);
      this.get("/get_collaborators/" + res.Plan.id, "collaborators");
      view.update({
        tplSelector: "#cardTpl",
        appendSelector: '.content',
        method: "appendTo"
      });
      $(".send-btn").click(function() {
        return location.href = "/plan/" + _this.data.plan.id + "/collaborator/confirm";
      });
      return view.update({
        tplSelector: "#planTpl",
        appendSelector: '.main_content .card-left',
        method: "appendTo",
        data: res
      });
    } else if (res.Friend) {
      view["delete"]('.main_content .owner');
      view["delete"]('.main_content .collaboratorPeople');
      view["delete"]('.main_content .card');
      view["delete"]('.main_content .card-sample');
      view.update({
        tplSelector: "#selectFriendTpl",
        appendSelector: ".main_content"
      });
      friendInfo = res.Friend;
      _ref = res.Friend;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        friend = _ref[_i];
        view.update({
          tplSelector: "#friendInfoTpl",
          appendSelector: ".main_content .select-friend",
          data: friend
        });
      }
      selector = ".main_content .friend-info";
      return $(selector).click(function() {
        var index;
        index = $(".main_content .friend-info").index(this);
        view["delete"]('.main_content .select-friend');
        alert("insert_plan index");
        alert(index);
        if (debug.alertflag) {
          alert(friendInfo[index]["id"]);
        }
        return connect.post("/insert_plan", friendInfo[index], connect.postSuccess);
      });
    } else if (res[0].Collaborator) {
      if (debug) {
        console.log("in collaborators");
      }
      return view.update({
        tplSelector: "#memberTpl",
        appendSelector: ".main_content",
        method: "appendTo",
        data: res
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

  Connect.prototype.post = function(url, data, func) {
    if (debug) {
      return func({
        Success: "true"
      });
    } else {
      return $.ajax({
        url: url,
        type: "POST",
        data: data,
        dataType: "json",
        success: func,
        error: this.postError
      });
    }
  };

  Connect.prototype.postSuccess = function(res) {
    var _this = this;
    if (res.Success === "true") {
      this.post("/post_fb_timeline", {}, function() {
        return alert("成功");
      });
      view.update({
        tplSelector: "#insertPlanSuccessTpl",
        appendSelector: ".content"
      });
      connect.get("/get_user", "user");
      return connect.get("/get_plan", "plan");
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
