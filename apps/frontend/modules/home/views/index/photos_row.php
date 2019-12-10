<?php
/**
 * @var array $context
 */
?>

<div class="<?= sprintf(' %s', !isset($context['css']) ?: $context['css']) ?>">
    <div class="small-title left square_p pl10 mb5">
        <a href="<?= $context['href'] ?>"><?= $context['title'] ?></a>
    </div>
    <?php if (isset($context['register_links']) && is_array($context['register_links'])) { ?>
        <?php foreach ($context['register_links'] as $link) { ?>
            <?php if (!isset($link['enable']) || (isset($link['enable']) && call_user_func($link['enable']))) { ?>
                <div class="register-link right">
                    <a href="<?= $link['href'] ?>" class="cpurple"><?= $link['text'] ?></a>
                </div>
            <?php } ?>
        <?php } ?>
    <?php } ?>
    <div class="clear"></div>
</div>
<?php $most = $context['collection']; ?>
<?php include __DIR__.'/girls.php' ?>
<div class="clear"></div>
