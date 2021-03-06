// Generated by CoffeeScript 1.3.3
var DataTable;

DataTable = (function() {
  'basic table';

  function DataTable(saveLocation) {
    this.saveLocation = saveLocation;
  }

  DataTable.prototype.getData = function() {
    ' abstract ';
    return this.data;
  };

  DataTable.prototype.viewUpdate = function() {
    var param, _i, _len, _ref;
    _ref = this.viewParams;
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      param = _ref[_i];
      console.log(param);
      view.update(param);
    }
    return this.setEvents();
  };

  DataTable.prototype.setEvents = function() {};

  DataTable.prototype.AjaxGet = function(id) {
    var res, self;
    self = this;
    if (debug.flag) {
      res = debug.getData(id);
      return this.getSuccess(res);
    } else {
      return $.ajax({
        type: "GET",
        url: this.getUrl,
        dataType: "json",
        success: function(data, type) {
          return self.getSuccess(data, type);
        }
      });
    }
  };

  DataTable.prototype.getSuccess = function() {
    return "abstract";
  };

  DataTable.prototype.AjaxPost = function(data, url, func) {
    if (url == null) {
      url = this.postUrl;
    }
    if (func == null) {
      func = this.postSuccess;
    }
    if (debug.flag) {
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

  DataTable.prototype.postSuccess = function() {
    return "abstract";
  };

  DataTable.prototype.postError = function() {
    return "abstract class";
  };

  return DataTable;

})();
