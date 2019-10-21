<? $user_additional_id = user_additional_peer::instance()->get_list(array("user_id" => $user_id)); ?>
<? $user_additional = user_additional_peer::instance()->get_item($user_additional_id[0]); ?>

<div id="profile-edit-frame-additional">
	<form id="profile-edit-form-additional" action="/profile/edit?id=<?=$profile["user_id"]?>&group=additional">
		<div>&nbsp;</div>
		<div class="mt10 mb10 <? if(profile_peer::get_type_by_user($profile["user_id"]) != 2){ ?>hide<? } ?>">
			<div class="left mr5 aright" style="width: 200px;"><?=t("Уровень знания английского языка")?>: </div>
			<div class="left">
				<select id="eng_knowledge">
					<option value="0">&mdash;</option>
					<? foreach(profile_peer::$eng_knowledge as $key => $val){ ?>
						<option value="<?=$key?>"><?=$val?></option>
					<? } ?>
				</select>
				<script type="text/javascript">
					$("#eng_knowledge").val(<?=$user_additional["eng_knowledge"]?>);
				</script>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mt10 mb10 <? if(profile_peer::get_type_by_user($profile["user_id"]) != 2){ ?>hide<? } ?>">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Опыт работы моделью")?>: </div>
			<div class="left">
				<select id="work_experience">
					<option value="0"><?=t("Нет опыта")?></option>
					<? foreach(profile_peer::$additional["work_experience"] as $key => $val){ ?>
						<option value="<?=$key?>"><?=$val?></option>
					<? } ?>
				</select>
				<script type="text/javascript">
					$("#work_experience").val(<?=$user_additional["work_experience"]?>);
				</script>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10 <? if(profile_peer::get_type_by_user($profile["user_id"]) != 2){ ?>hide<? } ?>">
			<div class="left mr5 aright" style="width: 200px;"><?=t("Наличие загранпаспорта")?>: </div>
			<div class="left">
				<input type="radio" id="foreign_passport-null" name="foreign_passport[]" <?= !isset($user_additional["foreign_passport"]) || $user_additional["foreign_passport"] == -1 ? "checked" : "" ?> />
				<label for="foreign_passport-null">&mdash;</label><br />
				<input type="radio" id="foreign_passport-yes" name="foreign_passport[]" <?= $user_additional["foreign_passport"] == 1 ? "checked" : "" ?> />
				<label for="foreign_passport-yes"><?=t("Да")?></label><br />
				<input type="radio" id="foreign_passport-no" name="foreign_passport[]" <?= isset($user_additional["foreign_passport"]) && $user_additional["foreign_passport"] == 0 ? "checked" : "" ?> />
				<label for="foreign_passport-no"><?=t("Нет")?></label><br />
				<input type="radio" id="foreign_passport-wait" name="foreign_passport[]" <?= $user_additional["foreign_passport"] == 2 ? "checked" : "" ?> />
				<label for="foreign_passport-wait"><?=t("Скоро будет")?></label>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10 <? if(profile_peer::get_type_by_user($profile["user_id"]) != 2){ ?>hide<? } ?>">
			<div class="left pt5 mr5 aright" style="width: 200px"><?=t("Наличие визы")?>:</div>
			<div class="left">
				<textarea id="visa" style="width: 300px;"><?=$user_additional["visa"]?></textarea>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10 <? if(profile_peer::get_type_by_user($profile["user_id"]) != 2){ ?>hide<? } ?>">
			<div class="left mr5 aright" style="width: 200px"><?=t("Опыт работы за границей")?>:</div>
			<div class="left">
				<div class="mb5">
					<input type="radio" id="foreign_work_experience-null" name="foreign_work_experience[]" <?= !isset($user_additional["foreign_work_experience"]) || $user_additional["foreign_work_experience"] == -1 ? "checked" : "" ?> />
					<label for="foreign_work_experience-null">&mdash;</label><br />
					<input type="radio" id="foreign_work_experience-yes" name="foreign_work_experience[]" <?= $user_additional["foreign_work_experience"] == 1 ? "checked" : "" ?> />
					<label for="foreign_work_experience-yes"><?=t("Да")?></label><br />
					<input type="radio" id="foreign_work_experience-no" name="foreign_work_experience[]" <?= isset($user_additional["foreign_work_experience"]) && $user_additional["foreign_work_experience"] == 0 ? "checked" : "" ?> />
					<label for="foreign_work_experience-no"><?=t("Нет")?></label>
				</div>
				<div>
					<textarea id="foreign_work_experience_desc" class="<?= $user_additional["foreign_work_experience"] == -1 ? "hide" : "" ?>" style="width: 300px;"><?=$user_additional["foreign_work_experience_desc"]?></textarea>
				</div>
				<script type="text/javascript">
					$("#foreign_work_experience-yes").click(function(){
							$("#foreign_work_experience_desc")
								.show()
								.val("");
					});

					$("#foreign_work_experience-no, #foreign_work_experience-null").click(function(){
							$("#foreign_work_experience_desc").hide();
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
		<div class="mb10 <? if(profile_peer::get_type_by_user($profile["user_id"]) != 2){ ?>hide<? } ?>">
			<div class="left mr5 aright" style="width: 200px;"><?=t("Семейное положение")?>: </div>
			<div class="left">
				<input type="radio" id="marital_status-null" name="marital_status[]" <?= !isset($user_additional["marital_status"]) || $user_additional["marital_status"] == -1 ? "checked" : "" ?> />
				<label for="marital_status-null">&mdash;</label><br />
				<input type="radio" id="marital_status-yes" name="marital_status[]" <?= $user_additional["marital_status"] == 1 ? "checked" : "" ?> />
				<label for="marital_status-yes"><?=t("Замужем")?></label><br />
				<input type="radio" id="marital_status-no" name="marital_status[]" <?= isset($user_additional["marital_status"]) && $user_additional["marital_status"] == 0 ? "checked" : "" ?> />
				<label for="marital_status-no"><?=t("Не замужем")?></label>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10 <? if(profile_peer::get_type_by_user($profile["user_id"]) != 2){ ?>hide<? } ?>">
			<div class="left mr5 aright" style="width: 200px;"><?=t("Есть ли дети")?>: </div>
			<div class="left">
				<input type="radio" id="kids-null" name="kids[]" <?= !isset($user_additional["kids"]) || $user_additional["kids"] == -1 ? "checked" : "" ?> />
				<label for="kids-null">&mdash;</label><br />
				<input type="radio" id="kids-yes" name="kids[]" <?= $user_additional["kids"] == 1 ? "checked" : "" ?> />
				<label for="kids-yes"><?=t("Да")?></label><br />
				<input type="radio" id="kids-no" name="kids[]" <?= isset($user_additional["kids"]) && $user_additional["kids"] == 0 ? "checked" : "" ?> />
				<label for="kids-no"><?=t("Нет")?></label>
			</div>
			<div class="clear"></div>
		</div>
		<div class="mb10">
			<div class="left pt5 mr5 aright" style="width: 200px;"><?=t("Текущее место работы")?>: </div>
			<div class="left">
				<div class="left pt5 mr5 aright" style="width: 100px;"><?=t("Название")?>: </div>
				<div class="left">
					<input class="mb5" type="text" id="current_work_place_name" value="<?=$user_additional["current_work_place_name"]?>" /><br />
				</div>
				<div class="clear"></div>
				<div class="left pt5 mr5 aright" style="width: 100px;"><?=t("Должность")?>: </div>
				<div class="left">
					<input class="mb5" type="text" id="current_work_place_appointment" value="<?=$user_additional["current_work_place_appointment"]?>" /><br />
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
		
		<? $higher_education = unserialize($user_additional['higher_education']) ?>
		<div class="mb10 <? if(profile_peer::get_type_by_user($profile["user_id"]) != 2){ ?>hide<? } ?>">
			<div class="left pt5 mr5 aright" style="width: 200px;"><?=t("Высшее образование")?>: </div>
			<div class="left">
				<div class="left pt5 mr5 aright" style="width: 100px;"><?=t("ВУЗ")?>: </div>
				<div class="left">
					<input class="mb5" id="university" type="text" value="<?=$higher_education[0]['university']?>" />
				</div>
				<div class="clear"></div>
				
				<div class="left pt5 mr5 aright" style="width: 100px;"><?=t("Факультет")?>: </div>
				<div class="left">
					<input class="mb5" id="faculty" type="text" value="<?=$higher_education[0]['faculty']?>" />
				</div>
				<div class="clear"></div>
				
				<div class="left pt5 mr5 aright" style="width: 100px;"><?=t("Форма обучения")?>: </div>
				<div class="left">
					<select class="mb5" id="study">
						<option value="0">&mdash;</option>
						<option value="1"><?=t('Дневная')?></option>
						<option value="2"><?=t('Вечерняя')?></option>
						<option value="3"><?=t('Заочная')?></option>
						<option value="4"><?=t('Ускоренная')?></option>
					</select>
					<script type="text/javascript">
						$(document).ready(function(){
							$('#study').val(<?= ($study = $higher_education[0]['study']) ? $study : 0 ?>);
						});
					</script>
				</div>
				<div class="clear"></div>
				
				<div class="left pt5 mr5 aright" style="width: 100px;"><?=t("Статус")?>: </div>
				<div class="left">
					<select class="mb5" id="status">
						<option value="0">&mdash;</option>
						<option value="1"><?=t('Абитуриент')?></option>
						<option value="2"><?=t('Студент')?></option>
						<option value="3"><?=t('Студент (бакалавр)')?></option>
						<option value="4"><?=t('Студент (магистр)')?></option>
						<option value="5"><?=t('Выпускник (специалист)')?></option>
						<option value="6"><?=t('Выпускник (бакалавр)')?></option>
						<option value="7"><?=t('Выпускник (магистр)')?></option>
					</select>
					<script type="text/javascript">
						$(document).ready(function(){
							$('#status').val(<?= ($status = $higher_education[0]['status']) ? $status : 0 ?>);
						});
					</script>
				</div>
				<div class="clear"></div>
				
				<div class="left pt5 mr5 aright" style="width: 100px;"><?=t("Год поступления")?>: </div>
				<div class="left">
					<select class="mb5" id="entry_year">
						<option value="0">&mdash;</option>
						<? for($i = 0; $i < 50; $i++){ ?>
							<option value="<?=(date('Y')-$i)?>">
								<?=(date('Y')-$i)?>
							</option>
						<? } ?>
					</select>
					<script type="text/javascript">
						$(document).ready(function(){
							$('#entry_year').val(<?= ($entry_year = $higher_education[0]['entry_year']) ? $entry_year : 0 ?>);
						});
					</script>
				</div>
				<div class="clear"></div>
				
				<div class="left pt5 mr5 aright" style="width: 100px;"><?=t("Год окончания")?>: </div>
				<div class="left">
					<select class="mb5" id="ending_year">
						<option value="0">&mdash;</option>
						<? for($i = 0; $i < 50; $i++){ ?>
							<option value="<?=(date('Y')-$i)?>">
								<?=(date('Y')-$i)?>
							</option>
						<? } ?>
					</select>
					<script type="text/javascript">
						$(document).ready(function(){
							$('#ending_year').val(<?= ($ending_year = $higher_education[0]['ending_year']) ? $ending_year : 0 ?>);
						});
					</script>
				</div>
				<div class="clear"></div>
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="mb10">
			<div class="left mr5 aright" style="width: 200px;"><?=t("О себе")?>: </div>
			<div class="left">
				<textarea style="width: 400px; height: 200px;" id="about_self"><?=$user_additional['about_self']?></textarea>
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="mb10 <? if(profile_peer::get_type_by_user($profile["user_id"]) != 2){ ?>hide<? } ?>">
			<div class="left mr5 aright" style="width: 200px;"><?=t("Отношение к курению")?>: </div>
			<div class="left">
				<input type="radio" id="smoke-null" name="smoke[]" <?= !isset($user_additional["smoke"]) || $user_additional["smoke"] == -1 ? "checked" : "" ?> />
				<label for="smoke-null">&mdash;</label><br />
				<input type="radio" id="smoke-yes" name="smoke[]" <?= $user_additional["smoke"] == 1 ? "checked" : "" ?> />
				<label for="smoke-yes"><?=t("Курю")?></label><br />
				<input type="radio" id="smoke-no" name="smoke[]" <?= isset($user_additional["smoke"]) && $user_additional["smoke"] == 0 ? "checked" : "" ?> />
				<label for="smoke-no"><?=t("Не курю")?></label>
			</div>
			<div class="clear"></div>
		</div>
	
		
		<div class="mt30">
			<div class="left pt5 mr5 aright" style="width: 200px">&nbsp;</div>
			<div class="left">
				<input type="button" id="submit" value="<?=t("Сохранить")?>" />
			</div>
			<div id="msg-success-additional" class="left pt5 ml10 acenter hide" style="color: #090">
				<?=t("Данные сохранены успешно")?>
			</div>
			<div class="clear"></div>
		</div>
	
		
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		var form = new Form("profile-edit-form-additional");
		form.onSuccess = function(data){
			if(data.success)
				$("#msg-success-additional")
					.show()
					.css("opacity", "0")
					.animate({
						"opacity": "1"
					}, 256, function(){
						setTimeout(function(){
							$("#msg-success-additional").animate({
								"opacity": "0"
							}, 256, function(){
								$(this).hide();
							})
						}, 1000);
					});
		}
		$("#profile-edit-form-additional #submit").click(function(){
			form.send();
		});
	})
</script>