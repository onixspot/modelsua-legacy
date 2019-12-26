<div id="album-viewer-blind" class="hide" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 999;">
	<div id="album-viewer" style="margin: 0px auto; margin-top: 24px; width: 800px; background: white; box-shadow: 0 0 5px black">
		<div class="pl20 pt20 pr20 aright fs12">
			<a id="album-viewer-a-close" href="javascript:void(0);">Закрыть</a>
		</div>
		<div class="pl20 pt20 pr20">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr>
					<td align="left" width="20px">
						<input id="album-viewer-button-left" type="button" value="<" />
					</td>
					<td>
						<div style="width: 698px; overflow: hidden;">
							<div id="album-viewer-photos-list">
							</div>
						</div>
					</td>
					<td align="right" width="20px">
						<input id="album-viewer-button-right" type="button" value=">" />
					</td>
				</tr>
			</table>
		</div>
		<div class="p20 acenter">
			<img id="album-viewer-photo" src="" width="700" />
		</div>
		<div id="album-viewer-photo-description" class="p20 hide">
		</div>
	</div>
</div>
<script type="text/javascript">
	var init_album_viewer;
	
	$(window).scroll(function(){
		$('#album-viewer-blind').height($(this).height()+$(this).scrollTop());
	});
	
	$(document).ready(function(){
		
		var list = {
			'photos': [],
			'show_first': 0,
			'width': 0,
			'description': []
		};
		
		init_album_viewer = function(photos, show_first, description)
		{
			list.photos = photos;
			list.description = description;
			list.show_first = show_first;
			list.width = 0;
			$('#album-viewer-photos-list')
				.css('margin-left', '0px')
				.html('');
			
			$('#album-viewer-button-left')
				.attr('disabled', false)
				.hide();
			$('#album-viewer-button-right')
				.attr('disabled', false)
				.hide();
			
			var flag = false;
			for(var i in list.photos)
			{
				var div = $('<div />')
					.attr({
						'id': 'album-viewer-photos-list-item-'+list.photos[i],
						'class': 'left p5'
					})
					.click(function(){
						var id = $(this).attr('id').split('-')[5];
						$('#album-viewer-photos-list > div').css({'background': 'none'});
						$(this).css({'background': '#eee'});
						$('#album-viewer-photo-description')
							.html('')
							.hide();
						$('#album-viewer-photo').attr('src', '/imgserve?pid='+id+'&w=780')
						$('#album-viewer-photo-description')
							.show()
							.html(description[id]['html'])
					})	
					.append(
						$('<img />').attr({
							'src': '/imgserve?pid='+list.photos[i]+'&h=72',
							'class': 'hide'
						})
							.load(function(){
								list.width = list.width + $(this).width() + 10;
								$('#album-viewer-photos-list').width(list.width);
								if($('#album-viewer-photos-list').width() > $('#album-viewer-photos-list').parent().width())
								{
									$('#album-viewer-button-left').show();
									$('#album-viewer-button-right').show();
								}
								$(this)
									.show()
									.css('opacity', '0')
									.animate({
										'opacity': '1'
									}, 512)
							})
					);
				
				$('#album-viewer-photos-list').append($(div));
				
				if(flag != true)
				{
					if(list.show_first > 0)
					{
						if($(div).attr('id').split('-')[5] == list.show_first)
						{
							$(div).click();
							flag = true;
						}
					}
					else
					{
						$(div).click();
						flag = true;
					}
				}
			}
			
			$('#album-viewer-photos-list').append($('<div />').attr('class', 'clear'));
			
			$('#album-viewer-blind')
				.show()
				.css('opacity', '0')
				.animate({
					'opacity': '1'
				}, 256);
				
			$('#album-viewer').animate({
				'margin-top': Math.round($(window).scrollTop()+24)+'px'
			});
			
			$('#album-viewer-button-left').attr('disabled', true);
		}
		
		$('#album-viewer-button-left').click(function(){
			var p = parseInt($('#album-viewer-photos-list').css('margin-left'));
			
			$('#album-viewer-button-left').attr('disabled', false);
			$('#album-viewer-button-right').attr('disabled', false);
			
			var st = 400;
			if(p + st > 0)
			{
				st = st - (p + st)
				$('#album-viewer-button-left').attr('disabled', true);
			}
			
			$('#album-viewer-photos-list')
				.animate({
					'margin-left': Math.round(p+st)+'px'
				}, 256)
		});
		
		$('#album-viewer-button-right').click(function(){
			var width = $('#album-viewer-photos-list').width();
			var ml = parseInt($('#album-viewer-photos-list').css('margin-left'));
			var pWidth = $('#album-viewer-photos-list').parent().width();
			
			$('#album-viewer-button-left').attr('disabled', false);
			$('#album-viewer-button-right').attr('disabled', false);
			
			var st = 400;
			if(Math.abs(ml - st)  > width - pWidth)
			{
				st = (width+ml)-pWidth;
				$('#album-viewer-button-right').attr('disabled', true);
			}
			
			if(width < pWidth)
			{
				st = 0;
				
			}
			
			$('#album-viewer-photos-list')
				.animate({
					'margin-left': Math.round(ml-st)+'px'
				}, 256);
		});
		
		$('#album-viewer-a-close').click(function(){
			$('#album-viewer-blind').hide();
		});
	});
</script>