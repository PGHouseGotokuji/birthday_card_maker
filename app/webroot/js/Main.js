// Generated by CoffeeScript 1.3.3
var connect;

connect = null;

$(function() {
  connect = new Connect();
  connect.get("/get_user", "user");
  return connect.get("/get_plan", "plan");
});
