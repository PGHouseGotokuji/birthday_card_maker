class GouseiComponent extends ImageComponent
    @index: 0
    @defaultParams: {
        width  : 300
        height : 300
        img: [
            {
                size: {width  : 300, height : 300}
                coords: {x: 0, y: 0}
                priority: 1
            }
            {
                size: {width  : 300, height : 300}
                coords: {x: 100, y: 100}
                priority: 20
            }
        ]
    }

    constructor: (srcList, @params=GouseiComponent.defaultParams) ->
        @coords    = {top : 0,  left : 0}
        @size      = {width  : 100,  height : 100}
        @focusSize = {width  : 20, height : 20}

        @flag = {
            focus: false
        }
        @img    = new Image()
        @canvas = new _Canvas("canvas_#{GouseiComponent.index}", true, params)
        GouseiComponent.index += 1
        @generate_image(srcList)

    generate_image: (srcList) ->
        $.when(@onload(srcList[0]), @onload(srcList[1])).then (img1, img2) =>
            @canvas.drawImage(img1, @params.img[0].coords)
            @canvas.drawImage(img2, @params.img[1].coords)
            @img.src = @canvas.getImageData()
            console.log @img

    onload: (src) =>
        dfd     = $.Deferred()
        img     = new Image()
        img.src = src
        img.onload = =>
            dfd.resolve(img)
        dfd.promise()

