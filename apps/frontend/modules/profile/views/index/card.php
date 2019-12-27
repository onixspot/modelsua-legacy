<div class="square_p pl15 mb10 fs12 ucase bold left">
        <a class="cblack left" href='javascript:void(0);' onclick="$('#profile-card-shower').toggleClass('hide')"><?=t("Визитка")?></a>
        
</div>
<?$pid = db::get_cols("SELECT id FROM user_photos WHERE user_id=:uid AND type=:type AND name IN ('ru','en')",array('uid'=>  $user_id, 'type'=>  user_photos_peer::TYPE_CARD));?>

<a  
    onmouseout="$(this).css('color', 'gray')" 
    onmouseover="$(this).css('color', 'black')" 
    href="/profile/edit?id=<?=$profile['user_id']?>&frame=card" id="add_card_link" class="fs12 underline cgray right <?if(count($pid)==2) {?>hide<? } ?>" style="color: gray;"><?=t('Добавить визитку')?></a>
<div class="clear"></div>

<?if(!empty($pid)) {?>
<div class="clear"></div>
<div class="p10 mb10" id="cards_box"  style="background: #eee; border: 1px solid #ccc">
    <table class="mb10">
	<tr>
            <?foreach($pid as $id) {?>
            <?$card_data = user_photos_peer::instance()->get_item($id);?>
	    <td style="padding-left: 0px;" class="profile-card-shower-<?=$id?>">
                <img src="/imgserve?pid=<?=$id?>" style="width: 70px;"/>
	    </td>
            <td style="padding-left: 10px; vertical-align: middle;"  class="profile-card-shower-<?=$id?>">
                <?if(session::has_credential('admin') || session::get_user_id()==$profile['user_id']) {?>
		    <div style="width: 100px;">
			    <a 
				href="javascript:void(0);"
				id="card-save-menu-<?=$id?>" 
				rel="" 
				style="color: rgb(185, 83, 131);"
				onclick=" "
			    >
					<?=t('Сохранить')?>
			    </a>
			    <div id="save-menu-<?=$id?>" style="position: absolute; background: none repeat scroll 0% 0% white; border: 1px solid rgb(185, 83, 131); box-shadow: 0px 1px 3px black; width: 98px;" class="pt5 pb5 hide acenter">
				    <a href="/profile/card?get_card=<?=$card_data['name']?>&type=blank">для принтера</a><br/>
				    <a href="/profile/card?get_card=<?=$card_data['name']?>&type=card">для печати</a>
			    </div>
			<script>
			    var show=false;
			    var _menu;
			    
			    $('a[id^="card-save-menu-"]').hover(
				function(){
				    var id =$(this).attr('id').split('-')[3];
				    _menu = $('#save-menu-'+id);
				    _menu.removeClass('hide');
				},
				function(){
				    setTimeout("if(!show) _menu.addClass('hide')",500);
				}
			    );
			    $('div[id^="save-menu-"]').hover(
				function(){show=true;},
				function(){$(this).addClass('hide');show=false;}
			    );
			</script>
		    </div>
                    
                    <a href="/profile/edit?id=<?=$profile['user_id']?>&frame=card"><?=t('Редактировать')?></a><br/>
                    <a href="javascript:void(0);" onClick="deleteCard('<?=$id?>')"><?=t('Удалить')?></a>
                <? } ?>
	    </td>
            <? } ?>
	</tr>
	
    </table>
</div>
<div class="clear"></div>
<? } ?>
<script>
    var deleteCard = function(id) {
        $.post(
            '/profile/card',
            {'delete_card' : id},
            function(resp) { 
                if(resp.success) { 
                    $('td[class="profile-card-shower-'+id+'"]').remove(); 
                        $('#add_card_link').removeClass('hide'); 
                        if(!$('td').attr('class')) 
                            $('#cards_box').remove();
                        
                    } 
                },
            'json'
        );
    }
</script>
