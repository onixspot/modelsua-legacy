<div>
	<div class="left aright mr10" style="width: 144px"><?=t('Тип')?>:</div>
	<div class="left">
		<div>
			<input type="radio" id="in_journal" name="type[]" checked />
			<label for="in_ukraine"><?=t('в журнале')?></label>
		</div>
		<div>
			<input type="radio" id="outdoor" name="type[]" />
			<label for="in_other_country"><?=t('наружная')?></label>
		</div>
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
		<select id="period_month">
			<option value="0">&mdash;</option>
			<option value="1"><?=t('Январь')?></option>
			<option value="2"><?=t('Февраль')?></option>
			<option value="3"><?=t('Март')?></option>
			<option value="4"><?=t('Апрель')?></option>
			<option value="5"><?=t('Май')?></option>
			<option value="6"><?=t('Июнь')?></option>
			<option value="7"><?=t('Июль')?></option>
			<option value="8"><?=t('Август')?></option>
			<option value="9"><?=t('Сентябрь')?></option>
			<option value="10"><?=t('Октябрь')?></option>
			<option value="11"><?=t('Ноябрь')?></option>
			<option value="12"><?=t('Декабрь')?></option>
		</select>
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
				$('#'+album.additional.type).attr('checked', 'checked');
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