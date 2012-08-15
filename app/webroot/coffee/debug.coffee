debug = 
    flag: false
    alertflag: false

    data:
        user: { 
            "User": {
                "id":"1","username":"imausr","fb_picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-ash2\/368785_100000588696676_2051595305_q.jpg"
            }
        }
        plan: {"Plan":{"id":"1","from_id":"1","to_id":"4294967295","username":"satosghun1","fb_picture":"https:\/\/fbcdn-profile-a.akamaihd.net\/hprofile-ak-snc4\/49066_100002595002132_583854562_q.jpg","memo":null,"created":null,"modified":null}}

        friend:  { "Friend":[
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

        collaborators: [{"Collaborator":{"id":"1","plan_id":"1","uid":"1","comment":"hogehogehogehoge","memo":null,"created":null,"modified":null}},{"Collaborator":{"id":"3","plan_id":"1","uid":"3","comment":"fugafugafugafuga","memo":null,"created":null,"modified":null}}]

        
    getData: (id) ->
        return @data[id]


    