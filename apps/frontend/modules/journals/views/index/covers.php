<div class="square_p pl15">
	<div class="left ucase bold"><a href="javascript:void(0);" class="cblack"><?=t("Обложки")?></a></div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<? if(count($journal["covers"]) > 0){ ?>
		<ul id="agency-models-list">
			<? $cnt = 0; ?>
			<? foreach($journal["covers"] as $id){ ?>
				<? $user_photo = user_photos_peer::instance()->get_item($id); ?>
				<? $images = array(); ?>
				<? $images[] = $id ?>
				<? $user_album = user_albums_peer::instance()->get_list(array("images" => $images)); ?>
				<? $user_data = profile_peer::instance()->get_item($user_photo["user_id"]) ?>
				<li id="journal-covers-item-<?=$id?>" class="left p0" style="margin-right: <? if($cnt < 4){ ?>10<? } else { ?>0<? } ?>px; margin-bottom: 10px; width: 114px; cursor: pointer;">
					<div id="journal-covers-item-tooltip-<?=$id?>" class="cwhite fs14 p10 hide" style="position: absolute; background: black; border-radius: 5px; z-index: 999;"><?=profile_peer::get_name($user_data)?></div>
					<div 
						id="journal-covers-item-cover-<?=$id?>" 
						uid="<?=$user_photo["user_id"]?>"
						aid="<?=$user_album[0]?>"
						style="background: url('/imgserve?pid=<?=$id?>&w=114') 100% no-repeat; height: 145px;"
					></div>
				</li>
				<? if($cnt > 3){ ?>
					<? $cnt = 0 ?>
				<? } else { ?>
					<? $cnt++ ?>
				<? } ?>
			<? } ?>
			<li class="clear"></li>
		</ul>
	<? } else { ?>
		<div id="block-journal-about-empty" class="acenter p10 cgray hide" style="border: 1px dotted #ccc; display: block; ">Пусто</div>
	<? } ?>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("div[id^='journal-covers-item-cover']").click(function(){
			var pid = $(this).attr("id").split('-')[4];
			var aid = $(this).attr("aid");
			var uid = $(this).attr("uid");
			
			var ntab = window.open('', "_blank");
			ntab.location = "/albums/album?aid="+aid+"&uid="+uid+"&show=viewer&pid="+pid;
		})
		.mouseover(function(){
			var id = $(this).attr('id').split('-')[4];
			$('#journal-covers-item-tooltip-'+id).show();
		})
		.mouseout(function(){
			var id = $(this).attr('id').split('-')[4];
			$('#journal-covers-item-tooltip-'+id).hide();
		})
		.mousemove(function(evn){
			var id = $(this).attr('id').split('-')[4];

			var x = evn.clientX + $(window).scrollLeft() + 16;
			var y = evn.clientY + $(window).scrollTop() + 16;

			$('#journal-covers-item-tooltip-'+id)
				.css({
					'left': x+'px',
					'top': y+'px'
				});
		});
	});
</script>