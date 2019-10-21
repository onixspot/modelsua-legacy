<?// $hidden_data = unserialize($profile['hidden_data']); ?>

<div class="mt20 black fs11">
	<? $user_agency_id = user_agency_peer::instance()->get_list(array("user_id" => $profile["user_id"], "foreign_agency" => 0)); ?>
	<? $user_agency = user_agency_peer::instance()->get_item($user_agency_id[0]); ?>
	<? if($user_agency["agency_id"] > 0){ ?>
		<? $_a = agency_peer::instance()->get_item($user_agency["agency_id"]); ?>
		<? $user_agency_name['name'] = '<a href="/agency/?id='.$user_agency["agency_id"].'">'.$_a['name'].'</a>' ?>
	<? } else { ?>
		<? $user_agency_name = $user_agency; ?>
	<? } ?>
	<? if($user_agency_name['name']){ // && session::has_credential('admin') ?>
		<div class="mt10">
			<div class="left cgray" style="width: <?=$attr_key_width?>px;"><?=t("Украинское агентство")?>:</div>
			<div class="left" style="width: <?=$attr_val_width?>px;">
				<span><?=$user_agency_name['name']?></span><br />
				<? if($user_agency["type"] > 0){ ?><span class="cgray"><?=t('Материнское агентство')?></span><br /><? } ?>
				<? if($user_agency["contract"] == 1){ ?>
					<span class="cgray">
						<? if($user_agency["contract_type"] == 1){ ?>
							<?=t("эксклюзивный")?>
						<? } elseif($user_agency["contract_type"] == -1){ ?>
							<?=t("неэксклюзивный") ?>
						<? } ?>&nbsp;<?=t("контракт")?>
					</span>
				<? } ?>
			</div>
			<div class="clear"></div>
		</div>
	<? } ?>
	
	<? $user_agency_id = user_agency_peer::instance()->get_list(array("user_id" => $profile["user_id"], "foreign_agency" => 1)); ?>
	<? $user_agency = user_agency_peer::instance()->get_item($user_agency_id[0]); ?>
	<? if($user_agency["name"]){ ?>
		<div class="mt10">
			<div class="left cgray" style="width: <?=$attr_key_width?>px;"><?=t("Иностранное агентство")?>:</div>
			<div class="left" style="width: <?=$attr_val_width?>px;">
				<span><?=$user_agency["name"]?><span class="cgray"> / <?=$user_agency["city"]?></span></span><br />
				<? if($user_agency["type"] > 0){ ?><span class="cgray"><?=t('Материнское агентство')?></span><? } ?>
			</div>
			<div class="clear"></div>
		</div>
	<? } ?>
	
	
	<? if($user_params["eye_color"]){ ?>
		<div class="mt10">
			<div class="left cgray" style="width: <?=$attr_key_width?>px;"><?=t("Цвет глаз")?>:</div>
			<div class="left" style="width: <?=$attr_val_width?>px;">
				<?=profile_peer::$params["eye_color"][$user_params["eye_color"]]?>
			</div>
			<div class="clear"></div>
		</div>
	<? } ?>

	<? if($user_params["hair_color"] || $user_params["hair_length"]){ ?>
		<div class="mt10">
			<div class="left cgray" style="width: <?=$attr_key_width?>px;">
				<?=t("Волосы")?>:
			</div>
			<div class="left" style="width: <?=$attr_val_width?>px;">
				<? if($user_params["hair_color"]){ ?><?=profile_peer::$params["hair_color"][$user_params["hair_color"]]?><? } ?><? if($user_params["hair_length"]){ ?><? if($user_params["hair_color"]){ ?>, <?=mb_strtolower(profile_peer::$params["hair_length"][$user_params["hair_length"]])?><? } else { ?><?=ucfirst(profile_peer::$params["hair_length"][$user_params["hair_length"]])?><? } ?><? } ?>
			</div>
			<div class="clear"></div>
		</div>
	<? } ?>

	<? $user_additional_id = user_additional_peer::instance()->get_list(array("user_id" => $profile["user_id"])); ?>
	<? $user_additional = user_additional_peer::instance()->get_item($user_additional_id[0]); ?>
	<? if($work_experience = profile_peer::$additional["work_experience"][$user_additional["work_experience"]]){ ?>
		<div class="mt10">
			<div class="left cgray" style="width: <?=$attr_key_width?>px;"><?=t("Стаж работы")?>:</div>
			<div class="left" style="width: <?=$attr_val_width?>px;">
				<?=$work_experience?>
			</div>
			<div class="clear"></div>
		</div>
	<? } ?>
	
	<? $he = unserialize($user_additional['higher_education']); ?>
	<? $study = array('', t('Дневная'), t('Вечерняя'), t('Заочная'), t('Ускоренная')); ?>
	<? $status = array('', t('Абитуриент'), t('Студент'), t('Студент (бакалавр)'), t('Студент (магистр)'), t('Выпускник (специалист)'), t('Выпускник (бакалавр)'), t('Выпускник (магистр)')); ?>
	<? if($he[0]['university'] != '' && $he[0]['faculty'] != '' && $he[0]['study'] > 0 && $he[0]['status'] > 0 && $he[0]['entry_year'] > 0 && $he[0]['ending_year'] > 0){ ?>
		<div class="mt10">
			<div class="left cgray" style="width: <?=$attr_key_width?>px;"><?=t("Высшее образование")?>:</div>
			<div class="left" style="width: <?=$attr_val_width?>px;">
				<?=$he[0]['university']?>,
				<?=$he[0]['faculty']?>,
				<?=mb_strtolower($study[$he[0]['study']])?> <?=t('ф.о.')?>,
				<?=mb_strtolower($status[$he[0]['status']])?>,
				<?=$he[0]['entry_year']?> - <?=$he[0]['ending_year']?>
			</div>
			<div class="clear"></div>
		</div>
	<? } ?>
	
	<? if(
		session::get_user_id() == $user_id || 
		session::has_credential("admin")
	){ ?>
		<? if($user_additional["foreign_work_experience"] > -1){ ?>
			<div class="mt10">
				<div class="left cgray" style="width: <?=$attr_key_width?>px;"><?=t("Опыт работы за границей")?>:</div>
				<div class="left" style="width: <?=$attr_val_width?>px;">
					<? if($user_additional["foreign_work_experience"]){ ?>
						<?=t("есть")?>
					<? } else { ?>
						<?=t("нет")?>
					<? } ?>
				</div>
				<div class="clear"></div>
			</div>
		<? } ?>
		
		<? if($user_additional["foreign_passport"] > -1){ ?>
			<div class="mt10">
				<div class="left cgray" style="width: <?=$attr_key_width?>px;"><?=t("Загранпаспорт")?>:</div>
				<div class="left" style="width: <?=$attr_val_width?>px;">
					<? if($user_additional["foreign_passport"] == 1){ ?>
						<?=t("есть")?>
					<? } elseif($user_additional["foreign_passport"] == 0) { ?>
						<?=t("нет")?>
					<? } else { ?>
						<?=t("скоро будет")?>
					<? } ?>
				</div>
				<div class="clear"></div>
			</div>
		<? } ?>

		<? if($visa = $user_additional["visa"]){ ?>
			<div class="mt10">
				<div class="left cgray" style="width: <?=$attr_key_width?>px;"><?=t("Визы")?>:</div>
				<div class="left" style="width: <?=$attr_val_width?>px;">
					<?=$visa?>
				</div>
				<div class="clear"></div>
			</div>
		<? } ?>
		
		<div class="mt10"></div>
		<? if($profile['type'] != 4 && $current_work_place_name = $user_additional["current_work_place_name"]){ ?>
			<div>
				<div class="left cgray" style="width: <?=$attr_key_width?>px;"><?=t("Место работы")?>:</div>
				<div class="left" style="width: <?=$attr_val_width?>px;">
					<?=$current_work_place_name?>, <?=$user_additional["current_work_place_appointment"]?>
				</div>
				<div class="clear"></div>
			</div>
		<? } ?>
		
		<? if($user_additional["marital_status"] > -1){ ?>
			<div class="mt10">
				<div class="left cgray" style="width: <?=$attr_key_width?>px;"><?=t("Семейное положение")?>:</div>
				<div class="left" style="width: <?=$attr_val_width?>px;">
					<? if($user_additional["marital_status"] == 1){ ?><?=t("Замужем")?><? } else { ?><?=t("Не замужем")?><? } ?><? if($user_additional["kids"] == 1){ ?>, <?=t("есть дети")?><? } elseif($user_additional["kids"] == 0) { ?>, <?=t("детей нет")?><? } ?>
				</div>
				<div class="clear"></div>
			</div>
		<? } ?>
		
		<? if($user_additional["smoke"] > -1){ ?>
			<div class="mt10">
				<div class="left cgray" style="width: <?=$attr_key_width?>px;"><?=t("Курю")?>:</div>
				<div class="left" style="width: <?=$attr_val_width?>px;">
					<? if($user_additional["smoke"] != 0){ ?>
						<?=t("да")?>
					<? } else { ?>
						<?=t("нет")?>
					<? } ?>
				</div>
				<div class="clear"></div>
			</div>
		<? } ?>
		
		<? if($user_additional["about_self"] != ''){ ?>
			<div class="mt10">
				<div class="left cgray" style="width: <?=$attr_key_width?>px;"><?=t("О себе")?>:</div>
				<div class="left" style="width: <?=$attr_val_width?>px;">
					<?=$user_additional["about_self"]?>
				</div>
				<div class="clear"></div>
			</div>
		<? } ?>
		
		<? if($user_additional["eng_knowledge"] > 0){ ?>
			<div class="mt10">
				<div class="left cgray" style="width: <?=$attr_key_width?>px;"><?=t("Уровень знания английского языка")?>:</div>
				<div class="left" style="width: <?=$attr_val_width?>px;">
					<?=profile_peer::$eng_knowledge[$user_additional["eng_knowledge"]]?>
				</div>
				<div class="clear"></div>
			</div>
		<? } ?>
		
		<? $__ud = user_data_peer::instance()->get_item($user_id); ?>
		<? $__hd = unserialize($__ud['hidden_data']) ?>
		<? if($__hd['why'] != ''){ ?>
			<div class="mt10">
				<div class="left cgray" style="width: <?=$attr_key_width?>px;"><?=t("Почему хотите стать моделью?")?>:</div>
				<div class="left" style="width: <?=$attr_val_width?>px;">
					<?=$__hd['why']?>
				</div>
				<div class="clear"></div>
			</div>
		<? } ?>
		
		
	<? } ?>
</div>
