<div class="small-title square_p pl10 mt10 mb10">
    <a href="/journals"><?=t('Журналы')?></a>
</div>

<div>
	<? $cnt = 0; ?>
	<? foreach($countries as $key => $value){ ?>
		<div class="mb10 left" style="width: 250px;">
			<div id="country-<?=$key?>" class="bold" style="cursor: pointer">
				<?=profile_peer::get_location(array("country" => $key))?>
			</div>
			<div id="block-journals-<?=$key?>" class="pt5 pb5 pl10">
				<? foreach($value as $id){ ?>
					<? $journal = journals_peer::instance()->get_item($id); ?>
					<div>
						<a class="fs18" href="/journals/?id=<?=$id?>"><?=$journal["name"]?></a>
					</div>
				<? } ?>
			</div>
		</div>
		<? if($cnt > 2){ ?>
			<div class="clear"></div>
			<? $cnt = 0; ?>
		<? } else { ?>
			<? $cnt++ ?>
		<? } ?>
	<? } ?>
	<div class="clear"></div>
</div>


<script type="text/javascript">
	$(document).ready(function(){
//		$("div[id^='country-']").click(function(){
//			var id = $(this).attr("id").split("-")[1];
//			
//			if($("#block-journals-"+id).is(":visible"))
//			{
//				$("#block-journals-"+id)
//					.animate({
//						"opacity": 0
//					}, 256, function(){
//						$(this).hide();
//					})
//			}
//			else
//			{
//				$("#block-journals-"+id)
//					.show()
//					.css("opacity", 0)
//					.animate({
//						"opacity": 1
//					}, 256);
//			}
//				
//			
//		});
	});
</script>