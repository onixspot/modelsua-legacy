<?php
/**
 * @var array $managers_list
 * @var bool $access
 */
?>

<div class="square_p pl15">
	<div class="left ucase bold"><a href="javascript:;" class="cblack"><?=t("Контакты")?></a></div>
	<? if($access){ ?>
		<div class="right"><a id="agency-contacts-button-edit" href="javascript:;" class="cgray">[<?=t('Редактировать')?>]</a></div>
	<? } ?>
	<div class="clear"></div>
</div>
<div id="block-agency-contacts" class="mt10">
	<div id="agency-link" class="pt5 pl25<?// if(null === $agency['link']){ ?> hide<?// } ?>" style="background: url('/icon_m.png') no-repeat; height: 19px;">
		<a href="http://<?=$agency['link']?>.<?=conf::get('server')?>"><?=$agency['link']?>.<?=conf::get('server')?></a>
	</div>
	<div id="agency-contacts-person" class="pt5 pl25<? if($agency['contacts']['_person'] === '0'){ ?> hide<? } ?>" style="background: url('/contacts_man.png') no-repeat; height: 19px;">
		<?=$agency['contacts']['_person']?>
	</div>
	<div id="agency-contacts-website" class="pt5 pl25<? if($agency['contacts']['website'] == ''){ ?> hide<? } ?>" style="background: url('/contacts_site.png') no-repeat; height: 19px;">
		<a href="<? if(strpos($agency['contacts']['website'], 'http://') !== true){ ?>http://<? } ?><?=$agency['contacts']['website']?>" target="_blank"><?=$agency['contacts']['website']?></a>
	</div>
	<div class="pt5 pl25<? if($agency['contacts']['phone'] === ''){ ?> hide<? } ?>" style="background: url('/contacts_tel.png') no-repeat; height: 19px;">
		<span id="agency-contacts-phone" class="cblack"><?=$agency['contacts']['phone']?></span> <span id="agency-contacts-phone2" class="cgray"><?=$agency['contacts']['phone2']?></span>
	</div>
	<div id="agency-contacts-email" class="pt5 pl25<? if($agency['contacts']['email'] == ''){ ?> hide<? } ?>" style="background: url('/contacts_mail.png') no-repeat; height: 19px;">
		<a href="mailto:<?=$agency['contacts']['email']?>"><?=$agency['contacts']['email']?></a>
	</div>
	<div id="agency-contacts-skype" class="pt5 pl25<? if($agency['contacts']['skype'] == ''){ ?> hide<? } ?>" style="background: url('/contacts_skype.png') no-repeat; height: 19px;">
		<a href="skype:<?=$agency['contacts']['skype']?>"><?=$agency['contacts']['skype']?></a>
	</div>
	<div id="agency-contacts-icq" class="pt5 pl25<? if($agency['contacts']['icq'] == ''){ ?> hide<? } ?>" style="background: url('/contacts_icq.png') no-repeat; height: 19px;">
		<?=$agency['contacts']['icq']?>
	</div>
	<div id="agency-contacts-address" class="pt5 pl25<? if($agency['contacts']['address'] == ''){ ?> hide<? } ?>" style="background: url('/contacts_address.png') no-repeat;">
		<?=$agency['contacts']['address']?>
	</div>
	<div id="agency-contacts-empty" class="acenter p10 cgray hide" style="border: 1px dotted #ccc;">
		<?=t('Пусто')?>
	</div>
