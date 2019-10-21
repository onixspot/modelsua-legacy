<div id="popup_title_content" class="hide">Вход</div>
<div class="hide" id="popup_content">
	<div class="fs14 cblack" style="margin-top: -10px;">
	    <p><?=t("Здравствуйте")?>,&nbsp;<?=profile_peer::get_name($profile)?></p>
	    <p><?=t("Пожалуйста, введите свой email и пароль,<br/> которые Вы будете использовать при последующих входах<br/> на сайт modelsua.org")?></p>
	    
	</div>
	<form id="save_user_auth">
	    <input type="hidden" name="code" value="<?=  request::get('code')?>">
	    <input type="hidden" name="save_params" value="1">
	    <table>
		<tr>
		    <td class="fs13 aright">
			<?=t("E-mail")?>:
		    </td>
		    <td>
			<input type="text" style="width: 250px;"  name="new_email"/>
		    </td>
		</tr>
		<tr>
		    <td class="fs13 aright">
			<?=t("Пароль")?>:
		    </td>
		    <td>
			<input type="password" style="width: 250px;" name="new_passwd"/>
		    </td>
		</tr>
		<tr>
		    <td class="fs13 aright">
			<?=t("Повторите пароль")?>:
		    </td>
		    <td>
			<input type="password" style="width: 250px;"  name="new_passwd_confirm"/>
		    </td>
		</tr>
		<tr>
		    <td>
			&nbsp;
		    </td>
		    <td>
			<input type="button" class="mt5" name="save_buttons" value="<?=t("Сохранить")?>"/>
			<input type="button" class="mt5 ml5" name="cancel" onClick="$(window).hidePopup()" value="<?=t("Отменить")?>"/>
		    </td>
		</tr>
	    </table>
	</form>
	<div class="clear"></div>
	<div class="error hide"></div>
</div>
<script>
$(document).ready(function(){
   $.fn.showPopup = function(){
       if($.browser.msie) {
	 $('#opaco').height($(document).height()).toggleClass('hide');
	 $(this).showWnd();
       }
       else
	 $('#opaco').height($(document).height()).toggleClass('hide').fadeTo('slow', 0.7, function() {$(this).showWnd();});

       
     }
     $.fn.hidePopup = function(){window.location = '/';}
     $.fn.showWnd = function(){
	   Popup.show();
	   Popup.setTitle($('#popup_title_content').html());
	   Popup.setHtml($('#popup_content').html());
	   $('#popup_content').remove();
	   Popup.position();
	   $('input[name="save_buttons"]').bind('click',function(){
		$.post(
		    '/sign/autologin',
		    $('#save_user_auth').serialize(),
		    function(resp) {
			if(resp.success) 
			    window.location = resp.url;
			else {
			    $('.error').html(resp.reason);
			    $('.error').fadeIn(300, function() {  $(this).fadeOut(3000);});
			}
		    },
		    'json'
		);
	    });
     }
     $(window).showPopup(); 
     
     
});

</script>

