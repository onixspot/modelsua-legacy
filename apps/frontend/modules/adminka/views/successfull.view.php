<script src="/static/javascript/library/form/form.js"></script>
<script src="/static/javascript/library/form/validators.js"></script>
<div class="left fs12">
    <?include 'admin_menu.php'?>
</div>
<div style="width: 75%; margin-left: 10px;" class="left">
        <table style="width:100%">
            <tr>
		<th>№</th>
                <th>id</th>
		<th style="width: 50px;">Фото</th>
                <th>Користувач</th>
                <th>Дія</th>
            <tr>
            <?if($most) {?>
            <?foreach($most as $k=>$uid) {
                $uinfo = user_data_peer::instance()->get_item($uid);
                ?>
            <tr style="background: <?=($k%2===0) ? '#f0f0f0;' : '#ffffff;'?>">
		<td class="acenter bold">
                    <?=($k+1)?>
                </td>
                <td class="acenter">
                    <?=$uid?>
                </td>
		<td class="acenter bold">
		    <?$crop = unserialize($uinfo['ph_crop'])?>
		    <?$v = $uinfo?>
		    <?$src = "https://".conf::get('server')."/imgserve?pid=".$v['pid']."&w=".$crop['w']."&h=".$crop['h']."&x=".$crop['x']."&y=".$crop['y']."&z=crop";?>
                    <img src="<?=$src?>" style="width: 50px;"/>
                </td>
                <td>
                    <a href="/profile?id=<?=$uid?>"><?=$uinfo['first_name'].' <b>'.mb_strtoupper($uinfo['last_name']).'</b>'?></a>
                </td>
                <td class="acenter">
                    <a href="javascript:;" onClick="changePlace('<?=$uid?>',2)">
                        <img src="/ui/up.png">
                    </a><a href="javascript:;" onClick="changePlace('<?=$uid?>',1)">
                        <img src="/ui/down.png">
                    </a>
                    
                    <a href="javascript:;" onClick="deleteItem('<?=$uid?>')">
                        <img src="/ui/delete.png">
                    </a>
                    
                </td>
            </tr>
        
    <? } } else { ?>
            <td colspan="5">
                <div class="message_box acenter p20 mb20" style="color: #838999; border: 1px solid #ccc;">
                    Не найдено ни одного пользователя
                </div>
            </td>
    <? } ?>
    </table>
    <div class="clear"></div>
    <form id="add_most" method="post">
        <input type="hidden" name="type" value="add">
        <table>
            <tr>
                <td>
                    Id користувача:
                </td>
                <td>
                    <input type="text" class="text" name="id" rel="Введіть АйДі користувача">
                </td>
                <td>
                    <input type="button" name="add_user" class="button" value="Додати" onClick="adduser()">
                </td>
            </tr>
            <tr>
                <td>
                    Отображать:
                </td>
                <td colspan="5">
		    <?
			switch(request::get_int('mt')) {
			    case 1:
				$key = 'successfull_models_view_type';
				break;
			    case 2:
				$key = 'perspective_models_view_type';
				break;
			    default:
				$key =  'new_faces_view_type';
				break;
			}
		    ?>
                    <input type="radio" name="view[]" value="0" <?// if(db_key::i()->exists($key)){ ?><?=(db_key::i()->get($key) ? '' : ' checked')?><?// } ?>/>по порядку
                    <input class="ml10" type="radio" name="view[]" value="1" <?// if(db_key::i()->exists($key)){ ?><?=(!db_key::i()->get($key) ? '' : ' checked')?><?// } ?>/>случайно
                </td>
            </tr>
        </table>
    </form>
    <div class="success hide"><?=t('Изменения сохранены')?></div>
    <div class="error hide"></div>
</div>
<script>
    function deleteItem(id) {
        $.post('/adminka/successfull', 
                {
                    submit: 1, 
                    type: 'delete', 
                    id:id,
		    mt: '<?=  request::get_int("mt")?>'
                },
                function(resp) {
                    data = resp;
                    if(data.success==1) { 
                        $('.success').fadeIn(300,function() {$(this).fadeOut(500,function() {window.location = window.location;}); }); 
                        
                    }
                    if(data.success==0) {
                        $('.error').html(data.reason);
                        $('.error').fadeIn(300,function() {$(this).fadeOut(5000); }); 
                    }
                },
                'json'
    );
    }
    function changePlace(id,direct) {
        $.post('/adminka/successfull', 
                {
                    submit: 1, 
                    type: 'change_place', 
                    id:id,
                    direct: direct,
		    mt: '<?=  request::get_int("mt")?>'
                },
                function(resp) {
                    data = eval('('+resp+')');
                    if(data.success==1) { 
                        $('.success').fadeIn(300,function() {$(this).fadeOut(500,function() {window.location = window.location;}); }); 
                        
                    }
                    if(data.success==0) {
                        $('.error').html(data.reason);
                        $('.error').fadeIn(300,function() {$(this).fadeOut(5000); }); 
                    }
        });
    }
    function adduser() {
        $.post('/adminka/successfull', 
                {
                    submit: 1, 
                    type: 'add', 
                    id:$('input[name="id"]').val(),
		    mt: '<?=  request::get_int("mt")?>'
                },
                function(resp) {
                    data = resp;
                    if(data.success==1) { 
                        $('.success').fadeIn(200,function() { $(this).fadeOut(500,function(){window.location = window.location;}); }); 
                    }
                    if(data.success==0) {
                        $('.error').html(data.reason);
                        $('.error').fadeIn(300,function() {$(this).fadeOut(5000); }); 
                    }
                },
                'json'
        );
    }
    $(function() {
        $('input[name="view[]"]').change(function(){
            $.post(
                '/adminka/successfull', 
                {
                    submit: 1, 
                    type: 'change_view', 
                    val: $(this).val(),
		    mt: '<?=  request::get_int("mt")?>'
                },
                function(resp) {console.log(resp);}
            );
        });
    });
    
</script>
