<form id="profile_editor" action="/profile/edit?id=<?=$profile["user_id"]?>" method="post">
			
			<div class="mb10 p5 bold" style="background: #eee;">
				<?=t("Общая информация")?>
			</div>
			
			
			<div class="mb10 p5 bold" style="background: #eee;">
				<?=t("Текущее место проживания")?>
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
			
			<div class="mb10 p5 bold" style="background: #eee;">
				<?=t("Параметры")?>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Рост")?>: </div>
				<div class="left">
					<select id="growth">
						<option value="0" selected>&mdash;</option>
						<? for($i = 150; $i <= 210; $i++){ ?>
							<option value="<?=$i?>"><?=$i?></option>
						<? } ?>
					</select>
					<script type="text/javascript">
						$("#growth").val(<?=$user_data["growth"]?>);
					</script>
				</div>
				<div class="left pt5 ml5"><?=t("см")?></div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Вес")?>: </div>
				<div class="left">
					<select id="weigth">
						<option value="0" selected>&mdash;</option>
						<? for($i = 35; $i <= 110; $i++){ ?>
							<option value="<?=$i?>"><?=$i?></option>
						<? } ?>
					</select>
					<script type="text/javascript">
						$("#weigth").val(<?=$user_data["weigth"]?>);
					</script>
				</div>
				<div class="left pt5 ml5"><?=t("кг")?></div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Объемы")?>: </div>
				<div class="left pt5 mr5"><?=t("груди")?></div>
				<div class="left mr5">
					<input type="text" id="breast" value="<?=$user_data["breast"]?>" style="width: 32px;" />
				</div>
				<div class="left pt5 mr5"><?=t("см / талии")?></div>
				<div class="left mr5">
					<input type="text" id="waist" value="<?=$user_data["waist"]?>" style="width: 32px;" />
				</div>
				<div class="left pt5 mr5"><?=t("см / бедер")?></div>
				<div class="left mr5">
					<input type="text" id="hip" value="<?=$user_data["hip"]?>" style="width: 32px;" />
				</div>
				<div class="left pt5 mr5"><?=t("см")?></div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">Цвет глаз: </div>
				<div class="left">
					<select id="eye_color">
						<option value="0">&mdash;</option>
						<option value="1"><?=t("Карие")?></option>
						<option value="2"><?=t("Голубые")?></option>
						<option value="3"><?=t("Зеленые")?></option>
						<option value="4"><?=t("Серо-голубые")?></option>
						<option value="5"><?=t("Каре-зеленые")?></option>
						<option value="6"><?=t("Серые")?></option>
						<option value="7"><?=t("Серо-зеленые")?></option>
						<option value="8"><?=t("Темно-карие")?></option>
						<option value="9"><?=t("Зелено-голубые")?></option>
						<option value="10"><?=t("Черный")?></option>
						<option value="11"><?=t("Зелено-карие")?></option>
					</select>
					<script type="text/javascript">
						$("#eye_color").val(<?=$user_data["eye_color"]?>);
					</script>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Цвет волос")?>: </div>
				<div class="left">
					<select id="hair_color">
						<option value="0" selected>&mdash;</option>
						<option value="1"><?=t("Светлые")?></option>
						<option value="2"><?=t("Светло-русые")?></option>
						<option value="3"><?=t("Русые")?></option>
						<option value="4"><?=t("Темно-русые")?></option>
						<option value="5"><?=t("Светло-каштановые")?></option>
						<option value="6"><?=t("Каштановые")?></option>
						<option value="7"><?=t("Темно-каштановые")?></option>
						<option value="8"><?=t("Черные")?></option>
						<option value="9"><?=t("Рыжие")?></option>
						<option value="10"><?=t("Без волос")?></option>
					</select>
					<script type="text/javascript">
						$("#hair_color").val(<?=$user_data["hair_color"]?>);
					</script>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">Длина волос: </div>
				<div class="left">
					<select id="hair_length">
						<option value="0" selected>&mdash;</option>
						<option value="1">Очень короткие</option>
						<option value="2">Короткие</option>
						<option value="3">Средние</option>
						<option value="4">Длинные</option>
						<option value="5">Очень длинные</option>
						<option value="6">Без волос</option>
					</select>
					<script type="text/javascript">
						$("#hair_length").val(<?=$user_data["hair_length"]?>);
					</script>
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="mb10 p5 bold" style="background: #eee;">
				Агентство
			</div>
			<div class="bold mb10">Материнское агентство (в Украине):</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">Название: </div>
				<div class="left">
					<select id="agency">
						<option value="0">&mdash;</option>
						<option value="1">&laquo;Лица Украины&raquo;</option>
						<option value="2">L-Models</option>
						<option value="3">"Faces"</option>
						<option value="4">"Линия 12"</option>
						<option value="5">"Олег и Ева" (Oleg & Eva)</option>
						<option value="6">EOS-Models</option>
						<option value="7">STAR MODEL GROUP</option>
						<option value="8">Prime Model Management</option>
						<option value="9">Merilyn Media Group</option>
						<option value="10">KOLTSO</option>
						<option value="11">La Prima Model Management</option>
						<option value="12">Grand Models</option>
						<option value="13">Fashion Look</option>
						<option value="14">Casting City</option>
						<option value="15">Prestige</option>
						<option value="16">V&D</option>
						<option value="17">InterModels</option>
						<option value="18">&laquo;Никита&raquo;</option>
						<option value="19">Angel</option>
						<option value="20">La Femme Models</option>
						<option value="-1">Другое</option>
					</select>
					<input type="text" class="hide" id="another_agency" style="width: 200px" />
					<script type="text/javascript">
						$(document).ready(function(){
							$("#agency").change(function(){
								if($(this).val() == -1){
									$(this).hide();
									$("#another_agency")
										.show()
										.focus();
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

							$("#another_agency").val("<?=$user_data["ukraine_agency"][0]["another_agency"]?>");

							$("#agency")
								.val("<?=$user_data["ukraine_agency"][0]["id"]?>")
								.change();
						});
					</script>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">Контракт: </div>
				<div class="left">
					<input type="radio" id="agency_contract-yes" name="agency_contract[]" <?= $user_data["ukraine_agency"][0]["contract"] == 1 ? "checked" : "" ?> />
					<label for="agency_contract-yes">Да</label>
					<input type="radio" id="agency_contract-no" name="agency_contract[]" <?= $user_data["ukraine_agency"][0]["contract"] == 0 ? "checked" : "" ?> />
					<label for="agency_contract-no">Нет</label>
				</div>
				<div class="clear"></div>
			</div>
			<div id="agency-contract-type-block" class="mb10 hide">
				<div class="left pt5 mr5 aright" style="width: 200px">Тип контракта: </div>
				<div class="left">
					<input type="radio" id="agency_contract_type-yes" name="agency_contract_type[]" <?= $user_data["ukraine_agency"][0]["contract_type"] == 1 ? "checked" : "" ?> />
					<label for="agency_contract_type-yes">Эксклюзивный</label><br />
					<input type="radio" id="agency_contract_type-no" name="agency_contract_type[]" <?= $user_data["ukraine_agency"][0]["contract_type"] == 0 ? "checked" : "" ?> />
					<label for="agency_contract_type-no">Неэксклюзивный</label>
				</div>
				<div class="clear"></div>
			</div>
			<script type="text/javascript">
				$(document).ready(function(){
					$("#agency_contract-yes").click(function(){
						$("#agency-contract-type-block").show();
					});
					$("#agency_contract-no").click(function(){
						$("#agency-contract-type-block").hide();
					});
					
					if($("#agency_contract-yes").attr("checked") == "checked"){
						$("#agency_contract-yes").click();
					}
				});
			</script>
			<div class="bold mb10">Иностранное агентство:</div>
			<div id="foreign-agency-blocks">
				<div id="foreign-agency-block-1">
					<div class="mb10">
						<div class="left pt5 mr5 aright" style="width: 200px">Название: </div>
						<div class="left">
							<input type="text" id="foreign_agency_name-1" value="<?=$user_data["foreign_agency"][0]["name"]?>" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="mb10">
						<div class="left pt5 mr5 aright" style="width: 200px">Город (пример: Paris, NY, Milan): </div>
						<div class="left">
							<input type="text" id="foreign_agency_city-1" value="<?=$user_data["foreign_agency"][0]["city"]?>" />
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<div id="foreign-agency-block-99" class="hide">
					<div><hr style="border: none; border-bottom: 1px solid #ccc;" /></div>
					<div class="mb10">
						<div class="left pt5 mr5 aright" style="width: 200px">Название: </div>
						<div class="left">
							<input type="text" id="foreign_agency_name-99" value="" />
						</div>
						<div class="left">
							<input type="button" id="rem-foreign-agency-99" value="Удалить" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="mb10">
						<div class="left pt5 mr5 aright" style="width: 200px">Город (пример: Paris, NY, Milan): </div>
						<div class="left">
							<input type="text" id="foreign_agency_city-99" value="" />
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">&nbsp;</div>
				<div class="left">
					<input type="button" id="add_foreign_agency" value="Добавить" />
				</div>
				<div class="clear"></div>
			</div>
			
			<script type="text/javascript">
				$(document).ready(function(){
					var add_foreign_agency = function(name, city)
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
					
					<? for($i = 1; $i < count($user_data["foreign_agency"]); $i++){ ?>
						add_foreign_agency("<?=$user_data["foreign_agency"][$i]["name"]?>", "<?=$user_data["foreign_agency"][$i]["city"]?>");
					<? } ?>
				});
			</script>
			
			<div class="mb10 p5 bold" style="background: #eee;">
				Дополнительная информация
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">Стаж работы моделью: </div>
				<div class="left">
					<select id="work_experience">
						<option value="0">Нет</option>
						<option value="-1">Менее 1 года</option>
						<option value="1">1 год</option>
						<option value="2">2 года</option>
						<option value="3">3 года</option>
						<option value="4">4 года</option>
						<option value="5">5 лет</option>
						<option value="6">6 лет</option>
						<option value="7">7 лет</option>
						<option value="8">8 лет</option>
						<option value="9">9 лет</option>
						<option value="10">10 лет</option>
						<option value="10+">Более 10 лет</option>
					</select>
					<script type="text/javascript">
						$("#work_experience").val(<?=$user_data["work_experience"]?>);
					</script>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px;">Наличие загранпаспорта: </div>
				<div class="left">
					<input type="radio" id="abroad_passport-yes" name="abroad_passport[]" <?= $user_data["abroad_passport"] == 1 ? "checked" : "" ?> />
					<label for="abroad_passport-yes">Да</label>
					<input type="radio" id="abroad_passport-no" name="abroad_passport[]" <?= $user_data["abroad_passport"] == 0 ? "checked" : "" ?> />
					<label for="abroad_passport-no">Нет</label>
					<input type="radio" id="abroad_passport-wait" name="abroad_passport[]" <?= $user_data["abroad_passport"] == 2 ? "checked" : "" ?> />
					<label for="abroad_passport-wait">Скоро будет</label>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">Наличие виз:</div>
				<div class="left">
					<textarea id="visa" style="width: 300px;"><?=$user_data["visa"]?></textarea>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">Опыт работы за границей:</div>
				<div class="left">
					<div class="mb5">
						<input type="radio" id="abroad_work_experience-yes" name="abroad_work_experience[]" <?= $user_data["abroad_work_experience"] == 1 ? "checked" : "" ?> />
						<label for="abroad_work_experience-yes">Да</label>
						<input type="radio" id="abroad_work_experience-no" name="abroad_work_experience[]" <?= $user_data["abroad_work_experience"] == 0 ? "checked" : "" ?> />
						<label for="abroad_work_experience-no">Нет</label>
					</div>
					<div>
						<textarea id="abroad_work_experience_desc" class="<?= $user_data["abroad_work_experience"] == 0 ? "hide" : "" ?>" style="width: 300px;"><?=$user_data["abroad_work_experience_desc"]?></textarea>
					</div>
					<script type="text/javascript">
						$("#abroad_work_experience-yes").click(function(){
								$("#abroad_work_experience_desc")
									.show()
									.val("");
						});
						
						$("#abroad_work_experience-no").click(function(){
								$("#abroad_work_experience_desc").hide();
						});
					</script>
				</div>
				<div class="clear"></div>
			</div>
			<!--<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px;">Знание языков: </div>
				<div class="left">
					<div>
						<select id="lang-name-1">
							<option id="0">&mdash;</option>
							<option id="en">Английский</option>
							<option id="fr">Французский</option>
							<option id="de">Немецкий</option>
							<option id="it">Итальянский</option>
							<option id="es">Испанский</option>
							<option id="es">Другой</option>
						</select>
						<select id="lang-knowledge-1">
							<option id="0">&mdash;</option>
							<option id="4">Свободно</option>
							<option id="3">Хорошо</option>
							<option id="2">Средний</option>
							<option id="1">Начальное</option>
						</select>
					</div>
				</div>
				<div class="clear"></div>
			</div>-->
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px;">Семейное положение: </div>
				<div class="left">
					<input type="radio" id="marital_status-yes" name="marital_status[]" <?= $user_data["marital_status"] == 1 ? "checked" : "" ?> />
					<label for="marital_status-yes">Замужем</label>
					<input type="radio" id="marital_status-no" name="marital_status[]" <?= $user_data["marital_status"] == 0 ? "checked" : "" ?> />
					<label for="marital_status-no">Не замужем</label>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px;">Есть ли дети: </div>
				<div class="left">
					<input type="radio" id="kids-yes" name="kids[]" <?= $user_data["kids"] == 1 ? "checked" : "" ?> />
					<label for="kids-yes">Да</label>
					<input type="radio" id="kids-no" name="kids[]" <?= $user_data["kids"] == 0 ? "checked" : "" ?> />
					<label for="kids-no">Нет</label>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px;">Текущее место работы: </div>
				<div class="left">
					<div class="left pt5 mr5 aright" style="width: 70px;">Название: </div>
					<div class="left">
						<input class="mb5" type="text" id="work_place_name" value="<?=$user_data["work_place_name"]?>" /><br />
					</div>
					<div class="clear"></div>
					<div class="left pt5 mr5 aright" style="width: 70px;">Должность: </div>
					<div class="left">
						<input class="mb5" type="text" id="work_place_post" value="<?=$user_data["work_place_post"]?>" /><br />
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px;">Отношение к курению: </div>
				<div class="left">
					<input type="radio" id="smoking-yes" name="smoking[]" <?= $profile["smoking"] == 1 ? "checked" : "" ?> />
					<label for="smoking-yes">Курю</label>
					<input type="radio" id="smoking-no" name="smoking[]" <?= $profile["smoking"] == 0 ? "checked" : "" ?> />
					<label for="smoking-no">Не курю</label>
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="mb10 p5 bold" style="background: #eee;">
				Контакты
			</div>
			<? $profile_contacts = profile_peer::instance()->get_contacts($user_id); ?>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">Email: </div>
				<div class="left">
					<input type="text" id="email" value="<?=$profile_contacts["email"]?>" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">Телефон: </div>
				<div class="left">
					<input type="text" id="phone" value="<?=$profile_contacts["phone"]?>" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">Веб сайт: </div>
				<div class="left">
					<input type="text" id="website" value="<?=$profile_contacts["website"]?>" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">Skype: </div>
				<div class="left">
					<input type="text" id="skype" value="<?=$profile_contacts["skype"]?>" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">ICQ: </div>
				<div class="left">
					<input type="text" id="icq" value="<?=$profile_contacts["icq"]?>" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">Facebook.com: </div>
				<div class="left">
					<input type="text" id="facebook" value="<?=$profile_contacts["facebook"]?>" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">Napodiume.ru: </div>
				<div class="left">
					<input type="text" id="napodiume" value="<?=$profile_contacts["napodiume"]?>" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">Vkontakte.ru: </div>
				<div class="left">
					<input type="text" id="vkontakte" value="<?=$profile_contacts["vkontakte"]?>" />
				</div>
				<div class="clear"></div>
			</div>
			<div class="mb10">
				<div class="left pt5 mr5 aright" style="width: 200px">&nbsp;</div>
				<div class="left">
					<input type="button" id="submit" value="Сохранить" />
				</div>
				<div class="clear"></div>
			</div>
		</form>

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
								128,
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
						128,
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
								128,
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
					256,
					function(){
						$(this).hide();
					}
				);
			}
		});
		
		var last_name = false;
		$("#city").change(function(){
			if($(this).val() == -1){
				$(this).hide();
				$("#another_city")
					.show()
					.focus();
			}
			if(last_name == false){
				$("#last_name").focus();
				last_name = true;
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
		
		var form = new Form("profile_editor");
		form.onSuccess = function(data){
		}
		
		$("#profile_editor #submit").click(function(){
			form.data.submit = 1;
			form.send();
		});
		
	});
</script>