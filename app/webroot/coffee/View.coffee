class View
	constructor: ->
		
    update: (params) ->
        if params.data is undefined
            $($(params.tplSelector).html()).appendTo(params.appendSelector)
        else
            content = _.template($(params.tplSelector).html(), params.data);
            switch params.method
                when "appendTo" 
                    $(content).appendTo(params.appendSelector)
                when "prependTo" 
                    $(content).prependTo(params.appendSelector);
                else                   
                    $(content).appendTo(params.appendSelector);

    delete: (selector) ->
        $(selector).remove()
