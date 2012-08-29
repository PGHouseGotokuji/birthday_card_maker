$ ->
    canvas = new CanvasImages()
    canvas.setCssAbsolute()

    $("#add").bind "click", =>
        canvas.inImage("static/img/test.png")
    
    $('#save').bind "click", =>
        canvas.savePng()
        