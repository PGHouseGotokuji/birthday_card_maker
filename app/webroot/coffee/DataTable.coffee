class DataTable
    '''
        basic table
    '''

    constructor: (@saveLocation) ->
    
    getData: ->
        ''' abstract '''
        return @data

    viewUpdate: ->
        for param in @viewParams
            console.log param
            view.update param

        @setEvents()

    setEvents: ->


    AjaxGet: (id) ->
        if debug.flag
            res = debug.getData id
            @getSuccess res
        else
            $.ajax {
                type: "GET"
                url: @getUrl
                dataType: "json"
                success: @getSuccess
            }

    getSuccess: ->
        return "abstract"

    AjaxPost: (data, url=@postUrl, func=@postSuccess) ->
        if debug.flag
            func {
                Success: "true"
            }
        else
            $.ajax {
                url: url
                type: "POST"
                data: data
                dataType: "json"
                success: func
                error: @postError
            }

    postSuccess: ->
        return "abstract"

    postError: ->
        return "abstract class"

