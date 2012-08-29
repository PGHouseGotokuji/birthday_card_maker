class BirthDrawing extends Drawing
    makeUrl: ->
        planId = ""
        collaboratorId = ""

        $.ajax {
            url: "/get_plan"
            method: "GET"
            async: false
            dataType: "json"
            success: (res) =>
                planId = res.Plan.id 
        }

        $.ajax {
            url: "/get_user"
            method: "GET"
            async: false
            dataType: "json"
            success: (res) =>
                collaboratorId = res.User.id 
        }

        return "/plan/" + planId + "/collaborator/" + collaboratorId + "/photo"
