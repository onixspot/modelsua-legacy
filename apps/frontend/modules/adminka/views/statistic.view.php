<div class="left pt5 mb5 fs12">
	<?include 'admin_menu.php'?>
</div>

<div class="left mt5 fs12" style="width: 700px">
	<div class="p10 mb10 bold cwhite" style="background: #000000; border-radius: 4px;">Статистика</div>
	<div class="p10">
		<div>
			<div class="left" style="width: 200px;">Facebook:</div>
			<div class="left acenter" style="width: 50px"><?=($_cnt = $learned_about['facebook']['cnt']) ? $_cnt : $_cnt = 0?></div>
			<div class="left acenter" style="width: 50px"><? if($cnt > 0){ ?><?= number_format(($_cnt * 100 / $cnt), 1) ?><? } else { ?><?=$_cnt?><? } ?>%</div>
			<div class="clear"></div>
		</div>
		<div class="mt10">
			<div class="left" style="width: 200px;">Vkontakte:</div>
			<div class="left acenter" style="width: 50px"><?=($_cnt = $learned_about['vkontakte']['cnt']) ? $_cnt : $_cnt = 0?></div>
			<div class="left acenter" style="width: 50px"><? if($cnt > 0){ ?><?= number_format(($_cnt * 100 / $cnt), 1) ?><? } else { ?><?=$_cnt?><? } ?>%</div>
			<div class="clear"></div>
		</div>
		<div class="mt10">
			<div class="left" style="width: 200px;">Odnoklassniki:</div>
			<div class="left acenter" style="width: 50px"><?=($_cnt = $learned_about['odnoklassniki']['cnt']) ? $_cnt : $_cnt = 0?></div>
			<div class="left acenter" style="width: 50px"><? if($cnt > 0){ ?><?= number_format(($_cnt * 100 / $cnt), 1) ?><? } else { ?><?=$_cnt?><? } ?>%</div>
			<div class="clear"></div>
		</div>
		<div class="mt10">
			<div class="left" style="width: 200px;">Друзья, знакомые:</div>
			<div class="left acenter" style="width: 50px"><?=($_cnt = $learned_about['friends']['cnt']) ? $_cnt : $_cnt = 0?></div>
			<div class="left acenter" style="width: 50px"><? if($cnt > 0){ ?><?= number_format(($_cnt * 100 / $cnt), 1) ?><? } else { ?><?=$_cnt?><? } ?>%</div>
			<div class="clear"></div>
		</div>
		<div class="mt10">
			<div class="left" style="width: 200px;">Фирменные материалы, визитки:</div>
			<div class="left acenter" style="width: 50px"><?=($_cnt = $learned_about['banners']['cnt']) ? $_cnt : $_cnt = 0?></div>
			<div class="left acenter" style="width: 50px"><? if($cnt > 0){ ?><?= number_format(($_cnt * 100 / $cnt), 1) ?><? } else { ?><?=$_cnt?><? } ?>%</div>
			<div class="clear"></div>
		</div>
	</div>
</div>

<div class="clear"></div>
