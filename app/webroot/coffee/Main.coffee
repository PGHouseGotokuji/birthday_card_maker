require [
    "vendor/jquery"
    "debug"
    "View"
    "DataTable"
    "lib/underscore-min"
    ], ->
        require [
          "UserTable"
          "PlanTable"
          "FriendsTable"
          "CollaboratorTable"
        ], ->

          window.dataTable = {}

          dataTable.userTable = new UserTable dataTable
          dataTable.planTable = new PlanTable dataTable
