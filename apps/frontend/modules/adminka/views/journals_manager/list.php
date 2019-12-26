<div>
	<table width="100%" cellpadding="5" cellspacing="2" style="border: 1px solid #ccc;">
		<thead>
			<tr style="background: #DDD;">
				<th>ID</th>
				<th>Наименование</th>
				<th>Публичный</th>
				<th>Действие</th>
			</tr>
		</thead>
		<tbody>
			<? $flag = true; ?>
			<? foreach($journals_list as $id){ ?>
				<? $journal = journals_peer::instance()->get_item($id); // ?>
				<? $flag = $flag ? false : true; ?>
				<tr id="journals-item-<?=$id?>" style="background: <?= $flag ? "#EEE" : "#FFF" ?>;">
					<td align="center"><?=$id?></td>
					<td>
						<div><a href="/journals?id=<?=$id?>" target="_blank"><?=$journal["name"]?> <?=profile_peer::get_location($journal)?></a></div>
						<div class="fs11 cgray mt5"><?=$journal["description"]?></div>
					</td>
					<td align="center">
						<input type="checkbox" id="journals-item-publicate-<?=$id?>"<? if($journal["public"]){ ?> checked<? } ?> />
					</td>
					<td align="center">
						<div><a href="/adminka/journals_manager?act=edit&id=<?=$id?>">Изменить</a></div>
						<div><a id="journals-item-remove-<?=$id?>" href="javascript:void(0);">Удалить</a></div>
					</td>
				</tr>
			<? } ?>
		</tbody>
	</table>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		$("input[id^='journals-item-publicate']").click(function(){
			var id = $(this).attr("id").split("-")[3];
			var state = $(this).attr("checked") ? 1 : 0;
			$.post("/adminka/journals_manager", {
				"act": "publicate_journal",
				"id": id,
				"state": state
			}, function(){}, "json");
		});
		
		$("a[id^='journals-item-remove']").click(function(){
			if(confirm("Вы действительно хотите удалить?"))
			{
				var id = $(this).attr("id").split("-")[3];
				$.post("/adminka/journals_manager", {
					"act": "remove_journal",
					"id": id
				}, function(response){
					if( ! response.success)
					{
						return false;
					}
					
					$("#journals-item-"+id).remove();
				}, "json");
			}
		});
		
	});
</script>