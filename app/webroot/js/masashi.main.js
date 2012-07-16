view = null

$(function () {
    var View = function () {
    };

    View.prototype = {

        update: function(params) {
            if(params.data == undefined){
                $($(params.tplSelector).html()).appendTo(params.appendSelector);
            }
            else{
                var content = _.template($(params.tplSelector).html(), params.data);
                switch(params.method){
                    case "appendTo":
                        $(content).appendTo(params.appendSelector);
                        break
                    case "prependTo":
                        $(content).prependTo(params.appendSelector);
                        break
                    default:
                        $(content).appendTo(params.appendSelector);
                }           
            }
        },

        delete: function(selector){
            $(selector).remove();
        },

        updateOwner:function (owner) {
            var content = _.template($('#ownerTpl').html(), owner);
            $(content).appendTo('.content .owner');
        },

        deleteOwner: function() {
            $('.content .owner').remove();
        },

        updatePlan:function (plan) {
            var content = _.template($('#planTpl').html(), plan);
            $(content).appendTo('.content .card-left');
        },

        deletePlan:function (){
            $(content).appendTo('.content .card-left');
        },

        updateCollaborators:function (collaborators) {
            var content = _.template($('#memberTpl').html(), collaborators);
            $(content).appendTo('.content .members');
        }

    }

    view = new View();

    // view.updateOwner({
    //     "User":{
    //         "id":"1", "username":"imausr",
    //         "fb_picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-ash2\/368785_100000588696676_2051595305_q.jpg"
    //     }
    // });

    // view.updatePlan({
    //     "Plan":{
    //         "id":"1",
    //         "from_id":"1", "to_id":"4294967295", "username":"satoshun1",
    //         "fb_picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-snc4\/49066_100002595002132_583854562_q.jpg",
    //         "memo":null, "created":null, "modified":null
    //     }
    // });

    // var cols = [
    //     {"Collaborator":{"id":"1", "plan_id":"1", "uid":"1", "comment":"hogehogehogehoge",
    //         "memo":null, "created":null, "modified":null}},

    //     {"Collaborator":{"id":"3", "plan_id":"1", "uid":"3", "comment":"fugafugafugafuga",
    //         "memo":null, "created":null, "modified":null}}
    // ];
    // view.updateCollaborators(cols);

});
