<div id="page-header">
	<div class="navbar">
		<div class="inner">
		<ul class="linklist leftside">
			<li class="icon-home" style="padding:0 0 0 20px;"><a accesskey="h" href="./index.php">Список форумов</a></li>
			<?if(forum_helper::is_authenticated()) {?>
			<li class="icon-ucp"  style="padding:0 0 0 20px;">
				<a accesskey="e" title="Личный раздел" href="/forum/profile">Личный раздел</a>
			</li>
			<? } ?>
		</ul>
		<ul class="linklist rightside">
<!--			<li class="icon-faq"><a title="Часто задаваемые вопросы" href="./faq.php">FAQ</a></li>-->
			<?if(!forum_helper::is_authenticated()) {?>
			<li class="icon-register"><a href="/forum/sign?mode=registration">Регистрация</a></li>
			<li class="icon-logout"><a accesskey="x" title="Вход" href="/forum/sign?mode=login">Вход</a></li>
			<? } else {?>
			<li class="icon-logout"><a title="Выход" href="javascript:void(0);" onclick="$.post('/forum/sign', {'act': 'logout'}, function(resp) { if (resp.success) { window.location.reload() } }, 'json');">Выход [<?=$forum_user['login']?>]</a></li>
			<? } ?>

		</ul>
		</div>
	</div>
</div>