<div class="square_p pl15">
	<div class="left ucase bold"><a href="javascript:void(0);" class="cblack"><?=t("Контакты")?></a></div>
	<? if($access){ ?>
		<div class="right"><a id="journal-contacts-button-edit" href="javascript:void(0);" class="cgray">[<?=t('Редактировать')?>]</a></div>
	<? } ?>
	<div class="clear"></div>
</div>
<div id="block-journal-contacts" class="mt10">
	<div id="journal-contacts-website" class="pt5 pl25<? if($journal['contacts']['website'] == ''){ ?> hide<? } ?>" style="background: url('/contacts_site.png') no-repeat; height: 19px;">
		<a href="<? if(strpos($journal['contacts']['website'], 'https://') !== true){ ?>https://<? } ?><?=$journal['contacts']['website']?>" target="_blank"><?=$journal['contacts']['website']?></a>
	</div>
	<div class="pt5 pl25<? if($journal['contacts']['phone'] == '' && $journal['contacts']['phone'] == ''){ ?> hide<? } ?>" style="background: url('/contacts_tel.png') no-repeat; height: 19px;">
		<span id="journal-contacts-phone" class="cblack"><?=$journal['contacts']['phone']?></span> <span id="journal-contacts-phone2" class="cgray"><?=$journal['contacts']['phone2']?></span>
	</div>
	<div id="journal-contacts-email" class="pt5 pl25<? if($journal['contacts']['email'] == ''){ ?> hide<? } ?>" style="background: url('/contacts_mail.png') no-repeat; height: 19px;">
		<a href="mailto:<?=$journal['contacts']['email']?>"><?=$journal['contacts']['email']?></a>
	</div>
	<div id="journal-contacts-skype" class="pt5 pl25<? if($journal['contacts']['skype'] == ''){ ?> hide<? } ?>" style="background: url('/contacts_skype.png') no-repeat; height: 19px;">
		<a href="skype:<?=$journal['contacts']['skype']?>"><?=$journal['contacts']['skype']?></a>
	</div>
	<div id="journal-contacts-icq" class="pt5 pl25<? if($journal['contacts']['icq'] == ''){ ?> hide<? } ?>" style="background: url('/contacts_icq.png') no-repeat; height: 19px;">
		<?=$journal['contacts']['icq']?>
	</div>
	<div id="journal-contacts-address" class="pt5 pl25<? if($journal['contacts']['address'] == ''){ ?> hide<? } ?>" style="background: url('/contacts_address.png') no-repeat;">
		<?=$journal['contacts']['address']?>
	</div>
	<div id="journal-contacts-empty" class="acenter p10 cgray hide" style="border: 1px dotted #ccc;">
		<?=t('Пусто')?>
	</div>
