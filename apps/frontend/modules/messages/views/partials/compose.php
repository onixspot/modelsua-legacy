<div class="square_p pl15 mt20 mb20 fs12 ucase bold left pointer" onclick="$('#compose_form').slideToggle()">
	<?=t('Написать сообщение')?>
</div>
<div class="clear"></div>
<?
    if(request::get_int('receiver')) 
	$profile = profile_peer::instance()->get_item(request::get_int('receiver'));
    
?>
<form class="form mt10" id="compose_form">
    <input type="hidden" value="<?=(!session::has_credential('admin') && !session::has_credential('amu')) ? -1 : ((request::get_int('receiver')) ? request::get_int('receiver') : '')?>" id="receiver_id">
    <input type="hidden" value="0" id="parent_id">
    <table width="100%" class="fs12">
	<tbody>
	    <tr>
		<td class="aright"><?=t('Имя получателя')?></td>
		<td>
		    <input type="text" id="receiver" style="width: 500px;" rel="Выберите получателя" class="text" id="receiver" autocomplete="off" <?if(!session::has_credential('admin') && !session::has_credential('amu')) {?> disabled value="Администрация ModelsUA" <? } elseif($profile) {?> value="<?=profile_peer::get_name($profile)?>"<? } ?>/>
		</td>
	    </tr>
	    <?if(session::has_credential('admin') || session::has_credential('amu')) {?>
	    <tr>
		    <td class="aright"></td>
		    <td>
			<span class="quiet fs11">
			    *&nbsp;<?=t('Начните вводить имя адресата')?><br>
			</span>
		    </td>
	    </tr>
	    <? } ?>
	    <tr>
		<td class="aright"><?=t('Тема')?></td>
		<td>
		    <input type="text" id="subject" style="width: 500px;" rel="Выберите получателя" class="text" value="<?=request::get_string('subject')?>"/>
		</td>
	    </tr>
	    <tr>
		    <td class="aright"><?=t('Сообщение')?></td>
		    <td>
			<textarea style="width: 500px; height:150px;" id="body" rel="Введите текст сообщения"><?=$body?></textarea>
		    </td>
	    </tr>
	    <?if(session::has_credential('superadmin')) {?>
	    <tr>
		<td class="aright"><?=t('От имени Администрации ModelsUA')?></td>
		<td>
		    <input type="checkbox" id="from_amu" name="from_amu" checked="1"/>
		</td>
	    </tr>
	    <? } ?>
	    <tr>
		    <td></td>
		    <td>
			    <input type="button" value="<?=t('Отправить')?>" id="send-message-button" class="button">
			    <input type="button" value="<?=t('Отмена')?>" class="button_gray" name="cancel" onclick="$('#compose_form').slideToggle(500,function() {$('#receiver_id,#receiver,#body').val('')})" id="cancel">
			    <img align="absmiddle" width="15" src="https://s1.meritokrat.org/common/loading.gif" class="hide ml10" id="wait_panel">					
			    <div class="success hide mr10 mt10"><?=t('Сообщение отправлено')?></div>
			    <div class="error hide mr10 mt10"></div>
		    </td>
	    </tr>
	</tbody>
    </table>
</form>

<?if(session::has_credential('admin') || session::has_credential('amu') || session::has_credential('superadmin')) {?>
<script>
$(function() {
	var jqac;
	$('#receiver').focus(function(){
	    if(!jqac) {
		jqac = new Autocomplete('receiver');
		jqac.init();
	    }
	});
    });
</script>
<? } ?>

<script>
    $('input[id="send-message-button"]').click(function(){
	$.post(
	    '/messages/index',
	    {
		"send_message": 1,
		"subject": $('input[id="subject"]').val(),
		"parent_id": $('input[id="parent_id"]').val(),
		"from_amu": (typeof $('#from_amu:checked').val() == 'undefined') ? 0 : 1 ,
		"receiver_id": $('input[id="receiver_id"]').val(),
		"body": $('textarea[id="body"]').val()
	    },
	    function(resp) {
		console.log(resp);
		if(resp.success) {
		    $('.success').fadeIn(300, function() {$(this).fadeOut(3000, function() {window.location=window.location;})});
		}
		else {
		    $('.error').html(resp.reason);
		    $('.error').fadeIn(300, function() { $(this).fadeOut(5000) });
		}
	    },
	    'json'
	);
    });	
</script>