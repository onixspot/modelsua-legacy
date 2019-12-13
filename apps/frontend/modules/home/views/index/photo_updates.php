<?php

return static function ($boxes) {

    $images = implode(
        PHP_EOL,
        array_map(
            static function ($box) {
                rsort($box['images']);

                return <<< HTML
<div style="position: relative">
    <img alt="" src="/imgserve?pid={$box['images'][0]}&h=295"
         class="img-fluid img-thumbnail"
         style="object-fit: cover; width: 100%; height: 295px"/>
</div>
HTML;

            },
            $boxes
        )
    );

    return <<< HTML
<div class="container">
    <div class="row">
        <div class="col small-title square_p pl10">
            <a href="/updates">Новые фотографии</a>
        </div>
    </div>
    <div class="row">
        <div class="grid auto-flow-column justify-content-between" style="grid-template-columns: repeat(4, 240px)">
        {$images}
        </div>
    </div>
</div>
HTML;
}
?>

<?php if (1 !== 1) { ?>
    <div class="mb20 updates" style="width: 100%;">
        <?php foreach ($boxes as $box) { ?>
            <?php rsort($box['images']) ?>
            <div class="update">
                <div style="width: 220px; height: 295px; overflow: hidden;">
                    <div class="photo" category="<?= $box['category'] ?>" step="0"
                         style="background: url('/imgserve?pid=<?= $box['images'][0] ?>&h=295') center;"></div>

                    <?php $user_data = profile_peer::instance()->get_item($users[$box['images'][0]]); ?>

                    <div class="desc"><a class="cwhite"
                                         href="/profile?id=<?= $users[$box['images'][0]] ?>"><?= profile_peer::get_name($user_data, '&fn') ?> <span
                                    class="ucase"><?= profile_peer::get_name($user_data, '&ln') ?></span></a></div>
                </div>
                <div class="acenter ucase fs14 bold mt10">
                    <a class="underline"
                       href="/updates?category=<?= $box['category'] == 'advertisement' || $box['category'] == 'catalogs' ? 'adv' : $box['category'] ?>">
                        <?= user_albums_peer::get_category(
                            $box['category'] == 'advertisement' || $box['category'] == 'catalogs' ? 'adv'
                                : $box['category']
                        ) ?>
                    </a>
                </div>
            </div>
        <?php } ?>
        <div class="clear"></div>
    </div>
<?php } ?>