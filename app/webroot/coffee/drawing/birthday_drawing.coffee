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

    create_tools: ->
      colorList = ["red", "pink", "yellow", "black", "white"]
      colorTmpl = "<li><a href='#' onclick='drawing.setColor(\"%s\");return false'>%s</a></li>"
      text = ""
      for ele in colorList
        text += colorTmpl.replace(/%s/g, ele);
      $("#drawing_color").append(text);

      boldList = {"細い": 1, "普通": 2, "太い": 3, "超太い": 10}
      boldTmpl = "<li><a href='#' onclick='drawing.setBold(%d);return false'>%s</a></li>"

      text = ""
      for key, value of boldList
        tmp = boldTmpl.replace("%d", value);
        text += tmp.replace("%s", key);
      $("#drawing_bold").append(text);
