debug = false
alertFlag = false

class Connect
    constructor: ->
        @data = {}

    get: (url, id) ->
        if debug
            res = {}
            if id is "user"
                res = {
                    "User": {
                        "id":"1","username":"imausr","fb_picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-ash2\/368785_100000588696676_2051595305_q.jpg"
                    }
                }
            if id is "plan"
                res = {"Plan":{"id":"1","from_id":"1","to_id":"4294967295","username":"satosghun1","fb_picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-snc4\/49066_100002595002132_583854562_q.jpg","memo":null,"created":null,"modified":null}}

            if id is "collaborators"
                res = [{"Collaborator":{"id":"1","plan_id":"1","uid":"1","comment":"hogehogehogehoge","memo":null,"created":null,"modified":null}},{"Collaborator":{"id":"3","plan_id":"1","uid":"3","comment":"fugafugafugafuga","memo":null,"created":null,"modified":null}}]
                
            if id is "friend"
                res = { "Friend":[
                        {
                            "name":"Xiaochen Su"
                            "id":"316087"
                            "fb_picture":"https:\/\/graph.facebook.com\/316087\/picture"
                        }
                        {
                            "name":"Takashi Nishimura"
                            "id":"1955966"
                            "fb_picture":"https:\/\/graph.facebook.com\/1955966\/picture"
                        }
                        {
                            "name":"Yuki Shiraji"
                            "id":"4203608"
                            "fb_picture":"https:\/\/graph.facebook.com\/4203608\/picture"
                        }
                        ]}

            @getSuccess res
            
        else
            $.ajax {
                type: "GET"
                url: url
                dataType: "json"
                success: @getSuccess
            }

    getSuccess: (res) =>
        console.log "getSuccess" if debug
        console.log res if debug

        alert "getSuccess" if alertFlag

        if res.User
            @data.user = res.User

            alert "User processing" if alertFlag

            console.log "in owner" if debug
            console.log res if debug

            view.update {
                tplSelector: "#ownerTpl"
                appendSelector: ".content"
                method: "prependTo"
                data: res
            }

            $(".owner-right").bind 'click', =>
                console.log "call getFriends" if debug
                @getFriends()

            # view.updateOwner res

        else if res.Plan
            @data.plan = res.Plan

            alert "Plan processing" if alertFlag
            console.log "in plan" if debug
            console.log res
            @get "/get_collaborators/#{res.Plan.id}", "collaborators"
            
            view.update {
                tplSelector: "#cardTpl"
                appendSelector: '.content'
                method: "appendTo"
                # data: res
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
                appendSelector: '.content .card-left'
                method:"appendTo"
                data: res
            }

        else if res.Friend
            view.delete '.content .owner'
            view.delete '.content .collaboratorPeople'
            view.delete '.content .card'
            view.delete '.content .card-sample'

            view.update {
                tplSelector: "#selectFriendTpl"
                appendSelector: ".content"
            }

            friendInfo = res.Friend

            for friend in res.Friend
                view.update {
                    tplSelector: "#friendInfoTpl"
                    appendSelector: ".content .select-friend"
                    data: friend
                }

            selector = ".content .friend-info" 
            $(selector).click ->
                index = $(".content .friend-info").index(@)
                view.delete '.content .select-friend'
                alert "insert_plan index"
                alert index
                alert friendInfo[index]["id"] if alertFlag
                connect.post "/insert_plan", friendInfo[index], connect.postSuccess

        else if res[0].Collaborator
            console.log "in collaborators" if debug
            view.update {
                tplSelector: "#memberTpl"
                appendSelector: ".content"
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

    getFriends: ->
        @get '/get_friends', "friend"

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

 