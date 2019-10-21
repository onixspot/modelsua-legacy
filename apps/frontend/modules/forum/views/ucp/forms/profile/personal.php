<div class="profile-personal-box <?=(request::get('tab')=='profile' && request::get('form','personal')=='personal') ? '' : ' hide'?>">
    <form action="/forum/profile" method="post" id="profile-personal-form">

    <input type="hidden" name="act" value="save_profile"/>
    <input type="hidden" name="subaction" value="personal"/>

    <h2>Личные данные</h2>

    <div class="panel">
	    <div class="inner"><span class="corners-top"><span></span></span>
	    <p>Пожалуйста, помните, что эта информация может быть доступна другим пользователям. Будьте осторожны при выборе указываемых персональных данных. Любые поля, обозначенные звёздочкой (*), должны быть заполнены.</p>

	    <fieldset>

	    <dl>
		    <dt><label for="icq">ICQ:</label></dt>
		    <dd><input type="text" class="inputbox" value="" maxlength="15" id="icq" name="icq"></dd>
	    </dl>
	    <dl>
		    <dt><label for="website">Сайт:</label></dt>
		    <dd><input type="text" class="inputbox" value="" maxlength="255" id="website" name="website"></dd>
	    </dl>
	    <dl>
		    <dt><label for="location">Откуда:</label></dt>
		    <dd><input type="text" class="inputbox" value="" maxlength="255" id="location" name="location"></dd>
	    </dl>
	    <dl>
		    <dt><label for="occupation"><?=t('Род занятий')?>:</label></dt>
		    <dd>
			<textarea cols="30" rows="3" class="inputbox" id="occupation" name="occupation">

			</textarea>
		    </dd>
	    </dl>
	    <dl>
		    <dt><label for="interests"><?=t('Интересы')?>:</label></dt>
		    <dd>
			<textarea cols="30" rows="3" class="inputbox" id="interests" name="interests">

			</textarea>
		    </dd>
	    </dl>

	    </fieldset>

	    <span class="corners-bottom"><span></span></span></div>
    </div>

	<fieldset class="submit-buttons">
	    <input type="button" class="button1" value="<?=t('Сохранить')?>" name="personal-submit" id="personal-submit">
	    <div class="personal-success aceter fs16 hide"><?=t('Изменения сохранены')?></div>
	    <div class="personal-error aceter fs16 hide"></div>
	</fieldset>

    </form>
</div>
<script>
    $(function() {
	var personal_form = new Form('profile-personal-form');
	$('input[id="personal-submit"]').click(function() {
	    personal_form.onSuccess = function(resp) {
		if(resp.success)
		    $('.personal-success')
			.fadeIn(300, function() { 
			    $(this).fadeOut(3000); 
			});
		else     
		    $('.personal-error')
			.html(resp.reason)
			.fadeIn(300, function() { 
			    $(this).fadeOut(3000); 
			});
	    }
	    personal_form.send();
	});
    })
</script>