</div>
<div id="block-journal-contacts-edit" class="mt10 hide">
	<div class="mt10 pl25" style="background: url('/contacts_site.png') no-repeat;">
		<div class="cgray mb5"><?=t('Веб сайт')?>:</div>
		<input type="text" id="journal-contacts-text-website" value="<?=$journal['contacts']['website']?>" style="width: 345px;" />
	</div>
	<div class="mt10 pl25" style="background: url('/contacts_tel.png') no-repeat;">
		<div class="cgray mb5"><?=t('Телефон')?>:</div>
		<div><input type="text" id="journal-contacts-text-phone" value="<?=$journal['contacts']['phone']?>" style="width: 345px;" /></div>
		<div class="mt5"><input type="text" id="journal-contacts-text-phone2" value="<?=$journal['contacts']['phone2']?>" style="width: 345px;" /></div>
	</div>
	<div class="mt10 pl25" style="background: url('/contacts_mail.png') no-repeat;">
		<div class="cgray mb5"><?=t('E-Mail')?>:</div>
		<input type="text" id="journal-contacts-text-email" value="<?=$journal['contacts']['email']?>" style="width: 345px;" />
	</div>
	<div class="mt10 pl25" style="background: url('/contacts_skype.png') no-repeat;">
		<div class="cgray mb5"><?=t('Skype')?>:</div>
		<input type="text" id="journal-contacts-text-skype" value="<?=$journal['contacts']['skype']?>" style="width: 345px;" />
	</div>
	<div class="mt10 pl25" style="background: url('/contacts_icq.png') no-repeat;">
		<div class="cgray mb5"><?=t('ICQ')?>:</div>
		<input type="text" id="journal-contacts-text-icq" value="<?=$journal['contacts']['icq']?>" style="width: 345px;" />
	</div>
	<div class="mt10 pl25" style="background: url('/contacts_address.png') no-repeat;">
		<div class="cgray mb5"><?=t('Адрес')?>:</div>
		<textarea id="journal-contacts-textarea-address" style="width: 345px; height: 100px"><?=$journal['contacts']['address']?></textarea>
	</div>
	<div class="mt10 aright">
		<input type="button" id="journal-contacts-button-save" value="Сохранить" />
		<input type="button" id="journal-contacts-button-cancel" value="Отмена" />
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		$('#journal-contacts-button-edit').click(function(){
			$('#block-journal-contacts').hide();
			$('#block-journal-contacts-edit').show();
		});
		
		if($('#block-journal-contacts > div:visible').length < 1)
		{
			$('#journal-contacts-empty').show();
			<? if( ! $access){ ?>
				$('#block-journal-contacts')
					.hide()
					.prev()
					.hide();
			<? } ?>
		}
		
		$('#journal-contacts-button-save').click(function(){
			var data = new Object({
				'website': $('#journal-contacts-text-website').val(),
				'phone': $('#journal-contacts-text-phone').val(),
				'phone2': $('#journal-contacts-text-phone2').val(),
				'email': $('#journal-contacts-text-email').val(),
				'skype': $('#journal-contacts-text-skype').val(),
				'icq': $('#journal-contacts-text-icq').val(),
				'address': $('#journal-contacts-textarea-address').val()
			});
			
			$.post('/journals', {
				'act': 'save_contacts',
				'id': '<?=$journal['id']?>',
				'data': data
			}, function(response){
				$("div[id^='journal-text-error-']").hide();
				if(response.success)
				{
					if(data.website != '')
					{
						var http = '';
						if(data.website.indexOf('https://') < 0)
							http = 'https://';
						$('#journal-contacts-website')
							.show()
							.find('a')
								.attr('href', http+data.website)
								.html(data.website);
					}
					else
						$('#journal-contacts-website').hide();

					if(data.phone != '' || data.phone2 != '')
					{
						$('#journal-contacts-phone').parent().show();
						if(data.phone != '')
							$('#journal-contacts-phone')
								.show()
								.html(data.phone);
						else
							$('#journal-contacts-phone').hide();
						if(data.phone2 != '')
							$('#journal-contacts-phone2')
								.show()
								.html(data.phone2);
						else
							$('#journal-contacts-phone2').hide();
					}
					else
						$('#journal-contacts-phone').parent().hide();

					if(data.email != '')
					{
						$('#journal-contacts-email')
							.show()
							.find('a')
								.attr('href', 'mailto:'+data.email)
								.html(data.email);
					}
					else
						$('#journal-contacts-email').hide();

					if(data.skype != '')
					{
						$('#journal-contacts-skype')
							.show()
							.find('a')
								.attr('href', 'skype:'+data.skype)
								.html(data.skype);
					}
					else
						$('#journal-contacts-skype').hide();

					if(data.icq != '')
					{
						$('#journal-contacts-icq')
							.show()
							.html(data.icq);
					}
					else
						$('#journal-contacts-icq').hide();

					if(data.address != '')
					{
						$('#journal-contacts-address')
							.show()
							.html(data.address);
					}
					else
						$('#journal-contacts-address').hide();

					$('#journal-contacts-button-cancel').click();
				}
				else if(typeof response.errors != 'undefined')
				{
					var errors = response.errors;
					for(var i in errors)
						$("#journal-text-error-"+errors[i]).show();
				}
			}, 'json');
		});
		
		$('#journal-contacts-button-cancel').click(function(){
			$('#block-journal-contacts').show();
			$('#block-journal-contacts-edit').hide();
			$('#journal-contacts-empty').hide();
			if($('#block-journal-contacts > div:visible').length < 1)
				$('#journal-contacts-empty').show();
		});
		
	});
</script>