<style>
    .amu_messages_menu {
	border-bottom: 1px solid #a0a0a0;
    }
    .amu_messages_menu a {
	border: 1px solid #a0a0a0;
	border-bottom: 0px;
	padding: 0 10px;
	margin-left: 10px;
    }
    .amu_messages_menu a:hover {
	background: #f0f0f0;
    }
    .amu_messages_menu a.selected {
	border: 1px solid #a0a0a0;
	border-bottom: 1px solid white;
    }
</style>
<?if(session::has_credential('admin') || session::has_credential('amu')) {?>
<div id="list-box-private" style="width: 700px;">
<div class="amu_messages_menu">
    <?
    $nv = ac_helper::get_not_viewed();
    ?>
    <?if(session::has_credential('amu') || !session::has_credential('admin')) {?>
    <a class="<?=($type=='public') ? ' selected ' : ''?>" href="/messages?type=public<?=(request::get('selector') ? '&selector='.request::get('selector') : '')?>"><?=t('Переписка Администрации').' ('.$nv['public'].')'?></a>
    <? } ?>
    <?if(session::has_credential('amu') || session::has_credential('admin')) {?>
	<a class="<?=$type=='private' ? ' selected ' : ''?>" href="/messages?type=private<?=(request::get('selector') ? '&selector='.request::get('selector') : '')?>"><?=t('Частная переписка').' ('.$nv['private'].')'?></a>
    <? } ?>
</div>
<? } ?>

<div id="messages-title">
<div class="mb5 right mt15">
    <a href="javascript:void(0);" class="dotted fs11 ml5" id="select_all"><?=t('Выбрать все')?></a>
    <a class="dotted fs11 ml10" onclick="m.deleteMessages()" href="javascript:void(0);"><?=t('Удалить')?></a>
    <a class="dotted fs11 ml10" onclick="m.markViewed()" href="javascript:void(0);"><?=t('Отметить прочитанными')?></a>
</div>
<div class="mb5 left mt15 " style="text-transform: uppercase;">
    <a href="/messages?selector=all<?=(request::get('type') ? '&type='.request::get('type') : '')?>" class="fs11 ml5"><?=t('Все')?></a>
<!--    <a href="/messages?selector=sender<?=(request::get('type') ? '&type='.request::get('type') : '')?>" class="fs11 ml5"><?=t('Отправленные')?></a>-->
<!--    <a href="/messages?selector=receiver<?=(request::get('type') ? '&type='.request::get('type') : '')?>" class="fs11 ml5"><?=t('Принятые')?></a>-->
    
