class BirthDrawing extends Drawing
    constructor: (canvas, planId)->
      super(canvas)
      @planId = planId

    makeUrl: ->
        collaboratorId = ""

        $.ajax {
            url: "/get_user"
            method: "GET"
            async: false
            dataType: "json"
            success: (res) =>
                collaboratorId = res.User.id 
        }

        return "/plan/" + @planId + "/collaborator/" + collaboratorId + "/photo"
