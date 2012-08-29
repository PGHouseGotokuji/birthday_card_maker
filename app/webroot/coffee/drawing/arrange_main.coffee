$ ->
    window.drawing = new BirthdayArrange()
    console.log drawing
    drawing.setCssAbsolute()
    drawing.setImages()

    $("#add").bind "click", =>
        drawing.inImage("static/img/test.png")
    
    # $('#save').bind "click", =>
    #     drawing.upload()
