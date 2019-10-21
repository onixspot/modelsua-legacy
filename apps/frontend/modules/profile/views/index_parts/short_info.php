<div class="mt20 black fs12">
	<div>
		<span class="bold"><?=profile_peer::get_age($profile["birthday"])?></span>&nbsp;
		<span style="color: #aeb7c9">(<?=profile_peer::get_birthday($profile["birthday"])?>)</span>
	</div>
	<? $country = geo_peer::instance()->get_countries(array("country_id" => $profile["country"])) ?>
	<? $city = geo_peer::instance()->get_cities(array("city_id" => $profile["city"])) ?>
	<div><?=$country["country"][0]["name"]?> / <? if($profile["city"] == -1){ ?><?=$profile["another_city"]?><? } else { ?><?=$city["city"][0]["name"]?><? } ?></div>
	
	
	<? $user_agency_id = user_agency_peer::instance()->get_list(array("user_id" => $profile["user_id"], "foreign_agency" => 0)); ?>
	<? $user_agency = user_agency_peer::instance()->get_item($user_agency_id[0]); ?>
	<? if($user_agency["agency_id"] > 0){ ?>
		<? $user_agency_name = agency_peer::instance()->get_item($user_agency["agency_id"])?>
	<? } else { ?>
		<? $user_agency_name = $user_agency; ?>
	<? } ?>
	<div>
		<span style="color: #aeb7c9">Материнское агентство:</span> <?=$user_agency_name["name"]?> / <?= $user_agency["contract_type"] == 1 ? "эксклюзивный" : "неэксклюзивный" ?> контакт
	</div>
	
	<? $user_agency_id = user_agency_peer::instance()->get_list(array("user_id" => $profile["user_id"], "foreign_agency" => 1)); ?>
	<? $user_agency = user_agency_peer::instance()->get_item($user_agency_id[0]); ?>
	<? if($user_agency["agency_id"] > 0){ ?>
		<? $user_agency_name = agency_peer::instance()->get_item($user_agency["agency_id"])?>
	<? } else { ?>
		<? $user_agency_name = $user_agency; ?>
	<? } ?>
	<div class="mb20">
		<span style="color: #aeb7c9">Иностранное агентство:</span> <?=$user_agency["name"]?>, <?=$user_agency["city"]?>
	</div>
	
	<? $user_params = profile_peer::instance()->get_params($profile["user_id"]); ?>
	<div>
		<span style="color: #aeb7c9">Рост:</span> <?=$user_params["growth"]?> см&nbsp;
		<span style="color: #aeb7c9">Вес:</span> <?=$user_params["weigth"]?> кг&nbsp;
		<span style="color: #aeb7c9">Объемы:</span> <?=$user_params["breast"]?> / <?=$user_params["waist"]?> / <?=$user_params["hip"]?>&nbsp;
	</div>
</div>
