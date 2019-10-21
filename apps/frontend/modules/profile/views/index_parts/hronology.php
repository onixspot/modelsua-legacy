<div class="mt30 fs12">
	<div>
		<div class="left square_p pl15 mb10 fs12 ucase bold">
			<a class="cblack" href='javascript:;'><?=t("Работы")?></a>
		</div>
		<? if(session::get_user_id() == $user_id || session::has_credential('admin')){ ?>
			<div class="right">
				<div>
					<a
						class="underline cgray"
						href="javascript:;"
						onmouseover="$(this).css('color', 'black')" onmouseout="$(this).css('color', 'gray')"
						onclick="
							if($('#window-categories_h').is(':visible'))
								$('#window-categories_h').animate({
									'opacity': '0'
								}, 256, function(){
									$(this).hide();
								});
							else
								$('#window-categories_h')
									.show()
									.css('opacity', '0')
									.animate({
										'opacity': '1'
									}, 256);
						"><?=t('Добавить работу')?></a>
				</div>
				<div id="window-categories_h" class="pb10 pl5 mt5 pr5 hide" style="position: absolute; border: 1px solid gray; background: #fff; box-shadow: 0px 0px 5px #aaa; margin-left: -34px">
					<? foreach($works as $category_key => $work){ ?>
						<? if(in_array($category_key, array('portfolio'))){ ?>
							<? continue; ?>
						<? } ?>
						<div class="pt5">
							<? if(in_array($category_key, array('covers'))){ ?>
								<a class="underline cgray" onmouseover="$(this).css('color', 'black')" onmouseout="$(this).css('color', 'gray')" href='/albums/album?aid=<?=$albums[$category_key][0]['id']?>&uid=<?=$user_id?>&show=add_photo'><?=$work?></a>
							<? } else { ?>
								<a class="underline cgray" onmouseover="$(this).css('color', 'black')" onmouseout="$(this).css('color', 'gray')" href='/albums?filter=<?=$category_key?>&uid=<?=$user_id?>&show=add_album'><?=$work?></a>
							<? } ?>
						</div>
					<? } ?>
				</div>
			</div>
		<? } ?>
		<div class="clear"></div>
	</div>
	<? $flag = false; ?>
	<? $cnt = 0; ?>
	<? foreach($hronologies as $hronology){ ?>
		<? if($hronology['name'] == ''){ ?>
			<? continue; ?>
		<? } ?>
		<? $flag = true; ?>
		<div class="pt10 pb10" style="<? if($cnt > 0){ ?>border-top: 1px solid #eee<? } ?>">
			<div class="left" style="width: 300px">
				<div><?=t($hronology['category'])?> :: <a href='<?=$hronology['link']?>'><?=$hronology['name']?></a></div>
				<!--<div class="fs10 cgray">Австрия</div>-->
			</div>
			<div class="right cgray">
				<? if($hronology['month'] > 0 && $hronology['year'] > 0){ ?>
					<?=(ui_helper::get_mounth_list($hronology['month']))?>, <?=$hronology['year']?>
				<? } else { ?>
					&mdash;
				<? } ?>
			</div>
			<div class="clear"></div>
		</div>
		<? $cnt++ ?>
	<? } ?>
	<? if( ! $flag){ ?>
		<div class="left acenter cgray mb30" style="width: 400px; height: 57px; background: #eee; padding-top: 45px;">
			<?=t("Тут еще нет работ")?>
		</div>
	<? } ?>
</div>
