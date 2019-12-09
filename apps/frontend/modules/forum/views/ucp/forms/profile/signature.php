<div class="profile-signature-box <?=(request::get('tab')=='profile' && request::get('form')=='signature') ? '' : ' hide'?>">
    <form action="/forum/profile" method="post" id="postform">

<h2>Подпись</h2>



<div class="panel">
	<div class="inner"><span class="corners-top"><span></span></span>

	<p>Это текст, который может автоматически добавляться к вашим сообщениям. Максимальная длина в символах: 255.</p>

<fieldset class="fields1">
	
<div style="display: none;" id="colour_palette">
	<dl style="clear: left;">
		<dt><label>Цвет шрифта:</label></dt>
		<dd>
		<script type="text/javascript">
		// &lt;![CDATA[
			function change_palette()
			{
				e = document.getElementById('colour_palette');
				e.slideToggle('slow')
				
			}

			colorPalette('h', 15, 10);
		// ]]&gt;
		</script>

		</dd>
	</dl>
</div>
<div id="preview-box" class="mt10 mb10 hide p10" style="background: #f0f0f0; border: 1px solid #e0e0e0;"></div>
<div id="format-buttons">
    
	<input type="button" title="Жирный текст: [b]text[/b]" onclick="bbstyle(0)" style="font-weight:bold; width: 30px" value=" B " name="addbbcode0" accesskey="b" class="button2">
	<input type="button" title="Наклонный текст: [i]text[/i]" onclick="bbstyle(2)" style="font-style:italic; width: 30px" value=" i " name="addbbcode2" accesskey="i" class="button2">
	<input type="button" title="Подчёркнутый текст: [u]text[/u]" onclick="bbstyle(4)" style="text-decoration: underline; width: 30px" value=" u " name="addbbcode4" accesskey="u" class="button2">
	
	<input type="button" title="Код: [code]code[/code]" onclick="bbstyle(8)" style="width: 40px" value="Code" name="addbbcode8" accesskey="c" class="button2">
	<input type="button" title="Список: [list]text[/list]" onclick="bbstyle(10)" style="width: 40px" value="List" name="addbbcode10" accesskey="l" class="button2">
	<input type="button" title="Нумерованный список: [list=]text[/list]" onclick="bbstyle(12)" style="width: 40px" value="List=" name="addbbcode12" accesskey="o" class="button2">
	<input type="button" title="Элемент списка: [*]text[/*]" onclick="bbstyle(-1)" style="width: 40px" value="[*]" name="addlistitem" accesskey="y" class="button2">
	
	<input type="button" title="Вставить изображение: [img]https://image_url[/img]" onclick="bbstyle(14)" style="width: 40px" value="Img" name="addbbcode14" accesskey="p" class="button2">
	
	<input type="button" title="Вставить ссылку: [url]https://url[/url] или [url=https://url]URL text[/url]" onclick="bbstyle(16)" style="text-decoration: underline; width: 40px" value="URL" name="addbbcode16" accesskey="w" class="button2">
	
	<select title="Размер шрифта: [size=85]small text[/size]" onchange="bbfontstyle('[size=' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + ']', '[/size]');this.form.addbbcode20.selectedIndex = 2;" name="addbbcode20">
		<option value="50">Очень маленький</option>
		<option value="85">Маленький</option>
		<option value="100">Нормальный</option>
		<option value="150">Большой</option>
		<option value="200">Огромный</option>
			
	</select>
	<input type="button" title="Цвет шрифта: [color=red]text[/color] Совет: вы можете использовать также конструкцию color=#FF0000" onclick="change_palette();" value="Цвет шрифта" id="bbpalette" name="bbpalette" class="button2">
	
</div>


	<div id="smiley-box" class="right">
		
		<a href="./faq.php?mode=bbcode">BBCode</a> <em>ВКЛЮЧЁН</em><br>
		
			[img] <em>ВКЛЮЧЁН</em><br>
			[flash] <em>ВЫКЛЮЧЕН</em><br>
			[url] <em>ВКЛЮЧЁН</em><br>
		
		Смайлики <em>ВКЛЮЧЕНЫ</em>
		
	</div>

	<div id="message-box">
	    <textarea class="inputbox" onfocus="initInsertions();" onkeyup="storeCaret(this);" onclick="storeCaret(this);" onselect="storeCaret(this);" tabindex="4" cols="76" rows="15" style="height: 9em;" id="message" name="message"><?=  stripslashes($forum_user['signature'])?></textarea>
	</div>
</fieldset>


	<h3>Настройки</h3>
	<fieldset>
		
			<div><label for="disable_bbcode"><input type="checkbox" id="disable_bbcode" name="disable_bbcode"> Отключить в этом сообщении BBCode</label></div>
		
			<div><label for="disable_smilies"><input type="checkbox" id="disable_smilies" name="disable_smilies"> Отключить в этом сообщении смайлики</label></div>
		
			<div><label for="disable_magic_url"><input type="checkbox" id="disable_magic_url" name="disable_magic_url"> Не преобразовывать адреса URL в ссылки</label></div>
		

	</fieldset>

	<span class="corners-bottom"><span></span></span></div>
</div>

    <fieldset class="submit-buttons">
	<input type="button" class="default-preview-action" value="<?=t('Предпросмотр')?>" name="preview"/>&nbsp;
	<input type="button" class="sugnature-submit-action" value="<?=t('Сохранить')?>" name="default-submit-action"/>
	<div class="signature-success hide fs16 acenter"><?=t('Изменения сохранены')?></div>
	<div class="signature-error hide fs16 acenter"></div>
    </fieldset>
</form>
</div>
<script>
    $(function(){
	var form = new Form('postform');
	$('.default-preview-action').click(function() {
	    
	    form.data['act'] = 'preview';
	    
	    form.onSuccess = function(resp) {
		if(resp.success)
		    $('#preview-box')
			.html(resp.content)
			.removeClass('hide');
		else console.log(resp);
	    }
	    form.send();
	});
	$('.sugnature-submit-action').click(function() {
	    
	    form.data['act'] = 'save_profile';
	    form.data['subaction'] = 'signature';
	    
	    form.onSuccess = function(resp) {
		if(resp.success)
		    $('.signature-success')
			.fadeIn(300, function() {
			    $(this).fadeOut(3000);
			});
		else $('.signature-error')
			.html(resp.reason)
			.fadeIn(300, function() {
			    $(this).fadeOut(3000);
			});
	    }
	    form.send();
	});
	
    });
</script>