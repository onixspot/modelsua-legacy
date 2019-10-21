<div>
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Название')?>:</div>
	<div class="left">
		<input type="text" id="name" style="width: 164px" />
	</div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Бренд')?>:</div>
	<div class="left">
		<input type="text" id="brand" style="width: 164px" />
	</div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Компания')?>:</div>
	<div class="left">
		<input type="text" id="company" style="width: 164px" />
	</div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Период')?>:</div>
	<div class="left mr5">
		<?$mounth = ui_helper::get_mounth_list();?>
		<?=tag_helper::select('period_month', $mounth,array('id'=>'period_month'));?>
	</div>
	<div class="left">
		<select id="period_year">
			<option value="0">&mdash;</option>
			<? for($i = 0; $i < 30; $i++){ ?>
				<option value="<?=(date('Y')-$i)?>">
					<?=(date('Y')-$i)?>
				</option>
			<? } ?>
		</select>
	</div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Визажист')?>:</div>
	<div class="left">
		<input type="text" id="visagist" style="width: 164px" />
	</div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Стилист')?>:</div>
	<div class="left">
		<input type="text" id="stylist" style="width: 164px" />
	</div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Фотограф')?>:</div>
	<div class="left">
		<input type="text" id="photographer" style="width: 164px" />
	</div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Дизайнер(-ы) одежды')?>:</div>
	<div class="left">
		<input type="text" id="designer" style="width: 164px" />
	</div>
	<div class="clear"></div>
</div>

<script type="text/javascript">
	var load_form;
	$(document).ready(function(){
		
		load_form = function(){
			if(typeof album.additional != 'undefined')
			{
				$('#name').val(album.additional.name);
				$('#brand').val(album.additional.brand);
				$('#company').val(album.additional.company);
				$('#period_month').val(album.additional.period_month);
				$('#period_year').val(album.additional.period_year);
				$('#visagist').val(album.additional.visagist);
				$('#stylist').val(album.additional.stylist);
				$('#photographer').val(album.additional.photographer);
				$('#designer').val(album.additional.designer);
				album = new Object();
			}
		}
		
	});
</script>