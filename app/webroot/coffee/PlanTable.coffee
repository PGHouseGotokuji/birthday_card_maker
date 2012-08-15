class PlanTable extends DataTable
    getUrl: "/get_plan"

    constructor: (saveLocation) ->
        super saveLocation
        @AjaxGet "plan"

    getSuccess: (res) ->
        @data = res
        @viewParams = [
            {
                tplSelector: "#cardTpl"
                appendSelector: '.content'
                method: "appendTo"
            }
            {
                tplSelector: "#planTpl"
                appendSelector: '.content .card-left'
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
        $(".send-btn").click =>
            console.log @data.Plan
            location.href = "/plan/#{@data.Plan.id}/post/confirm"
            #location.href = "/plan/#{@data.Plan.id}/collaborator/confirm"

        
