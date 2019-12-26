<div class="fs12" style="width: 1000px">
	
	<? include 'album/viewer.php'; ?>
	
	<div id="window-add-photo" class="pt10 pl10 pr10 hide" style="position: absolute; width: 400px; margin-left: 300px; background: #fff; box-shadow: 0 3px 6px black; z-index: 999;">
		<? include 'album/add_photo.php'; ?>
	</div>
	
	<div class="p5 mb10" style="background: #eee; border-top: 1px solid #ccc">
		<div class="left">
			<span><a href="/agency?id=<?=$agency_id?>"><?=$agency['name']?></a></span> ::
			<span><a href="/agency/albums?id=<?=$agency_id?>">Фотоальбомы</a></span> ::
			<span><?=$agency_album['additional']['name']?></span>
		</div>
		<div class="right">
			<a id="show-album-viewer" href="javascript:void(0);"><?=t('Смотреть все')?></a>
		</div>
		<? if($access){ ?>
			<div class="right mr10">
				<a id="show-window-add-photo" href="javascript:void(0);"><?=t('Загрузить фото')?></a>
			</div>
			<div class="right mr10">
				<a href="/agency/albums?id=<?=$agency_id?>&show=edit_album_<?=$agency_album['id']?>"><?=t('Редактировать')?></a>
			</div>
		<? } ?>
		<div class="clear"></div>
	</div>
	
	<? include 'album/list.php' ?>
	
</div>

<script type="text/javascript">
	var photo = new Object();
	
	$(document).ready(function(){
		
		$('#show-window-add-photo').click(function(){
			$('#window-add-photo')
				.show()
				.css('opacity', '0')
				.animate({
					'opacity': '1',
					'top': parseInt($(window).scrollTop()+128)+'px'
				}, 256);
		});
		
		<? if($show == 'add_photo'){ ?>
			$('#show-window-add-photo').click();
		<? } ?>
		
		var show_first = 0;
		$('#show-album-viewer').click(function(){
			$.post('/agency/album',{
				'act': 'get_photos',
				'aid': '<?=$agency_album['id']?>'
			}, function(resp){
				if(resp.success)
				{
					init_album_viewer(resp.photos, show_first, resp.additional);
					show_first = 0;
				}
			}, 'json');
		});
		
		$("#window-add-photo input[id='cancel']").click(function(){
			$('#window-add-photo').hide();
		});
		
		$("div[id^='photos-list-item-photo']").click(function(){
			show_first = $(this).attr('id').split('-')[4];
			$('#show-album-viewer').click();
		});
		
		<? if($show == 'viewer' && request::get_int('pid') > 0){ ?>
			$('#photos-list-item-photo-<?=request::get_int('pid')?>').click();
		<? } ?>
		
		$("a[id^='photos-list-item-modify']").click(function(){
			var id = $(this).attr('id').split('-')[4]
			$.post('/albums/album', {
				'act': 'get_photo',
				'pid': id
			}, function(resp){
				if(resp.success)
				{
					photo = resp.photo;
					photo.act = 'modify_photo';
					$('#window-add-photo #submit').attr('disabled', false);
					$('#show-window-add-photo').click();
				}
			}, 'json');
		});
		
		$("a[id^='photos-list-item-remove']").click(function(){
			var pid = $(this).attr('id').split('-')[4];
			remove_photo(pid);
		});
		
		var remove_photo = function(pid)
		{
			if(confirm('Вы действительно хотите удалить эту фотографию?'))
			{
				$.post('/agency/album', {
					'act': 'remove_photo',
					'aid': '<?=$agency_album['id']?>',
					'pid': pid
				}, function(resp){
					if(resp.success)
						window.location = '/agency/album?aid=<?=$agency_album['id']?>';
				}, 'json');
			}
		}
		
	});
</script>