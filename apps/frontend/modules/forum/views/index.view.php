<?include 'partials/top_menu.php'?>
<div class="clear"></div>
<?
	if(!empty($sections)) 
	    foreach ($sections as $section_id) {
		$section_data = forum_sections_peer::instance()->get_item($section_id);

	?>
<div class="forabg fs13 mt20" id="section<?=$section_id?>">
    <div class="inner">
	<ul class="topiclist">
		<li class="header">
			<dl class="icon">
				<dt><a href="/forum?s=<?=$section_data['id']?>"><?=$section_data['name']?></a></dt>
				<dd class="topics">Темы</dd>
				<dd class="posts">Сообщения</dd>
				<dd class="lastpost"><span>Последнее сообщение</span></dd>
				<?if(session::has_credential('admin')) {?>
				<dd style="float:right;">
				    <a href="/forum/admin?frame=sections&id=<?=$section_id?>"><img src="/ui/edit.png"/></a>
				    <a id="section-delete-<?=$section_id?>" href="javascript:void(0);"><img src="/ui/delete.png"/></a>
				</dd>
				<? } ?>
			</dl>
		</li>
	</ul>
	<ul class="topiclist forums">
	    
		<?
		if(!empty($forums_data[$section_id])) {
		    foreach ($forums_data[$section_id] as $sectionId=>$forum) {
			
		?>
	    <li class="row">
		<dl style="background-image: url(/forum/icons/forum_read.gif); background-repeat: no-repeat;" class="icon">
			<dt>

				<a title="<?=$forum['data']['body']?>" class="forumtitle" href="/forum/view?f=<?=$forum['data']['id']?>"><?=$forum['data']['subject']?></a><br>
				<?=$forum['data']['body']?>
				<br><strong>Модераторы:</strong> <a href="">СоАдмины</a>, <a href="">Модераторы</a>

			</dt>

				<dd class="topics"><?=$forum['topics_count']?></dd>
				<dd class="posts"><?=$forum['posts_count']?></dd>
				<?if($forum['last_post']) {?>
				<dd class="lastpost"><span>
					<a href="/profile?id=<?=$forum['last_post']['user_id']?>"><?=$forum['last_post']['user_name']?></a>
					<a href="">
					    <img width="11" height="9" title="Перейти к последнему сообщению" alt="Перейти к последнему сообщению" src="/forum/icons/icon_topic_latest.gif"></a> 
					<br>
					<label><?=$forum['last_post']['time']?></label>
				    </span>
				</dd>
				
				<? } else { ?>
				    <dd class="lastpost"><span>
					<br>Не создано ни одной темы...<br><br>
				    </dd>
				<? } ?>
				<?if(session::has_credential('admin')) {?>
				    <dd style="float:right;">
					<a href="/forum/admin?frame=forums&id=<?=$forum['data']['id']?>"><img src="/ui/edit.png"/></a>
					<a id="forum-delete-<?=$forum['data']['id']?>" href="javascript:void(0);"><img src="/ui/delete.png"/></a>
				    </dd>
				<? } ?>	
		</dl>
	    </li>
		<? } ?>
		<? } else { ?>
		<li class="row">
		    <dl class="icon">
			<dt>
			    <br>Здесь пока ничего нет...<br><br>
			</dt>
		    </dl>
		</li>
		<? } ?>
	    
	</ul>
	
    </div>
</div>
<? } ?>
<?if(session::has_credential('admin')) {?>
<script>
$('a[id^="section-delete-"]').click(function() {
	    var id = $(this).attr('id').split('-')[2];
	    $.post(
		'/forum/admin',
		{
		    "act": "delete_section",
		    "section": id
		},
		function(resp) {
		    if(resp.success)
			$('#section'+id).remove();
		    else
			$('.actions_error')
			    .html(resp.reason)
			    .fadeIn(300, function() {
				$(this).fadeOut(1000);
			    });
		},
		'json'
		
	    );
	});
	
$('a[id^="forum-delete-"]').click(function() {
	    var id = $(this).attr('id').split('-')[2];
	    $.post(
		'/forum/admin',
		{
		    "act": "delete_forum",
		    "forum": id
		},
		function(resp) {
		    if(resp.success)
			$('#forum'+id).remove();
		    else
			$('.actions_error')
			    .html(resp.reason)
			    .fadeIn(300, function() {
				$(this).fadeOut(1000);
			    });
		},
		'json'
		
	    );
	});	
</script>
<? } ?>