<div class="square_p pl15">
	<div class="left ucase bold"><a href="javascript:void(0);" class="cblack"><?=t("Fashion stories")?></a></div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<? if(count($journal["fashion"]) > 0){ ?>
		<ul id="agency-models-list">
			<? foreach($journal["fashion"] as $id){ ?>
				<? $user_album = user_albums_peer::instance()->get_item($id); ?>
				<li id="journal-fashion-item-<?=$id?>" class="left p0" style="margin-right: 1px; margin-bottom: 1px; width: 100px; cursor: pointer;">
					<div 
						id="journal-fashion-item-fashion-<?=$user_album["_i"][0]?>" 
						uid="<?=$user_album["user_id"]?>"
						aid="<?=$id?>"
						style="background: url('/imgserve?pid=<?=$user_album["_i"][0]?>&w=100') 100% no-repeat; height: 125px;"
					></div>
				</li>
			<? } ?>
			<li class="clear"></li>
		</ul>
	<? } else { ?>
		<div id="block-journal-about-empty" class="acenter p10 cgray hide" style="border: 1px dotted #ccc; display: block; ">Пусто</div>
	<? } ?>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("div[id^='journal-fashion-item-fashion']").click(function(){
			var pid = $(this).attr("id").split('-')[4];
			var aid = $(this).attr("aid");
			var uid = $(this).attr("uid");
			
			var ntab = window.open('', "_blank");
			ntab.location = "/albums/album?aid="+aid+"&uid="+uid+"&show=viewer&pid="+pid;
		});
	});
</script>