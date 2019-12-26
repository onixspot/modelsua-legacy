<?php $user_params = profile_peer::instance()->get_params($profile['user_id']); ?>
<?php if (
    $user_params['growth'] ||
    $user_params['weigth'] ||
    (
        $user_params['breast'] &&
        $user_params['waist'] &&
        $user_params['hip']
    )
) { ?>
    <div class="row mt-2 fs12">
        <div class="col">Pост <?= $user_params['growth'] ?> <?= t('см') ?></div>
        <div class="col">Вес <?= $user_params['weigth'] ?> <?= t('кг') ?></div>
        <?php if ($user_params['breast'] > 0 && $user_params['waist'] > 0 && $user_params['hip'] > 0) { ?>
            <div class="col">Объемы <?= sprintf('%s-%s-%s', $user_params['breast'], $user_params['waist'], $user_params['hip']) ?></div>
        <?php } ?>

        <!--<div class="left cgray">-->
        <?php //if ($user_params['growth']) { ?>
        <!--Рост-->
        <?php //} ?>
        <?php //if ($user_params['weigth']) { ?>
        <?php //if ($user_params['growth']) { ?>
        <!--/-->
        <?php //} ?>
        <!--Вес-->
        <?php //} ?>

        <?php //if ($user_params['breast'] && $user_params['waist'] && $user_params['hip']) { ?>
        <?php //if ($user_params['growth'] || $user_params['weigth']) { ?>
        <!--/-->
        <?php //} ?>
        <!--Объемы-->
        <?php //} ?>
        <!--</div>-->
        <!--<div class="left fs11">-->
        <?php //if ($user_params['growth']) { ?>

        <?php //} ?><!----><?php //if ($user_params['weigth']) { ?><!----><?php //if ($user_params['growth']) { ?><!-- / --><?php //} ?><!----><? //= $user_params['weigth'].' '. ?><!----><?php //} ?><!---->
        <?php //if ($user_params['breast'] && $user_params['waist'] && $user_params['hip']) { ?><!----><?php //if ($user_params['growth'] || $user_params['weigth']) { ?><!-- / --><?php //} ?><!----><? //= $user_params['breast'] ?><!-----><? //= $user_params['waist'] ?><!----->
        <?php //= $user_params['hip'] ?><!----><?php //} ?>
        <!--</div>-->
        <!--<div class="clear"></div>-->
    </div>
<?php } ?>
