<form id="login" method="post" action="/forum/sign">
<input type="hidden" name="act" value="login"/>
<div class="panel">
	<div class="inner"><span class="corners-top"><span></span></span>

	<div class="content fs14">
		<h2>Вход</h2>

		<fieldset class="fields1">
		
		<dl>
			<dt style="width: 15em;"><label for="username">Имя пользователя:</label></dt>
			<dd><input type="text" class="inputbox autowidth" value="" size="25" id="username" name="username" tabindex="1"></dd>
		</dl>
		<dl>
			<dt style="width: 15em;"><label for="password">Пароль:</label></dt>
			<dd><input type="password" class="inputbox autowidth" size="25" name="password" id="password" tabindex="2"></dd>
			<dd class="mt5"><a href="javascript:void(0);">Забыли пароль?</a></dd>
			<dd><a href="javascript:void(0);">Повторно выслать письмо для активации учётной записи</a></dd>
		</dl>
		
		<dl>
			<dd><label for="autologin"><input type="checkbox" tabindex="4" id="autologin" name="autologin"> Автоматически входить при каждом посещении</label></dd>
			<dd><label for="viewonline"><input type="checkbox" tabindex="5" id="viewonline" name="viewonline"> Скрыть моё пребывание на конференции в этот раз</label></dd>
		</dl>
		
		<dl>
			<dt>&nbsp;</dt>
			<dd>
			    <input type="button" class="button1" value="Вход" tabindex="6" name="login-button" id="login-button" style="margin-top: 1px;">
			</dd>
		</dl>
		</fieldset>
	</div>
	    <div class="login_error fs14 hide acenter fs16" style="padding: 10px; background: #e0e0e0; color: #333; "></div>
	<span class="corners-bottom"><span></span></span></div>
</div>



	<div class="panel">
		<div class="inner"><span class="corners-top"><span></span></span>
		<div class="content fs14">
			<h3>Регистрация</h3>
			<p>Для входа на конференцию вы должны быть зарегистрированы. Регистрация занимает всего несколько минут, но предоставляет вам более широкие возможности. Администратором конференции могут быть установлены также дополнительные привилегии для зарегистрированных пользователей. Прежде чем зарегистрироваться, вам следует ознакомиться с правилами и политикой, принятыми на конференции. Помните, что ваше присутствие на форумах означает согласие со <strong>всеми</strong> правилами.</p>
			<p><strong><a href="javascript:void(0);">Общие правила</a> | <a href="./ucp.php?mode=privacy">Соглашение о конфиденциальности</a></strong></p>
			<hr class="dashed">
			<p><input type="button" value="<?=t('Регистрация')?>" class="mb15" onclick="wondow.location='/forum/sign?mode=registration'"/></p>
		</div>
		<span class="corners-bottom"><span></span></span></div>
	</div>
</form>

<script>
    $(function() {
	var form = new Form('login');
	$('input[id="login-button"]').click(function(){
	    form.onSuccess = function(resp) {
		if(resp.success)
		    colsole.log(resp);
		else 
		    $('.login_error')
			.html(resp.reason)
			.fadeIn(300, function(){
			    $(this).fadeOut(3000);
			});
	    }
	    form.send();
	});
    })
</script>