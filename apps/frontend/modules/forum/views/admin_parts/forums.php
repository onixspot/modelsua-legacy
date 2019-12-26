<script>
var clear_form = function() {
    if($('#forum-form').hasClass('hide')) 
	$('#forum-form').toggleClass('hide'); 
    else { 
	$('#forum-form input[type="text"]').val(''); 
	$('select option').removeAttr('selected');
	$('#id').val(0); 
    }
}
</script>
<a href="javascript:void(0);" class="fs13" onclick="clear_form();">Добавить новый форум</a>
<form id="forum-form" class="mt10 <?=($post_data) ? ' ': ' hide'?>">
    <input type="hidden" name="act" value="save_forum"/>
    <input type="hidden" name="id" id="id" value="<?=$post_data['id']?>"/>
    <table>
	<tr>
	    <td>
		<?=t('Раздел')?>
	    </td>
	    <td>
		<?=tag_helper::select('section_id', $parents, array('value'=>$post_data['section_id'])); ?>
	    </td>
	</tr>
	<script>
	    $(function() {
		$('select[name="section_id"]').change(function(){
		    $.post(
			'/forum/admin',
			{
			    "act": 'get_child_forums',
			    "section_id": $(this).val()
			},
			function(resp) {
			    if(resp.success) {
				$.each(resp.data, function(){
				    var option = $("<option />");
				    $(option)
					    .attr("value", this.id)
					    .html(this.subject);
					    
				    if(this.id=='<?=$post_data['parent_id']?>')
					$(option).attr("selected", "selected");
					
				    $("#parent_id").append($(option));
				});
			    }
				    
			},
			'json'
		    )
		}).change();
	    });
	</script>
	<tr>
	    <td>
		<?=t('Pодительский элемент')?>
	    </td>
	    <td>
		<select id="parent_id">
		    <option value="0">&mdash;</option>
		</select>
	    </td>
	</tr>
	<tr>
	    <td>
		<?=t('Название форума')?>
	    </td>
	    <td>
		<input type="text" name="forum_name" id="forum_name" value="<?=$post_data['subject']?>"/>
	    </td>
	</tr>
	<tr>
	    <td>
		<?=t('Описание форума')?>
	    </td>
	    <td>
		<input type="text" name="forum_body" id="forum_body" value="<?=$post_data['body']?>"/>
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
	    <?=t('Название форума')?>
	</th>
	<th>
	    <?=t('Описание форума')?>
	</th>
	<th>
	    <?=t('Раздел')?>
	</th>
	<th>
	    <?=t('Действие')?>
	</th>
    </tr>
    <?
    if(!empty($list)) 
	foreach ($list as $k=>$id) {
	    $forum = forum_themes_peer::instance()->get_item($id);
	    $sData = forum_sections_peer::instance()->get_item($forum['section_id']);
    ?>
    <tr style="<?=($k%2) ? 'background: #FFF;' : 'background: #EEE;'?>" id="forum<?=$forum['id']?>">
	<td>
	    <?=$forum['subject']?>
	</td>
	<td>
	    <?=$forum['body']?>
	</td>
	<td>
	    <?=$sData['name']?>
	</td>
	<td class="acenter">
	    <a href="/forum/admin?frame=forums&id=<?=$forum['id']?>" id="edit-forum-<?=$forum['id']?>"/><img src="/ui/edit.png"/></a>
	    <a href="javascript:void(0);" id="delete-forum-<?=$forum['id']?>"/><img src="/ui/delete.png"/></a>
	</td>
    </tr>
    <? } ?>
</table>
<div class='actions_error mt5 hide'></div>
<script>
    $(function() {
	var form = new Form('forum-form');
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
	$('a[id^="delete-forum-"]').click(function() {
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
	})
    });
</script>
