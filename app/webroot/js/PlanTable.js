// Generated by CoffeeScript 1.3.3
var LOADING_ELEMENT, PlanTable, putLoading,
  __hasProp = {}.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

LOADING_ELEMENT = '<div class="loading"><img src="/img/loading.gif"/></div>';

putLoading = function(target) {
  var $elm, rem;
  $elm = $(LOADING_ELEMENT).appendTo(target);
  rem = function() {
    return $elm.remove();
  };
  return rem;
};

PlanTable = (function(_super) {

  __extends(PlanTable, _super);

  PlanTable.prototype.getUrl = "/get_plans";

  function PlanTable(saveLocation) {
    PlanTable.__super__.constructor.call(this, saveLocation);
    this.AjaxGet("plan");
  }

  PlanTable.prototype.getSuccess = function(res) {
    var $plan, planItemTpl;
    this.data = {
      "Plans": res
    };
    console.log("data");
    console.log(this.data);
    $plan = $(_.template($('#planTpl').html(), {})).appendTo('.main_contents');
    planItemTpl = _.template($('#planItemTpl').html());
    return _.each(res, function(plan) {
      var $planItem;
      $planItem = $(planItemTpl(plan)).appendTo($plan);
      $planItem.find('.send-btn button').click(function() {
        return location.href = "/plan/" + plan.Plan.id + "/post/confirm_friend_fb_timeline";
      });
      $planItem.find('.create_image button').click(function() {
        return location.href = "/arrange/" + plan.Plan.id;
      });
      return $planItem.find('.deleteButton').click(function() {
        var planId, rem;
        planId = plan.Plan.id;
        if (!confirm('カード作成プランを削除します。削除すると戻せませんが、よろしいですか？')) {
          return;
        }
        rem = putLoading($planItem);
        return $.ajax({
          type: "POST",
          url: '/plan/' + planId + '/delete',
          dataType: "json",
          success: function(data, type) {
            rem();
            return location.href = '/mypage';
          },
          error: function(xhr, status, err) {
            alert('削除失敗' + status + '/' + err);
            return rem();
          }
        });
      });
    });
  };

  PlanTable.prototype.setEvents = function() {};

  return PlanTable;

})(DataTable);
