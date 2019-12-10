<?php

/**
 * @var array $profile
 * @var array $user_params
 */

?>
<div class="mt20 black fs12">

    <div>
        <?php $counter = 0 ?>
        <?= implode(
            PHP_EOL,
            array_map(
                static function ($userAgencyId) use (&$counter) {
                    ob_start();
                    include __DIR__.'/info/agency.php';
                    $counter++;

                    return ob_get_clean();
                },
                user_agency_peer::instance()->get_list(['user_id' => $profile['user_id'], 'foreign_agency' => 0])
            )
        ) ?>
    </div>

    <div class="mt-3">
        <?php $counter = 0 ?>
        <?= implode(
            PHP_EOL,
            array_map(
                static function ($userAgencyId) use (&$counter) {
                    ob_start();
                    include __DIR__.'/info/agency.php';
                    $counter++;

                    return ob_get_clean();
                },
                user_agency_peer::instance()->get_list(['user_id' => $profile['user_id'], 'foreign_agency' => 1])
            )
        ); ?>
    </div>

    <?php if ($user_params['eye_color']) { ?>
        <div class="mt10">
            <div class="left cgray" style="width: <?= $attr_key_width ?>px;"><?= t('Цвет глаз') ?>:</div>
            <div class="left" style="width: <?= $attr_val_width ?>px;">
                <?= profile_peer::$params['eye_color'][$user_params['eye_color']] ?>
            </div>
            <div class="clear"></div>
        </div>
    <?php } ?>

    <?php if ($user_params['hair_color'] || $user_params['hair_length']) { ?>
        <div class="mt10">
            <div class="left cgray" style="width: <?= $attr_key_width ?>px;">
                <?= t('Волосы') ?>:
            </div>
            <div class="left" style="width: <?= $attr_val_width ?>px;">
                <?php if ($user_params['hair_color']) { ?><?= profile_peer::$params['hair_color'][$user_params['hair_color']] ?><?php } ?><?php if ($user_params['hair_length']) { ?><?php if ($user_params['hair_color']) { ?>, <?= mb_strtolower(
                    profile_peer::$params['hair_length'][$user_params['hair_length']]
                ) ?><?php } else { ?><?= ucfirst(profile_peer::$params['hair_length'][$user_params['hair_length']]) ?><?php } ?><?php } ?>
            </div>
            <div class="clear"></div>
        </div>
    <?php } ?>

    <?php $user_additional_id = user_additional_peer::instance()->get_list(['user_id' => $profile['user_id']]); ?>
    <?php $user_additional = user_additional_peer::instance()->get_item($user_additional_id[0]); ?>
    <?php if ($work_experience = profile_peer::$additional['work_experience'][$user_additional['work_experience']]) { ?>
        <div class="mt10">
            <div class="left cgray" style="width: <?= $attr_key_width ?>px;"><?= t('Стаж работы') ?>:</div>
            <div class="left" style="width: <?= $attr_val_width ?>px;">
                <?= $work_experience ?>
            </div>
            <div class="clear"></div>
        </div>
    <?php } ?>

    <?php $he = unserialize($user_additional['higher_education']); ?>
    <?php $study = ['', t('Дневная'), t('Вечерняя'), t('Заочная'), t('Ускоренная')]; ?>
    <?php $status = ['', t('Абитуриент'), t('Студент'), t('Студент (бакалавр)'), t('Студент (магистр)'), t('Выпускник (специалист)'), t('Выпускник (бакалавр)'), t('Выпускник (магистр)')]; ?>
    <?php if ($he[0]['university'] != '' && $he[0]['faculty'] != '' && $he[0]['study'] > 0 && $he[0]['status'] > 0 && $he[0]['entry_year'] > 0 && $he[0]['ending_year'] > 0) { ?>
        <div class="mt10">
            <div class="left cgray" style="width: <?= $attr_key_width ?>px;"><?= t('Высшее образование') ?>:</div>
            <div class="left" style="width: <?= $attr_val_width ?>px;">
                <?= $he[0]['university'] ?>,
                <?= $he[0]['faculty'] ?>,
                <?= mb_strtolower($study[$he[0]['study']]) ?> <?= t('ф.о.') ?>,
                <?= mb_strtolower($status[$he[0]['status']]) ?>,
                <?= $he[0]['entry_year'] ?> - <?= $he[0]['ending_year'] ?>
            </div>
            <div class="clear"></div>
        </div>
    <?php } ?>

    <?php if (
        session::get_user_id() == $user_id ||
        session::has_credential('admin')
    ) { ?>
        <?php if ($user_additional['foreign_work_experience'] > -1) { ?>
            <div class="mt10">
                <div class="left cgray" style="width: <?= $attr_key_width ?>px;"><?= t('Опыт работы за границей') ?>:</div>
                <div class="left" style="width: <?= $attr_val_width ?>px;">
                    <?php if ($user_additional['foreign_work_experience']) { ?>
                        <?= t('есть') ?>
                    <?php } else { ?>
                        <?= t('нет') ?>
                    <?php } ?>
                </div>
                <div class="clear"></div>
            </div>
        <?php } ?>

        <?php if ($user_additional['foreign_passport'] > -1) { ?>
            <div class="mt10">
                <div class="left cgray" style="width: <?= $attr_key_width ?>px;"><?= t('Загранпаспорт') ?>:</div>
                <div class="left" style="width: <?= $attr_val_width ?>px;">
                    <?php if ($user_additional['foreign_passport'] == 1) { ?>
                        <?= t('есть') ?>
                    <?php } elseif ($user_additional['foreign_passport'] == 0) { ?>
                        <?= t('нет') ?>
                    <?php } else { ?>
                        <?= t('скоро будет') ?>
                    <?php } ?>
                </div>
                <div class="clear"></div>
            </div>
        <?php } ?>

        <?php if ($visa = $user_additional['visa']) { ?>
            <div class="mt10">
                <div class="left cgray" style="width: <?= $attr_key_width ?>px;"><?= t('Визы') ?>:</div>
                <div class="left" style="width: <?= $attr_val_width ?>px;">
                    <?= $visa ?>
                </div>
                <div class="clear"></div>
            </div>
        <?php } ?>

        <div class="mt10"></div>
        <?php if ($profile['type'] != 4 && $current_work_place_name = $user_additional['current_work_place_name']) { ?>
            <div>
                <div class="left cgray" style="width: <?= $attr_key_width ?>px;"><?= t('Место работы') ?>:</div>
                <div class="left" style="width: <?= $attr_val_width ?>px;">
                    <?= $current_work_place_name ?>, <?= $user_additional['current_work_place_appointment'] ?>
                </div>
                <div class="clear"></div>
            </div>
        <?php } ?>

        <?php if ($user_additional['marital_status'] > -1) { ?>
            <div class="mt10">
                <div class="left cgray" style="width: <?= $attr_key_width ?>px;"><?= t('Семейное положение') ?>:</div>
                <div class="left" style="width: <?= $attr_val_width ?>px;">
                    <?php if ($user_additional['marital_status'] == 1) { ?><?= t('Замужем') ?><?php } else { ?><?= t('Не замужем') ?><?php } ?><?php if ($user_additional['kids'] == 1) { ?>, <?= t('есть дети') ?><?php } elseif ($user_additional['kids'] == 0) { ?>, <?= t('детей нет') ?><?php } ?>
                </div>
                <div class="clear"></div>
            </div>
        <?php } ?>

        <?php if ($user_additional['smoke'] > -1) { ?>
            <div class="mt10">
                <div class="left cgray" style="width: <?= $attr_key_width ?>px;"><?= t('Курю') ?>:</div>
                <div class="left" style="width: <?= $attr_val_width ?>px;">
                    <?php if ($user_additional['smoke'] != 0) { ?>
                        <?= t('да') ?>
                    <?php } else { ?>
                        <?= t('нет') ?>
                    <?php } ?>
                </div>
                <div class="clear"></div>
            </div>
        <?php } ?>

        <?php if ($user_additional['about_self'] != '') { ?>
            <div class="mt10">
                <div class="left cgray" style="width: <?= $attr_key_width ?>px;"><?= t('О себе') ?>:</div>
                <div class="left" style="width: <?= $attr_val_width ?>px;">
                    <?= $user_additional['about_self'] ?>
                </div>
                <div class="clear"></div>
            </div>
        <?php } ?>

        <?php if ($user_additional['eng_knowledge'] > 0) { ?>
            <div class="mt10">
                <div class="left cgray" style="width: <?= $attr_key_width ?>px;"><?= t('Уровень знания английского языка') ?>:</div>
                <div class="left" style="width: <?= $attr_val_width ?>px;">
                    <?= profile_peer::$eng_knowledge[$user_additional['eng_knowledge']] ?>
                </div>
                <div class="clear"></div>
            </div>
        <?php } ?>

        <?php $__ud = user_data_peer::instance()->get_item($user_id); ?>
        <?php $__hd = unserialize($__ud['hidden_data']) ?>
        <?php if ($__hd['why'] != '') { ?>
            <div class="mt10">
                <div class="left cgray" style="width: <?= $attr_key_width ?>px;"><?= t('Почему хотите стать моделью?') ?>:</div>
                <div class="left" style="width: <?= $attr_val_width ?>px;">
                    <?= $__hd['why'] ?>
                </div>
                <div class="clear"></div>
            </div>
        <?php } ?>


    <?php } ?>
</div>
