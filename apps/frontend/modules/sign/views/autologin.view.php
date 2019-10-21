<div style="width: 708px;">
    <div style="padding: 100px 0;">
        <center>
            <form id="save_user_auth">
                <input type="hidden" name="code" value="<?=  request::get('code')?>">
                <input type="hidden" name="save_params" value="1">
                <table>
                    <tr>
                        <td>
                            E-mail:
                        </td>
                        <td>
                            <input type="text" name="new_email"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Пароль:
                        </td>
                        <td>
                            <input type="password" name="new_passwd"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Повторите пароль:
                        </td>
                        <td>
                            <input type="password" name="new_passwd_confirm"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            &nbsp;
                        </td>
                        <td>
                            <input type="button" name="save_params" value="Сохранить"/>
                        </td>
                    </tr>
                </table>
            </form>
            <div class="clear"></div>
            <div class="error hide"></div>
        </center>
    </div>
</div>
<script>
    $('input[name="save_params"]').click(function(){
        $.post(
            '/sign/autologin',
            $('#save_user_auth').serialize(),
            function(resp) {
                if(resp.success) 
                    window.location = resp.url;
                else {
                    $('.error').html(resp.reason);
                    $('.error').fadeIn(300, function() {  $(this).fadeOut(10000);});
                }
            },
            'json'
        );
    })
</script>