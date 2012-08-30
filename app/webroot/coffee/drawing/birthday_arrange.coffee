class BirthdayArrange extends CanvasImages

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
            @ctx.drawImage(img, 0, 0, @canvas.width, @canvas.height)
            df.resolve()
        return df.promise()


    setImages: ->
        imageList = @getImages()
        writer = ""
        for element in imageList
            writer += "<a onclick='drawing.inImage(\"#{element}\")' href='#'>画像</a>"
        console.log writer
        $("#imageList").html(writer)

    getImageData: ->
        type = "image/png"
        data = @canvas.toDataURL(type)
        data = data.replace('data:image/png;base64,', '')
        return data

    getImages: ->
        # return ["http://www.brh.co.jp/s_library/j_site/scientistweb/no25/img/face.jpg"]
        planId = ""
        
        $.ajax {
            url: "/get_plan"
            type: "GET"
            async: false
            dataType: "json"
            success: (res) =>
                planId = res.Plan.id
        }

        url = "/get_collaborators/" + planId
        collaboratorList = []

        # get collaborators list
        $.ajax {
            url: url
            type: "GET"
            async: false
            dataType: "json"
            success: (res) =>
                for element in res
                    collaboratorList.push element.Collaborator.id
        }

        url = "/get_friends"
        collaboratorsProfileImages = []
        # get collaborators profile image
        $.ajax {
            url: url
            type: "GET"
            async: false
            dataType: "json"
            success: (res) ->
                
        }

        imageList = []

        for collaboratorId in collaboratorList
            if collaboratorId is "null"
                continue
            imageList.push(@fetchImage collaboratorId)

        return imageList

    fetchImage: (id) ->
        url = "/img/collabo-photo/#{id}.png"

        return url


    makeUrl: ->
        planId = ""

        $.ajax {
            url: "/get_plan"
            type: "GET"
            async: false
            dataType: "json"
            success: (res) =>
                planId = res.Plan.id 
        }

        return "/plan/" + planId + "/photo" 
