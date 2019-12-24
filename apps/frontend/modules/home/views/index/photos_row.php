<?php

$registerLink = static function ($link) {
    if (isset($link['enable']) && call_user_func($link['enable'])) {
        return null;
    }

    return <<<HTML
<div class="register-link right">
    <a href="{$link['href']}" class="cpurple">{$link['text']}</a>
</div>
HTML;
};

$includeGirls = static function ($most) {
    ob_start();
    include __DIR__.'/girls.php';

    return ob_get_clean();
};

return static function ($context) use ($registerLink, $includeGirls) {
    $links = [];
    if (isset($context['register_links']) && is_array($context['register_links'])) {
        foreach ($context['register_links'] as $link) {
            $links[] = $registerLink($link);
        }
    }
    $links = implode(PHP_EOL, $links);

    return <<<HTML
<div>
    <div class="small-title left square_p">
        <a href="{$context['href']}">{$context['title']}</a>
    </div>
    {$links}
    <div class="clear"></div>
</div>
{$includeGirls($context['collection'])}
<div class="clear"></div>
HTML;
};