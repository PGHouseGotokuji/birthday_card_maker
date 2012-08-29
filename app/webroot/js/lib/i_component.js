// Generated by CoffeeScript 1.3.3
var IComponent,
  __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

IComponent = (function() {

  function IComponent(src, type) {
    this.mouseup = __bind(this.mouseup, this);

    this.mouseout = __bind(this.mouseout, this);

    this.mousemove = __bind(this.mousemove, this);

    this.mousedown = __bind(this.mousedown, this);

    this.touchend = __bind(this.touchend, this);

    this.touchmove = __bind(this.touchmove, this);

    this.touchstart = __bind(this.touchstart, this);
    if (type === "img") {
      this.data = {
        top: 0,
        left: 0,
        width: 100,
        height: 100,
        priority: 1
      };
    }
  }

  IComponent.prototype.incrementPriority = function() {
    return this.data.priority += 1;
  };

  IComponent.prototype.setEvents = function(selector) {
    var func, key, that, _ref, _results;
    that = this;
    _ref = this.events;
    _results = [];
    for (key in _ref) {
      func = _ref[key];
      _results.push($(selector).bind(key, func));
    }
    return _results;
  };

  IComponent.prototype.touchstart = function() {
    this.clicking = true;
    return console.log(event);
  };

  IComponent.prototype.touchmove = function() {};

  IComponent.prototype.touchend = function() {
    return this.clicking = false;
  };

  IComponent.prototype.mousedown = function() {
    this.clicking = true;
    this.x = event.pageX;
    return this.y = event.pageY;
  };

  IComponent.prototype.mousemove = function() {
    var id;
    if (this.clicking) {
      id = event.target.id;
      $("#" + id).css({
        "top": "+=" + (event.pageY - this.y),
        "left": "+=" + (event.pageX - this.x)
      });
      this.x = event.pageX;
      return this.y = event.pageY;
    }
  };

  IComponent.prototype.mouseout = function() {
    return this.mouseup();
  };

  IComponent.prototype.mouseup = function() {
    return this.clicking = false;
  };

  return IComponent;

})();
