require [
    "vendor/jquery"
    "debug"
    "View"
    "DataTable"
    "lib/underscore-min"
    ], ->
        require [
          "UserTable"
          "PlanTable"
          "FriendsTable"
          "CollaboratorTable"
        ], ->

          window.dataTable = {}

          dataTable.userTable = new UserTable dataTable
          dataTable.planTable = new PlanTable dataTable

          setEvent()


setEvent = ->
  $(".create_image").click (res) ->
    view.delete '.main_contents > *'
    viewParam = {
      tplSelector: "#canvasTpl"
      # appendSelector: ".content"
      appendSelector: ".main_contents"
    }
    
    view.update viewParam

    window.drawing = new BirthdayArrange("canvas", flag=true)
    window.bg_drawing = new BirthdayArrange("bg_canvas", flag=true, event=false)

    # drawing.setCssAbsolute()
    drawing.setImages()
    bg_drawing.setBackgroundImages()

    $("#add").bind "click", =>
      drawing.inImage("static/img/test.png")

