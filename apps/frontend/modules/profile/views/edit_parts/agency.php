<? $user_agency_id = user_agency_peer::instance()->get_list(array("user_id" => $profile["user_id"], "foreign_agency" => 0)); ?>
<? $user_agency = user_agency_peer::instance()->get_item($user_agency_id[0]); ?>

<? $user_foreign_agency_id = user_agency_peer::instance()->get_list(array("user_id" => $profile["user_id"], "foreign_agency" => 1), array(), array("id ASC")); ?>
<? $user_foreign_agency = user_agency_peer::instance()->get_item($user_foreign_agency_id[0]); ?>

<div id="profile-edit-frame-agency">
	<form id="profile-edit-form-agency" action="/profile/edit?id=<?=$profile["user_id"]?>&group=agency">
		<div class="mt20 mb10 p5 bold">
			Украинское агенство:
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Название")?>: </div>
			<div class="left">
				<input type="text" class="hide" id="another_agency" style="position: absolute; width: 200px" />
				<select id="agency" style="width: 220px">
					<option value="0">&mdash;</option>
					<? $agency_list = agency_peer::instance()->get_list(array("public" => 1), array(), array("id ASC")) ?>
					<? foreach($agency_list as $agency_id){ ?>
						<? $agency = agency_peer::instance()->get_item($agency_id); ?>
						<option value="<?=$agency["id"]?>"><?=$agency["name"]?></option>
					<? } ?>
					<option value="-1"><?=t("Другое")?></option>
				</select>
				<script type="text/javascript">
					$(document).ready(function(){
						$("#agency").change(function(){
							if($(this).val() == -1){
//								$(this).hide();
								$("#another_agency")
									.show()
									.focus();
							} else {
								$("#another_agency")
									.val("")
									.hide();
							}
						});

						$("#another_agency").blur(function(){
							if($(this).val() == ""){
								$(this)
									.val("")
									.hide();
								$("#agency")
									.val(0)
									.show();
							}
						});

						$("#another_agency").val("<?=$user_agency["name"]?>");

						$("#agency")
							.val("<?=$user_agency["agency_id"]?>")
							.change();
					});
				</script>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px">&nbsp;</div>
			<div class="left">
				<input type="radio" id="agency-type" <? if($user_agency["type"]){ ?>checked<? } ?> name="materiskoe[]" />
				<label for="agency-type"><?=t('Материнское агентство')?></label>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left mr5 aright" style="width: 200px"><?=t("Контракт")?>: </div>
			<div class="left">
				<input type="radio" id="agency_contract-null" name="agency_contract[]" <?= $user_agency["contract"] == 0 ? "checked" : "" ?> />
				<label for="agency_contract-null">&mdash;</label>
				<input type="radio" id="agency_contract-yes" name="agency_contract[]" <?= $user_agency["contract"] == 1 ? "checked" : "" ?> />
				<label for="agency_contract-yes"><?=t("Да")?></label>
				<input type="radio" id="agency_contract-no" name="agency_contract[]" <?= $user_agency["contract"] == -1 ? "checked" : "" ?> />
				<label for="agency_contract-no"><?=t("Нет")?></label>
			</div>
			<div class="clear"></div>
		</div>
		<div id="agency-contract-type-block" class="mb10 hide">
			<div class="left mr5 aright" style="width: 200px"><?=t("Тип контракта")?>: </div>
			<div class="left">
				<input type="radio" id="agency_contract_type-null" name="agency_contract_type[]" <?= $user_agency["contract_type"] == 0 ? "checked" : "" ?> />
				<label for="agency_contract_type-null">&mdash;</label><br />
				<input type="radio" id="agency_contract_type-yes" name="agency_contract_type[]" <?= $user_agency["contract_type"] == 1 ? "checked" : "" ?> />
				<label for="agency_contract_type-yes"><?=t("Эксклюзивный")?></label><br />
				<input type="radio" id="agency_contract_type-no" name="agency_contract_type[]" <?= $user_agency["contract_type"] == -1 ? "checked" : "" ?> />
				<label for="agency_contract_type-no"><?=t("Неэксклюзивный")?></label>
			</div>
			<div class="clear"></div>
		</div>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#agency_contract-yes").click(function(){
					$("#agency-contract-type-block").show();
				});
				$("#agency_contract-null, #agency_contract-no").click(function(){
					$("#agency-contract-type-block").hide();
				});

				if($("#agency_contract-yes").attr("checked") == "checked"){
					$("#agency_contract-yes").click();
				}
			});
		</script>
		<div class="mb10 p5 bold">
			Иностранное агентство:
		</div>
		<div id="foreign-agency-blocks">
			<div id="foreign-agency-block-1">
				<div class="mb10">
					<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Название")?>: </div>
					<div class="left">
						<input type="text" id="foreign_agency_name-1" value="<?=$user_foreign_agency["name"]?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="mb10">
					<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Город (пример: Paris, NY, Milan)")?>: </div>
					<div class="left">
						<input type="text" id="foreign_agency_city-1" value="<?=$user_foreign_agency["city"]?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="mb10">
					<div class="left pt5 mr5 aright" style="width: 200px">&nbsp;</div>
					<div class="left">
						<input type="radio" id="foreign_agency_type-1" name="materiskoe[]" <? if($user_foreign_agency["type"]){ ?>checked<? } ?> />
						<label for="foreign_agency_type-1"><?=t('Материнское агентство')?></label>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div id="foreign-agency-block-99" class="hide">
				<div><hr style="border: none; border-bottom: 1px solid #ccc;" /></div>
				<div class="mb10">
					<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Название")?>: </div>
					<div class="left">
						<input type="text" id="foreign_agency_name-99" value="" />
					</div>
					<div class="left">
						<input type="button" id="rem-foreign-agency-99" value="<?=t("Удалить")?>" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="mb10">
					<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Город (пример: Paris, NY, Milan)")?>: </div>
					<div class="left">
						<input type="text" id="foreign_agency_city-99" value="" />
					</div>
					<div class="clear"></div>
				</div>
				<div class="mb10">
					<div class="left pt5 mr5 aright" style="width: 200px">&nbsp;</div>
					<div class="left">
						<input type="radio" id="foreign_agency_type-99" name="materiskoe[]" />
						<label for="foreign_agency_type-99"><?=t('Материнское агентство')?></label>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px">&nbsp;</div>
			<div class="left">
				<input type="button" id="add_foreign_agency" value="<?=t("Добавить")?>" />
			</div>
			<div class="clear"></div>
		</div>

		<script type="text/javascript">
			$(document).ready(function(){
				var add_foreign_agency = function(name, city, type)
				{
					var block = $("#foreign-agency-block-99").clone();
					var count = $("#foreign-agency-blocks div[id^='foreign-agency-block']").length;

					$(block)
						.attr("id", "foreign-agency-block-"+count)
						.find("input[id^='foreign_agency_name']")
							.attr("id", "foreign_agency_name-"+count)
							.val(name);

					$(block)
						.find("input[id^='foreign_agency_city']")
							.attr("id", "foreign_agency_city-"+count)
							.val(city);
							
					$(block)
						.find("input[id^='foreign_agency_type']")
							.attr({
								"id": "foreign_agency_type-"+count,
								'checked': type != 1 ? false : true
							})
							.val(city)
						.next()
							.attr('for', "foreign_agency_type-"+count);

					$(block)
						.find("input[id^='rem-foreign-agency']")
							.attr("id", "rem-foreign-agency-"+count)
							.click(function(){
								rem_foreign_agency($(this).attr('id').split('-')[3])
							})

					//$("#foreign-agancy-blocks").append($(block));

					$("#foreign-agency-blocks #foreign-agency-block-99").before($(block));

					$(block).show();
				}

				var rem_foreign_agency = function(id)
				{
					$("#foreign-agency-block-"+id).remove();
				}

				$("#add_foreign_agency").click(function(){
					add_foreign_agency();
				});

				<? for($i = 1; $i < count($user_foreign_agency_id); $i++){ ?>
					<? $user_foreign_agency = user_agency_peer::instance()->get_item($user_foreign_agency_id[$i]); ?>
					add_foreign_agency("<?=$user_foreign_agency["name"]?>", "<?=$user_foreign_agency["city"]?>", <?=$user_foreign_agency["type"]?>);
				<? } ?>
			});
		</script>
		
		<div class="mt30">
			<div class="left pt5 mr5 aright" style="width: 200px">&nbsp;</div>
			<div class="left">
				<input type="button" id="submit" value="<?=t("Сохранить")?>" />
			</div>
			<div id="msg-success-agency" class="left pt5 ml10 acenter hide" style="color: #090">
				<?=t("Данные сохранены успешно")?>
			</div>
			<div class="clear"></div>
		</div>
		
	</form>
	
</div>

<script type="text/javascript">
	$(document).ready(function(){
		var form = new Form("profile-edit-form-agency");
		form.onSuccess = function(data){
			if(data.success)
				$("#msg-success-agency")
					.show()
					.css("opacity", "0")
					.animate({
						"opacity": "1"
					}, 256, function(){
						setTimeout(function(){
							$("#msg-success-agency").animate({
								"opacity": "0"
							}, 256, function(){
								$(this).hide();
							})
						}, 1000);
					});
		}
		$("#profile-edit-form-agency #submit").click(function(){
			form.send();
		});
	})
</script>