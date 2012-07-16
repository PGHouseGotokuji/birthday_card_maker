connect = null

$ ->
    connect = new Connect()
    connect.get "/get_user", "user"
    connect.get "/get_plan", "plan"
    