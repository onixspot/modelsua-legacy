<?php

/**
 * @param string $expr
 *
 * @return string
 */
$expr = static function ($expr) {
    return $expr;
};

/**
 * @param array $profile
 * @param array $options
 *
 * @return string
 */
return static function ($profile, $options = []) use ($expr) {
    $days = array_map(static function ($value) use ($profile) {
        $selected = '';
        if ($value === $profile['dob_day']) {
            $selected = ' selected="selected"';
        }

        return sprintf('<option value="%s"%s>%1$s</option>', $value, $selected);
    }, range(1, 31));

    $months = array_map(static function ($value, $text) use ($profile) {
        $selected = '';
        if ($value === $profile['dob_month']) {
            $selected = ' selected="selected"';
        }

        return sprintf('<option value="%s"%3$s>%s</option>', $value, $text, $selected);
    }, range(1, 12), ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря']);

    $years = array_map(static function ($value) use ($profile) {
        $selected = '';
        if ($value === $profile['dob_year']) {
            $selected = ' selected="selected"';
        }

        return sprintf('<option value="%s"%s>%1$s</option>', $value, $selected);
    }, range(2019, 1970));

    return <<<HTML
<div class="{$expr(implode(' ', ['form-row', 'align-items-baseline', $options['class']]))}">
    <div class="col-3 text-right">Дата рождения:</div>
    <div class="col">
        <label class="m-0" style="display: grid; grid-auto-flow: column; grid-template-columns: 50px 100px 75px; grid-column-gap: 5px">
            <select id="dob_day">
                <option value="">&mdash;</option>
                {$expr(implode(PHP_EOL, $days))}
            </select>
            <select id="dob_month">
                <option value="">&mdash;</option>
                {$expr(implode(PHP_EOL, $months))}
            </select>
            <select id="dob_year">
                <option value="">&mdash;</option>
                {$expr(implode(PHP_EOL, $years))}
            </select>
        </label>
    </div>
</div>
HTML;
};