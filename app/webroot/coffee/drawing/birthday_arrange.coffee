class BirthdayArrange extends CanvasImages
    upload: ->
        url = @makeUrl()
        console.log url
        saveData = @getImageData()

        $.ajax {
            url: url
            type: "POST"
            data: {
                "img_file": saveData
            }
            success: (res) ->
                alert "save done"
                location.href = "/"
        }

        type = "image/png"
        data = @canvas.toDataURL(type)

    setImages: ->
        imageList = @getImages()
        

    getImageData: ->
        type = "image/png"
        # img.src = @canvas.toDataURL(type)
        data = @canvas.toDataURL(type)
        data = data.replace('data:image/png;base64,', '')
        return data

    getImages: ->
        return [{"http://www.google.co.jp/imgres?um=1&hl=ja&sa=N&biw=1440&bih=779&tbm=isch&tbnid=SZy4QAskl7tsQM:&imgrefurl=http://www.fujimura-auto.co.jp/topics/2006.03.24mls.html&imgurl=http://www.fujimura-auto.co.jp/topics/2006.03.26mls/hamao-gga.jpg&w=3072&h=2048&ei=Wh8-UP_RHcyYmQXfqIHYAQ&zoom=1&iact=rc&dur=109&sig=101403282153143219819&page=1&tbnh=127&tbnw=153&start=0&ndsp=29&ved=1t:429,r:2,s:0,i:77&tx=36&ty=59"}]
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
            imageList.push(fetchImage collaboratorId)

        return imageList

    fetchImage: (id) ->


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
