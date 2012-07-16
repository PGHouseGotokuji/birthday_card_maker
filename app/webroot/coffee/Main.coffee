connect = null

$ ->
    connect = new Connect()
    connect.get "/get_user"
    connect.get "/get_plan"
    