// Generated by CoffeeScript 1.3.3

$(function() {
  var _this = this;
  window.drawing = new BirthdayArrange();
  console.log(drawing);
  drawing.setCssAbsolute();
  drawing.setImages();
  return $("#add").bind("click", function() {
    return drawing.inImage("static/img/test.png");
  });
});
