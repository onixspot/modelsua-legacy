<div class="square_p pl15">
	<div class="left ucase bold"><a href="javascript:;" class="cblack"><?=t("Об агентстве")?></a></div>
	<? if($access){ ?>
		<div class="right"><a href="javascript:;" id="agency-about-button-edit" class="cgray">[<?=t('Редактировать')?>]</a></div>
	<? } ?>
	<div class="clear"></div>
</div>
<div id="block-agency-about" class="mt5 cgray ajustify"><?=$agency['about']?></div>
<div id="block-agency-about-empty" class="acenter p10 cgray hide" style="border: 1px dotted #ccc;"><?=t('Пусто')?></div>
<div id="block-agency-about-edit" class="mt10 hide">
	<div>
		<textarea id="agency-about-textarea" style="width: 370px; height: 100px"><?=$agency['about']?></textarea>
	</div>
	<div class="mt10 aright">
		<input type="button" id="agency-about-button-save" value="Сохранить" />
		<input type="button" id="agency-about-button-cancel" value="Отмена" />
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		if($('#block-agency-about').html().length < 1)
			$('#block-agency-about-empty').show();
		
		$('#agency-about-button-edit').click(function(){
			$('#block-agency-about').hide();
			$('#block-agency-about-edit').show();
			$('#block-agency-about-empty').hide();
		});
		
		$('#agency-about-textarea').bind('keypress change click focus blur', function(){
			if($(this).val().replace(' ', '').length >= 800)
			{
				var value = '';
				var tokens = $(this).val().split(' ');
				for(var i in tokens)
				{
					if((value+' '+tokens[i]).length < 800)
						value += tokens[i] + ' ';
					else
						break;
				}
				$(this).val(value);
			}
		});
		
		$('#agency-about-button-save').click(function(){
			$.post('/agency', {
				'act': 'save_about',
				'id': '<?=$agency['id']?>',
				'value': $('#agency-about-textarea').val()
			}, function(response){
				if( ! response.success)
					return false;
				
				$('#block-agency-about').html($('#agency-about-textarea').val());
				$('#agency-about-button-cancel').click();
			}, 'json');
		});
		
		$('#agency-about-button-cancel').click(function(){
			$('#block-agency-about').show();
			$('#block-agency-about-edit').hide();
			if($('#block-agency-about').html().length < 1)
				$('#block-agency-about-empty').show();
			else
				$('#block-agency-about-empty').hide();
		})
		
	});
</script>