</div>
<div id="block-agency-contacts-edit" class="mt10 hide">
	<div class="pl25 hide" style="background: url('/icon_m.png') no-repeat;">
		<div class="cgray mb5"><?=t('Домен')?>:</div>
		<input type="text" id="agency-text-link" value="<?=$agency['link']?>" style="width: 345px;" />
		<div id="agency-text-error-link-exists" class="hide" style="color: red">Домен недоступен. Пожалуйста выберете другой</div>
	</div>
	<div class="mt10 pl25" style="background: url('/contacts_man.png') no-repeat;">
		<div class="cgray mb5"><?=t('Контактное лицо')?> (<?=t('Фамилия')?> <?=t('Имя')?>):</div>
		<input type="text" id="agency-contacts-text-another_person" value="<?=$agency['contacts']['another_person']?>" class="hide" style="position: absolute; width: 327px;" />
		<select id="agency-contacts-text-person" style="width: 345px;">
			<option value="0">&mdash;</option>
            <?php foreach($managers_list as $id){ ?>
				<? $item = profile_peer::instance()->get_item($id); ?>
				<option value="<?=$id?>"><?=profile_peer::get_name($item)?></option>
			<? } ?>
			<option value="-1"><?=t('Другой')?></option>
		</select>
	</div>
	<div class="mt10 pl25" style="background: url('/contacts_site.png') no-repeat;">
		<div class="cgray mb5"><?=t('Веб сайт')?>:</div>
		<input type="text" id="agency-contacts-text-website" value="<?=$agency['contacts']['website']?>" style="width: 345px;" />
	</div>
	<div class="mt10 pl25" style="background: url('/contacts_tel.png') no-repeat;">
		<div class="cgray mb5"><?=t('Телефон')?>:</div>
		<div><input type="text" id="agency-contacts-text-phone" value="<?=$agency['contacts']['phone']?>" style="width: 345px;" /></div>
		<div class="mt5"><input type="text" id="agency-contacts-text-phone2" value="<?=$agency['contacts']['phone2']?>" style="width: 345px;" /></div>
	</div>
	<div class="mt10 pl25" style="background: url('/contacts_mail.png') no-repeat;">
		<div class="cgray mb5"><?=t('E-Mail')?>:</div>
		<input type="text" id="agency-contacts-text-email" value="<?=$agency['contacts']['email']?>" style="width: 345px;" />
	</div>
	<div class="mt10 pl25" style="background: url('/contacts_skype.png') no-repeat;">
		<div class="cgray mb5"><?=t('Skype')?>:</div>
		<input type="text" id="agency-contacts-text-skype" value="<?=$agency['contacts']['skype']?>" style="width: 345px;" />
	</div>
	<div class="mt10 pl25" style="background: url('/contacts_icq.png') no-repeat;">
		<div class="cgray mb5"><?=t('ICQ')?>:</div>
		<input type="text" id="agency-contacts-text-icq" value="<?=$agency['contacts']['icq']?>" style="width: 345px;" />
	</div>
	<div class="mt10 pl25" style="background: url('/contacts_address.png') no-repeat;">
		<div class="cgray mb5"><?=t('Адрес')?>:</div>
		<textarea id="agency-contacts-textarea-address" style="width: 345px; height: 100px"><?=$agency['contacts']['address']?></textarea>
	</div>
	<div class="mt10 aright">
		<input type="button" id="agency-contacts-button-save" value="Сохранить" />
		<input type="button" id="agency-contacts-button-cancel" value="Отмена" />
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){

		$('#agency-contacts-text-person').change(function(){
			var val = $(this).val();
			if(val == -1)
			{
				$('#agency-contacts-text-another_person')
					.show()
					.focus();
			}
			else
			{
				$('#agency-contacts-text-another_person')
					.val('')
					.hide();
			}
		});

		$('#agency-contacts-text-another_person').blur(function(){
			if($(this).val() == '')
			{
				$('#agency-contacts-text-person').val(0);
				$(this)
					.val('')
					.hide();
			}
		});

		<? if($agency['contacts']['person'] > 0 || $agency['contacts']['person'] < 0){ ?>
			$('#agency-contacts-text-person')
				.val(<?=$agency['contacts']['person']?>)
				.change();
		<? } ?>

		$('#agency-contacts-button-edit').click(function(){
			$('#block-agency-contacts').hide();
			$('#block-agency-contacts-edit').show();
		});

		if($('#block-agency-contacts > div:visible').length < 1)
		{
			$('#agency-contacts-empty').show();
			<? if( ! $access){ ?>
				$('#block-agency-contacts')
					.hide()
					.prev()
					.hide();
			<? } ?>
		}

		$('#agency-contacts-button-save').click(function(){
			var data = new Object({
				'link': $('#agency-text-link').val(),
				'person': $('#agency-contacts-text-person').val(),
				'another_person': $('#agency-contacts-text-another_person').val(),
				'website': $('#agency-contacts-text-website').val(),
				'phone': $('#agency-contacts-text-phone').val(),
				'phone2': $('#agency-contacts-text-phone2').val(),
				'email': $('#agency-contacts-text-email').val(),
				'skype': $('#agency-contacts-text-skype').val(),
				'icq': $('#agency-contacts-text-icq').val(),
				'address': $('#agency-contacts-textarea-address').val()
			});

			$.post('/agency', {
				'act': 'save_contacts',
				'id': '<?=$agency['id']?>',
				'data': data
			}, function(response){
				$("div[id^='agency-text-error-']").hide();
				if(response.success)
				{
					if(typeof response.link !== 'undefined')
					{
						$('#agency-link')
							.empty()
							.show();

						var link = 'http://'+response.link+'.<?=conf::get('server')?>';
						var a = $('<a />')
							.attr('href', link)
							.html(response.link+'.<?=conf::get('server')?>');

						$('#agency-link').append($(a));
					}
					else
						$('#agency-link').hide();

					if(response.person != '')
					{

						$('#agency-contacts-person')
							.show()
							.empty();

						if(typeof response.person_link != 'undefined')
						{
							var a = $('<a />')
								.attr('href', response.person_link)
								.html(response.person);
							$('#agency-contacts-person').append($(a));
						}
						else
							$('#agency-contacts-person').html(response.person);
					}
					else
						$('#agency-contacts-person').hide();

					if(data.website != '')
					{
						var http = '';
						if(data.website.indexOf('http://') < 0)
							http = 'http://';
						$('#agency-contacts-website')
							.show()
							.find('a')
								.attr('href', http+data.website)
								.html(data.website);
					}
					else
						$('#agency-contacts-website').hide();

					if(data.phone != '' || data.phone2 != '')
					{
						$('#agency-contacts-phone').parent().show();
						if(data.phone != '')
							$('#agency-contacts-phone')
								.show()
								.html(data.phone);
						else
							$('#agency-contacts-phone').hide();
						if(data.phone2 != '')
							$('#agency-contacts-phone2')
								.show()
								.html(data.phone2);
						else
							$('#agency-contacts-phone2').hide();
					}
					else
						$('#agency-contacts-phone').parent().hide();

					if(data.email != '')
					{
						$('#agency-contacts-email')
							.show()
							.find('a')
								.attr('href', 'mailto:'+data.email)
								.html(data.email);
					}
					else
						$('#agency-contacts-email').hide();

					if(data.skype != '')
					{
						$('#agency-contacts-skype')
							.show()
							.find('a')
								.attr('href', 'skype:'+data.skype)
								.html(data.skype);
					}
					else
						$('#agency-contacts-skype').hide();

					if(data.icq != '')
					{
						$('#agency-contacts-icq')
							.show()
							.html(data.icq);
					}
					else
						$('#agency-contacts-icq').hide();

					if(data.address != '')
					{
						$('#agency-contacts-address')
							.show()
							.html(data.address);
					}
					else
						$('#agency-contacts-address').hide();

					$('#agency-contacts-button-cancel').click();
				}
				else if(typeof response.errors != 'undefined')
				{
					var errors = response.errors;
					for(var i in errors)
						$("#agency-text-error-"+errors[i]).show();
				}
			}, 'json');
		});

		$('#agency-contacts-button-cancel').click(function(){
			$('#block-agency-contacts').show();
			$('#block-agency-contacts-edit').hide();
			$('#agency-contacts-empty').hide();
			if($('#block-agency-contacts > div:visible').length < 1)
				$('#agency-contacts-empty').show();
		});

	});
</script>
