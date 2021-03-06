class Drawing
    constructor: (@id) ->
        @canvas = new _Canvas(@id)
        @canvas.lineRound()
        @addEvents()

    addEvents: ->
        isTouch = document.documentElement.ontouchstart isnt undefined
        if isTouch
            @addTouchEvent "touchstart", @drawTouchStart
            @addTouchEvent "touchmove", @drawTouchMove
            @addTouchEvent "touchend", @drawTouchEnd
        else
            @addEvent "mousedown", @drawStart
            @addEvent "mousemove", @drawMove
            @addEvent "mouseup", @drawEnd


    addEvent: (type, func) =>
        $("##{@id}").bind type, (e) =>
            func(e)

    addTouchEvent: (type, func) =>
        target = document.getElementById("#{@id}")
        target.addEventListener(type, (e) =>
            e.preventDefault()
            func(e.touches[0])
        , false)

    drawTouchMove: (e) =>
        coords = offsetOn($("#{@id}"), e)
        @canvas.fillLine(coords)

    drawTouchStart: (e) =>
        @clicking = true
        coords = offsetOn($("#{@id}"), e)
        @canvas.fillLine(coords)

    drawTouchEnd: (e) =>
        @canvas.clearPreCoords()
        @clicking = false

    drawStart: (e) =>
        @clicking = true
        coords = {x: e.offsetX, y: e.offsetY}
        @canvas.fillLine(coords)

    drawMove: (e) =>
        coords = {x: e.offsetX, y: e.offsetY}
        if @clicking
            @canvas.fillLine(coords)

    drawEnd: (e) =>
        @canvas.clearPreCoords()
        @clicking = false

    setColor: (color) ->
        @canvas.setColor(color)

    setBold: (thickness) ->
        @canvas.setSize(thickness)

    getImageData: ->
        @canvas.save()

    upload: ->
        url = @makeUrl()
        saveData = @getImageData()
        saveData = saveData.replace('data:image/png;base64,', '')
        
        $.ajax {
            url: url
            type: "POST"
            data: {
                "img_file": saveData
            }
            success: (res) ->
                if (res)
                    location.href = "/mypage"
                else
                    location.href = "/"
        }

    makeUrl: ->
        ''' abstract '''
        return ""

    clear: ->
        @canvas.clear()
