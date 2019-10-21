<div class="mb20">
	<div class="mb5">
		<div class="left bold fs18">
			<? if(in_array($category_key, array('covers'))){ ?>
				<a href="/albums/album?aid=<?=$works[$category_key][0]['id']?>&uid=<?=$uid?>"><?=$category?></a>
			<? } else { ?>
				<a href="/albums?filter=<?=$category_key?>&uid=<?=$uid?>"><?=$category?></a>
			<? } ?>
		</div>
		<div class="mt10 right">
			<? if(in_array($category_key, array('covers'))){ ?>
				<a class="underline" href="/albums/album?aid=<?=$works[$category_key][0]['id']?>&uid=<?=$uid?>"><?=t('Смотреть все')?></a>
			<? } else { ?>
				<a class="underline" href="/albums?filter=<?=$category_key?>&uid=<?=$uid?>">Смотреть все</a>
			<? } ?>
		</div>
		<? if(session::get_user_id() == $uid || session::has_credential('admin')){ ?>
			<div class="mt10 mr10 right">
				<? if(in_array($category_key, array('covers'))){ ?>
					<a class="underline" href="/albums/album?aid=<?=$works[$category_key][0]['id']?>&uid=<?=$uid?>&show=add_photo">
						Добавить
					</a>
				<? } else { ?>
					<a class="underline" href="/albums?filter=<?=$category_key?>&uid=<?=$uid?>&show=add_album"><?=t('Добавить')?></a>
				<? } ?>
			</div>
		<? } ?>
		<div class="clear"></div>
	</div>
	
	<? if(count($works[$category_key][0]['images']) > 0){ ?>
	<div>
		<? for($i = 0; $i < 5; $i++){ // count($works[$category_key]) ?>
			<div class="left" style="width: 188px">
				<div
					class="mb10"
					style="height: 250px; background: url('/imgserve?pid=<?=$works[$category_key][$i]['images'][0]?>&h=300') no-repeat center; cursor: pointer"
					onclick="
						window.location = '/albums/album?aid=<?=$works[$category_key][$i]['id']?>&uid=<?=$uid?>';
					"
				></div>
				<div class="acenter">
					<a href='/albums/album?aid=<?=$works[$category_key][$i]['id']?>&uid=<?=$uid?>'><?=$works[$category_key][$i]['name']?></a>
				</div>
			</div>
			<? if($i < 4){ ?>
				<div class="left" style="width: 15px; height: 250px;"></div>
			<? } ?>
		<? } ?>
		<div class="clear"></div>
	</div>
	<? } else { ?>
	<div class="acenter">
		Тут еще нет работ.
	</div>
	<? } ?>
</div>
<? if(count($categories) - 1 > $g_counter){ ?>
	<div class="mt20">
		<hr style="border-color: #eee;" />
	</div>
<? } ?>