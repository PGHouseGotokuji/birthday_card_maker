class TextComponent
  constructor:(@text, @font="bold 16px sans-serif", @color="rgb(255,255,255)")->

  rangeImageInCheck: (point, coords = @coords, size = @size) ->
    return false

  rangeFocusInCheck: (point) ->
    return false;

  getfocus: ->
    return false;

  getWidth: (ctx)->
    ctx.font = @font
    return ctx.measureText(@text).width

  draw: (ctx)->
    ctx.font = @font;
    tmpFill = ctx.fillStyle
    ctx.fillStyle = @color
    ctx.fillText(@text, @left, @top);
    ctx.fillStyle = tmpFill

