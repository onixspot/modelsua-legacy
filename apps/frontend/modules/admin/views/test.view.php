<table cellpadding="5" cellspacing="2" style="border: 1px solid #ccc;">
	<tr>
		<th>ID</th>
		<th>Name</th>
	</tr>
	<? foreach($list as $item){ ?>
		<tr>
			<td><?=$item['id']?></td>
			<td><?=$item['name']?></td>
		</tr>
	<? } ?>
</table>