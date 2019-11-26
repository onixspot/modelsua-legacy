<script type="text/javascript">
    var approved_statements = new Array();
</script>


<style>
    div.main_user_menu .selected {
        border-radius: 4px 4px 0 0;
        background: #000000;
        padding: 5px;
    }

    div.main_user_menu .selected a {
        color: white;
        font-weight: bold;

    }

    div.submenu a {
        color: #bbb;
    }

    div.submenu a.selected {
        font-weight: bold;
        color: #fff;
    }

    #models_manager_table tr th {
        background: none repeat scroll 0 0 #CCCCCC;
    }

</style>

<? include 'partials/menu.php'; ?>

<div>
    <table cellspacing="2" cellpadding="5" width="100%" style="border: 1px solid #ccc;" id="models_manager_table">
        <? include 'partials/navigation.php'; ?>
        <? if (empty($list)) { ?>
            <tr>
                <td colspan="7" style="color: #838999; padding: 20px;" class="acenter">

                    Не найдено ни одного пользователя

                </td>
            </tr>
        <?php } ?>
        <?php foreach ($list as $nPP => $item_id) { ?>
            <?php
            $color = (($nPP + 1) % 2) ? '#FFF' : '#EEE';
            ?>
            <?php $profile = db::get_row("SELECT d,country, d.application, d.application_approve, d.region, d.city, d.another_city, a.created_ts,a.del, a.email, d.birthday, d.pid, d.ph_crop, d.first_name, d.last_name,d.user_id,a.registrator,a.type,a.hidden,a.active,d.rank,a.statement_type, d.learned_about, a.reserv, a.approve FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE d.user_id=:uid", ['uid' => $item_id]); ?>
            <tr id="adminka-item-<?= $item_id ?>" style="background: <?= $color ?>">
                <td class="bold">
                    <?= $nPP + 1 ?>
                </td>
                <td align="center">
                    <?= $item_id ?>
                </td>
                <td width="<?= (!$show) ? '60%' : '30%' ?>" class="aleft">
                    <div class="imagebox left mr5 acenter">
                        <? if ($profile['ph_crop']) {
                            $c = unserialize($profile['ph_crop']); ?>
                            <img style="width: <?= (!$show) ? '150px' : '50px' ?> "
                                 src="/imgserve?pid=<?= ($profile['pid']) ?>&w=<?= $c['w'] ?>&h=<?= $c['h'] ?>&x=<?= $c['x'] ?>&y=<?= $c['y'] ?>&z=crop"/>
                        <? } elseif ($profile['pid']) { ?>
                            <img src="/imgserve?pid=<?= ($profile['pid']) ?>&w= <?= (!$show) ? '150' : '50' ?> "
                                 style="width:  <?= (!$show) ? '150px' : '50px' ?> ;"/>
                        <?php } else { ?>
                            <img src="/no_image.png" style="width:  <?= (!$show) ? '150px' : '50px' ?> ;"/>
                        <? } ?>
                        <?php if ($filter[0] == 'byself' && $filter[1] != 'confirmed') { ?>
                            <div class="mt5"><input type="button" id="list-item-approve-<?= $profile['user_id'] ?>" value="Утвердить"/></div>
                            <div class="mt5">
                                <div id="block-list-item-remove-<?= $profile['user_id'] ?>">
                                    <input type="button" id="list-item-remove-<?= $profile['user_id'] ?>" value="Удалить"/>
                                </div>
                                <div id="block-list-item-removeto-<?= $profile['user_id'] ?>" class="hide">
                                    <input type="button" id="list-item-removeto-reserv-<?= $profile['user_id'] ?>" value="В резерв"/>
                                    <input type="button" id="list-item-removeto-archive-<?= $profile['user_id'] ?>" value="В архив"/>
                                </div>
                            </div>
                            <?php if ($filter[1] == 'new') { ?>
                                <div class="mt5"><input type="button" id="list-item-confirm-<?= $profile['user_id'] ?>" value="Отправить 'в работу'"/>
                                </div><?php } ?>
                        <?php } ?>
                    </div>
                    <div class="fs14 left" style="<?= ($show) ? 'width: 160px' : 'width: 167px' ?>;">
                        <a href="/profile?id=<?= $item_id ?>" target="_blank">

                            <?= profile_peer::get_name($profile) ?>
                        </a><br/>

                        <!-- START USER PARAMS -->
                        <?php $user_params = profile_peer::instance()->get_params($profile["user_id"]); ?>
                        <div class="fs11">
                            <span style="color: #aeb7c9">Рост:</span> <?= $user_params["growth"] ?> см&nbsp;
                            <span style="color: #aeb7c9">Вес:</span> <?= $user_params["weigth"] ?> кг&nbsp;<br/>
                            <span style="color: #aeb7c9">Объемы:</span> <?= $user_params["breast"] ?> / <?= $user_params["waist"] ?>
                            / <?= $user_params["hip"] ?>&nbsp;
                        </div>
                        <br/>
                        <!-- START BIRTHDAY AND BIRTHPLACE -->
                        <?php if (intval(profile_peer::get_age($profile["birthday"])) > 1 && !$show) { ?>
                            <div class="fs11">
                                <div class="left aright mr5 cgray"></div>
                                <div class="left">
                                    <span class="bold"><?= profile_peer::get_age($profile["birthday"]) ?></span>
                                    <span class="cgray">(<?= profile_peer::get_birthday($profile["birthday"]) ?>)</span>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <br/>
                        <?php } ?>

                        <!-- START REGISTRATION DATE -->
                        <?php if ($profile['created_ts'] > 0 && !$show) { ?>
                            <div class="fs12">
                                <span style="color: #aeb7c9;">Дата регистрации:</span> <?= date('d.m.Y, H:i:s', $profile["created_ts"]) ?>&nbsp;
                            </div>
                            <br/>
                        <?php } ?>

                        <!-- START COUNTRY -->
                        <?php $loc = profile_peer::get_location($profile) ?>
                        <?php if ($loc != '' && !$show) { ?>
                            <div class="fs12">
                                <span style="color: #aeb7c9;">Место нахождения:</span><br/><?= $loc ?>&nbsp;
                            </div>
                            <br/>
                        <?php } ?>

                        <!-- LEARNED ABOUT-->
                        <?php
                        $learned_about = $profile['learned_about'];
                        switch ($learned_about) {
                            case 'facebook':
                                $learned_about = 'Facebook';
                                break;
                            case 'vkontakte':
                                $learned_about = 'Vkontakte';
                                break;
                            case 'odnoklassniki':
                                $learned_about = 'Odnoklassniki';
                                break;
                            case 'friends':
                                $learned_about = 'Друзья, знакомые';
                                break;
                            case 'banners':
                                $learned_about = 'Фирменные материалы, визитки';
                                break;
                            default:
                                $learned_about = '&mdash;';
                                break;
                        }
                        ?>
                        <div class="fs12">
                            <span class="cgray fs10">Откуда узнала:</span> <?= $learned_about ?>
                        </div>
                        <br/>
                        <?php if ($profile["registrator"] > 0) { ?>
                            <?php $registrator = profile_peer::instance()->get_item($profile["registrator"]); ?>
                            <span class="cgray fs10">Зарегистрировал:</span> <a class="fs10" href="/profile/?id=<?= $registrator["user_id"] ?>">
                                <?= profile_peer::get_name($registrator) ?>
                            </a><br/>

                            <!-- INVITATION MESSAGE BLOCK -->
                            <?php $invitationMessages = user_invitation_message_peer::instance()->get_list(['user_id' => $item_id]) ?>
                            <span class="cgray fs10">Кол-во отправленных приглашений:</span>
                            <a href="/adminka/user_manager?act=invitation_messages&model_id=<?= $item_id ?>"
                               data-id="user[<?=$item_id?>]-invitation-messages-count"><?= count($invitationMessages) ?></a>
                            <br/>
                            <!-- END OF INVITATION MESSAGE BLOCK -->

                        <?php } ?>
                        <?php if ($profile["del"] > 0) { ?>
                            <?php $del_hist = profile_peer::instance()->get_last_del_hist($profile["user_id"]); ?>
                            <?php $remover = profile_peer::instance()->get_item($del_hist['operator']); ?>
                            <span class="cgray fs10">Удалил:</span> <a class="fs10" href="/profile/?id=<?= $remover["user_id"] ?>">
                                <?= profile_peer::get_name($remover) ?>
                            </a><br/>
                            <span class="cgray fs10"><?= date('Y-m-d', $del_hist['time']) ?></span><br/>
                        <?php } ?>

                        <?php $ud = user_data_peer::instance()->get_item($profile['user_id']); ?>
                        <?php $iwant = is_array($hd = unserialize($ud['hidden_data'])) ? $hd['iwant'] : ''; ?>
                        <?php if ($iwant != '') { ?>
                            <span class="fs10 cgray">Я хочу <?php if ($iwant == 'model') { ?>стать моделью<?php } elseif ($iwant
                                    == 'member') { ?>быть в каталоге моделей Украины<?php } ?></span>
                        <?php } ?>
                    </div>
                </td>
                <? if (!in_array($filter[1], ['applications', 'refuse'])) { ?>
                    <td align="left" style="width: 130px;">
                        <table>
                            <tr>
                                <td>
                                    <select id="adminka-status_change-<?= $item_id ?>" name="showtimes" style="width: 100px;">
                                        <?php
                                        $statuses = profile_peer::get_types_list();
                                        echo '<optgroup label="Статус не назначен" value="0">';
                                        echo '<option value="0" selected>&mdash;</option></optiongroup>';
                                        foreach ($statuses as $key => $value) {
                                            echo '<optgroup label="'.$value['type'].'" value="'.$key.'">';
                                            //echo '<option value="'.$key.'" '.((profile_peer::get_type_by_user($profile['user_id'])==$key && !profile_peer::get_status_by_user ($profile['user_id'])) ? ' selected' : '').'><i>'.($value['type']).(!in_array($key,array(5,6)) ? ('&nbsp;(no status)') : '').'</i></option>';
                                            if (is_array($value['status'])) {
                                                foreach ($value['status'] as $k => $v) {
                                                    echo '<option value="'.$k.'" '.(profile_peer::get_status_by_user($profile['user_id']) == $k
                                                            ? ' selected' : '').'>'.($v ? $v : $value['type']).'</option>';
                                                }
                                            }
                                            echo '</optgroup>';

                                        }
                                        ?>
                                    </select>
                                </td>
                                <td class="acenter">
                                    <img src="/ui/wait.gif" style="width: 20px;" class="hide" id="wait-image-<?= $item_id ?>"/>
                                </td>
                            </tr>
                        </table>
                    </td>
                <?php } ?>

                <td align="center">
                    <span style="color: <?php if ($profile["active"]){ ?> green">Активный <?php } else { ?> red">Неактивный <?php } ?> </span>
                </td>
                <td align="center">
                    <input id="adminka-hidden-item-<?= $item_id ?>" type="checkbox" <?php if ($profile["hidden"]){ ?>checked<?php } ?> />
                </td>
                <?php if ($show) { ?>
                    <td align="center">
                        <input style="width: 50px;" class="acenter" id="adminka-rank-item-<?= $profile['user_id'] ?>" type="text"
                               value="<?= $profile['rank'] ?>"/>
                    </td>
                <?php } ?>

                <td align="center" style="width:<?php if ($show) { ?> 150px; <?php } ?>">

                    <div>


                        <? if ($profile['application'] && !$profile['application_approve'] && $profile['approve'] == 2) { ?>
                            <? include 'popup/application_approve.php' ?>
                            <? if ($profile['application_approve'] == 0) { ?>
                                <a href="javascript:;" id="adminka-application-approve-<?= $item_id ?>">
                                    <img src="/ui/show_1.png" alt="Подтвердить" onclick="$('#application-<?= $profile['user_id'] ?>').show();">
                                </a>
                                <a href="javascript:;" id="adminka-application-refuse-<?= $item_id ?>">
                                    <img src="/ui/delete.png" alt="Удалить"/>
                                </a>
                            <?php } ?>
                            <? if ($profile['application_approve'] == 1) { ?>
                                <a href="javascript:;" id="adminka-application-send-<?= $item_id ?>">
                                    <img src="/ui/send.png" alt="Пригласить"/>
                                </a>
                            <?php } ?>
                        <?php } elseif ($profile['application'] && $profile['application_approve']) { ?>
                            <a id="adminka-remove-archive-item-<?= $item_id ?>" href="javascript:;">
                                <img src="/ui/delete.png" alt="В архив"/>
                            </a>
                        <?php } ?>






                        <? if ($profile['registrator'] > 0 && $profile['email'] && !$profile['del'] && !$profile['active']) {
                            $icount = (db_key::i()->exists('invitations_byadmin_'.$item_id)) ? db_key::i()->get('invitations_byadmin_'.$item_id)
                                : 0 ?>
                            <a id="adminka-send_invitation-<?= $item_id ?>" rel="<?= $icount ?>" href="javascript:;"><img src="/ui/send.png"
                                                                                                                          style="height: 16px;"
                                                                                                                          alt="Пригласить"/></a>
                        <?php } ?>

                        <? if (!$profile['registrator'] && session::has_credential('admin') && $profile['email'] && !$profile['del']
                            && !$profile['active']) {
                            $icount = (db_key::i()->exists('invitations_registred_'.$item_id)) ? db_key::i()->get('invitations_registred_'.$item_id)
                                : 0 ?>

                            <? if (request::get('filter') == 'byself-confirmed') { ?>
                                <a id="adminka-send_invitation_final-<?= $item_id ?>" rel="<?= $icount; ?>" href="javascript:;"><img
                                            src="/ui/send.png" style="height: 16px;" alt="Пригласить"/></a>
                            <?php } ?>

                            <? if (request::get('filter') == 'byself-new') { ?>
                                <a id="adminka-approve-item-<?= $item_id ?>" href="javascript:;"><img src="/ui/show_1.png" alt="Подтвердить"/></a>
                            <?php } ?>

                            <? if (request::get('filter') == 'byself-confirm' && in_array(session::get_user_id(), [1, 5, 4, 31])) { ?>
                                <a id="adminka-approvefinal-item-<?= $item_id ?>" href="javascript:;"><img src="/ui/show_1.png"
                                                                                                           alt="Подтвердить"/></a>
                            <?php } ?>
                        <?php } ?>

                        <a href="/profile/edit?id=<?= $item_id ?>"><img src="/ui/edit.png"/></a>
                        <? if ($catalog_filter) { ?>
                            <a onclick="changePlace('<?= $item_id ?>', '2')" title="Вверх" href="javascript:;"><img src="/ui/up.png"></a>
                            <a onclick="changePlace('<?= $item_id ?>', '1')" title="Вниз" href="javascript:;"><img src="/ui/down.png"></a>
                        <?php } ?>

                        <? if (!$profile['application']) { ?>
                            <?php if (($profile['del'] || $profile['reserv'])) { ?>
                                <?php if ($profile['reserv'] > 0) { ?>
                                    <a id="adminka-remove-archive-item-<?= $item_id ?>" href="javascript:;"><img src="/ui/delete.png" alt="В архив"/></a>
                                <?php } else { ?>
                                    <a id="adminka-remove-remove-item-<?= $item_id ?>" href="javascript:;"><img src="/ui/delete.png"
                                                                                                                alt="Удалить"/></a>
                                <?php } ?>
                                <a id="adminka-remove-restore-item-<?= $item_id ?>" href="javascript:;"><img src="/ui/restore.png"
                                                                                                             alt="Восстановить"/></a>
                            <?php } else { ?>
                                <a id="adminka-remove-archive-item-<?= $item_id ?>" href="javascript:;"><img src="/ui/delete.png" alt="В архив"/></a>
                            <?php } ?>
                        <?php } ?>
                        <? if (request::get('filter') == 'byself-confirm') { ?>
                            <a class="cgray" href="/messages?receiver=<?= $profile['user_id'] ?>"><?= t("Написать") ?></a><br/>
                        <?php } ?>
                    </div>
                </td>
            </tr>
        <?php } ?>

    </table>
