<style type="text/css">

	div.adminka_menu {
		padding: 5px;
		background: #b95383;
		border-radius: 4px;
	}
	div.adminka_menu ul {
		padding: 0px;
		margin: 0px;
	}
	div.adminka_menu ul li {
	}
	div.adminka_menu ul li a {
		color: #fff;
	}
	div.adminka_menu ul li:first-child a {
	}
	div.adminka_menu ul li:last-child a {
	}
	div.adminka_menu ul li a.selected {
	}
	div.adminka_menu ul li a:hover {
	}

</style>
<div class="adminka_menu mr10 left" style="width: <?= (request::get('action') == 'pages') ? '345px;' : '200px;' ?>">
	<ul>
		<li>
			<a <? if (request::get('action') == "user_manager") { ?> class="underline" <? } ?> href="/adminka/user_manager?filter=byself-new">Модели</a>
		</li>
		<li>
			<a <? if (request::get('action') == "umanager") { ?> class="underline" <? } ?> href="/adminka/umanager">Участники</a>
		</li>
		<li>
			<a <? if (request::get('action') == "agency_manager") { ?> class="underline" <? } ?> href="/adminka/agency_manager">Агентства</a>
		</li>
		<li>
			<a <? if (request::get('action') == "news") { ?> class="underline" <? } ?> href="/adminka/news">Новости</a>
		</li>
		<li>
			<a <? if (request::get('action') == "pages") { ?> class="underline" <? } ?> href="/adminka/pages">Текстовые страницы</a>
		</li>
		<li>
			<a <? if (request::get('action') == "successfull" && !request::get_int('mt')) { ?> class="underline" <? } ?> href="/adminka/successfull">Успешные модели</a>
		</li>
		<li>
			<a <? if (request::get('action') == "successfull" && request::get_int('mt') == 2) { ?> class="underline" <? } ?> href="/adminka/successfull?mt=2">Перспективные модели</a>
		</li>
		<li>
			<a <? if (request::get('action') == "successfull" && request::get_int('mt') == 1) { ?> class="underline" <? } ?> href="/adminka/successfull?mt=1">Новые лица</a>
		</li>
		<li>
			<a <? if (request::get('action') == "credentials") { ?> class="underline" <? } ?> href="/adminka/credentials">Администраторы</a>
		</li>
		<li>
			<a <? if (request::get('action') == "email_templates") { ?> class="underline" <? } ?> href="/adminka/email_templates">Шаблоны сообщений</a>
		</li>

		<li>
			<a <? if (request::get('action') == "statistic") { ?> class="underline" <? } ?> href="/adminka/statistic">Статистика</a>
		</li>
		<li>
			<a <? if (request::get('action') == "list_management") { ?> class="underline" <? } ?> href="/adminka/list_management">Управление списками</a>
		</li>
		<li>
			<a <? if (request::get('action') == "mailer") { ?> class="underline" <? } ?> href="/adminka/mailer">Рассылки</a>
		</li>
		<li>
			<a <? if (request::get('action') == "journals_manager") { ?> class="underline" <? } ?> href="/adminka/journals_manager">Менеджер журналов</a>
		</li>
	</ul>
</div>