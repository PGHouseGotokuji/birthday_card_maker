class Component
    constructor: (src, type)->
        if type is "img"
            @data = {
                top: 0
                left: 0
                width: 100
                height: 100
                priority: 1
            }

    incrementPriority: ->
        @data.priority += 1

    setEvents: (selector) ->
        that = @
        for key, func of @events
            $(selector).bind key, func

    touchstart: =>
        @clicking = true
        console.log event

    touchmove: =>

    touchend: =>
        @clicking = false

    mousedown: =>
        @clicking = true
        @x = event.pageX
        @y = event.pageY

    mousemove: =>
        if @clicking
            id = event.target.id

            $("##{id}").css {
                "top":  "+=#{event.pageY - @y}"
                "left": "+=#{event.pageX - @x}"
            }

            @x = event.pageX
            @y = event.pageY

    mouseout: =>
        @mouseup()

    mouseup: =>
        @clicking = false




