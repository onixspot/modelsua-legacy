<style>
    .amu_messages_menu {
	border-bottom: 1px solid #a0a0a0;
    }
    .amu_messages_menu a {
	border: 1px solid #a0a0a0;
	border-bottom: 0px;
	padding: 0 10px;
	margin-left: 3px;
    }
    .amu_messages_menu a:hover {
	background: #f0f0f0;
    }
    .amu_messages_menu a.selected {
	border: 1px solid #a0a0a0;
	border-bottom: 1px solid white;
    }
</style>

<?include 'admin_menu.php'?>
<div style="width:750px;" class="left">
    <div class="amu_messages_menu">
	<a href="/adminka/mailer?frame=add" class="<?=(request::get('frame')=='add' || !request::get('frame')) ? ' selected' : ''?>">Новая рассылка</a>
	<a href="/adminka/mailer?frame=drafts" class="<?=(request::get('frame')=='drafts') ? ' selected' : ''?>">Черновики</a>
	<a href="/adminka/mailer?frame=active" class="<?=(request::get('frame')=='active') ? ' selected' : ''?>">Активные</a>
	<a href="/adminka/mailer?frame=in_queue" class="<?=(request::get('frame')=='in_queue') ? ' selected' : ''?>">В очереди</a>
	<a href="/adminka/mailer?frame=complete" class="<?=(request::get('frame')=='complete') ? ' selected' : ''?>">Завершенные</a>
	
    </div>
    <?if(request::get('frame')=='add' || !request::get('frame')) { ?>
    <div id="form-box">
	<?include 'mailer/form.php'?>
    </div>
    <? } ?>
    <div class="clear"></div>
    <?if(in_array(request::get('frame'),array('active','in_queue','complete'))) {?>
    <div id="list-box">
	<?include 'mailer/list.php'?>
    </div>
    <? } ?>
    <?if(request::get('frame')=='drafts') { ?>
    <div id="drafts-box">
	<?include 'mailer/drafts.php'?>
    </div>
    <? } ?>
    
</div> 
<div class="clear"></div>
