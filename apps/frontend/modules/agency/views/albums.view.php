<div class="fs12" style="width: 1000px">
	
	<div id="window-add-album" class="pt10 pl10 pr10 hide" style="position: absolute; width: 400px; margin-left: 300px; background: #fff; box-shadow: 0 3px 6px black; z-index: 999;">
		<? include 'albums/add_album.php'; ?>
	</div>
	
	<div class="p5 mb10" style="background: #e6e8eb; border-top: 1px solid #c4cad6">
		<div class="left">
			<span><a href="/agency?id=<?=$agency_id?>"><?=$agency['name']?></a></span> ::
			<span>Фотоальбомы</span>
		</div>
		<? if($access){ ?>
			<div class="right">
				<a id="show-window-add-album" href='javascript:;'><?=t('Создать альбом')?></a>
			</div>
		<? } ?>
		<div class="clear"></div>
	</div>
	
	<? include 'albums/list.php'; ?>
	
</div>

<script type="text/javascript">
	var data = new Object();
	
	$(document).ready(function(){
		$('#show-window-add-album').click(function(){
			$('#window-add-album')
				.show()
				.css('opacity', '0')
				.animate({
					'opacity': '1',
					'top': parseInt($(window).scrollTop()+128)+'px'
				}, 256, function(){
					init_form(data);
					data = new Object();
				});
		});
		
		<? if($show == 'add_album'){ ?>
			$('#show-window-add-album').click();
		<? } ?>
	});
</script>