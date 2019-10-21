<div id="block-journal-location-edit" class="hide">
	<div>
		<div class="left pt5" style="width: 100px"><?=t("Страна")?>: </div>
		<div class="left">
			<select id="country" style="width: 270px;">
				<option value="0">&mdash;</option>
			</select>
		</div>
		<div class="clear"></div>
	</div>
	<div id="region_block" class="mt10 hide">
		<div class="left pt5" style="width: 100px"><?=t("Регион")?>: </div>
		<div class="left">
			<select id="region" style="width: 270px;">
				<option value="0">&mdash;</option>
			</select>
		</div>
		<div class="clear"></div>
	</div>
	<div id="city_block" class="mt10 hide">
		<div class="left pt5" style="width: 100px"><?=t("Город")?>: </div>
		<div class="left">
			<input class="hide" type="text" id="another_city" value="<?=$profile["another_city"]?>" style="position: absolute; width: 252px;" />
			<select id="city" style="width: 270px;">
				<option value="0">&mdash;</option>
			</select>
		</div>
		<div class="clear"></div>
	</div>
	<div class="mt10 aright">
		<input type="button" id="journal-location-button-save" value="Сохранить" />
		<input type="button" id="journal-location-button-cancel" value="Отмена" />
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		$('#journal-location-button-edit').click(function(){
			$('#block-journal-location').hide();
			$('#block-journal-location-edit').show();
		});
		
		$('#journal-location-button-save').click(function(){
			$.post('/journals', {
				'act': 'set_location',
				'id': <?=$journal['id']?>,
				'country': $('#country').val(),
				'region': $('#region').val(),
				'city': $('#city').val(),
				'another_city': $('#another_city').val()
			}, function(response){
				if(response.success)
				{
					if(response.location != '')
						$('#block-journal-location > div').eq(0)
							.css({
								'font-weight': 'bold',
								'color': '#888'
							})
							.html(response.location);
					else
						$('#block-journal-location > div').eq(0)
							.css({
								'font-weight': 'normal',
								'color': 'gray'
							})
							.html('<?=t('Место нахождения')?>');
					$('#journal-location-button-cancel').click();
				}
			}, 'json');
		});
		
		$('#journal-location-button-cancel').click(function(){
			$('#block-journal-location').show();
			$('#block-journal-location-edit').hide();
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
			$("#country").val(<?=$journal["country"]?>);
			$("#country").change();
		}, "json");
		
	});
</script>