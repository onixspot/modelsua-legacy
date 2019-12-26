<ul id="ucp-menu-review" class="<?=(request::get('tab')=='groups') ? ' ': ' hide'?>">
	<li id="active-subsection">
	    <a href="javascript:void(0);" id="ucp-review-front">
		<span><?=t('Начало')?></span>
	    </a>
	</li>
	<li>
	    <a href="javascript:void(0);" id="ucp-review-subscribed">
		<span><?=t('Подписки')?></span>
	    </a>
	</li>
	<li>
	    <a href="javascript:void(0);" id="ucp-review-drafts">
		<span><?=t('Черновики')?></span>
	    </a>
	</li>
</ul>
<script>
$(function() {
    $('a[id^="ucp-review-"]').click(function() {
	
	var tab = $(this).attr('id').split('-')[1];
	var form = $(this).attr('id').split('-')[2];
	
	$('ul[id="ucp-menu-review"] > li').removeAttr('id');
	$(this).parent('li').attr('id','active-subsection');
	
	$('[class^="review-"]').hide();
	$('.'+tab+'-'+form+'-box').show();
    });
});
</script>