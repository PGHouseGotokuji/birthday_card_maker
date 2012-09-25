
LOADING_ELEMENT='<div class="loading"><img src="/img/loading.gif"/></div>';
putLoading = (target)->
  $elm = $(LOADING_ELEMENT).appendTo(target);
  rem = ()->
    $elm.remove();
  return rem;

class PlanTable extends DataTable
    getUrl: "/get_plans"

    constructor: (saveLocation) ->
        super saveLocation
        @AjaxGet "plan"

    getSuccess: (res) ->
        @data = {"Plans": res}
        console.log "data"
        console.log @data

        # ビュー作成の論理が違うようなので、 @viewUpdate()を利用しない実装に差し替え。
        # 後で要調整

#        @viewParams = [
#            {
#                tplSelector: "#cardTpl"
#                # appendSelector: '.content'
#                appendSelector: '.main_contents'
#                method: "appendTo"
#            }
#            {
#                tplSelector: "#planTpl"
#                # appendSelector: '.content .card-left'
#                appendSelector: '.main_contents .card-left'
#                method:"appendTo"
#                data: @data
#            }
#        ]
#
#        alert "processing" if debug.alertflag
#
#        console.log "in owner" if debug.flag
#        console.log res if debug.flag
#
#        @viewUpdate()

        #@saveLocation.collaborators = new CollaboratorTable(@saveLocation)


        $plan = $(_.template($('#planTpl').html(), {})).appendTo('.main_contents');
        planItemTpl = _.template($('#planItemTpl').html());
        _.each(res, (plan)->
          $planItem = $(planItemTpl(plan)).appendTo($plan);

          $planItem.find('.send-btn button').click ->
            location.href = "/plan/#{plan.Plan.id}/post/confirm_friend_fb_timeline"

          $planItem.find('.create_image button').click ->
            location.href = "/arrange/#{plan.Plan.id}"

          $planItem.find('.deleteButton').click ->
            planId = plan.Plan.id

            if !confirm('カード作成プランを削除します。削除すると戻せませんが、よろしいですか？')
              return

            rem = putLoading('ul.plans');
            $.ajax {
            type: "POST"
            url: '/plan/' + planId + '/delete'
            dataType: "json"
            success: (data, type)->
              rem();
              location.href='/mypage';

            error: (xhr, status, err)->
              alert('削除失敗' + status + '/' + err)
              rem()
            }
        );

    setEvents: ->
