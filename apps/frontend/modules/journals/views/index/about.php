<div class="square_p pl15">
	<div class="left ucase bold"><a href="javascript:void(0);" class="cblack"><?=t("О журнале")?></a></div>
	<? if($access){ ?>
		<div class="right"><a href="javascript:void(0);" id="journal-about-button-edit" class="cgray">[<?=t('Редактировать')?>]</a></div>
	<? } ?>
	<div class="clear"></div>
</div>
<div id="block-journal-about" class="mt5 cgray ajustify"><?=$journal['about']?></div>
<div id="block-journal-about-empty" class="acenter p10 cgray hide" style="border: 1px dotted #ccc;"><?=t('Пусто')?></div>
<div id="block-journal-about-edit" class="mt10 hide">
	<div>
		<textarea id="journal-about-textarea" style="width: 370px; height: 100px"><?=$journal['about']?></textarea>
	</div>
	<div class="mt10 aright">
		<input type="button" id="journal-about-button-save" value="Сохранить" />
		<input type="button" id="journal-about-button-cancel" value="Отмена" />
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		if($('#block-journal-about').html().length < 1)
			$('#block-journal-about-empty').show();
		
		$('#journal-about-button-edit').click(function(){
			$('#block-journal-about').hide();
			$('#block-journal-about-edit').show();
			$('#block-journal-about-empty').hide();
		});
		
		$('#journal-about-textarea').bind('keypress change click focus blur', function(){
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
		
		$('#journal-about-button-save').click(function(){
			$.post('/journals', {
				'act': 'save_about',
				'id': '<?=$journal['id']?>',
				'value': $('#journal-about-textarea').val()
			}, function(response){
				if( ! response.success)
					return false;
				
				$('#block-journal-about').html($('#journal-about-textarea').val());
				$('#journal-about-button-cancel').click();
			}, 'json');
		});
		
		$('#journal-about-button-cancel').click(function(){
			$('#block-journal-about').show();
			$('#block-journal-about-edit').hide();
			if($('#block-journal-about').html().length < 1)
				$('#block-journal-about-empty').show();
			else
				$('#block-journal-about-empty').hide();
		});
		
	});
</script>