<div>
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Название журнала')?>:</div>
	<div class="left">
		<input type="text" id="journal_name" style="width: 146px; position: absolute; z-index: 999;" />
		<select id="journal_id" style="width: 164px">
			<option value="0">&mdash;</option>
			<? foreach($journals_list as $id){ ?>
				<? $journal = journals_peer::instance()->get_item($id); ?>
				<option value="<?=$id?>"><?=$journal["name"]?> <?=profile_peer::get_location($journal)?></option>
			<? } ?>
			<option value="-1"><?=t("Другой")?></option>
		</select>
	</div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<div class="left aright mr10" style="width: 144px"><?=t('Напечатан')?>:</div>
	<div class="left">
		<div>
			<input type="radio" id="in_ukraine" name="printed[]" checked />
			<label for="in_ukraine"><?=t('в Украине')?></label>
		</div>
		<div>
			<input type="radio" id="in_other_country" name="printed[]" />
			<label for="in_other_country"><?=t('в другой стране')?></label>
		</div>
		<div>
			<input type="radio" id="in_few_countries" name="printed[]" />
			<label for="in_few_countries"><?=t('в нескольких стран')?></label>
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Номер, месяц, год')?>:</div>
	<div class="left mr5">
		<input type="text" id="journal_number" style="width: 32px;" />
	</div>
	<div class="left mr5">
		<?$mounth = ui_helper::get_mounth_list();?>
		<?=tag_helper::select('journal_month', $mounth,array('id'=>'journal_month'));?>
	</div>
	<div class="left">
		<select id="journal_year">
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
<div class="mt10 hide">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Дизайнер(-ы) одежды')?>:</div>
	<div class="left">
		<input type="text" id="designer" style="width: 164px" />
	</div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px">Fashion story <?=t('в интернете')?>:</div>
	<div class="left">
		<div>
			<input type="text" id="link" style="width: 164px" />
		</div>
		<div class="fs10 cgray">
			<?=t('Вставте ссылку. Пример')?>: http://www.story.com.ua
		</div>
	</div>
	<div class="clear"></div>
</div>

<script type="text/javascript">
	var load_form;
	$(document).ready(function(){
		
		load_form = function(){
			if(typeof album.additional != 'undefined')
			{
				if(typeof album.additional.journal_id != 'undefined' && album.additional.journal_id != -1)
				{
					$('#journal_id').val(album.additional.journal_id);
				}
				else
				{
					$('#journal_name')
						.show()
						.val(album.additional.journal_name);
				}
				$('#'+album.additional.printed).attr('checked', 'checked');
				$('#journal_number').val(album.additional.journal_number);
				$('#journal_month').val(album.additional.journal_month);
				$('#visagist').val(album.additional.visagist);
				$('#stylist').val(album.additional.stylist);
				$('#photographer').val(album.additional.photographer);
				$('#designer').val(album.additional.designer);
				$('#link').val(album.additional.link);
				album = new Object();
			}
		}
		
		$("#journal_id").change(function(){
			var state = $(this).val();
			
			if(state == -1)
			{
				$("#journal_name")
					.show()
					.val("")
					.focus();
			}
			else
			{
				$("#journal_name")
					.val($("option[value='"+state+"']", this).html())
					.hide();
			}
		});
		
		$("#journal_name").blur(function(){
			var state = $(this).val();
			
			if(state == '')
			{
				$(this).hide();
				$("#journal_id").val(0);
			}
		});
		
	});
</script>