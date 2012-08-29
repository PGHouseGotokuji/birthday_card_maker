class BirthDrawing extends Drawing
    makeUrl: ->
        planId = ""
        collaboratorId = ""


        $.ajax {
            url: "/get_plan"
            method: "GET"
            dataType: "json"
            async: false
            success: (res) =>
                planId = res.Plan.id 
        }

        $.ajax {
            url: "/get_user"
            method: "GET"
            dataType: "json"
            async: false
            success: (res) =>
                collaboratorId = res.User.id 
        }

        return "/plan/" + planId + "/collaborator/" + collaboratorId + "/photo"
    
