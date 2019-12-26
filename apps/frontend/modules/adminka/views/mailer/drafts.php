<style>
    table.maillist_table  td {
	text-align: center;
    }
    table.maillist_table  th {
	background: #ddd;
	height: 30px;
	padding: 0 5px;
    }
</style>
<script>
    $(function(){
	$('a[id^="delete-draft-link-"]').click(function(){
	    var id = $(this).attr('id').split('-')[3];
	    $.post(
		'/adminka/mailer',
		{
		    "act": "delete_draft",
		    'draft_id': id
		},
		function(resp) {
		    if(resp.success)
			$('#draft-'+id).remove();
		},
		'json'
	    )
	});
    });
</script>
<table style="width: 100%;" class="mt10 maillist_table">
    <tr>
	<th>
	    Тема
	</th>
	<th>
	    Фильтр
	</th>
	<th>
	    Получателей 
	</th>
	<th>
	    Действие
	</th>
    </tr>
    <?if($list) {?>
	<?foreach($list as $k=>$id) {
	    $mailing = mailing_peer::instance()->get_item($id);
	    $css = !(($k+1)%2) ? 'background: #f0f0f0;' : 'background: #ffffff;'
	    ?>
    <tr style="<?=$css?>" id="draft-<?=$id?>">
	<td>
	    <?=  stripslashes($mailing['subject'])?>
	</td>
	<td style="width: 200px; text-align: left;">
	    <?$filters = mailing_peer::parse_filters($mailing['filters']);?>
	    <?
		    foreach ($filters as $key => $filter) { 
			echo "<span class='fs11 bold'>".$filter['type']."</span>";
			if(!empty($filter['values'])) {
			    echo '<br/><i class="fs10">'.implode(', ',$filter['values'])."</i><br/>";
			}
		    }
	    ?>
	</td>
	<td>
	    <?=$mailing['receivers']?>
	</td>
	<td>
	    <a href="/adminka/mailer?frame=add&id=<?=$id?>" ><img src="/ui/edit.png"/></a>
	    <a href="javascript:void(0);" id="delete-draft-link-<?=$id?>"><img src="/ui/delete.png"/></a>
	</td>
    </tr>
	<? } ?>
    <? } else { ?>
    <tr>
	<td colspan="6">
	    <div style="color: #838999; border: 1px solid #ccc;" class="message_box acenter p20 mb20">
		Здесь пока пусто...
	    </div>
	</td>
    </tr>
    <? } ?>
</table>