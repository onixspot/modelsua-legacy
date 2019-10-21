var Form = function(form_id)
{
	
	var id = form_id;
	this.action = "/";
	this.method = "post";
	this.data = new Object();
	this.onSuccess = undefined;
	this.response = undefined;
	
	// Private methods
	var init = function()
	{
		$("form[id='"+id+"']").attr("onsubmit", "return false;");
	}
	
	// Public methods
	this.scan = function()
	{
		var data = new Object();
		this.action = $("form[id='"+id+"']").attr("action");
		this.method = $("form[id='"+id+"']").attr("method");
		$.each(
			$("form[id='"+id+"']").find(
				"input[type='text'], input[type='checkbox'], input[type='radio'], input[type='hidden'], input[type='password'], select, textarea"
			),
			function(index, element)
			{
				var key;
				var value;
				
				if(
					$.inArray($(element).attr("type"), ["text", "hidden", "password"]) != -1 ||
					$(element).is("textarea") ||
					$(element).is("select")
				)
				{
					if(typeof $(element).attr("id") != "undefined")
					{
						key = $(element).attr("id");
					}
					else if(typeof $(element).attr("name") != "undefined")
					{
						key = $(element).attr("name");
					}
					
					value = $(element).val();
				}
				
				if(
					$.inArray($(element).attr("type"), ["checkbox", "radio"]) != -1 &&
					$(element).attr("checked") == "checked"
				)
				{
					if(typeof $(element).attr("id") != "undefined")
					{
						key = $(element).attr("id");
					}
					else if(typeof $(element).attr("name") != "undefined")
					{
						if($(element).attr("name").match(/(\[)/))
							key = $(element).attr("name").split(/(\[|\])/)[0];
						else
							key = $(element).attr("name");
					}
					
					value = 1;
				}
				
				if(typeof key != "undefined")
					data[key] = value;
			}
		);
		return data;
	}
	
	this.send = function()
	{
		var data = $.extend(this.scan(), this.data);
		
		var form = this;
		$.post(
			this.action, 
			data, 
			function(response){
				if(typeof form.onSuccess != "undefined")
					form.onSuccess(response);
			},
			"json"
		);
	}
	
	// Block of initialization
	init();
	
}

