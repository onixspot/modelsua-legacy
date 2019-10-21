<div class="fs11" style="width: 1000px">
	
	<div class="p5 mb10" style="background: #eee; border-top: 1px solid #ccc">
		<div class="left">
			<a href="/profile?id=<?=$uid?>"><?=profile_peer::get_name($profile)?></a>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="aleft square_p pl15 mt20">
		<div class="left ucase bold cblack">
			<?=t('Работы')?>
		</div>
		<div class="clear"></div>
	</div>
	
	<? $g_counter = 0 ?>
	<? foreach($categories as $category_key => $category){ ?>
		<? include 'works/work.php'; ?>
		<? $g_counter++ ?>
	<? } ?>
	
</div>