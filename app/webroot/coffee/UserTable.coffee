class UserTable extends DataTable
    getUrl: "/get_user"

    constructor: (saveLocation) ->
        super saveLocation
        @AjaxGet "user"

    getSuccess: (res) ->
        @data = res
        @viewParams = [
            {
                tplSelector: "#ownerTpl"
                # appendSelector: ".content"
                appendSelector: ".main_contents"
                method: "prependTo"
                data: @data
            }
        ]

        alert "processing" if debug.alertflag

        console.log "in owner" if debug.flag
        console.log res if debug.flag

        @viewUpdate()

        $(".owner-right").bind 'click', =>
            console.log "call getFriends" if debug.flag
            @createFriends()

    createFriends: ->
        @saveLocation["friends"] = new FriendsTable @saveLocation
        