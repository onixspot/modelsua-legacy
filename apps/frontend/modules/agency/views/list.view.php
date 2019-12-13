<?php
/**
 * @var array $context
 * @var array $agenciesWithoutLocation
 */
?>

<div class="small-title square_p pl10 mt10 mb5">
    <a href="/"><?= t('Агенства') ?></a>
</div>

<div class="grid-container horizontal-scrolling-container">
    <?php foreach ($context as $country) { ?>
        <div>
            <h3 class="p0 m0 mt10 mb15">
                <span class="flag-icon flag-icon-<?= $country['code'] ?>"
                      style="font-size: 200%; background-size: cover; width: 90%; line-height: 1.5em; box-shadow: 0px 0px 5px #9CA1AE"/>
            </h3>
            <ul>
                <?php foreach ($country['cities'] as $city) { ?>
                    <li class="p-0 mt-1">
                        <h6 class="mt-3 p-1 text-uppercase" style="width: 90%; background-color: #6C7580; color: white"><?= $city['name'] ?></h6>
                        <ul>
                            <?php foreach ($city['agencies'] as $agency) { ?>
                                <li class="p0 mt5" style="color: #aaa">
                                    <a href="/agency/?id=<?= $agency['id'] ?>" class="mr-1"><?= $agency['name'] ?></a><?= $agency['members_count'] > 0 ? $agency['members_count'] : '' ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
</div>

<div class="grid-container mt15 p15" style="background-color: #eee">
    <?php foreach (array_chunk($agenciesWithoutLocation, 5) as $group) { ?>
        <ul>
            <?php foreach ($group as $agency) { ?>

                <a href="<?= $agency['link'] ?>"><?= $agency['name'] ?></a><?= $agency['members_count'] > 0 ? " - {$agency['members_count']}"
                    : '' ?>
            <? } ?>
        </ul>
    <?php } ?>

</div>

<ul id="sortable" class="connectedSortable" style="display:none;">
    <?php if (count($list) > 0) { ?>
        <?php foreach ($list as $aid => $pids) { ?>
            <?php $agency = agency_peer::instance()->get_item($aid); ?>
            <li class="grag-end-drop" id="<?= $agency['id'] ?>" rel="<?= $pos + 100 ?>">
                <a class="fs18" href="/agency/?id=<?= $aid ?>"/><?= $agency['name'] ?> - <?= count($pids) ?></a>
                <div>
                    <?php $cnt = 0; ?>
                    <?php foreach ($pids as $pid) { ?>
                        <?php if ($cnt > 19) {
                            break;
                        } ?>
                        <?php $user_data = profile_peer::instance()->get_item(db::get_scalar('SELECT user_id FROM user_data WHERE pid = '.$pid)); ?>
                        <div id="agency-models-item-<?= $user_data['user_id'] ?>" class="left mr5 mt5"
                             style="width: 45px; height: 60px; background: url('/imgserve?pid=<?= $pid ?>&h=60') no-repeat center">
                            <div id="agency-models-item-tooltip-<?= $user_data['user_id'] ?>" class="cwhite fs14 p10 hide"
                                 style="position: absolute; background: black; border-radius: 5px; z-index: 999;"><?= profile_peer::get_name($user_data) ?></div>
                        </div>
                        <?php $cnt++ ?>
                    <?php } ?>
                    <div class="clear"></div>
                </div>
            </li>
        <?php } ?>
    <?php } ?>
</ul>

<script type="text/javascript">
    $(document).ready(function () {
        $("div[id^='agency-models-item']")
            .mouseover(function () {
                var id = $(this).attr('id').split('-')[3];
                $('#agency-models-item-tooltip-' + id).show();
            })
            .mouseout(function () {
                var id = $(this).attr('id').split('-')[3];
                $('#agency-models-item-tooltip-' + id).hide();
            })
            .mousemove(function (evn) {
                var id = $(this).attr('id').split('-')[3];

                var x = evn.clientX + $(window).scrollLeft() + 16;
                var y = evn.clientY + $(window).scrollTop() + 16;

                $('#agency-models-item-tooltip-' + id)
                    .css({
                        'left': x + 'px',
                        'top': y + 'px'
                    });
            })
            .click(function () {
                var id = $(this).attr('id').split('-')[3];
                var newWindow = window.open('/profile/?id=' + id, '_blank');
                newWindow.focus();
            });
    });
</script>

<style type="text/css">
    div.grid-container {
        display: grid;
        grid-auto-flow: column;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }

    div.horizontal-scrolling-container {
        overflow-x: scroll;
    }

    div.grid-container > div {
        padding: 0;
        min-width: 150px;
    }

</style>
