<?php

return static function ($context) {
    $countries = implode(PHP_EOL, array_map(static function ($country) {
        return sprintf('<option value="%s">%s</option>', $country['id'], $country['name']);
    }, geo_peer::instance()->get_countries()));

    return <<<HTML
<div class="form-row align-items-baseline">
    <div class="col-1 offset-2 text-right">Страна:</div>
    <div class="col-3">
        <select class="form-control form-control-sm">
            <option value="0">&mdash;</option>
            {$countries}
        </select>
    </div>
</div>

<div class="form-row align-items-baseline mt-1">
    <div class="col-1 offset-2 text-right">Журнал:</div>
    <div class="col-3">
        <select class="form-control form-control-sm">
            <option value="0">&mdash;</option>
        </select>
    </div>
</div>

<div class="mt-2 mb10">
    <div class="left pt5 mr5 aright" style="width: 200px;">Место работы:</div>
    <div class="left">
        <div class="left pt5 mr5 aright" style="width: 100px;">Название:</div>
        <div class="left">
            <input class="mb5" type="text" id="current_work_place_name" value="{$context['current_work_place_name']}"/><br/>
        </div>
        <div class="clear"></div>
        <div class="left pt5 mr5 aright" style="width: 100px;">Должность:</div>
        <div class="left">
            <input class="mb5" type="text" id="current_work_place_appointment" value="{$context['current_work_place_appointment']}"/><br/>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
HTML;
};