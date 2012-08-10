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
                appendSelector: ".content"
            }
        ]
        for friend in @data
            @viewParams.push {
                tplSelector: "#friendInfoTpl"
                appendSelector: ".content .select-friend"
                data: friend
            }

        view.delete '.content > *'
        @viewUpdate()
    
    setEvents: ->
        selector = ".content .friend-info" 
        friendInfo = @data
        self = @

        $(selector).click ->
            index = $(selector).index(@)
            view.delete '.content > *'
            self.AjaxPost friendInfo[index]


    postSuccess: (res) =>
        if res.Success is "true"
            @AjaxPost {}, "/post_fb_timeline", =>
                alert "成功"

            window.dataTable.userTable.viewUpdate()
            window.dataTable.planTable.viewUpdate()
            window.dataTable.collaborators.viewUpdate()

        else
            view.update {
                tplSelector: "#insertPlanErrorTpl"
                appendSelector: ".content"
            }
