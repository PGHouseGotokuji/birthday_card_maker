deletePlan = (planId)->
  if !confirm('カード作成プランを削除します。削除すると戻せませんが、よろしいですか？')
    return

  rem = putLoading('ul.plans');
  $.ajax {
  type: "POST"
  url: '/plan/' + planId + '/delete'
  dataType: "json"
  success: (data, type)->
    alert('削除成功しました')
    #この後UIから削除
    rem();
  error: (xhr, status, err)->
    alert('削除失敗' + status + '/' + err)
    rem()
  }

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

        @viewParams = [
            {
                tplSelector: "#cardTpl"
                # appendSelector: '.content'
                appendSelector: '.main_contents'
                method: "appendTo"
            }
            {
                tplSelector: "#planTpl"
                # appendSelector: '.content .card-left'
                appendSelector: '.main_contents .card-left'
                method:"appendTo"
                data: @data
            }
        ]

        alert "processing" if debug.alertflag

        console.log "in owner" if debug.flag
        console.log res if debug.flag

        @viewUpdate()
        #@saveLocation.collaborators = new CollaboratorTable(@saveLocation)
    
    setEvents: ->
        self = @


        $(".send-btn").click ->
          index = $(".send-btn").index(@)
          plan =  self.data.Plans[index]
          location.href = "/plan/#{plan.Plan.id}/post/confirm_friend_fb_timeline"

        $(".create_image button").click ->
          index = $(".create_image button").index(@)
          plan =  self.data.Plans[index]
          location.href = "/arrange/#{plan.Plan.id}"

