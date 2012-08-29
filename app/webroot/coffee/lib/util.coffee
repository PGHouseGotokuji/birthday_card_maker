eventExCoords = (event) ->
    if event.offsetX
        coords = {
            x: event.offsetX
            y: event.offsetY
        }
    else
        coords = {
            x: event.pageX
            y: event.pageY - 85
        }

    return coords
