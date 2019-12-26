<?include 'partials/top_menu.php'?>
<div class="clear"></div>
<div class="topic-actions">
	<div class="buttons">
		<div title="Ответить" class="reply-icon"><a href="/forum/compose?tp=msg&pid=<?=$topic['id']?>"><span></span>Ответить</a></div>
	</div>

	<div class="search-box left">
		<form action="/forum/search" id="forum-search" method="get">
		<fieldset>
			<input type="text" onblur="if (this.value == '') this.value = 'Поиск в форуме…';" onclick="if (this.value == 'Поиск в форуме…') this.value = '';" value="Поиск в форуме…" size="20" id="search_keywords" name="keywords" class="inputbox search tiny">
			<input type="submit" value="Поиск" class="button2">
			<input type="hidden" value="15" name="fid[0]">
		</fieldset>
		</form>
	</div>
	<div class="paginator right fs13 mr20">
	    <?=  pager_helper::get_full($pager); ?>
	</div>
</div>
<?$ts = profile_peer::instance()->get_item($topic['user_id']);?>
<div class="clear"></div>
<div class="forabg fs13">
    <div class="inner">
	<ul class="topiclist">
		<li class="header">
			<dl class="icon">
				<dt class="topic">
				<a title="<?=  stripslashes($forum['subject'])?>" href="/forum/view?f=<?=$forum['id']?>"><?=  stripslashes($forum['subject'])?></a> - 
				    <h1><?= stripslashes($topic['subject'])?></h1>
				</dt>
			</dl>
		</li>
		<?if(request::get_int('page',1) <= 1 ) { ?>
		<div class="post bg2" id="p8362">
		<div class="inner">
		
		<div class="postbody">
			<?if(forum_helper::get_user_id()==$topic['user_id']) {?>
			<ul class="profile-icons">
				<li class="edit-icon">
				    <a title="Редактировать" href="/forum/compose?edit=<?=$topic['id'].'&tp=tpc&pid='.$topic['forum_id']?>">
					<span>Редактировать сообщение</span>
				    </a>
				</li>
				<li class="delete-icon">
				    <a title="Удалить сообщение" href="javascript:void(0);"  onclick="$.post('/forum/view', {'forum_id': <?=$topic['id']?>,'act': 'delete_forum'}, function(resp) {if(resp.success) window.location='/forum/view';}, 'json')">
					<span>Удалить сообщение</span>
				    </a>
				</li>
				<li class="report-icon">
				    <a title="Пожаловаться на это сообщение" href="javascript:void(0);">
					<span>Пожаловаться на это сообщение</span>
				    </a>
				</li>
				<li class="quote-icon">
				    <a title="Цитата" href="#postingbox">
					<span>Цитата</span>
				    </a>
				</li>
			</ul>
			<? } ?>
			<h3 class="first"><a title="<?=  stripslashes($topic['subject'])?>" href="#p8362"><?=  stripslashes($topic['subject'])?></a></h3>
			<p class="author"><a href="/forum/viewtopic?t=<?=$topic['id']?>">
				<img width="11" height="9" title="Сообщение" alt="Сообщение" src="/forum/icons/icon_post_target.gif">
			    </a> 
			    <strong>
				<a href="/profile?id=<?=$ts['user_id']?>""><?=profile_peer::get_name($ts);?></a>
			    </strong><?=($topic['created_ts']) ? date('d.m.Y H:i:s',$topic['created_ts']) : '?' ;?></p>
			<div class="content">
			    <?=stripslashes($topic['body']);?>
			</div>
		</div>
		<dl id="profile8362" class="postprofile">
		    <dt>
			<a href="/profile?id=<?=$ts['user_id']?>">
			    <?=  ui_helper::photo($ts, array('style'=>'width: 100px;') ) ?>
			    
			</a>
		    <br>
			<a href="/profile?id=<?=$ts['user_id']?>"><?=profile_peer::get_name($ts);?></a>
		    </dt>
		    <dd>&nbsp;</dd>
		    <dd><strong>Сообщения:</strong>&nbsp;<?=forum_posts_peer::get_user_posts_count($ts['user_id']);?></dd>
		    <dd><strong>Зарегистрирован:</strong><?=($ts['created_ts']) ? date('d.m.Y',$ts['created_ts']) : '?' ;?></dd>
		    <dd><strong>Откуда:</strong><?=geo_peer::instance()->get_country($ts['country']); ?></dd>
		    <dd class="hide">
			    <ul class="profile-icons">
				    <li class="icq-icon"><a title="ICQ" onclick="popup(this.href, 550, 320); return false;" href="https://www.icq.com/people/117045076/"><span>ICQ</span></a></li>
			    </ul>
		    </dd>
		</dl>
		<div class="back2top"><a title="Вернуться к началу" class="top" href="javascrip:;">Вернуться к началу</a></div>

		</div>
	</div>
	<? } ?>
	<?
	if(!empty($posts))
	    foreach ($posts as $k=>$post_data) {
		$profile = profile_peer::instance()->get_item($post_data['user_id']);
	?>
	<div class="post bg<?=($k%2+1)?>" id="post<?=$post_data['id']?>">
		<div class="inner">
		<div class="postbody">
			<?if(forum_helper::get_user_id()==$post_data['user_id']) {?>
			<ul class="profile-icons">
				<li class="edit-icon">
				    <a title="Редактировать сообщение" href="/forum/compose?edit=<?=$post_data['id'].'&tp=msg&pid='.$post_data['topic_id']?>">
					<span>Редактировать сообщение</span>
				    </a>
				</li>
				<li class="delete-icon">
				    <a title="Удалить сообщение" href="javascript:void(0);"  onclick="$.post('/forum/viewtopic', {'post_id': <?=$post_data['id']?>,'act': 'delete_post'}, function(resp) {if(resp.success) $('#post<?=$post_data['id']?>').remove();}, 'json')">
					<span>Удалить сообщение</span>
				    </a>
				</li>
				<li class="report-icon">
				    <a title="Пожаловаться на это сообщение" href="">
					<span>Пожаловаться на это сообщение</span>
				    </a>
				</li>
				<li class="quote-icon">
				    <a title="Цитата" href="#postingbox">
					<span>Цитата</span>
				    </a>
				</li>
			</ul>
			<? } ?>
			<h3><a title="" href="#p8367"></a></h3>
			<p class="author">
			    <a href="/forum/viewtopic?t=<?=$topic['id']?>#p8367">
				<img width="11" height="9" src="/forum/icons/icon_post_target.gif" alt="Сообщение" title="Сообщение">
			    </a> 
			    <strong>
			    <a class="username-coloured" style="color: #AA0000;" href="/profile?id=<?=$profile['user_id']?>"><?=profile_peer::get_name($profile);?></a>
			    </strong><?=($profile['created_ts']) ? date('d.m.Y H:i:s',$profile['created_ts']) : '' ;?>
			</p>
			<div class="content">
			    <?=stripslashes($post_data['body']);?>
			</div>
			<div class="signature hide" id="sig8367">
			    <span style="font-size: 85%; line-height: 116%;">с уважением, Андрей Голубев.</span>
			</div>

		</div>
		<dl id="profile8367" class="postprofile">
		    <dt>
			<a href="/profile?id=<?=$profile['user_id']?>">
			    <?=  ui_helper::photo($profile, array('style'=>'width: 100px;')) ?>
			</a>
			<br>
			<a class="username-coloured" style="color: #AA0000;" href=""/profile?id=<?=$profile['user_id']?>"><?=profile_peer::get_name($profile);?></a>
		    </dt>
		<dd>anykey</dd>
		<dd>&nbsp;</dd>
		<dd><strong>Сообщения:</strong>&nbsp;<?=forum_posts_peer::get_user_posts_count($post_data['user_id']);?></dd>
		<dd><strong>Зарегистрирован:</strong> <?=($profile['created_ts']) ? date('d.m.Y',$profile['created_ts']) : '' ;?></dd>
		<dd><strong>Откуда:</strong> <?=geo_peer::instance()->get_country($ts['country']); ?></dd>
			<dd>
				<ul class="profile-icons">
					<li class="web-icon"><a title="WWW: https://440hz.ru" href="https://440hz.ru"><span>Сайт</span></a></li><li class="icq-icon"><a title="ICQ" onclick="popup(this.href, 550, 320); return false;" href="https://www.icq.com/people/43053852/"><span>ICQ</span></a></li>
				</ul>
			</dd>
		</dl>
		<div class="back2top"><a title="Вернуться к началу" class="top" href="#wrap">Вернуться к началу</a></div>
		</div>
	</div>
	<? } ?>
	</ul>
    </div>
</div>
<div class="clear"></div>
<div class="paginator right fs13 mr20">
    <?=  pager_helper::get_full($pager); ?>
</div>
<h2 style="border-bottom: 1px solid #CCCCCC;
    color: #989898;
    font-size: 1.6em;
    font-weight: normal;
    margin-bottom: 0.5em;
    margin-top: 0.5em;
    padding-bottom: 0.5em;">Быстрый ответ</h2>
<?
$_REQUEST = array_merge(
		array(
		    'tp' => 'msg',
		    'pid' => $topic['id']
		),
		$_REQUEST
	    );
$parent['subject'] = $topic['subject'];
?>
<?include 'compose.view.php'?> 