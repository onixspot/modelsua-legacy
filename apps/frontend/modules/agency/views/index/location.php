<div id="block-agency-location" <? if(empty($agency['location']) && ! $access){ ?>class="hide"<? } ?>>
	<? if($agency['location'] != ''){ ?>
		<div class="left bold"><?=$agency['location']?></div>
	<? } else { ?>
		<div class="left cgray">Место нахождения</div>
	<? } ?>
	<? if($access){ ?>
		<div class="left ml10"><a id="agency-location-button-edit" href="javascript:void(0);" class="cgray">[<?=t('Редактировать')?>]</a></div>
	<? } ?>
	<div class="clear"></div>
</div>
<div id="block-agency-location-edit" class="hide">
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
		<input type="button" id="agency-location-button-save" value="Сохранить" />
		<input type="button" id="agency-location-button-cancel" value="Отмена" />
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		$('#agency-location-button-edit').click(function(){
			$('#block-agency-location').hide();
			$('#block-agency-location-edit').show();
		});
		
		$('#agency-location-button-save').click(function(){
			$.post('/agency', {
				'act': 'set_location',
				'id': <?=$agency['id']?>,
				'country': $('#country').val(),
				'region': $('#region').val(),
				'city': $('#city').val(),
				'another_city': $('#another_city').val()
			}, function(response){
				if(response.success)
				{
					if(response.location != '')
						$('#block-agency-location > div').eq(0)
							.css({
								'font-weight': 'bold',
								'color': 'black'
							})
							.html(response.location);
					else
						$('#block-agency-location > div').eq(0)
							.css({
								'font-weight': 'normal',
								'color': 'gray'
							})
							.html('<?=t('Место нахождения')?>');
					$('#agency-location-button-cancel').click();
				}
			}, 'json');
		});
		
		$('#agency-location-button-cancel').click(function(){
			$('#block-agency-location').show();
			$('#block-agency-location-edit').hide();
		});
		
		$("#country").change(function(){
			$("#region > option").remove();
			var option = $('<option />');
			$(option)
				.val(0)
				.html('&mdash;');
			$('#region').append($(option));
			$('#region_block').hide();
			$("#region").change();
		});
		
		$("#region").change(function(){
			var country_id = $("#country").val();
			$("#city > option").remove();
			var option = $('<option />');
			$(option)
				.val(0)
				.html('&mdash;');
			$('#city').append($(option));
			if(country_id != 0) {
				$('#city_block').show();
				$.post('/geo', {
					'act': 'get_cities',
					'country_id': country_id,
					'big_cities': 1
				}, function(resp){
					for(var i = 0; i <= resp.cities.length; i++)
					{
						var option = $('<option />');
						if(typeof resp.cities[i] != 'undefined'){
							$(option)
								.val(resp.cities[i].city_id)
								.html(resp.cities[i].name);
						} else {
							$(option)
								.val(-1)
								.html('Другой');
						}
						$('#city').append($(option));
					}
					$("#city").val('<?=$agency["city"]?>');
					$("#city").change();
				}, 'json')
			} else {
				$('#city_block').hide();
				$("#city").change();
			}
		});
		
		$("#city").change(function(){
			if($(this).val() == -1){
//				$(this).hide();
				$("#another_city")
					.show()
					.val('<?=$agency["another_city"]?>')
					.focus();
			}
			else
			{
				$("#another_city")
					.val('')
					.hide();
			}
		});
		
		$("#another_city").blur(function(){
			if($(this).val() == ""){
				$(this)
					.val("")
					.hide();
//				$("#city")
//					.show()
//					.val(0)
			}
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
			$("#country").val(<?=$agency["country"]?>);
			$("#country").change();
		}, "json");
		
	});
</script>