<a href="/messages" class="fs12 mt5 left">&larr;&nbsp;<?=t('Назад к сообщениям')?></a><br/>
<div class="clear"></div>
<div class="square_p pl15 mt10 mb20 fs12 ucase bold left pointer" onclick="$('#messages').slideToggle()">
	<?=t('История сообщений')?>: <?=  profile_peer::get_name($messager);?>
</div>
<div class="clear"></div>
<div class="left acenter">
	<div class="left acenter">
	    <a href="/profile?id=<?=$messager['user_id']?>">
		<?=ui_helper::photo($messager,array('style'=>'width: 100px;'))?>
	    </a><br/>
<!--	    <a href="/profile?id=<?=$messager['user_id']?>"><?=profile_peer::get_name($messager)?></a>-->
	</div>
</div>
<div style="margin-left: 140px;" class="ml10 mr10">
	<div id="messages">
	    <?foreach ($list as $mid) {
		    $message = messages_peer::instance()->get_item($mid); ?>
	    <?$administrator_data = user_data_peer::instance()->get_item($message['sender']);?>
		    <?$uid = ($message['receiver']!=-1) ? $message['receiver'] : $message['sender'];?>
		    <?if($message['type']==messages_peer::ADMIN_TYPE && $uid!=$message['sender']) {?>
			<?$name = t('Администрация ModelsUA')?>
			<?$name.="(".profile_peer::get_name($administrator_data).")"?>
			<?$color = 'background: #ffffff;';?>
		    <?} else {?>
		    	<?$name = profile_peer::get_name(user_data_peer::instance()->get_item($message['sender']));?>
			<?$color = ($message['sender']==session::get_user_id() && $message['type']!=1) ? '' : 'background: #f0f0f0;';?>
		    <? } ?>
		    
	    <div class="mr10 mb5 p10 fs11" style="width: 500px;<?=$color?>" id="message_<?=$mid?>">
		<a href="/profile?id=<?=$message['sender']?>" class="bold cblack fs11">
		    <?=$name?>
		</a>		
		<span class="cgray fs11 ml10"><?=date('H:i d.m.Y',$message['created_ts'])?></span>
		<a href="javascript:void(0);" class="ml10 right" onclick="if(confirm('<?=t('Удалить сообщение')?>?')) { App.delete_message('<?=$mid?>','message_<?=$mid?>') }"><?=t('Удалить')?></a>
		<a href="/messages?resend=<?=$mid?>" class="ml10 right"><?=t('Переслать')?></a>
		<div class="mt5 fs12">
		   <?=$message['body']?>
		</div>
	    </div>
	    <? } ?>
	    <div class="paginator ml10 fs12" style="text-align: left;">
		<?if($pager->get_pages()>1) echo pager_helper::get_full($pager)?>
	    </div>
	</div>
    
    
    <div class="form_bg">
	    <form class="m10" action="/messages/reply" name="reply_form" id="reply_form">
		<h3 class="column_head_small"><?=t('Написать сообщение')?></h3>
		<?
		    if($message['type']==messages_peer::ADMIN_TYPE && session::has_credential('amu')) {
			$receiver_id = ($message['receiver']==-1) ? $message['sender'] : $message['receiver'];
		    }
		    elseif($message['type']==messages_peer::ADMIN_TYPE) {
			$receiver_id = -1;
		    }
		    else {
			$receiver_id = $messager['user_id'];
		    }
		?>
		<input type="hidden" value="<?=$receiver_id?>" id="receiver_id">
		<input type="hidden" value="0" id="parent_id">
		<textarea style="width: 500px; height: 150px;" id="body"></textarea><br/>
		<input type="button" value="<?=t('Отправить')?>" class="mt5 mb5 button"  id="send-message-button">
	    </form>
	</div>
    <div class="error hide"></div>
</div>
<script>
$('input[id="send-message-button"]').click(function(){
	$.post(
	    '/messages/index',
	    {
		"send_message": 1,
		"receiver_id": $('input[id="receiver_id"]').val(),
		"body": $('textarea[id="body"]').val()
	    },
	    function(resp) {
		console.log(resp);
		if(resp.success) {
		    window.location = window.location;
		}
		else {
		    $('.error').html(resp.reason);
		    $('.error').fadeIn(300, function() { $(this).fadeOut(5000) });
		}
	    },
	    'json'
	);
    });	
</script>