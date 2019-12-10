<?php

/**
 * @var int $userAgencyId
 * @var int $counter
 */

$userAgency = user_agency_peer::instance()->get_item($userAgencyId);

$name = $userAgency['name'];
if ($userAgency['agency_id'] > 0) {
    $agency = agency_peer::instance()->get_item($userAgency['agency_id']);
    $name   = sprintf('<a href="/agency/?id=%s">%s</a>', $agency['id'], $agency['name']);
}

$type         = (int) $userAgency['type'];
$contract     = (int) $userAgency['contract'];
$contractType = (int) $userAgency['contract_type'];

$geo = [];
if ((int) $userAgency['city_id'] !== 0) {
    $geo[] = $userAgency['city_id'] !== -1
        ? geo_peer::instance()->get_city($userAgency['city_id'])
        : $userAgency['city'];
}
if ((int) $userAgency['country_id'] > 0) {
    $geo[] = geo_peer::instance()->get_country($userAgency['country_id']);
}

$label = 'Украинское';
if ((bool) $userAgency['foreign_agency']) {
    $label = 'Иностранное';
    if (count($geo) > 0) {
        $name = sprintf('%s <span class="text-muted"> / %s</span>', $name, implode(' / ', $geo));
    }
}

if (empty($name)) {
    return;
}

?>
<div class="row">
    <?php if ($counter === 0) { ?>
        <div class="col-5 align-text-top">
            <span class="cgray"><?= t(sprintf('%s агентство', $label)) ?>:</span>
        </div>
    <?php } ?>
    <div class="col-7 <?= $counter === 0 ?: 'offset-5' ?>">
        <span><?= $name ?></span>
        <?php if ($type === 1) { ?>
            <br/>
            <span class="text-muted"><?= t('Материнское агентство') ?></span>
        <?php } ?>
        <?php if ($contract === 1) { ?>
            <br/>
            <span class="text-muted"><?= t(user_agency_peer::CONTRACT_TYPES[$contractType]) ?> <?= t('контракт') ?></span>
        <? } ?>
    </div>
</div>
