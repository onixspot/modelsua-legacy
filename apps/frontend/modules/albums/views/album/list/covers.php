<div class="aleft square_p pl15 mt20">
	<div class="left ucase bold cblack">
		<?=t('Работы')?>
	</div>
	<div class="clear"></div>
</div>
<div class="mb30">
	<div class="left bold fs18">
		<a href="/albums/album?aid=<?=$aid?>&uid=<?=$uid?>"><?=t('Обложки')?></a>
	</div>
	<div class="clear"></div>
</div>

<div id="photos-list">
	
	<? $counter = 0; ?>
	<? $pt = 0; ?>
	<? $pb = 30; ?>
	<? $orient = 'left'; ?>
	<? $row_style = ''; ?>
	<? $item_style = ''; ?>
	<? foreach($album['images'] as $pid){ ?>
		
		<? $photo = user_photos_peer::instance()->get_item($pid); ?>
		<? $photo['additional'] = unserialize($photo['additional']); ?>
		<? $name = $photo['additional']['journal_name']." №".$photo['additional']['journal_number'].', '.mb_strtolower(date_peer::instance()->get_month($photo['additional']['journal_month'])).' '.$photo['additional']['journal_year']; ?>
	
		<? if($counter == 0){ ?>
			<div class="pt<?=$pt?>" style="<?=$row_style?>">
		<? } ?>

			<div id="photos-list-item-<?=$pid?>" class="<?=$orient?> acenter" style="<?=$item_style?>">
				<div>
					<? if(session::get_user_id() == $uid || session::has_credential('admin')){ ?>
						<div class="aright">
							<? if(in_array($category_key, array('covers'))){ ?>
								<a id="photos-list-item-modify-<?=$pid?>" class="mr5" href="javascript:;">
									<img src="/ui/edit2.png" height="12" />
								</a>
							<? } ?>
							<a id="photos-list-item-remove-<?=$pid?>" href="javascript:;">
								<img src="/ui/delete2.png" height="20" />
							</a>
						</div>
					<? } ?>
					<div>
						<div class="left mr10 pb<?=$pb?>" style="width: 270px; border: 1px solid #eee;">
							<div id="photos-list-item-photo-<?=$pid?>" style="height: 355px; background: url('/imgserve?pid=<?=$pid?>&h=420') no-repeat center; cursor: pointer;"></div>
						</div>
						<div class="left aleft" style="width: 189px">
							<div class="mb5 fs18 bold" style="color: #000000">
								<a href='javascript:;' onclick="$('#photos-list-item-photo-<?=$pid?>').click()"><?=$photo['name']?></a>
							</div>
							<div>
								<? if($photo['additional']['photographer'] != '') { ?>
									<span class="cgray"><?=t('Фотограф')?>: </span><span><?=$photo['additional']['photographer']?></span><br />
								<? } ?>
								<? if($photo['additional']['visagist'] != '') { ?>
									<span class="cgray"><?=t('Визажист')?>: </span><span><?=$photo['additional']['visagist']?></span><br />
								<? } ?>
								<? if($photo['additional']['stylist'] != '') { ?>
									<span class="cgray"><?=t('Стилист')?>: </span><span><?=$photo['additional']['stylist']?></span><br />
								<? } ?>
								<? if($photo['additional']['designer'] != '') { ?>
									<span class="cgray"><?=t('Одежда')?>: </span><span><?=$photo['additional']['designer']?></span><br />
								<? } ?>
							</div>
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		
		<? if($counter == 1){ ?>
				<div class="clear"></div>
			</div>
			<? $item_style = ''; ?>
			<? $orient = 'left'; ?>
			<? $counter = 0; ?>
			<? $pt = 30; ?>
			<? $row_style = 'padding-top: 30px; border-top: 1px solid #eee; '; ?>
		<? } else { ?>
			<? $item_style = 'padding-left: 30px; border-left: 1px solid #eee;'; ?>
			<? $orient = 'right'; ?>
			<? $counter++ ?>
		<? } ?>
	
	<? } ?>
	
	<? if($counter <= 1){ ?>
			<div class="clear"></div>
		</div>
	<? } ?>
	
</div>
