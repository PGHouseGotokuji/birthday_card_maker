class BirthdayDeco

  constructor: (planId)->
    @planId = planId

  load: ->
    self = this;

    d = $.Deferred();

    ($.ajax {
    url: "/plan/" + @planId
    method: "GET"
    dataType: "json"
    }).then((res)->
      if(!res)
        alert '該当するPlanが存在しません'
        return

      self.plan = res.Plan
      self.username = self.plan.username

      self.profileImage = new Image();
      self.profileImage.onload = ->
          d.resolve()

      self.profileImage.onerror = (err)->
          d.relect(err)

      self.profileImage.src = '/facebook/' + self.plan.to_id + '/picture'

    , (err)->
      d.reject(err);
    )

    return d;



