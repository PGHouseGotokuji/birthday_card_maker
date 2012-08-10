class CollaboratorTable extends DataTable
    getUrl: "/get_collaborators"

    constructor: (saveLocation, @planId) ->
        super saveLocation

        @getUrl += "/#{@planId}"
        @AjaxGet "collaborators"
        

    getSuccess: (res) ->
        @data = res

        @viewParams = [
            {
                tplSelector: "#memberTpl"
                appendSelector: ".content"
                method: "appendTo"
                data: @data
            }
        ]

        @viewUpdate()
