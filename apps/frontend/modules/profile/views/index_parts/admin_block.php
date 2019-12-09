<?php
/**
 * @var array $profile
 */
?>

<?php if (session::has_credential('admin')) { ?>

    <div class="square_p pl15 mb10 fs12 ucase bold">
        <a class="cblack" href='javascript:;' onclick="$('#profile-admin-shower').toggleClass('hide')"><?= t('Администрирование') ?></a>
    </div>
    <div class="p10 hide" id="profile-admin-shower" style="background: #eee; border: 1px solid #ccc">
        <table class="mb10" style="">
            <tr>
                <td style="padding-left: 0">
                    <b><?= t('Списки') ?>:</b><br/>
                    <div style="display: grid; grid-auto-flow: row">
                        <label>
                            <input type="radio" class="mr5" name="additional-list-change[]" value=""<?= (
                                $profile['show_on_main'] === user_auth_peer::successful
                            ) ? ' checked' : ' ' ?>>&mdash;</label>

                        <label>
                            <input type="radio" class="mr5" name="additional-list-change[]" value="1"<?= (
                                $profile['show_on_main'] > user_auth_peer::successful
                                && $profile['show_on_main'] < user_auth_peer::new_faces
                            ) ? ' checked' : ' ' ?>><?= t('Успешные') ?></label>

                        <label>
                            <input type="radio" class="mr5" name="additional-list-change[]" value="3"<?= (
                                $profile['show_on_main'] >= user_auth_peer::perspective
                                && $profile['show_on_main'] < user_auth_peer::legendary
                            ) ? ' checked' : ' ' ?>><?= t('Перспективные') ?></label>

                        <label>
                            <input type="radio" class="mr5" name="additional-list-change[]" value="2"<?= (
                                $profile['show_on_main'] >= user_auth_peer::new_faces
                                && $profile['show_on_main'] < user_auth_peer::perspective
                            ) ? ' checked' : ' ' ?>><?= t('Новые лица') ?></label>

                        <label>
                            <input type="radio" class="mr5" name="additional-list-change[]" value="4"<?= (
                                $profile['show_on_main'] >= user_auth_peer::legendary
                            ) ? ' checked' : ' ' ?>><?= t('Самые успешные') ?></label>
                    </div>
                </td>
            </tr>

            <?php if (!$profile['email'] && $profile['security']) { ?>
                <tr>
                    <td>
                        <div class="fs12">
                            <br/><b><?= t('Ccылка для приглашения') ?>:</b><br/>
                            <a href="https://<?= conf::get('server') ?>/profile?code=<?= $profile['security'] ?>">
                                https://<?= conf::get('server') ?>/profile?code=<?= $profile['security'] ?>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td>
                    <br/><b><?= t('Cкрытый') ?>:</b><br/>
                    <input type="radio" class="left" name="additional-hidden-change[]" value="1"<?= ($profile['hidden']) ? ' checked' : ' ' ?>><label
                            class="left"><?= t('Да') ?></label>
                    <input type="radio" class="left ml10" name="additional-hidden-change[]" value="0"<?= (!$profile['hidden']) ? ' checked'
                        : ' ' ?>><label class="left"><?= t('Нет') ?></label>
                </td>
            </tr>
            <tr>
                <td>
                    <br/><b><?= t('Может переписываться') ?>:</b><br/>
                    <input type="radio" class="left" name="additional-can_write-change[]" value="1"<?= ($profile['can_write']) ? ' checked'
                        : ' ' ?>><label class="left"><?= t('Да') ?></label>
                    <input type="radio" class="left ml10" name="additional-can_write-change[]" value="0"<?= (!$profile['can_write']) ? ' checked'
                        : ' ' ?>><label class="left"><?= t('Нет') ?></label>
                </td>
            </tr>
            <tr>
                <td>
                    <br/><b><?= t('Статус') ?>:</b><br/>
                    <select id="additional-status_change-<?= $profile['user_id'] ?>" class="left" style="width: 200px;">
                        <?php
                        $statuses = profile_peer::get_types_list();
                        echo '<optgroup label='.t('Статус не назначен').' value="0">';
                        echo '<option value="0" selected>&mdash;</option></optiongroup>';
                        foreach ($statuses as $key => $value) {
                            echo '<optgroup label="'.$value['type'].'" value="'.$key.'">';
                            if (is_array($value['status'])) {
                                foreach ($value['status'] as $k => $v) {
                                    echo '<option value="'.$k.'" '.(profile_peer::get_status_by_user($profile['user_id']) == $k ? ' selected' : '')
                                        .'>'.(t($v) ? t($v) : t($value['type'])).'</option>';
                                }
                            }
                            echo '</optgroup>';

                        }
                        ?>
                    </select>
                    <img src="/ui/wait.gif" style="width: 20px;" class="hide left" id="wait-image-<?= $profile['user_id'] ?>"/>
                </td>
            </tr>
            <tr id="block-agency" class="<?php if ($profile['status'] != 42) { ?>hide<?php } ?>">
                <td>
                    <select id="agency" style="width: 200px;">
                        <option value="0">&mdash;</option>
                        <?php $agency_list = agency_peer::instance()->get_list(['public' => 1, 'page_active' => true], [], ['id ASC']) ?>
                        <?php foreach ($agency_list as $agency_id) { ?>
                            <?php $agency = agency_peer::instance()->get_item($agency_id); ?>
                            <option value="<?= $agency['id'] ?>"><?= $agency['name'] ?></option>
                        <?php } ?>
                    </select>
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $('#agency').val(<?=$profile['manager_agency_id']?>);

                            $('#agency').change(function () {
                                $.post('/adminka/umanager', {
                                    'act': 'set_agency',
                                    'user_id': '<?=$profile['user_id']?>',
                                    'agency_id': $(this).val()
                                }, function (response) {
                                    console.log(response);
                                }, 'json');
                            });
                        });
                    </script>
                </td>
            </tr>
            <tr>
                <td>
                    <br/>
                    <b>Откуда узнала:</b> <?= $learned_about ?>
                </td>
            </tr>
            <tr>
                <td>
                    <br/>
                    <?php if ($profile['registrator'] > 0) { ?>
                        <?php $registrator = profile_peer::instance()->get_item($profile['registrator']); ?>
                        <b>Зарегистрировал:</b> <a href="/profile?id=<?= $profile['registrator'] ?>"><?= profile_peer::get_name($registrator) ?></a>
                    <?php } else { ?>
                        <b>Саморегистрация</b>
                    <?php } ?>
                    <?php if ($profile['created_ts'] > 0) { ?><?= date('d.m.Y h:s', $profile['created_ts']) ?><?php } ?>
                </td>
            </tr>
            <tr>
                <td>
                    <br/>
                    <?php if ($profile['active']) { ?><b>Активная</b><?php } else { ?><b>Неактивная</b><?php } ?>
                    <?php if ($profile['activated_ts'] > 0) { ?><?= date('d.m.Y h:s', $profile['activated_ts']) ?><?php } ?>
                </td>
            </tr>

            <tr>
                <td>
                    <b><?= t('Позиция в каталоге') ?>:</b> <?= $page_position['page'] ?> <?= t('страница') ?>
                    , <?= $page_position['position'] ?> <?= t('позиция') ?>
                </td>
            </tr>

        </table>
        <script>
            $("select[id^='additional-status_change']").change(function () {
                var id = $(this).attr("id").split("-")[2];
                var act = $(this).attr("id").split("-")[1];
                $("img[id*='wait-image-" + id + "']").toggleClass('hide');

                if ($(this).val() != 42) {
                    $('#block-agency').hide();
                    $('#agency').val(0).change();
                } else {
                    $('#block-agency').show();
                }

                $.post("/adminka/user_manager", {
                    "act": act,
                    "user_id": id,
                    "status": $(this).val()
                }, function (data) {
                    if (data.success)
                        setTimeout("$(\"img[id*='wait-image-" + id + "']\").toggleClass('hide');", 300)
                }, "json");
            });
            $("input[name='additional-hidden-change[]']").change(function () {
                $.post("/adminka/user_manager", {
                    "act": "modify",
                    "user_id":  <?=$profile['user_id']?>,
                    "hidden": $(this).val()
                }, function (data) {
                    console.log(data);
                }, "json");
            });
            $("input[name='additional-can_write-change[]']").change(function () {
                $.post("/adminka/user_manager", {
                    "act": "can_write",
                    "user_id":  <?=$profile['user_id']?>,
                    "can_write": $(this).val()
                }, function (data) {
                    console.log(data);
                }, "json");
            });
            $('input[name="additional-list-change[]"]').change(function () {
                var del_data = {"submit": 1, "id": <?=$profile['user_id']?>, "type": "delete"};
                var val = $(this).val();

                $.post(
                    '/adminka/successfull',
                    del_data,
                    function (resp) {
                        if (val) {
                            var add_data = {"submit": 1, "id": <?=$profile['user_id']?>, "type": "add", "mt": (parseInt(val) - 1)};
                            $.post(
                                '/adminka/successfull',
                                add_data,
                                function (resp) {
                                    console.log(resp);
                                },
                                'json'
                            );
                        }
                    },
                    'json'
                );
            });
        </script>
        <div class="clear"></div>
    </div>
<?php } ?>
