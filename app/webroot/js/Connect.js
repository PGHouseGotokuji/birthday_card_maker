// Generated by CoffeeScript 1.3.3
var Connect, debug;

debug = true;

Connect = (function() {

<<<<<<< HEAD
  function Connect() {}
=======
  function Connect() {
//    this.data = new ClassList();
  }
>>>>>>> 42f46ece7ed55622821f33f1c9e37592d992d0c6

  Connect.prototype.get = function(url) {
    var res;
    if (debug) {
      res = {
        "User": {
          "id": "1",
          "username": "imausr",
          "fb_picture": "https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-ash2\/368785_100000588696676_2051595305_q.jpg"
        }
      };
      ({
        "Plan": {
          "id": "1",
          "from_id": "1",
          "to_id": null,
          "memo": null,
          "created": null,
          "modified": null
        }
      });
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
    var _this = this;
    if (debugs) {
      console.log("getSuccess");
    }
    if (debug) {
      console.log(res);
    }
    if (res.Owner) {
      if (debug) {
        console.log("in owner");
      }
      if (debug) {
        console.log(res);
      }
      $("." + (owner - right)).bind('click', function() {
        if (debug) {
          console.log("call getFriends");
        }
        return _this.getFriends();
      });
      return view.update({
        tplSelector: "#ownerTpl",
        appendSelector: ".content .owner",
        data: res
      });
    } else if (res.Plan) {
      if (debug) {
        console.log("in plan");
      }
      this.get("/get_collaborators");
      return view.updatePlan(res);
    } else if (res.collaborators) {
      if (debug) {
        console.log("in collaborators");
      }
      return view.updateCollaborators(res);
    } else {
      if (debug) {
        console.log("in collaborators");
      }
      if (res.Friend) {
        $("." + (select - user)).bind('click', function() {
          return _this.insertPlan();
        });
        return $('#content').html = this.setHtml(res);
      }
    }
  };

  Connect.prototype.eventSet = function(res) {};

  Connect.prototype.setHtml = function(res) {
    var key, text, value;
    text = '<div class="select-friend">';
    for (key in res) {
      value = res[key];
      text += "<ul>";
      text += "<li class='friend-img'>";
      text += "<img src=" + value.fb_profile + ">";
      text += "</li>";
      text += "<li class='friend-name'>";
      text += "" + value.username;
      text += "</li>";
      text += "</ul>";
    }
    text += '</div>';
    return text;
  };

  Connect.prototype.getFriends = function() {
    return this.get('/get_friends');
  };

  Connect.prototype.insertPlan = function() {
    return this.post("/insertPlan", {
      to_id: "",
      fb_picture: "",
      username: ""
    });
  };

  Connect.prototype.post = function(url, data) {
    return $.ajax({
      url: url,
      type: "POST",
      data: data,
      dataType: "json",
      success: this.postSuccess,
      error: this.postError
    });
  };

  Connect.prototype.postSuccess = function(res) {};

  Connect.prototype.postError = function(res) {};

  Connect.prototype.sendButtonClick = function() {
    return this.contributor("http://yahoo.co.jp");
  };

  Connect.prototype.contributor = function(url) {
    return location.href = url;
  };

  return Connect;

})();
