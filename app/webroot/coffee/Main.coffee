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

    window.drawing = new BirthdayArrange()
    console.log drawing
    drawing.setCssAbsolute()
    drawing.setImages()

    $("#add").bind "click", =>
      drawing.inImage("static/img/test.png")

