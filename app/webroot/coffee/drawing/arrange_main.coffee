$ ->
    url = location.href
    planid = url.substr(url.lastIndexOf('/') + 1)

    window.drawing = new BirthdayArrange(planid)
    console.log drawing
    drawing.setCssAbsolute()
    drawing.setImages()

    $("#add").bind "click", =>
        drawing.inImage("static/img/test.png")
    
    # $('#save').bind "click", =>
    #     drawing.upload()
