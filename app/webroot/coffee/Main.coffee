require [
    "vendor/jquery"
    "debug"
    "View"
    "DataTable"
    "UserTable"
    "PlanTable"
    "FriendsTable"
    "CollaboratorTable"
    "lib/underscore-min"
    ], ->
        window.dataTable = {}
        
        dataTable.userTable = new UserTable dataTable
        dataTable.planTable = new PlanTable dataTable
