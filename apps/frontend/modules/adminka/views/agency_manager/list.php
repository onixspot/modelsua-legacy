<div class="p10" style="background: #eee; border-radius: 5px;">
	<div class="mb5" style="background: #ddd; border-radius: 5px;">
		<div class="left mr10 acenter pt5" style="width: 24px; margin-top: -3px;">
			<input type="checkbox" id="agency-selector-all" />
		</div>
		<div class="left bold mr10 acenter pt5" style="width: 24px">ID</div>
		<div class="left bold mr10 pt5">Имя агентства</div>
		
		<div class="right bold ml10 acenter pt5" style="width: 150px">Действия</div>
		<div class="right bold ml10 acenter pt5" style="width: 100px">Публичное</div>
		
		<div class="clear"></div>
	</div>
	<? foreach($list as $item){ ?>
		<? $agency_data = agency_peer::instance()->get_item($item); ?>
		<div id="agency-item-<?=$agency_data["id"]?>" class="pt5 mb5" style="border-top: 1px dotted #ccc;">
			<div class="left mr10 acenter" style="width: 24px; margin-top: -3px;">
				<input type="checkbox" id="agency-selector-item-<?=$agency_data["id"]?>" />
			</div>
			<div class="left mr10 acenter" style="width: 24px"><?=$agency_data["id"]?></div>
			<div class="left mr10">
				<? if($agency_data["page_active"]){ ?>
					<a href="/agency/?id=<?=$agency_data["id"]?>"><?=$agency_data["name"]?>
				<? } else { ?>
					<?=$agency_data["name"]?>
				<? } ?>
			</div>
			<div class="right ml10 acenter" style="width: 150px">
				<!-- ACTIONS BLOCK -->
				<? if( ! $agency_data["page_active"]){ ?>
					<a id="agency-create-page-<?=$agency_data["id"]?>" href="javascript:;">Создать стриницу</a>
				<? } ?>
				<a href="/adminka/agency_manager?act=edit&id=<?=$agency_data["id"]?>">Изменить</a><br />
				<a href="javascript:;" id="agency-item-remove-<?=$agency_data["id"]?>">Удалить</a>
			</div>
			<div class="right ml10 acenter" style="width: 100px; margin-top: -3px;">
				<input type="checkbox" id="agency-item-public-<?=$agency_data["id"]?>"
						<? if($agency_data["public"]){ ?>checked<? } ?> />
			</div>
			<div class="clear"></div>
		</div>
	<? } ?>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("a[id^='agency-create-page']").click(function(){
			var id = $(this).attr('id').split('-')[3];
			$.post('/adminka/agency_manager', {
				'act': 'create_page',
				'id': id
			}, function(response){
				window.location.reload();
			}, 'json');
		});
		
		$("#agency-selector-all").click(function(){
			var checked = $("#agency-selector-all").attr("checked") == "checked" ? true : false;
			$.each($("input[id^='agency-selector-item']"), function(){
				$(this).attr("checked", checked);
			});
		});
		
		$("input[id^='agency-item-public']").click(function(){
			var id = $(this).attr("id").split("-")[3];
			var checked = $(this).attr("checked") == "checked" ? 1 : 0;
			$.post("/adminka/agency_manager?act=publicate", {"agency_id": id, "public": checked}, function(data){
				console.log(data);
			}, "json");
		});
		
		$("#agency-button-remove").click(function(){
			if(confirm("Точно удалить?")){
				var agency_id = new Array();
				$.each($("input[id^='agency-selector-item']"), function(){
					if($(this).attr("checked") == "checked")
						agency_id.push($(this).attr("id").split("-")[3]);
				});
				$.post("/adminka/agency_manager?act=remove_selected",
					{"agency_id" : agency_id}, function(data){
//						console.log(data);
						$.each(data.agency_id, function(){
							$("#agency-item-"+this)
								.animate({"opacity" : "0"}, 256, function(){
									$(this).remove();
								});
						});
					}, "json");
			}
		});
		
		$("a[id^='agency-item-remove']").click(function(){
			var agency_id = $(this).attr("id").split("-")[3];
//			console.log(id);
			if(confirm("Точно удалить?")){
				$.post("/adminka/agency_manager?act=remove", {"agency_id": agency_id}, function(data){
//					console.log(data);
					if(data.success)
						$("#agency-item-"+data.agency_id)
							.animate({"opacity" : "0"}, 256, function(){
								$(this).remove();
							});
				}, "json");
			}
		});
	});
</script>