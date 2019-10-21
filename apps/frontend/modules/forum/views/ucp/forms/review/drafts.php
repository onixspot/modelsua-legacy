<div class="review-drafts-box <?=(request::get('tab')=='review' && request::get('form')=='drafts') ? '' : ' hide'?>">
    <form action="/forum/profile" method="post" id="save-preview-drafts">

	<input type="hidden" name="act" value="save_review"/>
	<input type="hidden" name="subaction" value="drafts"/>

	<h2>Черновики</h2>

	<div class="panel">
		<div class="inner"><span class="corners-top"><span></span></span>

		<p>Здесь вы можете просмотреть, отредактировать и удалить ваши сохранённые черновики.</p>


			<p><strong>Нет сохранённых черновиков.</strong></p>


			<span class="corners-bottom"><span></span></span>
		</div>
	</div>
    </form>
</div>    