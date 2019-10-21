<div class="profile-registration-box <?=(request::get('tab')=='profile' && request::get('form')=='registration') ? '' : ' hide'?>">
    <form action="/forum/profile" method="post" id="profile-registration-form">

	<input type="hidden" name="act" value="save_profile"/>
	<input type="hidden" name="subaction" value="registration"/>
	
	<h2>Регистрационные данные</h2>
	
	<div class="panel">
		<div class="inner"><span class="corners-top"><span></span></span>

		<fieldset>

		<dl>
			<dt><label>Имя пользователя:</label><br><span>Имя пользователя должно быть от 3 и до 20 знаков.</span></dt>
			<dd><strong>postgres</strong></dd>
		</dl>
		<dl>
			<dt><label for="email">Адрес email:</label></dt>
			<dd><input type="text" title="Адрес email" class="inputbox" value="tarasenko_a@list.ru" maxlength="100" id="email" name="email"></dd>
		</dl>

			<dl>
				<dt><label for="email_confirm">Подтвердите email:</label><br><span>Указывайте email только если вы хотите его поменять.</span></dt>
				<dd><input type="text" title="Подтвердите email" class="inputbox" value="" maxlength="100" id="email_confirm" name="email_confirm"></dd>
			</dl>

			<dl>
				<dt><label for="new_password">Новый пароль:</label><br><span>Должен быть длиной от 6 до 100 знаков.</span></dt>
				<dd><input type="password" title="Изменить пароль" class="inputbox" value="" maxlength="255" id="new_password" name="new_password"></dd>
			</dl>
			<dl>
				<dt><label for="password_confirm">Подтвердите новый пароль:</label><br><span>Указывайте пароль только если вы изменили его выше.</span></dt>
				<dd><input type="password" title="Подтвердите новый пароль" class="inputbox" value="" maxlength="255" id="password_confirm" name="password_confirm"></dd>
			</dl>

		</fieldset>
		<span class="corners-bottom"><span></span></span></div>
	</div>

	<div class="panel">
		<div class="inner"><span class="corners-top"><span></span></span>

		<fieldset>
		<dl>
			<dt><label for="cur_password">Текущий пароль:</label><br><span>Если вы хотите изменить имя пользователя, email или пароль, вы должны указать текущий пароль.</span></dt>
			<dd><input type="password" title="Текущий пароль" class="inputbox" value="" maxlength="255" id="cur_password" name="cur_password"></dd>
		</dl>
		</fieldset>

		<span class="corners-bottom"><span></span></span></div>
	</div>

	<fieldset class="submit-buttons">
		<input type="button" class="button1" value="Отправить" name="save-profile-reg" id="save-profile-reg">
		<div class="profile-reg-success hide"><?=t('Изменения сохранены')?></div>
		<div class="profile-reg-error acenter fs16 hide"></div>
	</fieldset>
    </form>
</div>
<script>
    $(function() {
	var profile_reg_form = new Form('profile-registration-form');
	$('input[id="save-profile-reg"]').click(function(){
	    profile_reg_form.onSuccess = function(resp) {
		if(resp.success)
		    $('.profile-reg-success')
			.fadeIn(300, function() { 
			    $(this).fadeOut(3000); 
			});
		else     
		    $('.profile-reg-error')
			.html(resp.reason)
			.fadeIn(300, function() { 
			    $(this).fadeOut(3000); 
			});
	    }
	    profile_reg_form.send();
	});
    });
</script>