<?include 'partials/top_menu.php'?>
<div class="clear"></div>
<div class="topic-actions">
	<div class="buttons">
		<div title="Новая тема" class="post-icon"><a href="/forum/compose?tp=tpc&pid=<?=$forum['id']?>"><span></span>Новая тема</a></div>
	</div>
	<div class="search-box">
		<form action="/forum/search" id="forum-search" method="get">
		<fieldset>
			<input type="text" onblur="if (this.value == '') this.value = 'Поиск в форуме…';" onclick="if (this.value == 'Поиск в форуме…') this.value = '';" value="Поиск в форуме…" size="20" id="search_keywords" name="keywords" class="inputbox search tiny">
			<input type="submit" value="Поиск" class="button2">
			<input type="hidden" value="15" name="fid[0]">

		</fieldset>
		</form>
	</div>
</div>
<div class="forabg fs13">
    <div class="inner">
	<ul class="topiclist">
		<li class="header">
			<dl class="icon">
				<dt><a href="/forum"><?=$forum['subject']?></a></dt>
				<dd class="topics">Ответы</dd>
				<dd class="posts">Просмотров</dd>
				<dd class="lastpost"><span>Последнее сообщение</span></dd>
			</dl>
		</li>
	</ul>
	<ul class="topiclist forums">
	    <?
	    if(!empty($topics))
		foreach ($topics as $tkey=>$tData) {
		    $topic_id = $tData['topic_id'];
		    $topic_data = forum_topics_peer::instance()->get_item($topic_id);
		    $ts_profile = profile_peer::instance()->get_item($topic_data['user_id']);
		    $posts = db::get_scalar("SELECT COUNT(id) FROM forum_posts WHERE topic_id=:tid",array('tid'=>$topic_id));

	    ?>
	    <li class="row bg2 sticky">
		<dl style="background-image: url(/forum/icons/sticky_read.gif); background-repeat: no-repeat;" class="icon">
			<dt>
			<a title="<?=  stripcslashes($topic_data['subject'])?>" class="topictitle" href="/forum/viewtopic?t=<?=$topic_data['id']?>"><?=  stripcslashes($topic_data['subject'])?></a>
				<br>
				<div class="paginator right fs11" style="margin-top: 5px;">
				    <?=$tData['pager']?>
				</div> 
			<a href="/profile?id=<?=$topic_data['user_id']?>"><?=profile_peer::get_name($ts_profile)?></a>&nbsp;<?=($topic_data['created_ts']) ? date('d.m.Y H:i:s',$topic_data['created_ts']) : ''?>
			</dt>
			<dd class="posts"><?=$posts?></dd>
			<dd class="views"><?=$topic_data['views']?></dd>
			<?
			    $lp_profile = ($tData['last_post']) ? profile_peer::instance()->get_item($tData['last_post']['user_id']) : profile_peer::instance()->get_item($topic_data['user_id']);
			?>
			<dd class="lastpost"><span><a href="/profile?id=<?=$lp_profile['user_id']?>"><?=profile_peer::get_name($lp_profile)?></a>
				<a href="#">
				    <img width="11" height="9" title="Перейти к последнему сообщению" alt="Перейти к последнему сообщению" src="/forum/icons/icon_topic_latest.gif"></a> <br><?=($tData['last_post']) ? date('d.mY H:i:s', $tData['last_post']['created_ts']) : date('d.mY H:i:s', $topic_data['created_ts']);?></span>
			</dd>
		</dl>
	    </li>
	    <? } else { ?>
		<dl class="icon">
		    <dt>
			<br>Здесь пока ничего нет...<br><br>
		    </dt>
		</dl>
	    <? } ?>
	</ul>
    </div>
</div>