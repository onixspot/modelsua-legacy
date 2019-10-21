<div>
	<form id="journals_form" action="/adminka/journals_manager" method="post">
		<div>
			<div class="left mt5 mr10 aright" style="width: 150px">Наименование:</div>
			<div class="left"><input type="text" id="name" value="<?= isset($journal["name"]) ? $journal["name"] : "" ?>" style="width: 200px;" /></div>
			<div class="clear"></div>
		</div>
		
		<div class="mt10">
			<div class="left mt5 mr10 aright" style="width: 150px">Страна:</div>
			<div class="left">
				<select id="country" style="width: 200px;">
					<option value="0">&mdash;</option>
				</select>
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="mt10">
			<div class="left mt5 mr10 aright" style="width: 150px">Описание:</div>
			<div class="left">
				<textarea id="description" style="width: 200px; height: 100px;"><?=
					isset($journal["description"]) ? $journal["description"] : ""
				?></textarea>
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="mt10">
			<div class="left mt5 mr10 aright" style="width: 150px">&nbsp;</div>
			<div class="left">
				<input type="submit" value="Сохранить" />
				<input type="button" value="Отмена" onclick="window.location = '/adminka/journals_manager'" />
			</div>
			<div class="clear"></div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		var form = new Form("journals_form");
		form.data = {
			"act": "save_journal",
			"id": "<?= isset($journal["id"]) ? $journal["id"] : 0 ?>"
		};
		form.onSuccess = function(response){
			if( ! response.success)
				return false;
			
			window.location = "/adminka/journals_manager";
		};
		$("#journals_form input[type='submit']").click(function(){
			form.send();
		});
		
		$.post("/geo", {
			"act" : "get_countries"
		}, function(data){
			$.each(data.countries, function(){
				var option = $("<option />");
				$(option)
					.attr("value", this.country_id)
					.html(this.name)
				$("#country").append($(option));
			});
			$("#country").val(<?= isset($journal["country"]) ? $journal["country"] : 0 ?>);
			$("#country").change();
		}, "json");
	});
</script>