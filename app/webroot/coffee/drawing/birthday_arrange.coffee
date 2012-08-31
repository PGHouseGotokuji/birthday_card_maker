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

    createFixedImageComponent: (src)->
      #完全に固定になっている画像
      ic = new ImageComponent(src, 'none')
      ic.focus = ->
        @flag.focus = false
      ic.rangeImageInCheck = ->
        return false
      ic.rangeFocusInCheck = ->
        return false
      ic.focusSize.width = 0
      ic.focusSize.height = 0
      ic.draw = (ctx)->
        ctx.drawImage(@img, 0, 0, @img.width, @img.height, @coords.left, @coords.top, @size.width, @size.height)
      return ic;

    setImages: ->
        self = this
        @setBackgroundImages().then (img)-> #ここのimgは単なるダミー扱い。あとで見直す
          ic = self.createFixedImageComponent(img.src)
          ic.size.width = self.canvas.width
          ic.size.height = self.canvas.height

          self.pushImage(ic)

          bdeco = new BirthdayDeco(self.planId);
          bdeco.load().then ->
            profileIC = self.createFixedImageComponent(bdeco.profileImage.src);
            profileIC.size.width = 100;
            profileIC.size.height = 100;
            profileIC.coords.left = (self.canvas.width - profileIC.size.width) * 0.5;
            profileIC.coords.top = (self.canvas.height - profileIC.size.height) * 0.5;
            self.pushImage(profileIC)

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
                          uurl = coll.user.fb_picture;
                          $("#imageList").append("<a href='javascript:void(0)'><img src='#{uurl}' style='width: 120px'></a>").click(->
                            self.inImage(uurl)
                          )
                          furl = self.fetchImage(coll.photo_id)
                          $("#imageList").append("<a onclick='drawing.inImage(\"#{furl}\")' href='javascript:void(0)'><img src='#{furl}' style='width: 120px'></a>")
                    }
        }


    fetchImage: (id) ->
        url = "/img/collabo-photo/#{id}.png"

        return url


    makeUrl: ->
        return "/plan/" + @planId + "/photo"
