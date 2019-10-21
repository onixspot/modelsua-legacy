<div>
	Admin Geo Module
</div>

<? foreach($countries_ids as $country_id){ ?>
	<? $country = geo_peer::instance()->get_country($country_id, "meritokrat"); ?>
	<? if($country["id"] != 0){ ?>
		<? $sql = "INSERT INTO geo_countries (id, text) VALUES (".(int) $country["id"].", '".serialize(array("ru" => $country["name_ru"]))."')"; ?>
		<pre><?=$country["id"]?> = <?=$sql?></pre>
		<pre><?// geo_peer::instance()->exec($sql)?></pre>
	<? } ?>
<? } ?>

<? foreach($regions_ids as $region_id){ ?>
	<? $region = geo_peer::instance()->get_region($region_id, "meritokrat"); ?>
		<? $sql = "INSERT INTO geo_regions (geo_country_id, text) VALUES (".(int) $region["country_id"].", '".serialize(array("ru" => $region["name_ru"]))."')"; ?>
		<pre><?=$region["id"]?> = <?=$sql?></pre>
		<pre><?// geo_peer::instance()->exec($sql)?></pre>
<? } ?>
---
<? foreach($cities_ids as $city_id){ ?>
	<? $city = geo_peer::instance()->get_city($city_id, "meritokrat"); ?>
		<? $sql = "INSERT INTO geo_cities (geo_country_id, geo_region_id, text) VALUES (1, ".(int) $city["region_id"].", '".serialize(array("ru" => $city["name_ru"]))."')"; ?>
		<pre><?=$city["id"]?> = <?=$sql?></pre>
		<pre><?// geo_peer::instance()->exec($sql)?></pre>
<? } ?>