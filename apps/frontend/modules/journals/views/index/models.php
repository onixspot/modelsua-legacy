<div class="mt10">
	<ul id="journal-models-list">
		<? foreach($journal["models"] as $id){ ?>
			<? $item = profile_peer::instance()->get_item($id); ?>
			<li id="journal-models-item-<?=$id?>" class="left p0" style="margin-right: 1px; margin-bottom: 1px; width: 100px; cursor: pointer;">
				<div id="journal-models-item-tooltip-<?=$id?>" class="cwhite fs14 p10 hide" style="position: absolute; background: black; border-radius: 5px; z-index: 999;"><?=profile_peer::get_name($item)?></div>
				<? if(count($item['crop']) > 0){ ?>
					<img src="/imgserve?pid=<?=$item['pid']?>&x=<?=$item['crop']['x']?>&y=<?=$item['crop']['y']?>&w=<?=$item['crop']['w']?>&h=<?=$item['crop']['h']?>&z=crop" style="width: 100px; height: 100px;" />
				<? } else { ?>
					<img src="/no_image.png" style="width: 100px; height: 100px;" />
				<? } ?>
			</li>
		<? } ?>
		<li class="clear"></li>
	</ul>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		var mouseDown = false;
		
		$("li[id^='journal-models-item']")
			.mouseover(function(){
				var id = $(this).attr('id').split('-')[3];
				$('#journal-models-item-tooltip-'+id).show();
			})
			.mouseout(function(){
				var id = $(this).attr('id').split('-')[3];
				$('#journal-models-item-tooltip-'+id).hide();
			})
			.mousemove(function(evn){
				var id = $(this).attr('id').split('-')[3];
				
				if(mouseDown != false)
				{
					$('#journal-models-item-tooltip-'+id).hide();
					return;
				}
				
				var x = evn.clientX + $(window).scrollLeft() + 16;
				var y = evn.clientY + $(window).scrollTop() + 16;
				
				$('#journal-models-item-tooltip-'+id)
					.css({
						'left': x+'px',
						'top': y+'px'
					});
			})
			.click(function(){
				var id = $(this).attr('id').split('-')[3];
				window.location = '/profile/?id='+id;
			});
	});
</script>