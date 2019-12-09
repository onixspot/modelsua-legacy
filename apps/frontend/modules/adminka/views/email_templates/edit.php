<form class="form mt10" id="save-template-form">
        <input type="hidden" value="<?=$post_data['id']?>" id="id">
        <table width="100%" class="fs12 ml10">
            <tbody><tr>
                <td>
                    Псевдоним
		</td>
		<td>
                    <input type="text" value="<?=$post_data['alias']?>" id="alias" style="width: 500px;">
                </td>
            </tr>
            <tr>
                <td>
                    Название
		</td>
		<td>
                    <input type="text" value="<?=$post_data['name']?>" id="name" style="width: 500px;">
                </td>
            </tr>
	    <tr>
                <td>
                    Тема письма
		</td>
		<td>
                    <input type="text" value="<?=$post_data['subject']?>" id="subject" style="width: 500px;">
                </td>
            </tr>
            <tr>
                <td>
                    Адрес отправителя
		</td>
		<td>
                    <input type="text" value="<?=$post_data['sender_email']?>" id="sender_email" style="width: 500px;">
                </td>
            </tr>
            <tr>
                <td>
                    Имя отправителя
		</td>
		<td>
                    <input type="text" value="<?=$post_data['sender_name']?>" id="sender_name" style="width: 500px;"/>
                </td>
            </tr>
            <tr>
                <td>
                    Текст
		</td>
		<td>
                    <textarea id="body"><?=$post_data['body']?></textarea>
                </td>
            </tr>
            <tr>
		<td>
		</td>
                <td>
                        <input type="button" value=" Сохранить " class="button" id="save-email-template">
                        <input type="button" value=" Отмена " class="button_gray" name="cancel" id="cancel">
                        
                </td>
            </tr>
	    <tr>
		<td></td>
		<td><div class="success hide fs14">Изменения сохранены</div>
		<div class="error hide fs14"></div></td>
	    </tr>
        </tbody>
    </table>
</form>

<script>
    $(function() {
	var opts = {
                cssClass : 'el-rte',
                lang     : 'ru',
                height   : 450,
		width	 : 600,
                toolbar  : 'complete',
                cssfiles : ['https://css.<?=conf::get('server')?>/elrte.css']
        }
        $('#body').elrte(opts);
    });
    $('input[id="save-email-template"]').click(function(){
	$.post(
	    '/adminka/email_templates',
	    {
		'alias': $('#alias').val(),
		'name': $('#name').val(),
		'subject': $('#subject').val(),
		'sender_email': $('#sender_email').val(),
		'sender_name': $('#sender_name').val(),
		'id': $('#id').val(),
		'body': $('#body').elrte('val')
	    },
	    function(resp) {
		if(resp.success) {
		    $('.success').fadeIn(300, function() { $(this).fadeOut(3000); });
		    $('#id').val(resp.id);
		}
		else {
		    $('.error').html(resp.reason);
		    $('.error').fadeIn(300, function() { $(this).fadeOut(3000); });
		}
	    },
	    'json'
	);
    });
</script>