<div id="albums-list">
	
	<? $counter = 0; ?>
	<? $mt = 0; ?>
	<? foreach($albums_list as $aid){ ?>
		<? $album = agency_albums_peer::instance()->get_item($aid); ?>
	
		<? if($counter == 0){ ?>
			<div class="mt<?=$mt?>">
		<? } ?>
				
		<div class="left acenter" style="width: 250px;">
			<div class="p5">
				<? if($access){ ?>
					<div class="aright">
						<a id="albums-list-item-modify-<?=$aid?>" href="javascript:void(0);" class="mr5">
							<img src="/ui/edit2.png" style="height: 12px; ">
						</a>
						<a id="albums-list-item-remove-<?=$aid?>" href="javascript:void(0);">
							<img src="/ui/delete2.png" style="height: 20px; ">
						</a>
					</div>
				<? } ?>
				<? $img_link = ($album['images'][0] > 0) ? "/imgserve?pid=".$album['images'][0]."&h=200" : "/no_image.png" ; ?>
				<div id="albums-list-item-<?=$aid?>" style="background: url('<?=$img_link?>') no-repeat center; width: 240px; height: 200px; cursor: pointer;"></div>
				<div class="mt5"><a href="/agency/album?aid=<?=$aid?>"><?=$album['additional']['name']?></a></div>
				<div class="mt5 fs10"><?=$album['additional']['description']?></div>
			</div>
		</div>
		
		<? if($counter == 3){ ?>
				<div class="clear"></div>
			</div>
			<? $counter = 0; ?>
			<? $mt = 10; ?>
		<? } else { ?>
			<? $counter++ ?>
		<? } ?>
	
	<? } ?>
	
	<? if($counter <= 3){ ?>
			<div class="clear"></div>
		</div>
	<? } ?>

</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		$("div[id^='albums-list-item-']").click(function(){
			var id = $(this).attr('id').split('-')[3];
			window.location = '/agency/album?aid='+id;
		});
		
		$("a[id^='albums-list-item-modify']").click(function(){
			var id = $(this).attr('id').split('-')[4];
			modify_album(id);
		});
		
		$("a[id^='albums-list-item-remove']").click(function(){
			var id = $(this).attr('id').split('-')[4];
			remove_album(id);
		});
		
		var modify_album = function(aid)
		{
			$.post('/agency/albums', {
				'act': 'get_album',
				'aid': aid
			}, function(response){
				if( ! response.success)
					return;
				
				data = response.data;
				data.aid = aid;
				data.act = 'modify_album';
				$('#show-window-add-album').click();
			}, 'json')
		}
		
		<? if(strpos($show, 'edit_album') !== false){ ?>
			<? $id = ($tokens = explode('_', $show)) ? $tokens[2] : 0; ?>
			modify_album(<?=$id?>);
		<? } ?>
		
		var remove_album = function(aid)
		{
			if(confirm('<?=t('Вы действительно хотите удалить альбом')?>?'))
			{
				$.post('/agency/albums', {
					'act': 'remove_album',
					'aid': aid
				}, function(response){
					if(response.success)
						window.location = '/agency/albums?id=<?=$agency['id']?>'
				}, 'json')
			}
		}
		
	});
</script>
	
