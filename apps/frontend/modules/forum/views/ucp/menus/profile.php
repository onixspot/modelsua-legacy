<ul id="ucp-menu-profile" class="<?=(request::get('tab')=='profile' || !request::get('tab')) ? ' ': ' hide'?>">
	<li id="active-subsection">
	    <a href="javascript:void(0);" id="ucp-profile-personal">
		<span><?=t('Личные данные')?></span>
	    </a>
	</li>
	<li>
	    <a href="javascript:void(0);" id="ucp-profile-signature">
		<span><?=t('Подпись')?></span>
	    </a>
	</li>
	<li>
	    <a href="javascript:void(0);" id="ucp-profile-avatara">
		<span><?=t('Аватара')?></span>
	    </a>
	</li>
	<li>
	    <a href="javascript:void(0);" id="ucp-profile-registration">
		<span><?=t('Регистрационные данные')?></span>
	    </a>
	</li>
</ul>
<script>
$(function() {
    $('a[id^="ucp-profile-"]').click(function() {
	
	var tab = $(this).attr('id').split('-')[1];
	var form = $(this).attr('id').split('-')[2];
	
	$('ul[id="ucp-menu-profile"] > li').removeAttr('id');
	$(this).parent('li').attr('id','active-subsection');
	
	$('[class*="-box"]').hide();
	$('.'+tab+'-'+form+'-box').show();
    });
});
</script>