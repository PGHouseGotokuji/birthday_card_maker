class BirthdayArrange extends CanvasImages

    constructor: (planId)->
        super()
        @planId = planId



    upload: ->
        url = @makeUrl()
        @setBackgroundImages().then =>
            saveData = @getImageData()

            $.ajax {
                url: url
                type: "POST"
                data: {
                    "img_file": saveData
                }
                success: (res) ->
                    alert "save done"
                    location.href = "/mypage"
            }

    setBackgroundImages: ->
        df = $.Deferred()

        src = "/img/card-bg.png"
        img = new Image()
        img.src = src
        img.onload = =>
            #@ctx.drawImage(img, 0, 0, @canvas.width, @canvas.height)
            df.resolve(img)
        img.onerror = (err)->
          df.reject(err)

        return df.promise()


    setImages: ->
        self = this
        @setBackgroundImages().then (img)-> #ここのimgは単なるダミー扱い。あとで見直す
          ic = new ImageComponent(img.src, 'none')
          ic.focus = ->
            @flag.focus = false
          ic.rangeImageInCheck = ->
            return false
          ic.rangeFocusInCheck = ->
            return false
          ic.size.width = 320;
          ic.size.height = 320;
          ic.focusSize.width = 0;
          ic.focusSize.height = 0;
          ic.draw = (ctx)->
              ctx.drawImage(@img, 0, 0, img.width, img.height, @coords.left, @coords.top, @size.width, @size.height)

          self.pushImage(ic)
          self.getImages()

    getImageData: ->
        type = "image/png"
        data = @canvas.toDataURL(type)
        data = data.replace('data:image/png;base64,', '')
        return data

    getImages: ->
        # return ["http://www.brh.co.jp/s_library/j_site/scientistweb/no25/img/face.jpg"]

        self = this
        url = "/get_collaborators/" + @planId
        collaboratorList = []

        # get collaborators list
        $.ajax {
            url: url
            type: "GET"
            dataType: "json"
            success: (res) ->
                for element in res
                    coll = element.Collaborator;
                    uid = coll.uid
                    collaboratorList.push coll

                    $.ajax {
                      url: '/user/' + uid
                      type: "GET"
                      dataType: "json"
                      success: (res) ->
                         coll.user = res.User
                         furl = self.fetchImage(coll.photo_id)
                         $("#imageList").append("<a onclick='drawing.inImage(\"#{furl}\")' href='javascript:void(0)'><img src='#{furl}' style='width: 120px'></a>")
                    }
        }


    fetchImage: (id) ->
        url = "/img/collabo-photo/#{id}.png"

        return url


    makeUrl: ->
        return "/plan/" + @planId + "/photo"
