<a href="javascript:void(0);" class="fs13" onclick="if($('#section-form').hasClass('hide')) $('#section-form').toggleClass('hide'); else { $('#section_name').val(''); $('#id').val(0); }">Добавить новый раздел</a>
<form id="section-form" class="mt10 <?=($post_data) ? ' ': ' hide'?>">
    <input type="hidden" name="act" value="save_section"/>
    <input type="hidden" name="id" id="id" value="<?=$post_data['id']?>"/>
    <table>
	<tr>
	    <td>
		<?=t('Название раздела')?>
	    </td>
	    <td>
		<input type="text" name="section_name" id="section_name" value="<?=$post_data['name']?>"/>
	    </td>
	</tr>
	<tr>
	    <td>
		&nbsp;
	    </td>
	    <td>
		<input type="button" name="submit" value="<?=t('Сохранить')?>"/>
	    </td>
	</tr>
    </table>
</form>
<div class="clear"></div>
<div class='add_error mt5 hide'></div>
<table class="admin_forum_table mt10"  width="100%" cellspacing="2" cellpadding="5" style="border: 1px solid #ccc; width: 100%;">
    <tr>
	<th>
	    <?=t('Название раздела')?>
	</th>
	<th>
	    <?=t('Действие')?>
	</th>
    </tr>
    <?
    if(!empty($list)) 
	foreach ($list as $k=>$id) {
	    $section = forum_sections_peer::instance()->get_item($id);
    ?>
    <tr style="<?=($k%2) ? 'background: #FFF;' : 'background: #EEE;'?>" id="section<?=$section['id']?>">
	<td>
	    <?=$section['name']?>
	</td>
	<td class="acenter">
	    <a href="/forum/admin?frame=sections&id=<?=$section['id']?>" id="edit-section-<?=$section['id']?>"/><img src="/ui/edit.png"/></a>
	    <a href="javascript:void(0);" id="edit-delete-<?=$section['id']?>"/><img src="/ui/delete.png"/></a>
	</td>
    </tr>
    <? } ?>
</table>
<div class='actions_error mt5 hide'></div>
<script>
    $(function() {
	var form = new Form('section-form');
	$('input[name="submit"]').click(function(){
	    form.onSuccess = function(resp) {
		if(resp.success)
		    window.location = window.location;
		else 
		    $('.add_error')
			.html(resp.reason)
			.fadeIn(300, function() {
			    $(this).fadeOut(1000);
			});
	    }
	    form.send();
	});
	$('a[id^="edit-delete-"]').click(function() {
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
	})
    });
</script>
