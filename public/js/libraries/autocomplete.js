var Autocomplete = function(objId, options) {
    
    var object = $('#'+objId);
    
    
    if ( typeof options == 'undefined' )
    {
	    options = {};
    }
    
    if ( typeof options.row_class_name == 'undefined' )
    {
	    options.row_class_name = 'autocomplete_row';	    /////////// !important
    }
    
    if ( typeof options.template == 'undefined' )
    {
	    options.template = '<tr class="autocomplete_row">'+	    /////////// !important
				     '<td>'+
					 '<img src="%src%" style="width: 30px;"/>'+
				      '</td>'+
				      '<td style="vertical-align: middle;">'+
					  '<a class="fs14 user_name_class" href="javascript:;">%name%</a>'+
				      '</td>'+
				'</tr>';
    }
    
    if ( typeof options.min_symbols == 'undefined' )
    {
	    options.min_symbols = 2;
    }
    
    if ( typeof options.view_mode == 'undefined' )
    {
	    options.view_mode = 'table'; 
    }

    if ( typeof options.container_name == 'undefined' )
    {
	    options.container_name = 'autocomplete_box';
    }

    if ( typeof options.post_url == 'undefined' )
    {
	    options.post_url = '/messages/index';
    }
    
    if ( typeof options.selected_class == 'undefined' )
    {
	    options.selected_class = 'ac_selected_row';
    }
    
    if ( typeof options.receiver_id == 'undefined' )
    {
	    options.receiver_id = 'receiver_id';
    }

    

    this.init = function () {
	this.prepareTemplate();
	this.initContainer();
	this.setSearchEvents();
	this.setListControls()
    }
    
    this.prepareTemplate = function() {
	options.template = options.template.replace(options.row_class_name,options.row_class_name+'_%id%');
    }
    
    this.initContainer = function() {
	$('body').append('<div id="'+options.container_name+'"></div>');
	$('body > div[id="'+options.container_name+'"]').css({
							'left': parseInt(object.position().left),
							'top' : parseInt(object.position().top + object.innerHeight())
						  });  
    }
    
    this.setListControls = function() {
	
	var listObj = $('#'+options.container_name);
	var recIdObj = $('#'+options.receiver_id);
	
	listObj.mouseover(function(e) {
	    $("[class^="+options.row_class_name+"]").removeClass(options.selected_class);
	    $(e.target).parents("[class^="+options.row_class_name+"]").addClass(options.selected_class);
	});
	
	listObj.mouseout(function(e) {
	    $("[class^="+options.row_class_name+"]").removeClass(options.selected_class);
	    $(e.target).parents("[class^="+options.row_class_name+"]").removeClass(options.selected_class);
	});
	
	listObj.click(function(e) {
	    recIdObj.val($(e.target).parents("[class^="+options.row_class_name+"]").removeClass(options.selected_class).attr('class').replace(options.row_class_name+"_",""));
	    object.val($(e.target).parents("[class^="+options.row_class_name+"]").find('.user_name_class').html());
	    listObj.html('');
	});
	
//	object.focusout(function(){
//	    recIdObj.val('');
//	    listObj.html('');
//	})
	
	object.keypress(function(e){

	    var curRow = $('.'+options.selected_class);
	    
	    switch(e.keyCode) {
		case 40:
		case 38:
		    
		    var state = (e.keyCode==38) ? {'nextRow': curRow.prev(), 'selectors': [':first', ':last']} : {'nextRow': curRow.next(), 'selectors': [':last', ':first']};
		    
		    if(curRow && state.nextRow.length) {
			state.nextRow.addClass(options.selected_class);
			curRow.removeClass(options.selected_class);
		    }
		    else {
			$("[class^="+options.row_class_name+"]"+state.selectors[0]).removeClass(options.selected_class);
			$("[class^="+options.row_class_name+"]"+state.selectors[1]).addClass(options.selected_class);
		    }
		    break;
		case 13:
		    recIdObj.val($(curRow).removeClass(options.selected_class).attr('class').replace(options.row_class_name+"_",""));
		    object.val($(curRow).find('.user_name_class').html());
		    listObj.html('');
		    return false;
		    break;
		case 27:
		    recIdObj.val('');
		    listObj.html('');
		    break;
	    }
	    
	});
	
    }
    this.setSearchEvents = function() {
	var excodes = new Array(9,13,16,18,17,20,27,38,40);
	object.keyup(function(e) {
	    var search_value = object.val();
	    var listBox = $('#'+options.container_name);
	    if($.inArray(e.keyCode, excodes)==-1) {
		if(search_value.length>options.min_symbols) {
		    $.post(
			options.post_url,
			{
			    "ajax_search" : search_value 
			},
			function(resp) {
			    if(resp) {
				if(resp.length) {
				    var addHtml = '';
				    listBox.html('');
				    for(k=0; k<resp.length; k++) {
					var html = options.template;
					for(i in resp[k]) 
					    html = html.replace("%"+i+"%",resp[k][i]);
					addHtml += html;
				    }
				    listBox.append('<'+options.view_mode+'>'+addHtml+'</'+options.view_mode+'>');
				}
				else 
				    listBox.html('');
			    }
			},
			'json'
		    );
		}
	    }
	});

    }
    
}
