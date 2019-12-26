<div class="fs12" style="width: 1000px">
	
	<? include 'album/viewer.php'; ?>
	
	<div id="window-add-photo" class="pt10 pl10 pr10 hide" style="position: absolute; width: 400px; margin-left: 300px; background: #fff; box-shadow: 0 3px 6px black; z-index: 999;">
		<? include 'album/add_photo.php'; ?>
	</div>
	
	<div class="p5 mb10" style="background: #eee; border-top: 1px solid #ccc">
		<div class="left">
			<span>
				<a href='/profile?id=<?=$uid?>'><?=profile_peer::get_name($profile)?></a> :: 
			</span>
			<span>
				<? if($category_key){ ?>
					<? if($category_key != 'portfolio'){ ?>
						<span>
							<a href="/albums/works?uid=<?=$uid?>"><?=t('Работы')?></a> :: 
						</span>
					<? } ?>
					<? if(in_array($category_key, array('covers', 'portfolio'))){ ?>
						<?=user_albums_peer::get_category($category_key)?>
					<? } else { ?>
						<span>
							<a href="/albums?filter=<?=$category_key?>&uid=<?=$uid?>"><?=user_albums_peer::get_category($category_key)?></a>
						</span>
						<!-- :: <span>
							<?=$album['name']?>
						</span>-->
					<? } ?>
				<? } else { ?>
					<span>
						<a href="/albums?uid=<?=$uid?>"><?=t('Фотографии')?></a> :: 
					</span>
					<span>
						<?=$album['name']?>
					</span>
				<? } ?>
			</span>
		</div>
		<div class="right">
			<a href='javascript:void(0);' id="show-album-viewer"><?=t('Смотреть все')?></a>
		</div>
		<? if(session::has_credential('admin') || session::get_user_id() == $uid){ ?>
			<div class="right mr10">
				<a id="show-window-add-photo"><?=t('Загрузить фото')?></a>
			</div>
			<? if( ! in_array($category_key, array('portfolio', 'covers')) && request::get_string('filter') != 'deleted'){ ?>
				<div class="right mr10">
					<a href="/albums?filter=<?=$category_key?>&uid=<?=$uid?>&show=edit_album_<?=$album['id']?>"><?=t('Редактировать')?></a>
				</div>
			<? } ?>
		<? } ?>
		<div class="clear"></div>
	</div>
	
	<div class="mb10">
		<div class="fs20" style="color: #000000"><?=$information[$category_key]['label']?></div>
		<div class="fs12 mt5"><?=$information[$category_key]['text']?></div>
		<div class="fs12 mt5"><?=$information[$category_key]['link']?></div>
	</div>
	
	<? if(in_array($category_key, array('covers'))){ ?>
		<? include 'album/list/'.$category_key.'.php'; ?>
	<? } else { ?>
		<? include 'album/list/default.php'; ?>
	<? } ?>
	
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
				}, 256, function(){
					var act = 'add_photo';
					if(typeof photo.act != 'undefined')
					{
						act = photo.act;
						$('#window-add-photo #submit').attr('disabled', false);
					}
					else
					{
						$('#window-add-photo #submit').attr('disabled', true);
					}
					$('#window-add-photo #pid').val(photo.id);
					$('#window-add-photo #old_pid').val(photo.id);
					$('#window-add-photo #act').val(act);
					load_form();
				});
		});
		
		<? if($show == 'add_photo'){ ?>
			$('#show-window-add-photo').click();
		<? } ?>
		
		var show_first = 0;
		$('#show-album-viewer').click(function(){
			$.post('/albums/album',{
				<? if(request::get_string('filter') == 'deleted'){ ?>'type': 'deleted',<? } ?>
				'act': 'get_photos',
				'aid': '<?=$aid?>',
				'uid': '<?=$uid?>'
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
			if(confirm('Точно удалить?'))
			{
				$.post('/albums/album', {
					'act': 'remove_photo',
					'aid': '<?=$aid?>',
					'pid': pid
				}, function(resp){
					if(resp.success)
						window.location = '/albums/album?aid=<?=$aid?>&uid=<?=$uid?>';
				}, 'json');
			}
		}
		
	});
</script>
