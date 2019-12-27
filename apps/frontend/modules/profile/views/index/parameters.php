<?php

$badge = static function ($label, $value, $units = null) {
    return <<<HTML
<div class="pr-2">{$label} <span class="badge badge-dark fs12">{$value} ${$units}</span></div>
HTML;
};

return static function ($profile) use ($badge) {
    $user_params = profile_peer::instance()->get_params($profile['user_id']);

    // if (!($user_params['growth'] || $user_params['weigth'] || ($user_params['breast'] && $user_params['waist'] && $user_params['hip']))) {
    //     return null;
    // }

    $badges = [$badge('Pост', $user_params['growth'], 'см')];

    if ($user_params['weigth'] > 0) {
        $badges[] = $badge('Вес', $user_params['weigth'], 'кг');
    }

    if ($user_params['breast'] > 0 && $user_params['waist'] > 0 && $user_params['hip'] > 0) {
        $badges[] = $badge('Обьемы', sprintf('%s-%s-%s', $user_params['breast'], $user_params['waist'], $user_params['hip']));
    }

    return sprintf('<div class="d-flex flex-row mt-2 fs12">\n%s\n</div>', implode(PHP_EOL, $badges));
};