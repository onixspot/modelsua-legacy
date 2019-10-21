var Popup = new function()
{
	this.calculateY = function()
	{
		var clientHeight =
		document.documentElement.clientHeight > window.innerHeight
		? window.innerHeight
		: document.documentElement.clientHeight;
		return parseInt($(window).scrollTop() + (clientHeight - $('#popup_box').height())/2);
	};

	this.calculateX = function()
	{
		
		return parseInt((document.body.clientWidth - $('#popup_box').width())/2);
	};

	this.show = function()
	{
		if ( !$('#popup_box').length )
		{
			$('body').append(
				'<div id="popup_box" class="popup_box tahoma">\n\
				<div class="popup_title"></div>\n\
				<div class="frame">' +
				'</div></div>'
			);
		}
		this.setTitle();
		this.setHtml();
		this.position();

		$('#popup_box').fadeIn( 150 );
	};


	this.close = function( fadeTime )
	{
		if ( !fadeTime )
		{
			fadeTime = 150;
		}

		$('#popup_box').fadeOut( fadeTime );
	};

	this.setTitle = function( title )
	{
		if ( !title )
		{ 
			title = 'Уведомление';
		}
		
		$('#popup_box > div:eq(0)').html( title );
	}

        this.setHtml = function( html )
	{
		if ( !html )
		{ 
			html = '<img src="http://' + context.host + '/loading.gif" class="m10" />';
		}
		
		$('#popup_box > div:eq(1)').html( html );
	}
        
        this.position = function(x,y)
	{
	    if(!x) x=this.calculateX();
	    if(!y) y=this.calculateY();
	    $('#popup_box').css({
		    top: y + 'px',
		    left: x + 'px'
	    });
	};
}