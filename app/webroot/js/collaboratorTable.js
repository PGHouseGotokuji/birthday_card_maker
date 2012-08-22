// Generated by CoffeeScript 1.3.3
var CollaboratorTable,
  __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

CollaboratorTable = (function(_super) {

  __extends(CollaboratorTable, _super);

  CollaboratorTable.prototype.getUrl = "/get_collaborators";

  function CollaboratorTable(saveLocation, planId) {
    this.planId = planId;
    CollaboratorTable.__super__.constructor.call(this, saveLocation);
    this.getUrl += "/" + this.planId;
    this.AjaxGet("collaborators");
  }

  CollaboratorTable.prototype.getSuccess = function(res) {
    this.data = res;
    this.viewParams = [
      {
        tplSelector: "#memberTpl",
        appendSelector: ".main_contents",
        method: "appendTo",
        data: this.data
      }
    ];
    return this.viewUpdate();
  };

  return CollaboratorTable;

})(DataTable);
