<style>
    
    .form dl {
        float:left;
        width: 100%;
        margin: 10px 0 0 0px;
    }
    .form dt {
        float:left;
        padding: 4px;
        width: 200px;
        font-size: 14px;
        text-align: right;
    }
    .form dd {
        float:left;
        padding: 4px;
        width: 350px;
        margin-left: 0px;
    }
</style>
<form onsubmit="" action="/feedback/index" method="POST" class="form cgray left" id="send_form">
    <input type="hidden" name="send" value="1"/>
	<dl>
            <div style="" id="password_email_h">
                <dt>
                    <label for="password_email"><?=t("E-почта")?> <span class="starred">*</span></label>
                </dt>
                <dd>
                    <input type="text" value="" name="email" class="itext left">
                </dd>
            </div>
            <div class="clear"></div>
            <div style="" id="password_name_h">
                <dt>
                    <label for="password_name"><?=t("Имя")?> <span class="starred">*</span></label>
                </dt>
                <dd>
                    <input type="text" value="" name="name" class="itext left">
                </dd>
            </div>
            <div class="clear"></div>
            <div style="" id="password_last_name_h">
                <dt>
                    <label for="password_last_name"><?=t("Фамилия")?> <span class="starred">*</span></label>
                </dt>
                <dd>
                    <input type="text" value="" name="last_name" class="itext left">
                </dd>
            </div>
            <div class="clear"></div>
            <div style="" id="password_phone_h">
                <dt>
                    <label for="password_phone"><?=t("Контактный телефон")?></label>
                </dt>
                <dd>
                    <input type="text" value="" name="phone" class="itext left">
                </dd>
            </div>
            <div class="clear"></div>
            <div style="" id="password_text_h">
                <dt>
                    <label for="password_text"><?=t("Текст")?> <span class="starred">*</span></label>
                </dt>
                <dd>
                    <textarea style="width:350px;height:150px;" name="text" class="iarea"></textarea>
                </dd>
            </div>
        </dl>
	<dl>
		<dt class="sm cgray" style="font-size: 12px;">
                    <span class="starred cgray" style="font-size: 12px;">*</span> &mdash; <?=t("обязательные поля")?>
                </dt>
                <div class="clear"></div>
                <dt class="sm">
                    &nbsp;
                </dt>
		<dd style="margin-left: 0px;">
                    <input type="button" class="button" name="submit" onClick="Validate('#send_form')" value="<?=t("Отправить письмо")?>">
                </dd>
	</dl>
	
</form>
<div class="clear"></div>
<div class="screen_message hide p10 fs14 acenter">
    <?=t("Спасибо за Ваше обращение. <br/> В ближайшее время с Вами свяжутся представители Ассоциации Моделей Украины")?>.
</div>
<div class="error hide p10 acenter">

</div>
<script>
function Validate(id) {
    _this = $(id);
    console.log(_this);
    $.ajax({
        type: 'post',
        url: '/feedback/index',
        data: _this.serialize(),
        success: function(resp) {
            data = eval("("+resp+")");
            if(data.success==1) {
                _this.hide();
                $('.screen_message').show();
            }
            else 
                $('.error').fadeIn(300,function(){$(this).html(data.reason); $(this).fadeOut(5000,function(){$(this).html('')});})
        }
    });
    return false;
}
</script>