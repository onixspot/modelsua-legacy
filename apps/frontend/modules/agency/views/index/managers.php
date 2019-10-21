<div class="square_p pl15">
	<div class="left ucase bold"><a href="javascript:;" class="cblack">* <?=t("Менеджеры")?></a></div>
	<!--<div class="right"><a href="javascript:;" class="cgray">[<?=t('Редактировать')?>]</a></div>-->
	<div class="clear"></div>
</div>
<div id="block-agency-managers" class="mt10">
	<? foreach($managers_list as $id){ ?>
		<? $item = profile_peer::instance()->get_item($id); ?>
		<div><a href="/profile?id=<?=$id?>"><?=profile_peer::get_name($item)?></a></div>
	<? } ?>
</div>
<div id="block-agency-managers-empty" class="acenter p10 cgray" style="border: 1px dotted #ccc;"><?=t('Пусто')?></div>
<div class="mt5 cgray"></div>
<script type="text/javascript">
	$(document).ready(function(){
		if($('#block-agency-managers > div').length > 0)
			$('#block-agency-managers-empty').hide();
	});
</script>
