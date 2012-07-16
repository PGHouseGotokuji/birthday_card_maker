debug = true

class Connect
    constructor: ->
        # @data = new ClassList()

    get: (url) ->
        if debug
            res = {
                "User": {
                    "id":"1","username":"imausr","fb_picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-ash2\/368785_100000588696676_2051595305_q.jpg"
                }
            }
            {
                "Plan": {
                    "id":"1","from_id":"1","to_id":null,"memo":null,"created":null,"modified":null
                }
            }
            @getSuccess res
            
        else
            $.ajax {
                type: "GET"
                url: url
                dataType: "json"
                success: @getSuccess
            }

    getSuccess: (res) ->

        console.log "getSuccess" if debugs
        console.log res if debug

        if res.Owner
            console.log "in owner" if debug
            console.log res if debug

            $(".#{owner-right}").bind 'click', =>
                console.log "call getFriends" if debug
                @getFriends()
            view.update {
                tplSelector: "#ownerTpl"
                appendSelector: ".content .owner"
                data: res
            }
            
            # view.updateOwner res

        else if res.Plan
            console.log "in plan" if debug
            @get("/get_collaborators")

            view.updatePlan res

        else if res.collaborators
            console.log "in collaborators" if debug
            view.updateCollaborators res
        else
            console.log "in collaborators" if debug
            if res.Friend
                $(".#{select-user}").bind 'click', =>
                    @insertPlan()
                $('#content').html = @setHtml res

    eventSet: (res) ->
        


    setHtml: (res) ->
        text = '<div class="select-friend">'

        for key, value of res
            text += "<ul>"
            text += "<li class='friend-img'>"
            text += "<img src=#{value.fb_profile}>"
            text += "</li>"
            text += "<li class='friend-name'>"
            text += "#{value.username}"
            text += "</li>"
            text += "</ul>"

        text += '</div>'

        return text

    getFriends: ->
        @get '/get_friends'

    insertPlan: ->
        @post "/insertPlan", {
            to_id: ""
            fb_picture: ""
            username: ""
        }

    post: (url, data) ->
        $.ajax {
            url: url
            type: "POST"
            data: data
            dataType: "json"
            success: @postSuccess
            error: @postError
        }

    postSuccess: (res) ->


    postError: (res) ->



    sendButtonClick: ->
        @contributor("http://yahoo.co.jp")

    contributor: (url) ->
        location.href = url

 