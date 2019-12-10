<?php
/**
 * @var array $most
 */
?>

<div class="main-page-gallery">
    <div class="image-box">
        <?php if ($most) { ?>
            <?php foreach ($most as $k => $v) {
                $baseUrl = sprintf('https://%s', conf::get('server'));
                $urlPath = 'no_image.png';

                if ($v['ph_crop']) {
                    $c       = unserialize($v['ph_crop']);
                    $urlPath = sprintf('imgserve?pid=%s&w=%s&h=%s&x=%s&y=%s&z=crop', $v['pid'], $c['w'], $c['h'], $c['x'], $c['y']);
                }

                $imgSrc = sprintf('%s/%s', $baseUrl, $urlPath);

                ?>
                <a href="/profile?id=<?= $v['id'] ?>" data-user-id="<?= $v['id'] ?>">
                    <img src="<?= $imgSrc ?>" <?= ($k % 10 === 0 && $k) ? 'style="margin-right: 0px;"'
                        : ($i === 0 ? 'style="margin-left: 0px;"' : '') ?>>

                    <?php $tooltip_style = [
                        'position: absolute',
                        'background: black',
                        'color: white',
                        'border-radius: 5px',
                        'font-size: 11px',
                        'padding: 5px 10px'
                    ] ?>

                    <div data-tooltip="<?= $v['id'] ?>"
                         class="hide"
                         style="<?=implode('; ', $tooltip_style)?>"><?= profile_peer::get_name($v) ?></div>
                </a>
            <?php } ?>
        <?php } ?>
    </div>
</div>


