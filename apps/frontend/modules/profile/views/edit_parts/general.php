<div id="profile-edit-frame-general">
	<form id="profile-edit-form-general" action="/profile/edit?id=<?=$profile["user_id"]?>&group=general">
		<div class="mt20 mb10">
			<div class="left pt5 mr5 aright bold" style="width: 200px;"><?=t("По русски")?>:</div>
			<div class="clear"></div>
		</div>
		<div class="mt20 mb10">
			<div class="left pt5 mr5 aright" style="width: 200px;"><?=t("Фамилия")?>:</div>
			<div class="left">
				<input type="text" id="last_name" value="<?=$profile["last_name"]?>" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px;"><?=t("Имя")?>:</div>
			<div class="left">
				<input type="text" id="first_name" value="<?=$profile["first_name"]?>" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px;"><?=t("Отчество")?>:</div>
			<div class="left">
				<input type="text" id="middle_name" value="<?=$profile["middle_name"]?>" />
			</div>
			<div class="clear"></div>
		</div>
	    
		
		<!--		ENGLISH -->
		<div class="mt20 mb10">
			<div class="left pt5 mr5 aright bold" style="width: 200px;"><?=t("По английски")?>:</div>
			<div class="clear"></div>
		</div>
		<div class="mt20 mb10">
			<div class="left pt5 mr5 aright" style="width: 200px;"><?=t("Фамилия")?>:</div>
			<div class="left">
				<input type="text" id="last_name_en" value="<?=$profile["last_name_en"]?>" />
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px;"><?=t("Имя")?>:</div>
			<div class="left">
				<input type="text" id="first_name_en" value="<?=$profile["first_name_en"]?>" />
			</div>
			<div class="clear"></div>
		</div>
	    
		
		
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px;"><?=t("Пол")?>: </div>
			<div class="left">
				<input type="radio" id="male" name="sex[]" <?= $profile["sex"] == 0 ? "checked" : "" ?> />
				<label for="male"><?=t("Мужской")?></label>
				<input type="radio" id="female" name="sex[]" <?= $profile["sex"] == 1 ? "checked" : "" ?> />
				<label for="female"><?=t("Женский")?></label>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Дата рождения")?>: </div>
			<div class="left">
				<?=ui_helper::datefields("birthday", strtotime($profile["birthday"]))?>
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Страна")?>: </div>
			<div class="left">
				<select id="country" style="width: 256px;">
					<option value="0">&mdash;</option>
				</select>
			</div>
			<div class="clear"></div>
		</div>
		<div id="region_block" class="mb10 hide">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Регион / Город")?>: </div>
			<div class="left">
				<select id="region" style="width: 256px;">
					<option value="0">&mdash;</option>
				</select>
			</div>
			<div class="clear"></div>
		</div>
		<div id="city_block" class="mb10 hide">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Город / Район")?>: </div>
			<div class="left">
				<select id="city" style="width: 256px;">
					<option value="0">&mdash;</option>
				</select>
				<input class="hide" type="text" id="another_city" value="<?=$profile["another_city"]?>" style="width: 256px;" />
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="mt30">
			<div class="left pt5 mr5 aright" style="width: 200px">&nbsp;</div>
			<div class="left">
				<input type="button" id="submit" value="<?=t("Сохранить")?>" />
			</div>
			<div id="msg-success-general" class="left pt5 ml10 acenter hide" style="color: #090">
				<?=t("Данные сохранены успешно")?>
			</div>
			<div class="clear"></div>
		</div>
		
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		
		$("#country").change(function(){
			var country_id = $(this).val();
			$("#region > option").remove();
			var option = $('<option />');
			$(option)
				.val(0)
				.html('&mdash;');
			$('#region').append($(option));
			// 9908 - country_id Украины
			if(country_id != 9908){
				$('#region_block').hide();
				$("#region").change();
			} else {
				$('#region_block').show();
				$.post('/geo', {
					'act': 'get_regions',
					'country_id': country_id
				}, function(resp){
					$.each(resp.regions, function(){
						var option = $('<option />');
						$(option)
							.val(this.region_id)
							.html(this.name);
						$('#region').append($(option));
					});
					$("#region").val('<?=$profile["region"]?>');
					$("#region").change();
				}, 'json');
			}
		});
		
		$("#region").change(function(){
			var country_id = $("#country").val();
			var region_id = $(this).val();
			$("#city > option").remove();
			var option = $('<option />');
			$(option)
				.val(0)
				.html('&mdash;');
			$('#city').append($(option));
			if(region_id != 0){
				$('#city_block').show();
				$.post('/geo', {
					'act': 'get_cities',
					'region_id': region_id
				}, function(resp){
					$.each(resp.cities, function(){
						var option = $('<option />');
						$(option)
							.val(this.city_id)
							.html(this.name);
						$('#city').append($(option));
					});
					$("#city").val('<?=$profile["city"]?>');
					$("#city").change();
				}, 'json');
			} else if(country_id != 0 && country_id != 9908) {
				$('#city_block').show();
				$.post('/geo', {
					'act': 'get_cities',
					'country_id': country_id
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
					$("#city").val('<?=$profile["city"]?>');
					$("#city").change();
				}, 'json')
			} else {
				$('#city_block').hide();
				$("#city").change();
			}
		});
		
		$("#city").change(function(){
			if($(this).val() == -1){
				$(this).hide();
				$("#another_city")
					.show()
					.focus();
			}
		});
		
		$("#another_city").blur(function(){
			if($(this).val() == ""){
				$(this)
					.val("")
					.hide();
				$("#city")
					.show()
					.val(0)
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
			$("#country").val(<?=$profile["country"]?>);
			$("#country").change();
		}, "json");
		
		var form = new Form("profile-edit-form-general");
		form.onSuccess = function(data){
			if(data.success)
				$("#msg-success-general")
					.show()
					.css("opacity", "0")
					.animate({
						"opacity": "1"
					}, 256, function(){
						setTimeout(function(){
							$("#msg-success-general").animate({
								"opacity": "0"
							}, 256, function(){
								$(this).hide();
							})
						}, 1000);
					});
		}
		$("#profile-edit-form-general #submit").click(function(){
			form.send();
		});
	})
</script>