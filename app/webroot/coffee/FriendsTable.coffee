class FriendsTable extends DataTable
    getUrl: "/get_friends"
    postUrl: "/insert_plan"

    constructor: (saveLocation) ->
        super saveLocation
        @AjaxGet "friend"

    getSuccess: (res) ->
        @data = res.Friend
        @viewParams = [
            {
                tplSelector: "#selectFriendTpl"
                # appendSelector: ".content"
                appendSelector: ".main_contents"
            }
        ]
        for friend in @data
            @viewParams.push {
                tplSelector: "#friendInfoTpl"
                # appendSelector: ".content .select-friend"
                appendSelector: ".main_contents .select-friend"
                data: friend
            }

        # view.delete '.content > *'
        view.delete '.main_contents > *'
        @viewUpdate()
    
    setEvents: ->
        # selector = ".content .friend-info" 
        selector = ".main_contents .friend-info" 
        friendInfo = @data
        self = @

        $(selector).click ->
            index = $(selector).index(@)
            # view.delete '.content > *'
            view.delete '.main_contents > *'
            self.AjaxPost friendInfo[index]


    postSuccess: (res) =>
        if res.Success is "true"
            @AjaxPost {}, "/post/post_fb_timeline", =>
                alert "成功"

            location.href = '/mypage'

        else
            view.update {
                tplSelector: "#insertPlanErrorTpl"
                # appendSelector: ".content"
                appendSelector: ".main_contents"
            }
