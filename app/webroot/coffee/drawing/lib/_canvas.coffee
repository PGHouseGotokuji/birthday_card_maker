class _Canvas
    '''
        this class canvas javascript

        with use drawing class
        (modify schedule as soon as)
    '''
    constructor: (@id = "canvas", flag=false) ->
        '''
            if flag is true -> new canvas (add container)
        '''
        if flag
            # $(".canvas_space").append("<canvas id='#{@id}'></canvas>")
            $("##{id}").css {
                "position": "absolute"
                "top": 50
                "left": 50
            }
        @canvas = $("##{@id}").get(0)

        @canvas.height = 500
        @canvas.width = 500

        # @canvas.height = 500
        @ctx = @canvas.getContext('2d')
        @data = {}

        @setSize("1")
        @setColor("black")

    setCssAbsolute: ->
        $("##{@id}").css {
            top: 100
            left: 100
        }

    clear: ->
        @ctx.clearRect 0, 0, 10000, 10000

    setWidth: ->
        if $(window).width() >= 980
            @canvas.width = 980
        else
            @canvas.width = $(window).width()

    drawCircle: ->
        @ctx.beginPath()
        @ctx.arc(coords.x, coords.y, @data.size, 0, Math.PI * 2.0, true)
        @ctx.stroke()
        @ctx.closePath()

    setColor: (color) ->
        @data.color = color
        @ctx.fillStyle = @data.color
        @ctx.strokeStyle = @data.color


    setSize: (size) ->
        @data.size = size
        @ctx.lineWidth = @data.size

    fillCircle: (coords) ->
        @ctx.beginPath()
        @ctx.arc(coords.x, coords.y, @data.size, 0, Math.PI * 2.0, true)
        @ctx.fill()
        @ctx.closePath()

    fillLine: (coords1, coords2=null) ->
        @ctx.beginPath()
        if not coords2
            if @data.preCoords
                coords2 = @data.preCoords
            else
                coords2 = coords1

        @ctx.moveTo(coords1.x, coords1.y)
        @ctx.lineTo(coords2.x, coords2.y)
        @ctx.stroke()
        @ctx.closePath()
        @data.preCoords = coords1

    clearPreCoords: ->
        delete @data.preCoords 

    save: ->
        # img = new Image()
        type = "image/png"
        # img.src = @canvas.toDataURL(type)
        data = @canvas.toDataURL(type)
        data = data.replace('data:image/png;base64,', '')
        return data

    lineRound: ->
        @ctx.lineJoin = "round"
        @ctx.lineCap = "round"
