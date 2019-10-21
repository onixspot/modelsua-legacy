<div class="fs12" style="width: 1000px">
	
	<div id="window-add-album" class="pt10 pl10 pr10 hide" style="position: absolute; width: 400px; margin-left: 300px; background: #fff; box-shadow: 0 3px 6px black; z-index: 999;">
		<? include 'index/add_album.php'; ?>
	</div>
	
	<div class="p5 mb10" style="background: #e6e8eb; border-top: 1px solid #c4cad6">
		<div class="left">
			<span>
				<a href='/profile?id=<?=$uid?>'><?=profile_peer::get_name($profile)?></a> :: 
			</span>
			<span>
				<? if($category_key && $category_key != 'portfolio'){ ?>
					<span>
						<a href='/albums/works?uid=<?=$uid?>'>Работы</a>
					</span>
					<!-- :: <span>
						<?=user_albums_peer::get_category($category_key)?>
					</span>-->
				<? } elseif($category_key == 'portfolio') { ?>
					<?=user_albums_peer::get_category($category_key)?>
				<? } else { ?>
					Фотографии
				<? } ?>
			</span>
		</div>
		<div class="right">
			<a id="show-window-add-album" href='javascript:;'><?=t('Создать альбом')?></a>
		</div>
		<div class="clear"></div>
	</div>
	
	<? if($category_key && $category_key != 'portfolio'){ ?>
		<div class="mb10 fs20" style="color: #B95383">
			<?=user_albums_peer::get_category($category_key)?>
		</div>
	<? } ?>
	
	<div id="albums-list">
		
		<div>
			<div id="albums-list-item-1" class="left acenter" style="width: 250px">
				<div class="mr5" style="background: #eee">
					<div class="p5 aright">
						<a href="javascript:;">
							<img src="/ui/delete2.png" height="20" />
						</a>
					</div>
					<div style="height: 180px; background: url('/imgserve?pid=863&h=180') no-repeat center"></div>
					<div class="p5">
						<a href="javascript:;">Name</a>
					</div>
				</div>
			</div>
			<div id="albums-list-item-1" class="left acenter" style="width: 250px">
				<div class="ml5 mr5" style="background: #eee">
					<div class="p5 aright">
						<a href="javascript:;">
							<img src="/ui/delete2.png" height="20" />
						</a>
					</div>
					<div style="height: 180px; background: url('/imgserve?pid=863&h=180') no-repeat center"></div>
					<div class="p5">
						<a href="javascript:;">Name</a>
					</div>
				</div>
			</div>
			<div id="albums-list-item-1" class="left acenter" style="width: 250px">
				<div class="ml5 mr5" style="background: #eee">
					<div class="p5 aright">
						<a href="javascript:;">
							<img src="/ui/delete2.png" height="20" />
						</a>
					</div>
					<div style="height: 180px; background: url('/imgserve?pid=863&h=180') no-repeat center"></div>
					<div class="p5">
						<a href="javascript:;">Name</a>
					</div>
				</div>
			</div>
			<div id="albums-list-item-1" class="left acenter" style="width: 250px">
				<div class="ml5" style="background: #eee">
					<div class="p5 aright">
						<a href="javascript:;">
							<img src="/ui/delete2.png" height="20" />
						</a>
					</div>
					<div style="height: 180px; background: url('/imgserve?pid=863&h=180') no-repeat center"></div>
					<div class="p5">
						<a href="javascript:;">Name</a>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="mt10">
			<div id="albums-list-item-1" class="left acenter" style="width: 250px">
				<div class="mr5" style="background: #eee">
					<div class="p5 aright">
						<a href="javascript:;">
							<img src="/ui/delete2.png" height="20" />
						</a>
					</div>
					<div style="height: 180px; background: url('/imgserve?pid=863&h=180') no-repeat center"></div>
					<div class="p5">
						<a href="javascript:;">Name</a>
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
		
	</div>
	
</div>

