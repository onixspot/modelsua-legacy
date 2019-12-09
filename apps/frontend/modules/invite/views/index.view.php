<div class="small-title left square_p pl10 mt10 mb10">
        <a href="/"><?=t('Пригласите подруг зайти на наш сайт')?></a>
    </div>
<div class="clear"></div>
<div class="box_content acenter p10 fs12">

		<form id="send-form">
		    <input type="hidden" id="ignore" value="0">
			<table>
				<tbody><tr>
                                    <td style="width:30%" class="aright"><b><?=t('Електронные адреса людей, которых Вы хотите пригласить')?></b><span class="fs11 cgray"><br>(<?=t('Вы можете ввести до 30 адресов через запятую')?>)</span></td>
					<td class="aleft">
                                            <textarea style="width: 400px; height: 100px;" id="emaillist" rows="5" cols="5"></textarea>
                                        </td>
				</tr>
                                
				<tr>
                                    <td class="aright"><b><?=t('Текст приглашения')?>:</b></td>
					<td class="aleft">
                                            <textarea style="width: 400px; height: 150px;" id="recomendation" rows="10" cols="5"><?=t('Привет!

Я нашла интересный и полезный для моделей сайт, рекомендую его тебе - https://www.modelsua.org')?></textarea>
                                        </td>
				</tr>
                        </tbody></table>

			<input type="button" id="send-invite-button" value="<?=t('Отправить')?>" class="button" name="submit">
		</form>
    <div class="clear"></div>
    <div class="error hide"></div>
    <div class="bad_emails hide ">
	<div class="">
	    <?=t('Следующие адреса не прошли проверку')?>:
	</div>
	<div id="bad_list" class="p5" style="color: red;"></div>
	<div class="bold mb10">
	    <?=t('Внимание! Приглашения на них отправлены не будут')?>.
	</div>
	<input type="button" value="<?=t('Продолжить')?>" id="ignore-button"></input>
	<input type="button" value="<?=t('Отменить')?>" class="ml10" id="cancel-button" onClick="$('.bad_emails').hide();"></input>
    </div>
    <div class="success hide">
	<?=t('Приглашения отправлены')?>
    </div>
	</div>
<script>
    $('input[id="send-invite-button"]').click(function() {
	$.post(
	    '/invite/index',
	    {
		"submit": 1,
		"ignore": $('input[id="ignore"]').val(),
		"emaillist": $('textarea[id="emaillist"]').val(),
		"body": $('textarea[id="recomendation"]').val()
		
	    },
	    function(resp) {
		if(resp.success) {
		    if(resp.bad_emails || resp.exist_emails) {
			html = '';
			if(resp.bad_emails) html += '<div><span style="color: black !important"><?=t('Не корректные')?>:</span><br/>'+resp.bad_emails.join('<br/>')+'<div>';
			if(resp.exist_emails) html += '<div><span style="color: black"><?=t('Уже зарегестрированные')?>:</span><br/>'+resp.exist_emails.join('<br/>')+'<div>';
			$('#bad_list').html(html);
			$('.bad_emails').show();
		    }
		    else {
			$('input[id="ignore"]').val(0);
			$('.success').fadeIn(300,function(){$(this).fadeOut(3000, function(){window.location="/profile?id=<?=session::get_user_id()?>"})});
		    }
		}
		else {
		    $('.error').html(resp.reason);
		    $('.error').fadeIn(300,function(){$(this).fadeOut(5000)});
		}
	    },
	    'json'
	)
    });
    $('input[id="ignore-button"]').click(function(){
	$('input[id="ignore"]').val(1);
	$('.bad_emails').hide();
	$('input[id="send-invite-button"]').click();
    });
</script>
