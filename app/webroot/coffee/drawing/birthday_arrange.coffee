class BirthdayArrange extends CanvasImages
    upload: ->
        url = @makeUrl()
        console.log url
        saveData = @getImageData()
        console.log saveData

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

    setImages: ->
        imageList = @getImages()
        writer = ""
        for element in imageList
            writer += "<a onclick='drawing.inImage(\"#{element}\")' href='#'>画像</a>"
        console.log writer
        $("#imageList").html(writer)

    getImageData: ->
        type = "image/png"
        # img.src = @canvas.toDataURL(type)
        data = @canvas.toDataURL(type)
        data = data.replace('data:image/png;base64,', '')
        return data

    getImages: ->
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
        $.ajax {
            url: url
            type: "GET"
            async: false
            dataType: "json"
            success: (res) =>
                for element in res
                    collaboratorList.push element.Collaborator.id
        }
        for collaboratorId in collaboratorList
            if collaboratorId is "null"
                continue
            imageList.push(fetchImage collaboratorId)

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
