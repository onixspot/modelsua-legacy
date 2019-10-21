<div>
	<div class="left mr10 p5"<? if($filter == ''){ ?> style="background: #eee;"<? } ?>>
		<? if($filter != ''){ ?>
			<a href="?id=<?=$agency['id']?>"><?=t('Все модели агентства')?></a>
		<? } else { ?>
			<?=t('Все модели агентства')?>
		<? } ?>
	</div>
    <? if(session::has_credential('admin')) { ?>
	<div class="left mr10 p5"<? if($filter == 'successful'){ ?> style="background: #eee;"<? } ?>>
		<? if($filter != 'successful'){ ?>
			<a href="?id=<?=$agency['id']?>&filter=successful"><?=t('Успешные')?></a>
		<? } else { ?>
			<?=t('Успешные')?>
		<? } ?>
	</div>
	<div class="left mr10 p5"<? if($filter == 'perspective'){ ?> style="background: #eee;"<? } ?>>
		
		<? if($filter != 'perspective'){ ?>
			<a href="?id=<?=$agency['id']?>&filter=perspective"><?=t('Перспективные')?></a>
		<? } else { ?>
			<?=t('Перспективные')?>
		<? } ?>
	</div>
	<div class="left mr10 p5"<? if($filter == 'new_faces'){ ?> style="background: #eee;"<? } ?>>
		<? if($filter != 'new_faces'){ ?>
			<a href="?id=<?=$agency['id']?>&filter=new_faces"><?=t('Новые лица')?></a>
		<? } else { ?>
			<?=t('Новые лица')?>
		<? } ?>
	</div>
    <? } ?>
	<div class="clear"></div>
</div>
<div class="mt10">
	<ul id="agency-models-list">
		<? foreach($models_list as $id){ ?>
			<? $item = profile_peer::instance()->get_item($id); ?>
			<li id="agency-models-item-<?=$id?>" class="left p0" style="margin-right: 1px; margin-bottom: 1px; width: 100px; cursor: pointer;">
				<div id="agency-models-item-tooltip-<?=$id?>" class="cwhite fs14 p10 hide" style="position: absolute; background: black; border-radius: 5px; z-index: 999;"><?=profile_peer::get_name($item)?></div>
				<? if(count($item['crop']) > 0){ ?>
					<img src="/imgserve?pid=<?=$item['pid']?>&x=<?=$item['crop']['x']?>&y=<?=$item['crop']['y']?>&w=<?=$item['crop']['w']?>&h=<?=$item['crop']['h']?>&z=crop" style="width: 100px; height: 100px;">
				<? } else { ?>
					<img src="/no_image.png" style="width: 100px; height: 100px;">
				<? } ?>
			</li>
		<? } ?>
		<li class="clear"></li>
	</ul>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		var mouseDown = false;
		
		$("li[id^='agency-models-item']")
			.mouseover(function(){
				var id = $(this).attr('id').split('-')[3];
				$('#agency-models-item-tooltip-'+id).show();
			})
			.mouseout(function(){
				var id = $(this).attr('id').split('-')[3];
				$('#agency-models-item-tooltip-'+id).hide();
			})
			<? if($access){ ?>
			.mouseup(function(){
				mouseDown = false;
			})
			.mousedown(function(){
				mouseDown = true;
			})
			<? } ?>
			.mousemove(function(evn){
				var id = $(this).attr('id').split('-')[3];
				
				if(mouseDown != false)
				{
					$('#agency-models-item-tooltip-'+id).hide();
					return;
				}
				
				var x = evn.clientX + $(window).scrollLeft() + 16;
				var y = evn.clientY + $(window).scrollTop() + 16;
				
				$('#agency-models-item-tooltip-'+id)
					.css({
						'left': x+'px',
						'top': y+'px'
					});
			})
			.click(function(){
				var id = $(this).attr('id').split('-')[3];
				window.location = '/profile/?id='+id;
			});
		
		<? if($access && $filter == ''){ ?>
			$('#agency-models-list').sortable({
				items: 'li',
				stop: function(evn, ui)
				{
					var rank = new Array();
					$.each($('#agency-models-list > li'), function(){
						if(typeof $(this).attr('id') == 'undefined')
							return;
						
						var id = $(this).attr('id').split('-')[3];
						rank.push(id);
					});
					
					$.post('/agency', {
						'act': 'set_agency_rank',
						'rank': rank
					}, function(response){
//						console.log(response);
					}, 'json');
				}
			});
		<? } ?>
	});
</script>