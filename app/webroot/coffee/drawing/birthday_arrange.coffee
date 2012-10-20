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
                    location.href = "/mypage"
            }

    promiseImage: (src)->
      df = $.Deferred()

      img = new Image()
      img.src = src
      img.onload = =>
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

        bdeco = new BirthdayDeco(self.planId);
        $.when(@promiseImage("/img/card-bg.png"), @promiseImage("/img/cover.png"), bdeco.load()).then (img, covImg, decoImg)-> #ここのimgは単なるダミー扱い。あとで見直す
          ic = self.createFixedImageComponent(img.src)
          ic.size.width = self.canvas.width
          ic.size.height = self.canvas.height

          self.pushImage(ic)

          imageUrl = bdeco.profileImage.src
          profileIC = self.createFixedImageComponent(imageUrl);
          profileIC.size.width = 100;
          profileIC.size.height = 100;
          profileIC.coords.left = (self.canvas.width - profileIC.size.width) * 0.5;
          profileIC.coords.top = (self.canvas.height - profileIC.size.height) * 0.5;
          self.pushImage(profileIC);

          ic = self.createFixedImageComponent(covImg.src)
          ic.size.width = 200
          ic.size.height = 200
          ic.coords.top = 140;
          ic.coords.left = 150;
          self.pushImage(ic)

          textC = new TextComponent(bdeco.username, "bold 12px sans-serif");
          width = textC.getWidth(self.ctx);
          textC.left = self.canvas.width * 0.5 - width * 0.5;
          textC.top = self.canvas.height * 0.5 + profileIC.size.height * 0.5 + 28;
          self.pushComponent(textC)


        self.getImages()


    getImageData: ->
        @reDraw(false)
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
                    c = element.Collaborator;

                    ((coll)->
                      uid = coll.uid
                      collaboratorList.push coll

                      $.ajax {
                      url: '/user/' + uid
                      type: "GET"
                      dataType: "json"
                      success: (res) ->
                        coll.user = res.User
                        #                          uurl = coll.user.fb_picture;
                        uurl = '/facebook/' + coll.user.fb_id + '/picture';
                        $("<a href='javascript:void(0)'><img src='#{uurl}' style='width: 120px'></a>").appendTo("#imageList").click(->
                          self.inImage(uurl)
                        )
                        furl = self.fetchImage(coll.photo_id)
                        $("#imageList").append("<a onclick='drawing.inImage(\"#{furl}\")' href='javascript:void(0)'><img src='#{furl}' style='width: 120px'></a>")

                      })(c)

        }


    fetchImage: (id) ->
        url = "/img/collabo-photo/#{id}.png"

        return url


    makeUrl: ->
        return "/plan/" + @planId + "/photo"
