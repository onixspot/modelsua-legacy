<div id="profile-edit-frame-locality">
	
	<form id="profile-edit-form-locality" action="/profile/edit?id=<?=$profile["user_id"]?>&group=locality">
		<div class="mt20 mb10">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Страна")?>: </div>
			<div class="left">
				<select id="country" style="width: 256px;">
					<option value="0">&mdash;</option>
				</select>
			</div>
			<div class="clear"></div>
		</div>
		<div id="region_block" class="mb10 hide">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Регион")?>: </div>
			<div class="left">
				<select id="region" style="width: 256px;">
					<option value="0">&mdash;</option>
				</select>
			</div>
			<div class="clear"></div>
		</div>
		<div id="city_block" class="mb10 hide">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Город")?>: </div>
			<div class="left">
				<select id="city" style="width: 256px;" e>
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
			<div id="msg-success-locality" class="left pt5 ml10 acenter hide" style="color: #090">
				<?=t("Данные сохранены успешно")?>
			</div>
			<div class="clear"></div>
		</div>
		
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		$("#country").change(function(){
			// 9908 - Украина
			if($(this).val() != 0 && $(this).val() == 9908){
				$.post(
					"/geo",
					{
						"act" : "get_regions",
						"by": {
							"country_id": $(this).val()
						}
					},
					function(data){
						$("#region_block")
							.show()
							.css("opacity", "0")
							.animate(
								{
									"opacity" : "1"
								},
								1,
								function(){
									$("#region option").remove();
									$("#region").append(
											$("<option >")
												.attr({
													"value": "0",
													"selected": "selected"
												})
												.html("&mdash;")
										);
									$.each(data.region, function(){
										$("#region").append(
											$("<option >")
												.attr("value", this.region_id)
												.html(this.name)
										);
									});
									$("#region").val(<?=$profile["region"]?>);
									$("#region").change();
								}
							);
					},
					"json"
				);
			} else {
				$("#region option").remove();
				$("#region")
					.val("0")
					.change();
				$("#region_block")
					.css("opacity", "1")
					.animate(
						{
							"opacity" : "0"
						},
						1,
						function(){
							$(this).hide();
						}
					);
			}
		});
		
		$("#region").change(function(){
			if($(this).val() != 0){
				var by = new Object();
				if($(this).val() == null)
					by = {"country_id": $("#country").val()};
				else
					by = {"region_id": $(this).val()};
				$.post(
					"/geo",
					{
						"act" : "get_cities",
						"by": by
					},
					function(data){
						$("#city_block")
							.show()
							.css("opacity", "0")
							.animate(
								{
									"opacity" : "1"
								},
								1,
								function(){
									$("#city option").remove();
									$("#city").append(
											$("<option >")
												.attr({
													"value": "0",
													"selected": "selected"
												})
												.html("&mdash;")
										);
									$.each(data.city, function(){
										if(
											$("#country").val() == 9908 ||
												$.inArray(this.city_id, new Array(
													"10805", "10685", "10822", "1835", "1820", "278193", "1863",
													"1853", "1822", "1886", "1875", "5911563", "7992", "279123", "6788",
													"6517", "8721", "9085", "9327", "11267", "11125", "6470",
													"2372615", "3503075", "5000000", "5827", "1733", "7016", 
													"1764", "1107", "278190", "1175", "1117", "11199"
												)) > -1
										){
											$("#city").append(
												$("<option >")
													.attr("value", this.city_id)
													.html(this.name)
											);
										}
									});
									$("#city").append(
										$("<option >")
											.attr({
												"value": "-1"
											})
											.html("Другой")
									);
									$("#city").val(<?=$profile["city"]?>);
									$("#city").change();
								}
							);
					},
					"json"
				);
			} else {
				$("#city option").remove();
				$("#city_block").animate(
					{
						"opacity": "0"
					},
					1,
					function(){
						$(this).hide();
					}
				);
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
		
		$.post(
			"/geo",
			{
				"act" : "get_countries"
			},
			function(data){
				$.each(data.country, function(){
					$("#country").append(
						$("<option />")
							.attr("value", this.country_id)
							.html(this.name)
					)
				});
				$("#country").val(<?=$profile["country"]?>);
				$("#country").change();
			},
			"json"
		);
		
		var form = new Form("profile-edit-form-locality");
		form.onSuccess = function(data){
			if(data.success)
				$("#msg-success-locality")
					.show()
					.css("opacity", "0")
					.animate({
						"opacity": "1"
					}, 256, function(){
						setTimeout(function(){
							$("#msg-success-locality").animate({
								"opacity": "0"
							}, 256, function(){
								$(this).hide();
							})
						}, 1000);
					});
		}
		
		$("#profile-edit-form-locality #submit").click(function(){
			form.data.submit = 1;
			form.send();
		});
		
	});
</script>