</div>
<table style="width: 100%">
    <tr>
        <td>
            <? $limit = (db_key::i()->exists('users_manage_limit_'.session::get_user_id())) ? db_key::i()->get('users_manage_limit_'
                .session::get_user_id()) : 25; ?>
            Показывать по:&nbsp;
            <select id="results-count">
                <option value="25" <?= ($limit == 25) ? ' selected' : '' ?>>25</option>
                <option value="50" <?= ($limit == 50) ? ' selected' : '' ?>>50</option>
                <option value="100" <?= ($limit == 100) ? ' selected' : '' ?>>100</option>
            </select>
        </td>
        <td>
            <div style="color: #899999;">
                Пользователей: <?= $results ?>
                Страниц: <?= $pages ?>
            </div>
        </td>
        <td>
            <div class="right paginator"><?= pager_helper::get_full($pager) ?></div>
        </td>
    </tr>

</table>


<script type="text/javascript">

    $(document).ready(function () {

        $("input[id^='list-item-removeto-archive-']").click(function () {
            if (confirm('Вы действительно хотите удалить в архив?')) {
                var id = $(this).attr('id').split('-')[4];
                $.post('/adminka/user_manager', {
                    'act': 'in_arhive',
                    'uid': id
                }, function (response) {
                    if (response.success) {
                        $('#adminka-item-' + id).remove();
                    }
                }, 'json');
            }
        });

        $("input[id^='list-item-removeto-reserv-']").click(function () {
            if (confirm('Вы действительно хотите удалить в резерв?')) {
                var id = $(this).attr('id').split('-')[4];
                $.post('/adminka/user_manager', {
                    'act': 'in_reserv',
                    'uid': id
                }, function (response) {
                    if (response.success) {
                        $('#adminka-item-' + id).remove();
                    }
                }, 'json');
            }
        });

        $("input[id^='list-item-confirm-']").click(function () {
            if (confirm('Вы действительно хотите отправить в работу?')) {
                var id = $(this).attr('id').split('-')[3];
                $.post('/adminka/user_manager', {
                    'act': 'approve',
                    'uid': id
                }, function (response) {
                    if (response.success) {
                        $('#adminka-item-' + id).remove();
                    }
                }, 'json');
            }
        });

        $("input[id^='list-item-approve-']").click(function () {
            if (confirm('Вы действительно хотите утвердить?')) {
                var id = $(this).attr('id').split('-')[3];
                $.post('/adminka/user_manager', {
                    'act': 'approvefinal',
                    'uid': id
                }, function (response) {
                    if (response.success) {
                        $('#adminka-item-' + id).remove();
                    }
                }, 'json');
            }
        });

        $("input[id^='list-item-remove-']").click(function () {
            var id = $(this).attr('id').split('-')[3];
            $('#block-list-item-remove-' + id)
                .animate({
                    'opacity': 0
                }, 200, function () {
                    $(this).hide();
                    $('#block-list-item-removeto-' + id)
                        .show()
                        .css('opacity', 0)
                        .animate({
                            'opacity': 1
                        }, 200, function () {
                            setTimeout(function () {
                                $('#block-list-item-removeto-' + id)
                                    .animate({
                                        'opacity': 0
                                    }, 200, function () {
                                        $(this).hide();
                                        $('#block-list-item-remove-' + id)
                                            .show()
                                            .css('opacity', 0)
                                            .animate({
                                                'opacity': 1
                                            }, 200, function () {
                                                setTimeout(function () {

                                                }, 3000);
                                            });
                                    });
                            }, 3000);
                        });
                });
        });

        $('[id^="adminka-approve-status-"]').click(function () {
            var id = $(this).attr("id").split("-")[3];
            $.post("/adminka/user_manager", {
                "act": 'status_change',
                "application_approve": 1,
                "user_id": id,
                "status": $('#application-status-' + id).val()
            }, function (data) {
                if (data.success)
                    $("#adminka-item-" + id).remove();
            }, "json");
        });

        $('[id^="adminka-application-refuse-"]').click(function () {
            var id = $(this).attr("id").split("-")[3];
            $.post("/adminka/user_manager", {
                "act": 'refuse',
                "user_id": id
            }, function (data) {
                if (data.success)
                    $("#adminka-item-" + id).remove();
            }, "json");
        });

        $("input[id^='statement']").click(function () {
            var type = $(this).attr('id').split('-')[1];
            var act = $(this).attr('id').split('-')[2];
            var uid = $(this).attr('id').split('-')[3];
            $.post('/adminka/user_manager', {
                'act': 'statement',
                'act_type': act,
                'key': type,
                'uid': uid
            }, function (resp) {
                if (resp.act == 'approve')
                    $('#statement-act-' + type + '-' + uid).html("<span style='color: #0a0'>Заявка подтверждена</span>");
                else
                    $('#statement-act-' + type + '-' + uid).html("<span style='color: #a00'>Заявка отклонена</span>");
            }, 'json');
        });

        $("input[id*='adminka-active-item']").click(function () {
            var id = $(this).attr("id").split("-")[3];
            $.post("/adminka/user_manager", {
                "act": "modify",
                "user_id": id,
                "active": $(this).attr("checked") == "checked" ? 1 : 0
            }, function (data) {
                console.log(data);
            }, "json");
        });

        $("input[id*='adminka-hidden-item']").click(function () {
            var id = $(this).attr("id").split("-")[3];
            $.post("/adminka/user_manager", {
                "act": "modify",
                "user_id": id,
                "hidden": $(this).attr("checked") == "checked" ? 1 : 0
            }, function (data) {
                console.log(data);
            }, "json");
        });

        $("a[id*='adminka-send_invitation']").click(function () {
            var id = $(this).attr("id").split("-")[2];
            var act = $(this).attr("id").split("-")[1];
            const targetElement = $('a[data-id="user['+id+']-invitation-messages-count"]');

            if (confirm("Отправить приглашение?\r\nУже отправлено: " + $(this).attr('rel'))) {
                $.post("/adminka/user_manager", {
                    "act": act,
                    "user_id": id
                }, function (data) {
                    $(targetElement).html(data.inv_count);
                    console.log(data);
                }, "json");
            }
        });

        $("a[id*='adminka-remove-']").click(function () {
            var id = $(this).attr("id").split("-")[4];
            var act = $(this).attr("id").split("-")[2];
            if (confirm("Точно?")) {
                $.post("/adminka/user_manager?act=remove", {
                    "act": act,
                    "user_id": id
                }, function (data) {
                    if (data.success) {
                        $("#adminka-item-" + data.user_id).remove()
                    }
                }, "json");
            }
        });

        $("input[id^='adminka-rank-item-']")
            .keydown(function (e) {
                if (e.keyCode == 13) {
                    $.post(
                        '/adminka/user_manager',
                        {
                            act: 'change_rank',
                            user_id: $(this).attr("id").split("-")[3],
                            rank: $(this).val()
                        },
                        function (resp) {
                            if (resp.success == 1)
                                window.location = window.location;
                        },
                        'json'
                    );
                    $(this).blur();
                }
            });

        $("a[id*='adminka-approve']").click(function () {
            var id = $(this).attr("id").split("-")[3];
            $('#open-statements-' + id).click();
//			if(confirm("Подтвердить регистрацию?")){
//				$.post("/adminka/user_manager", {
//					"act": approve_type,
//					"user_id": id
//				}, function(data){
//				    	if(data.success){
//						$("#adminka-item-"+data.user_id).remove();
//					}
//				}, "json");
//			}
        });

        $("input[id^='adminka-item-approve']").click(function () {
            var id = $(this).attr("id").split("-")[3];
            var approve_type = '<?php if($filter[1] != 'new'){ ?>approvefinal<?php } else { ?>approve<?php } ?>';
            if (confirm("<?php if($filter[1] != 'new'){ ?>Подтвердить регистрацию?<?php } else { ?>Отправить на подтверждение?<?php } ?>")) {
                $.post("/adminka/user_manager", {
                    "act": approve_type,
                    "user_id": id
                }, function (data) {
                    if (data.success) {
                        $("#adminka-item-" + data.user_id).remove();
                    }
                }, "json");
            }
        });

        $("input[id^='adminka-item-arhive'], input[id^='adminka-item-reserv']").click(function () {

            var msg = new Object({
                'arhive': 'Вы действительно хотите отправить этот профиль в архив?',
                'reserv': 'Вы действительно хотите отправить этот профиль в резерв?'
            });

            var act = $(this).attr('id').split('-')[2];
            var uid = $(this).attr('id').split('-')[3];

            if (confirm(msg[act])) {
                $.post('/adminka/user_manager', {
                    'act': 'in_' + act,
                    'uid': uid
                }, function (resp) {
                    if (resp.success)
                        window.location = '<?=$_REQUEST['REQUEST_URI']?>';
                }, 'json');
            }
        });

        $("select[id*='adminka-status_change']").change(function () {
            var id = $(this).attr("id").split("-")[2];
            var act = $(this).attr("id").split("-")[1];
            $("img[id*='wait-image-" + id + "']").toggleClass('hide');
//			if(confirm("Подтвердить регистрацию?")){
            $.post("/adminka/user_manager", {
                "act": act,
                "user_id": id,
                "status": $(this).val()
            }, function (data) {
                if (data.success)
                    setTimeout("$(\"img[id*='wait-image-" + id + "']\").toggleClass('hide');", 300)
                <?if(in_array($filter[1], ['candidates', 'members'])) {?>
                $("#adminka-item-" + id).remove();
                <?php } ?>
            }, "json");
//			}
        });

        $("select[id*='results-count']").change(function () {
            $.post("/adminka/user_manager", {
                "act": 'change_limit',
                "limit": $(this).val()
            }, function (data) {
                if (data.success)
                    window.location = window.location;
            }, "json");

        });
        $("select[id='adminka-status_filter'],select[id='adminka-active-filter'],select[id='adminka-public-filter']").change(function () {
            if ($(this).val()) window.location = $(this).val();
        });


    });

    function changePlace(id, direct) {
        $.post('/adminka/user_manager',
            {
                act: 'change_place',
                user_id: id,
                direct: direct
            },
            function (resp) {
                if (resp.success == 1)
                    window.location = window.location;
            },
            'json'
        );
    }
</script>
