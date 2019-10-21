<div>
	<div id="preview" class="hide acenter" style="width: 256px; margin: 0px auto; background: #eee; border: 1px solid #ccc;"></div>
</div>
<div class="acenter mt10 hide">
	<input type="text" id="pid" />
	<input type="text" id="old_pid" />
</div>
<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Название журнала')?>:</div>
	<div class="left">
		<input type="text" id="journal_name" class="hide" style="width: 146px; position: absolute; z-index: 999;" />
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
			<label for="in_few_countries"><?=t('в нескольких странах')?></label>
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
		<select id="journal_month">
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
<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Дизайнер(-ы) одежды')?>:</div>
	<div class="left">
		<input type="text" id="designer" style="width: 164px" />
	</div>
	<div class="clear"></div>
</div>
<div class="mt10">
	<div class="left aright mr10 mt5" style="width: 144px"><?=t('Обложка в интернете')?>:</div>
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
<div class="mt10 acenter">
	<input type="file" class="hide" id="uploadify" />
</div>

<script type="text/javascript">
	var load_form;
	
	$(document).ready(function(){
		
		$('#window-add-photo #submit').attr('disabled', true);
		
		load_form = function(){
			if(typeof photo.additional != 'undefined')
			{
				if(typeof photo.additional.journal_id != 'undefined' && photo.additional.journal_id != -1)
				{
					$('#journal_id').val(photo.additional.journal_id);
				}
				else
				{
					$('#journal_name')
						.show()
						.val(photo.additional.journal_name);
				}
				$('#'+photo.additional.printed).attr('checked', 'checked');
				$('#journal_number').val(photo.additional.journal_number);
				$('#journal_month').val(photo.additional.journal_month);
				$('#journal_year').val(photo.additional.journal_year);
				$('#visagist').val(photo.additional.visagist);
				$('#stylist').val(photo.additional.stylist);
				$('#photographer').val(photo.additional.photographer);
				$('#designer').val(photo.additional.designer);
				$('#link').val(photo.additional.link);
				photo = new Object();
			}
		}
		
		$('#uploadify').uploadify({
			'uploader': '/uploadify.swf',
			'script': '/imgserve',
			'fileDataName': 'image',
			'buttonImg': '/buttons/upload_cover.png',
			'width': '153',
			'scriptData': 
			{
				'act': 'upload',
				'uid': '<?=$uid?>',
				'key': 'image'
			},
			'cancelImg': '/cancel.png',
			'transparent': true,
			'folder': '/',
			'fileDesc': 'jpg; gif; png; jpeg;',
			'fileExt': '*.jpg;*.gif;*.png;*.jpeg;',
			'auto': true,
			'multi': false,
			'onError': function(event, queueID, fileObj, response)
			{
			},
			'onSelectOnce': function()
			{
				$('#blind-wait').show();
				$('#blind-wait').width($('#window-add-photo').width());
				$('#blind-wait').height($('#window-add-photo').height());
			},
			'onComplete': function(event, queueID, fileObj, pid, data)
			{
				$('<img />')
					.attr('src', '/imgserve?pid='+pid+'&w='+$('#preview').width())
					.load(function(){
						$('#pid').val(pid);
						$('#preview')
							.html('')
							.append(
								$(this)
							)
							.css({
								'opacity': '0'
							})
							.show()
							.animate({
								'opacity': '1'
							}, 256, function(){
								$('#window-add-photo #submit').attr('disabled', false);
								$('#blind-wait').hide();
							});
					});

			}
		});
		
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