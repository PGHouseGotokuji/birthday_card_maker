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
        @saveLocation.collaborators = new CollaboratorTable(@saveLocation)
    
    setEvents: ->
        self = @


        $(".send-btn").click ->
          index = $(".send-btn").index(@)
          plan =  self.data.Plans[index]
          location.href = "/plan/#{plan.Plan.id}/post/confirm"

        $(".create_image button").click ->
          index = $(".create_image button").index(@)
          plan =  self.data.Plans[index]
          location.href = "/arrange/#{plan.Plan.id}"

