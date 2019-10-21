<div class="review-subscribed-box <?=(request::get('tab')=='review' && request::get('form')=='subscribed') ? '' : ' hide'?>">
    <form action="/forum/profile" method="post" id="ucp-review-form">

	<input type="hidden" name="act" value="save_review"/>
	<input type="hidden" name="subaction" value="subscribes"/>

	<h2>Подписки</h2>

	<div class="panel">
		<div class="inner"><span class="corners-top"><span></span></span>

		<p>Ниже расположен список форумов и тем, на которые вы подписаны. Вы будете оповещены о появлении в них новых сообщений. Чтобы отписаться от них, выделите форум или тему и нажмите кнопку <em>Отписаться от выделенного</em>.</p>


		<p><strong>Вы не подписаны на какие-либо форумы.</strong></p>

		<p><strong>Вы не подписаны на какие-либо темы.</strong></p>


		<span class="corners-bottom"><span></span></span></div>
	</div>


    </form>
</div>