<script>
    $(function() {
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
	$('.default-submit-action').click(function() {
	    var redirect_url = window.location;
	    switch($('input[name="tp"]').val()) {
		case 'tpc':
		    form.data['act'] = 'save_topic';
		    redirect_url = '/forum/view?f='+$('input[name="pid"]').val()+'<?=(request::get_int('page') ? '&page='.request::get_int('page') : '')?>';
		    break;
		case 'msg':
		    form.data['act'] = 'save_post';
		    redirect_url = '/forum/viewtopic?t='+$('input[name="pid"]').val()+'<?=(request::get_int('page') ? '&page='.request::get_int('page') : '')?>';
		    break;
	    }
	    
	    form.onSuccess = function(resp) {
		if(resp.success)
		    window.location = redirect_url;
		else 
		    console.log(resp);
	    }
	    
	    form.send();
	})
    })
    
    
</script>
<script type="text/javascript">
// &lt;![CDATA[



// ]]&gt;
</script>
<script>
    $(function() {
	$("img[id^='code_']").click(function() {
	    insert_text($(this).attr('id').split('_')[1]);
	    document.forms[form_name].elements[text_name].focus();
	});
    });
</script>
<?
if(request::get('action')=='compose') 
    include 'partials/top_menu.php';
?>
<div class="clear"></div>
<form action="/forum/compose" method="post" id="postform">
<input type="hidden" name="tp" value="<?=request::get('tp')?>"/>
<input type="hidden" name="pid" value="<?=request::get_int('pid')?>"/>
<div class="forumbg">
	<ul class="topiclist" style=" <?=(request::get('action')!='compose') ? 'display: none;' : ''?>">
		<li class="header" style="padding: 0px;">
			<dl class="icon">
				<dt class="topic"><?=$title?>
				
					: <a href="/forum/view?f=<?=$parent['id']?>"><?=$parent['subject']?></a>
				
				</dt>
			</dl>
		</li>
	</ul>
	<div id="postingbox" class="panel">
		<div class="inner">
		<script type="text/javascript">
// &lt;![CDATA[
	//onload_functions.push('apply_onkeypress_event()');
// ]]&gt;
</script>

<fieldset class="fields1">
	<input type="hidden" name="id" value="<?=$edit_data['id']?>"/>
	<dl style="clear: left;">
		<dt><label for="subject">Заголовок:</label></dt>
		<dd><input type="text" class="inputbox autowidth" tabindex="2" maxlength="60" size="45" id="subject" name="subject" value="<?=($edit_data) ? stripslashes(trim($edit_data['subject'])) : 'Re: '.stripslashes(trim($parent['subject']))?>"></dd>
	</dl>
	<div id="colour_palette" class="hide">
	    <dl style="clear: left;">
		    <dt><label>Цвет шрифта:</label></dt>
		    <dd>
		    <script type="text/javascript">
		    // &lt;![CDATA[
			    function change_palette()
			    {
				    $('#colour_palette').toggleClass('hide');
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

		<input type="button" title="Цитата: [quote]text[/quote]" onclick="bbstyle(6)" style="width: 50px" value="Quote" name="addbbcode6" accesskey="q" class="button2">

		<input type="button" title="Код: [code]code[/code]" onclick="bbstyle(8)" style="width: 40px" value="Code" name="addbbcode8" accesskey="c" class="button2">
		<input type="button" title="Список: [list]text[/list]" onclick="bbstyle(10)" style="width: 40px" value="List" name="addbbcode10" accesskey="l" class="button2">
		<input type="button" title="Нумерованный список: [list=]text[/list]" onclick="bbstyle(12)" style="width: 40px" value="List=" name="addbbcode12" accesskey="o" class="button2">
		<input type="button" title="Элемент списка: [*]text[/*]" onclick="bbstyle(-1)" style="width: 40px" value="[*]" name="addlistitem" accesskey="y" class="button2">

			<input type="button" title="Вставить изображение: [img]https://image_url[/img]" onclick="bbstyle(14)" style="width: 40px" value="Img" name="addbbcode14" accesskey="p" class="button2">

			<input type="button" title="Вставить ссылку: [url]https://url[/url] или [url=https://url]URL text[/url]" onclick="bbstyle(16)" style="text-decoration: underline; width: 40px" value="URL" name="addbbcode16" accesskey="w" class="button2">

		<select title="Размер шрифта" onchange="bbfontstyle('[size=' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + ']', '[/size]');this.form.addbbcode20.selectedIndex = 2;" name="addbbcode20">
			<option value="50">Очень маленький</option>
			<option value="85">Маленький</option>
			<option value="100">Нормальный</option>
			<option value="150">Большой</option>
			<option value="200">Огромный</option>
		</select>
		<input type="button" title="Цвет шрифта: [color=red]text[/color] Совет: вы можете использовать также конструкцию color=#FF0000" onclick="change_palette();" value="Цвет шрифта" id="bbpalette" name="bbpalette" class="button2">

<!--		<input type="button" title="Вставить видео YouTube" onclick="bbstyle(22)" value="YouTube" name="addbbcode22" class="button2">-->

	</div>

	<?if(request::get('action')=='compose') {?>
	<div id="smiley-box" class="right fs14">
		<hr>
		<a href="javascript:void(0);">BBCode</a> <em>ВКЛЮЧЁН</em><br>
		
			[img] <em>ВКЛЮЧЁН</em><br>
			[flash] <em>ВЫКЛЮЧЕН</em><br>
			[url] <em>ВКЛЮЧЁН</em><br>
		
			<div class="forumbg mt5" style="width: auto;">
			    <ul class="topiclist">
				    <li class="header" style="padding: 0px; line-height: 14px;">
					    <dl class="icon">
						    <dt class="topic">
							Смайлики
						    </dt>
					    </dl>
				    </li>
			    </ul>
			    <div class="smile-list" style="overflow-y: scroll; height: 145px;">
				<table style="width: 150px;">
				    <tr><td>
				<?
				foreach($smiles as $code=>$image) {?>
					<?=$image?>
				<? } ?>
					</td></tr>
				</table>
			    </div>
			</div>
		
	</div>
	<? } ?>
	<div id="message-box" class="mt5">
		<textarea class="inputbox" onfocus="initInsertions();" onkeyup="storeCaret(this);" onclick="storeCaret(this);" onselect="storeCaret(this);" tabindex="4" cols="76" rows="15" id="message" name="message"><?=  stripslashes(trim($edit_data['body']))?></textarea>
	</div>
</fieldset>
		</div>
	</div>
	<div class="panel bg2">
		<div class="inner">
		<fieldset class="submit-buttons">
			<input type="button" class="button2 default-draft-action" value="Сохранить черновик">&nbsp; 
			<input type="submit" class="button1 default-preview-action" value="Предпросмотр" name="preview" tabindex="5">&nbsp;
			<input type="submit" class="button1 default-submit-action" value="Отправить" name="post" tabindex="6" accesskey="s">&nbsp;
		</fieldset>

		</div>
	</div>
</div>
</form>
