<? $agency_data = array('name' => ''); ?>
<? if($adminka["id"] > 0){ ?>
	<? $agency_data = agency_peer::instance()->get_item($adminka["id"]); ?>
<? } ?>

<form id="agency" action="/adminka/agency_manager?act=<? if($adminka["id"] < 1){ ?>add<? } else { ?>edit_json&id=<?=$adminka["id"]?><? } ?>" method="post">
	<div id="agency-blocks" class="p10" style="background: #eee; border-radius: 5px;">

		<div id="agency-block-0" class="mb5">
			<div class="left mt5 mr5 aright" style="width: 100px">Имя:</div>
			<div class="left">
				<input type="text" id="agency-text-name-0" value="<?=htmlspecialchars($agency_data['name'])?>" />
			</div>
			<? if($adminka["id"] < 1){ ?>
				<div class="left">
					<input type="button" id="agency-button-add-0" value="Добавить" />
				</div>
			<? } ?>
			<div id="agency-msg-success-0" class="left mt5 ml5 hide" style="color: #090">
				<? if($adminka["id"] < 1){ ?>Добавлена успешно<? } else { ?>Сохранена успешно<? } ?>
			</div>
			<div id="agency-msg-error-0" class="left mt5 ml5 hide" style="color: #900">
				Уже существует
			</div>
			<div class="clear"></div>
		</div>
		
		<div id="agency-block-999" class="mt5">
			<div class="left mt5 mr5 aright" style="width: 100px">&nbsp</div>
			<div class="left">
				<input type="button" id="submit" value="Сохранить" />
			</div>
			<? if($adminka["id"] < 1){ ?>
				<div class="left mr5">
					<input type="button" value="Готово" onclick="window.location = '/adminka/agency_manager'" />
				</div>
			<? } ?>
			<div class="clear"></div>
		</div>
		
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function(){
		
		var form = new Form("agency");
		form.onSuccess = function(data)
		{
			if(data.success){
				$("div[id^='agency-msg']").hide();
				$.each(data.agency, function(key, agency){
					var i = key.split("-")[3];
					if(agency)
						$("#agency-msg-success-"+i).show();
					else
						$("#agency-msg-error-"+i).show();
				});
				<? if($adminka["id"] > 0){ ?>window.location = '/adminka/agency_manager';<? } ?>
			}
		}
		$("#agency #submit").click(function(){
			form.send();
		});
		
		$("input[id^='agency-button-add']").click(function(){
			console.log($(this).attr("id"));
			var block = $("#agency-block-0").clone();
			var count = $("#agency-blocks div[id^='agency-block']").length - 1;
			$(block).find("input[id^='agency-text-name']")
				.attr("id", "agency-text-name-"+count)
				.val("");
			$(block).find("div[id^='agency-msg-success']")
				.attr("id", "agency-msg-success-"+count)
				.hide();
			$(block).find("div[id^='agency-msg-error']")
				.attr("id", "agency-msg-error-"+count)
				.hide();
			$(block).find("input[id^='agency-button-add']")
				.attr("id", "agency-button-add-"+count)
				.bind("click", {"this": this}, function(evn){
					evn.data["this"].click();
				});
			
			$("#agency-blocks #agency-block-999").before($(block));
			
		})
		
	});
</script>
