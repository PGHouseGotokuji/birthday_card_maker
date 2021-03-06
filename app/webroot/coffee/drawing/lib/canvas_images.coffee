class CanvasImages extends _Canvas
    cursors = [
        "nw-resize"
        "n-resize"
        "ne-resize"
        "e-resize"
        "se-resize"
        "s-resize"
        "sw-resize"
        "w-resize"
        "default"
    ]

    constructor: (@id = "canvas", flag=false, event=true) ->
        super(@id, flag)
        @imageList = []
        @touchComponent = null
        @flag = {
            focusClicking: false
            clicking: false
            focus: false
            focusCursor: false
        }

        if event
            isTouch = document.documentElement.ontouchstart isnt undefined
            if isTouch
                @setTouchEvent "touchstart", @touchstart
                @setTouchEvent "touchmove", @touchmove
                @setTouchEvent "touchend", @touchend
            else
                @events = {"mousedown": @mousedown, "mousemove": @mousemove, "mouseup": @mouseup, "mouseout": @mouseout }
                @setEvents()

    setTouchEvent: (type, func) ->
        $("##{@id}").on type, =>
            # func(event.targetTouches[0])
            func(event.targetTouches[0], event)

    setEvents: ->
        for key, func of @events
            $("##{@id}").on key, func

    touchstart: (e, ecore) =>
        @mousedown(e, ecore)

    touchmove: (e, ecore) =>
        @mousemove(e, ecore)

    touchend: (e, ecore) =>
        @mouseup(e, ecore)

    mousedown: (event, ecore = event) =>
        '''
            touch is image field or not
        '''
        for component ,index in @imageList

#            coords = eventExCoords(event)
#            console.log('event.pageX' + event.pageX);
#            console.log('event.pageY' + event.pageY);
#            console.log('event.offsetX' + event.offsetX);
#            console.log('event.offsetY' + event.offsetY);
#            console.log('offset.left' + $("##{@id}").offset().left);
#            console.log('offset.top' + $("##{@id}").offset().top);

            coords = offsetOn($("##{@id}"), event)

            i = component.rangeFocusInCheck(coords)
#            console.dir(i);
            if i isnt false
                @flag.focusClicking = true

                @touchComponent = {
                    x: event.pageX
                    y: event.pageY
                    index: index
                    focusPoint: i
                }

#                console.log('focused');
                component.focus()
                ecore.preventDefault()
                break

            else if component.rangeImageInCheck(coords)
                @flag.clicking = true

                @touchComponent = {
                    x: event.pageX
                    y: event.pageY
                    index: index
                }
                component.focus()
                ecore.preventDefault()
                break

        if @touchComponent is null
            @reDraw()


    mousemove: (event, ecore = event) =>
        if @flag.clicking
          ecore.preventDefault()
          @rangeInAction(event)

        else if @flag.focusClicking
            ecore.preventDefault()
            coords = offsetOn($("##{@id}"), event)
            component = @imageList[@touchComponent.index]
            @rangeFocusInAction(event)

        else if @flag.focus
            #ecore.preventDefault()
            component = @imageList[@touchComponent.index]
            coords = offsetOn($("##{@id}"), event)
            index = component.rangeFocusInCheck(coords)
            if index isnt false
                @focusCursorSet(index)
            else
                @focusCursorSet(cursors.length - 1)

    focusCursorSet: (index) ->
        $("body").css {
            cursor: cursors[index]
        }

    mouseout: (event, ecore = event) =>
        @mouseup()

    mouseup: (event, ecore = event) =>
        @mouseupRoutine()

    mouseupRoutine: ->
        if @flag.clicking
            @flag.clicking = false
            @flag.focus = true

        if @flag.focusClicking
            @flag.focusClicking = false

    rangeInAction: (event) ->
        index = @touchComponent.index

        coords = {
            x: event.pageX - @touchComponent.x
            y: event.pageY - @touchComponent.y
        }

        @imageList[index].addCoords(coords)
        @touchComponent.x = event.pageX
        @touchComponent.y = event.pageY
        @reDraw()


    rangeFocusInAction: (event) ->
        focusPoint = @touchComponent.focusPoint
        index = @touchComponent.index
        changex = event.pageX - @touchComponent.x
        changey = event.pageY - @touchComponent.y
        size = {width: 0, height: 0}
        coords = {x: 0, y: 0}

        # x
        if focusPoint in [0, 6, 7]
            size.width = -changex
            coords.x = changex
        else if focusPoint in [2, 3, 4]
            size.width = changex

        # y
        if focusPoint in [0, 1, 2]
            size.height = -changey
            coords.y = changey
        else if focusPoint in [4, 5, 6]
            size.height = changey

        @imageList[index].addSize(size)
        @imageList[index].addCoords(coords)

        @touchComponent.x = event.pageX
        @touchComponent.y = event.pageY

        @reDraw()

    inImage: (src) ->
        imgComponent = new ImageComponent(src)
        @pushImage(imgComponent)

    gouseiImage: (srcList) ->
        srcList = ['/img/hukidashi.jpeg', '/img/ken.jpg']
        gouseiComponent = new GouseiComponent(srcList)
        @pushImage(gouseiComponent)

    pushImage: (imgComponent) ->
      self = this;
      img  = imgComponent.getImage()

      @imageList.push imgComponent

      if img.complete
        #self.pushComponent(imgComponent)
        self.reDraw()
      else
        img.onload = =>
          #self.pushComponent(imgComponent)
          self.reDraw()

    pushComponent: (cmp)->
        @componentDraw(cmp)
        @imageList.push cmp

    reDraw: (withDot = true)->
        @clear()
        for component in @imageList
            @componentDraw(component)
            if component.getfocus()
                if withDot
                  @componentFocusDraw(component)


    componentFocusDraw: (component) ->
        @ctx.lineWidth = 5
        # @ctx.strokeRect(component.getLeft(), component.getTop(), component.getWidth(), component.getHeight())
        @focusMousePoint(component)

    focusMousePoint: (component) ->
        size = 5
        coords = {
            top: component.getTop()
            left: component.getLeft()
            bottom: component.getBottom()
            right: component.getRight()
        }

        @ctx.beginPath()
        @ctx.arc(coords.left, coords.top, size, 0, Math.PI*2, false)
        @ctx.fill()
        @ctx.beginPath()
        @ctx.arc( (coords.left + coords.right) / 2, coords.top, size, 0, Math.PI*2, false)
        @ctx.fill()
        @ctx.beginPath()
        @ctx.arc(coords.right, coords.top, size, 0, Math.PI*2, false)
        @ctx.fill()
        @ctx.beginPath()
        @ctx.arc(coords.right, (coords.top + coords.bottom)/2, size, 0, Math.PI*2, false)
        @ctx.fill()
        @ctx.beginPath()
        @ctx.arc(coords.right, coords.bottom, size, 0, Math.PI*2, false)
        @ctx.fill()
        @ctx.beginPath()
        @ctx.arc( (coords.left + coords.right) / 2, coords.bottom, size, 0, Math.PI*2, false)
        @ctx.fill()
        @ctx.beginPath()
        @ctx.arc(coords.left, coords.bottom, size, 0, Math.PI*2, false)
        @ctx.fill()
        @ctx.beginPath()
        @ctx.arc(coords.left, (coords.top + coords.bottom) / 2, size, 0, Math.PI*2, false)
        @ctx.fill()

    _drawFocusMousePoint: (coords) ->

    componentDraw: (component) ->
        component.draw(@ctx)

    getImageData: ->
        @canvas.save()
