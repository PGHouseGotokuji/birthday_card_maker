class ImageComponent
    constructor: (src, type) ->
        @img = new Image()
        @img.src = src
        @coords = {
            top: 0
            left: 0
        }
        @size = {
            width: 100
            height: 100
        }
        @focusSize = {
            width: 20
            height: 20
        }

        @flag = {
            focus: false
        }

        if type is "touch"
            @inImage = @touchInImage
            @onImage = @touchOnImage
            @outImage = @touchoutImage
        else if type is "mouse"
            @inImage = @mouseInImage
            @onImage = @mouseOnImage
            @outImage = @mouseoutImage

    rangeImageInCheck: (point, coords = @coords, size = @size) ->
#      console.log('point.x:' + point.x)
#      console.log('point.y:' + point.y)
#      console.log('coords.x:' + coords.left)
#      console.log('coords.y:' + coords.top)
#      console.log('size.width:' + size.width)
#      console.log('size.height:' + size.height);

      if coords.left <= point.x <= coords.left + size.width
            if coords.top <= point.y <= coords.top + size.height
                return true
        return false

    rangeFocusInCheck: (point) ->
        focusPoints = @generateFocusLeftTop()

        for focusPoint, index in focusPoints
            if @rangeImageInCheck(point, focusPoint, @focusSize)
                return index

        return false

    generateFocusPoints: ->
        '''
            左上から時計回り
        '''
        return [
            {top: @getTop(), left: @getLeft()}
            {top: @getTop(), left: (@getLeft() + @getRight()) / 2}
            {top: @getTop(), left: @getRight()}
            {top: (@getTop() + @getBottom()) / 2, left: @getRight()}
            {top: @getBottom(), left: @getRight()}
            {top: @getBottom(), left: (@getLeft() + @getRight()) / 2}
            {top: @getBottom(), left: @getLeft()}
            {top: (@getTop() + @getBottom()) / 2, left: @getLeft()}
        ]

    generateFocusLeftTop: ->
        list = @generateFocusPoints()

        retVal = []
        for element in list
            retVal.push {top: element.top - @focusSize.height / 2, left: element.left - @focusSize.width / 2}
        #console.log retVal
        return retVal

    getImage: ->
        return @img

    getCoords: ->
        return @coords

    addCoords: (diff) ->
        @coords.left += diff.x
        @coords.top += diff.y

    addSize: (diff) ->
        console.log "diff"
        console.log diff

        @size.width += diff.width
        @size.height += diff.height

    getSize: ->
        return @size

    getLeft: ->
        return @coords.left

    getTop: ->
        return @coords.top

    getRight: ->
        return @coords.left + @size.width

    getBottom: ->
        return @coords.top + @size.height

    getWidth: ->
        return @size.width

    getHeight: ->
        return @size.height

    clearfocus: ->
        @flag.focus = false

    getfocus: ->
        return @flag.focus

    focus: ->
        @flag.focus = true

    draw: (ctx)->
      ctx.drawImage(@img, 0, 0, @img.width, @img.height, @coords.left, @coords.top, @size.width, @size.height)

