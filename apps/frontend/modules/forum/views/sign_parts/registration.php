<form id="register" class="mt10" action="/forum/sign" method="post">
<input type="hidden" name="act" id="act" value="register"/>
<div class="panel" style="padding-bottom: 0px;">
	<div class="inner"><span class="corners-top"><span></span></span>

	<h2 style="margin-bottom: 0px;">Ассоциация моделей Украины - Регистрация</h2>

	<fieldset class="fields2" style="padding: 0px 10px;">
	
	    <dl><dd><strong>Обратите внимание на то, что вы должны ввести правильный адрес электронной почты перед активацией. На указанный вами адрес придёт письмо, содержащее ссылку для активации учётной записи.</strong></dd></dl>

	    <dl>
		    <dt><label for="username">Имя пользователя:</label><br><span>Имя пользователя должно быть от 3 и до 20 знаков.</span></dt>
		    <dd><input type="text" title="Имя пользователя" class="inputbox autowidth" value="" size="25" id="username" name="username" tabindex="1"></dd>
	    </dl>
	    <dl>
		    <dt><label for="email">Адрес email:</label></dt>
		    <dd><input type="text" title="Адрес email" class="inputbox autowidth" value="" maxlength="100" size="25" id="email" name="email" tabindex="2"></dd>
	    </dl>
<!--	    <dl>
		    <dt><label for="email_confirm">Подтвердите email:</label></dt>
		    <dd><input type="text" title="Подтвердите email" class="inputbox autowidth" value="" maxlength="100" size="25" id="email_confirm" name="email_confirm" tabindex="3"></dd>
	    </dl>-->
	    <dl>
		    <dt><label for="new_password">Пароль:</label><br><span>Должен быть длиной от 6 до 100 знаков.</span></dt>
		    <dd><input type="password" title="Новый пароль" class="inputbox autowidth" value="" size="25" id="new_password" name="new_password" tabindex="4"></dd>
	    </dl>
	    <dl>
		    <dt><label for="password_confirm">Подтвердите новый пароль:</label></dt>
		    <dd><input type="password" title="Подтвердите новый пароль" class="inputbox autowidth" value="" size="25" id="password_confirm" name="password_confirm" tabindex="5"></dd>
	    </dl>

	    <hr>

	    

	

	</fieldset>
	<span class="corners-bottom"><span></span></span></div>
</div>

<div class="panel">
	<div class="inner"><span class="corners-top"><span></span></span>

	<fieldset class="submit-buttons">

<!--		<input type="button" class="button2" name="reset" value="Вернуть">&nbsp;-->
		<input type="button" class="button1 default-submit-action" value="Отправить" id="submit_registration" name="submit_registration" tabindex="9">
		
	</fieldset>

	<span class="corners-bottom"><span></span></span></div>
</div>
<div class="reg_error fs14 hide acenter fs16" style="padding: 10px; background: #e0e0e0; color: #333; "></div>
</form>

<script>
    $(function() {
	var form = new Form('register');
	$('input[id="submit_registration"]').click(function(){
	    form.onSuccess = function(resp) {
		if(resp.success)
		    colsole.log(resp);
		else 
		    $('.reg_error')
			.html(resp.reason)
			.fadeIn(300, function(){
			    $(this).fadeOut(3000);
			});
	    }
	    form.send();
	});
    })
</script>