<script type="text/javascript">
	
	var album = new Object();
	
	$(document).ready(function(){
		
		$('#show-window-add-album').click(function(){
			$('#window-add-album')
				.show()
				.css('opacity', '0')
				.animate({
					'opacity': '1',
					'top': parseInt($(window).scrollTop()+128)+'px'
				}, 256, function(){
					var act = 'add_album';
					if(typeof album.act != 'undefined')
						act = album.act;
					$('#window-add-album #aid').val(album.id);
					$('#window-add-album #act').val(act);
					load_form();
				});
		});
		
		<? if($show == 'add_album'){ ?>
			$('#show-window-add-album').click();
		<? } ?>
		
		$("#window-add-album input[id='cancel']").click(function(){
			$('#window-add-album').hide();
		});
		
		var init_albums_list = function(data)
		{
			$('#albums-list').html('');
			
			var counter = 0;
			var row = $('<div />');
			
			if(data.length == 0)
			{
				$(row)
					.addClass('acenter')
					.html('<?=t('Тут еще нет альбомов')?>.');
				$('#albums-list').append($(row));
				return true;
			}
			
			for(var key in data)
			{
				var item = $('<div />');
				
				var _class = 'mr5';
				if(counter > 0 && counter < 3)
					_class = 'ml5 mr5';
				else if(counter != 0)
					_class = 'ml5';
				
				$(item)
					.attr({
						'id': 'albums-list-item-'+data[key].id,
						'class': 'left acenter'
					})
					.width(250)
					.append(
						$('<div />')
							.attr({
								'class': _class
							})
							.css({
								'background': '#f5f6f7'
							})
//							.height(200)
							<? if(session::get_user_id() == $uid || session::has_credential('admin')){ ?>
							.append(
								$('<div />')
									.attr('class', 'p5 aright')
									.append(
										$('<a />').attr({
											'id': 'albums-list-item-modify-'+data[key].id,
											'href': 'javascript:;',
											'class': 'mr5'
										})
										.click(function(){
											var id = $(this).attr('id').split('-')[4]
											$.post('/albums', {
												'act': 'get_album',
												'aid': id
											}, function(resp){
												album = resp.album;
												album.act = 'modify_album';
												$('#show-window-add-album').click();
												//album = new Object();
											}, 'json');
										})
//										.html('Edit')
										.append(
												$('<img />')
													.attr('src', '/ui/edit2.png')
													.height(12)
											)
									)
									.append(
										$('<a />')
											.attr({
												'id': 'albums-list-item-remove-'+data[key].id,
												'href': 'javascript:;'
											})
											.click(function(){
												var id = $(this).attr('id').split('-')[4];
												remove_album(id);
											})
//											.html('Delete')
											.append(
												$('<img />')
													.attr('src', '/ui/delete2.png')
													.height(20)
											)
									)
							)
							<? } else { ?>
							.append(
								$('<div />')
									.addClass('p5')
									.css('height', '20px')
							)
							<? } ?>
							.append(
								$('<div />')
								.attr('id', 'open-album-'+data[key].id)
								.css({
									'height': '180px',
									'background': "url('"+data[key].image+"') no-repeat center",
									'cursor': 'pointer'
								})
								.click(function(){
									var id = $(this).attr('id').split('-')[2];
									window.location = '/albums/album?aid='+id+'&uid=<?=$uid?>';
								})
							)
							.append(
								$('<div />')
									.addClass('p5')
									.css('min-height', '20px')
									.append(
										$('<a />')
											.attr('href', '/albums/album?aid='+data[key].id+'&uid=<?=$uid?>')
											.html( ! data[key].name ? '<?=t('Открыть')?>' : data[key].name)
									)
									.append(
										$('<div />')
											.html( ! data[key].desc ? '' : data[key].desc)
									)
							)
					);
				
				$(row).append($(item));
				
				if(counter != 3)
					counter++;
				else
				{
					$(row).append(
						$('<div />').addClass('clear')
					);
					$('#albums-list').append($(row));
					row = $('<div />').addClass('mt10');
					counter = 0;
				}
			}
			if($(row).children('div').length < 5)
			{
				$(row).append(
					$('<div />').addClass('clear')
				);
				$('#albums-list').append($(row));
			}
		}
		
		var remove_album = function(aid)
		{
			if(confirm('<?=t('Точно удалить')?>?'))
			{
				$.post('/albums', {
					'act': 'remove_album',
					'aid': aid
				}, function(resp){
					if(resp.success)
						window.location = '/albums?uid=<?=$uid?><?= $category_key ? '&filter='.$category_key : '' ?>';
				}, 'json')
			}
		}
		
		var data = [
			<? foreach($albums as $album_id){ ?>
				<? $album = user_albums_peer::instance()->get_item($album_id); ?>
				<? $album['images'] = unserialize($album['images']); ?>
				{
					'id': '<?=$album['id']?>',
					'name': "<?=$album['name']?>",
					'desc': '<?=$album['description']?>',
					'image': '<?= $album['images'][0] ? "/imgserve?pid=".$album['images'][0]."&h=180" : "/no_image.png" ?>'
				},
			<? } ?>
		];
		init_albums_list(data);
		
		<? if(strpos($show, 'edit_album') !== false){ ?>
//			albums-list-item-modify-1
			var edit_album = '<?=$show?>';
			var id = edit_album.split('_')[2];
			$('#albums-list-item-modify-'+id).click();
		<? } ?>
	});
</script>