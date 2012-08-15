require [
    "vendor/jquery"
    "debug"
    "view"
    "dataTable"
    "userTable"
    "planTable"
    "friendsTable"
    "collaboratorTable"
    "lib/underscore-min"
    ], ->
        window.dataTable = {}
        
        dataTable.userTable = new UserTable dataTable
        dataTable.planTable = new PlanTable dataTable