</div>
<div class='clear'></div>
<div id="message-list" style="width: 700px;">
<?
if($list) {
    foreach ($list as $mid) {
	$message = messages_peer::instance()->get_item($mid);
	$sender = user_data_peer::instance()->get_item($message['sender']);
	$receiver = user_data_peer::instance()->get_item($message['receiver']);
	
	if(db_key::i()->exists('message_'.$mid)) {
	    $ids = unserialize(db_key::i()->get('message_'.$mid));
	    $viewed = (in_array(session::get_user_id(),$ids)) ? true : false;
	}
	
	switch($user_type) {
	    case 'administration':
		if($type=='private') $color = (!$message['receiver_action'] && $message['receiver']==session::get_user_id()) ? '#ccc' : '#f7f7f7' ;
		else $color = (($message['receiver']==-1 && !$message['receiver_action']) || $viewed) ? '#ccc' : '#f7f7f7';
		break;
	    case 'admin':
	    case 'model':
		$color = (!$message['receiver_action'] && $message['receiver']==session::get_user_id()) ? '#ccc' : '#f7f7f7' ;
		break;
	}
	unset($viewed);
?>

<div 
     class="thread p10 mb10 highlight box_empty" 
     onmouseout="document.getElementById('controls_message_<?=$mid?>').style.display ='none'" 
     onmouseover="document.getElementById('controls_message_<?=$mid?>').style.display ='block'" 
     id="message_<?=$mid?>" style="background: <?=$color?>; border: 1px solid #ffffff;"
 >
	<div class="left mr10">
		<input type="checkbox" class="messages_checkbox" value="<?=$message['id']?>" name="messages_list[]">
	</div>

	<a href="/messages/view?id=<?=$message['id']?>"></a>
	<?if($message['type']==1) {?>
	    <img src="/no_image.png" style="width: 60px;" class="left"/>
	<? } else {?>
	<?$user_data = $sender;?>
	    <a href="/profile?id=<?=$user_data['user_id']?>">
		<?=ui_helper::photo($user_data,array('style'=>'width: 60px;','class'=>'left'))?>
	    </a>       
	<? } ?>
	<div style="margin-left: 100px;" class="pointer ml10">
		<div class="bold left fs12">
                    <a style="color:black;text-decoration:none" href="/messages/view?id=<?=$mid?>">
			
			
			<?if($message['type']==1) {?>
			    <?if($message['receiver']==-1) {?>
				<?=profile_peer::get_name($sender)?>
			    <? } else {?>
				<?=t("Администрация ModelsUA")?>
				(<?=profile_peer::get_name($sender)?>)
				&nbsp;&rarr;&nbsp;
				<?=profile_peer::get_name($receiver)?>
			    <? } ?>
			<? } else {?>
			    <?if($sender['user_id']==session::get_user_id()) {?>
				<?=t('Я')?>
				&nbsp;&rarr;&nbsp;
				<?=  profile_peer::get_name($receiver)?>
			    <? } else { ?>
				<?=profile_peer::get_name($sender)?>
			    <? } ?>
			<? } ?>
			<?$msg_count = db::get_scalar("SELECT COUNT(id) FROM messages WHERE parent_id=:pid AND parent_id>0",array('pid'=>$message['parent_id']));?>
			<?=($msg_count) ? "(".($msg_count+1).")" : ''?>	
		    </a>
		</div>
		<div class="right cgray fs11">
                        <?=date('H:i d.m.Y',$message['created_ts'])?><br>
                        <div id="controls_message_<?=$mid?>" class="fs11 quiet hidden mt10" style="display: none;">
                            <a onclick="if(confirm('<?=t('Удалить сообщение')?>?')) { App.delete_message('<?=$mid?>','message_<?=$mid?>') }" href="javascript:void(0);"><?=t('Удалить')?></a><br/>
			    <a onclick='$("#compose_form").css("display","block");$("textarea[id=&apos;body&apos;]").html("<?=t('Пересланое сообщение')?>:\n\n"+$.trim($("#body_<?=$mid?>").html()));' href="#logout"><?=t('Переслать')?></a>
                        </div>
                </div>
		<br>
		<div style="width:480px" class="fs11 quiet">
		    <a href="/messages/view?id=<?=$mid?>" style="color:black;text-decoration:none">
			<span class="cblack bold"><?=t('Тема')?>:</span>			
			<span class="cgray"><?=($message['subject']) ? mb_substr($message['subject'],0,100).'...' : 'без темы'?></span>			
			<br/>
			<span class="cblack bold"><?=t('Сообщение')?>:</span>			
			<span class="cgray">
				<?=mb_substr($message['body'],0,250).'...'?>
			</span>
			<span class="cgray hide" id="body_<?=$mid?>">
				<?=$message['body']?>
			</span>
		    </a>
                </div>
	</div>
	<div class="clear"></div>
</div>
<? } ?>
   <div class="paginator fs12 left mb10 mt10">
	    <?=pager_helper::get_full($pager)?>
    </div>
<? } ?>
    <div class="clear"></div>
</div>
</div>
<script>
    var messagesController = function() {
	this.init = function() {
	    $("#select_all").bind('click',function() {select_all(1);});
	}
	
	var select_all = function(check) {
	    if(check) {
		$("input[name='messages_list[]']").attr("checked",1);
		$("#select_all")
		    .unbind()
		    .html('<?=t('Снять отметки')?>')
		    .bind('click',function() {
			select_all(false)
		    });
	    }
	    else {
		$("input[name='messages_list[]']").removeAttr("checked");
		$("#select_all")
		    .unbind()
		    .html('<?=t('Выбрать все')?>')
		    .bind('click',function() {
			select_all(true)
		    });
	    }
	}
	
	this.deleteMessages = function() {
	    var messageList = [];
	    $("input[name='messages_list[]']:checked").each(function(){messageList.push($(this).val());});
	    App.delete_message(messageList,'');
	    window.location=window.location;
	}
	
	this.markViewed = function() {
	    var messageList = [];
	    $("input[name='messages_list[]']:checked").each(function(){messageList.push($(this).val());});
	    $.post(
		'/messages/index',
		{
		    'mark_viewed': 1,
		    'messages_list': messageList
		},
		function(resp) {
		    if(resp.success)
			window.location=window.location;
		},
		'json'
	    );
	    
	}
    }
    var m = new messagesController();
    m.init();
</script>
</div>
