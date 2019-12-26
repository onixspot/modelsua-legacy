<style>
    #templates-table td {
	text-align: center;
    }
</style>
<table style="width: 100%" id="templates-table"> 
    <tr>
	<th>№</th>
	<th>Псевдоним</th>
	<th>Название</th>
	<th>Адрес отправителя</th>
	<th>Имя отправителя </th>
	<th>Действие</th>
    </tr>
    <?if(!empty($list)) {?>
	<?foreach($list as $num=>$Id) {
	    $email = email_templates_peer::instance()->get_item($Id)?>
	    <tr id="template-<?=$Id?>">
		<td><?=$num?></td>
		<td><?=$email['alias']?></td>
		<td><?=$email['name']?></td>
		<td><?=$email['sender_email']?></td>
		<td><?=$email['sender_name']?></td>
		<td>
		    <a href="/adminka/email_templates?act=edit&edit_id=<?=$Id?>"><img src="/ui/edit.png"/></a>
		    <a href="javascript:void(0);" onclick="$.post('/adminka/email_templates',{'delete': 1, 'del_id': '<?=$Id?>'},function(resp) { if(resp.success) $('#template-'+resp.id).remove();}, 'json')"><img src="/ui/delete.png"/></a>
		</td>
	    </tr>
	<? } ?>
    <? } ?>
</table>
