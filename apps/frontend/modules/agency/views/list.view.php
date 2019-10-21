<div class="small-title square_p pl10 mt10 mb10">
    <a href="/"><?=t('Агенства')?></a>
</div>
<ul id="sortable" class="connectedSortable">
    <? if(count($list) > 0){ ?>
		<? foreach($list as $aid => $pids){ ?>
			<? $agency = agency_peer::instance()->get_item($aid); ?>
			<li class="grag-end-drop" id="<?=$agency['id']?>" rel="<?=$pos+100?>">
				<a class="fs18" href="/agency/?id=<?=$aid?>"/><?=$agency['name']?> - <?=count($pids)?></a>
				<div>
					<? $cnt = 0; ?>
					<? foreach($pids as $pid){ ?>
						<? if($cnt > 19){ break; } ?>
						<? $user_data = profile_peer::instance()->get_item(db::get_scalar("SELECT user_id FROM user_data WHERE pid = ".$pid)); ?>
						<div id="agency-models-item-<?=$user_data["user_id"]?>" class="left mr5 mt5" style="width: 45px; height: 60px; background: url('/imgserve?pid=<?=$pid?>&h=60') no-repeat center">
							<div id="agency-models-item-tooltip-<?=$user_data["user_id"]?>" class="cwhite fs14 p10 hide" style="position: absolute; background: black; border-radius: 5px; z-index: 999;"><?=profile_peer::get_name($user_data)?></div>
						</div>
						<? $cnt++ ?>
					<? } ?>
					<div class="clear"></div>
				</div>
			</li>
		<? } ?>
	<? } ?>
</ul>

<script type="text/javascript">
	$(document).ready(function(){
		$("div[id^='agency-models-item']")
			.mouseover(function(){
				var id = $(this).attr('id').split('-')[3];
				$('#agency-models-item-tooltip-'+id).show();
			})
			.mouseout(function(){
				var id = $(this).attr('id').split('-')[3];
				$('#agency-models-item-tooltip-'+id).hide();
			})
			.mousemove(function(evn){
				var id = $(this).attr('id').split('-')[3];
				
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
				var newWindow = window.open('/profile/?id='+id, '_blank');
				newWindow.focus();
			});
	});
</script>