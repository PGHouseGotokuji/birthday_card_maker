class Connect
    constructor: ->
        @data = {}

    get: (url, id) ->
        if debug.flag
            res = debug.getData id
            @getSuccess res
            
        else
            $.ajax {
                type: "GET"
                url: url
                dataType: "json"
                success: @getSuccess
            }

    getFriends: ->
        @get '/get_friends', "friend"

    getSuccess: (res) =>
        console.log "getSuccess" if debug.flag
        console.log res if debug.flag

        alert "getSuccess" if debug.alertflag

        # userTable.process(@getFriends)

        if res.User
            @data.user = res.User

            alert "User processing" if debug.alertflag

            console.log "in owner" if debug.flag
            console.log res if debug.flag

            view.update {
                tplSelector: "#ownerTpl"
                # appendSelector: ".content"
                appendSelector: ".main_content"
                method: "prependTo"
                data: res
            }

            $(".owner-right").bind 'click', =>
                console.log "call getFriends" if debug.flag
                @getFriends()

        else if res.Plan
            @data.plan = res.Plan

            alert "Plan processing" if debug.alertflag
            console.log "in plan" if debug.flag
            console.log res
            @get "/get_collaborators/#{res.Plan.id}", "collaborators"
            
            view.update {
                tplSelector: "#cardTpl"
                appendSelector: '.content'
                method: "appendTo"
            }

            $(".send-btn").click =>
                location.href = "/plan/#{@data.plan.id}/collaborator/confirm"
                # view.delete ".owner"
                # view.delete ".card"
                # view.delete ".card-sample"

                # @post "/postcard", {}, (res) =>
                #     if res.Success is "true"
                #         alert "投稿成功しました"
                #     else
                #         alert "投稿失敗しました"

            view.update {
                tplSelector: "#planTpl"
                # appendSelector: '.content .card-left'
                appendSelector: '.main_content .card-left'
                method:"appendTo"
                data: res
            }

        else if res.Friend
            view.delete '.main_content .owner'
            view.delete '.main_content .collaboratorPeople'
            view.delete '.main_content .card'
            view.delete '.main_content .card-sample'

            view.update {
                tplSelector: "#selectFriendTpl"
                # appendSelector: ".content"
                appendSelector: ".main_content"
            }

            friendInfo = res.Friend

            for friend in res.Friend
                view.update {
                    tplSelector: "#friendInfoTpl"
                    # appendSelector: ".content .select-friend"
                    appendSelector: ".main_content .select-friend"
                    data: friend
                }

            # selector = ".content .friend-info" 
            selector = ".main_content .friend-info" 
            $(selector).click ->
                # index = $(".content .friend-info").index(@)
                index = $(".main_content .friend-info").index(@)
                # view.delete '.content .select-friend'
                view.delete '.main_content .select-friend'
                alert "insert_plan index"
                alert index
                alert friendInfo[index]["id"] if debug.alertflag
                connect.post "/insert_plan", friendInfo[index], connect.postSuccess

        else if res[0].Collaborator
            console.log "in collaborators" if debug
            view.update {
                tplSelector: "#memberTpl"
                # appendSelector: ".content"
                appendSelector: ".main_content"
                method: "appendTo"
                data: res
            }

        else
            console.log "in else" if debug
            if res.Friend
                $(".select-user").bind 'click', =>
                    @insertPlan()
                $('#content').html = @setHtml res

    eventSet: (selector, func) =>
        $(selector).click func

    post: (url, data, func) ->
        if debug
            func {
                Success: "true"
            }

        else
            $.ajax {
                url: url
                type: "POST"
                data: data
                dataType: "json"
                success: func
                error: @postError
            }

    postSuccess: (res) =>
        if res.Success is "true"
            @post "/post_fb_timeline", {}, =>
                alert "成功"

            view.update {
                tplSelector: "#insertPlanSuccessTpl"
                appendSelector: ".content"
            }

            connect.get "/get_user", "user"
            connect.get "/get_plan", "plan"

        else
            view.update {
                tplSelector: "#insertPlanErrorTpl"
                appendSelector: ".content"
            }

    postError: (res) ->


    sendButtonClick: ->
        @contributor("http://yahoo.co.jp")

    contributor: (url) ->
        location.href = url

 