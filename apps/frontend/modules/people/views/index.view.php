<div class="mb10 fs12">

    <!--<div class="left mr10">
		<? foreach ($list as $user_id) { ?>
			<? $ud = user_data_peer::instance()->get_item($user_id); ?>
			<div class="pt5 pb5">
				<a id="member-link-<?= $user_id ?>" href='javascript:;'>
					<? $name = explode(' ', profile_peer::get_name($ud)); ?>
					<?= $name[0].' '.mb_strtoupper($name[1] != '' ? $name[1] : $name[2]) ?>
				</a>
			</div>
		<? } ?>
	</div>-->

    <div class="square_p pl15 mt20 mb20 ucase bold left">
        <?= t("Каталог моделей Украины") ?>
    </div>
    <div class="register-link right mt20 mb20 mr25 <?= (session::get('language') == 'en') ? ' hide' : '' ?>">
        <a href="/sign/registration" class="cpurple"><?= t('Зарегистрироваться в каталоге') ?></a>
    </div>
    <div class="clear"></div>
    <div class="">

        <style type="text/css">
            #sortable, sortable2 {
                list-style-type: none;
                margin: 0;
                padding: 0;
                width: 100%;
            }

            .ui-state-highlight {
                background: #fff;
                border: none;
                padding: 1px;
                float: left;
                width: 165px;
                height: 165px;
            }
        </style>

        <div class="mb10" style="width: 1024px; height: 668px; overflow: hidden;">
            <ul id="sortable" class="connectedSortable" style="height: 675px">
                <? foreach ($list as $user_id) { ?>
                    <? $profile = profile_peer::instance()->get_item($user_id); ?>
                    <? $crop = unserialize($profile["ph_crop"]) ?>
                    <li class="ui-state-highlight" id="member-blind-<?= $profile['user_id'] ?>" rel="<?= $profile['rank'] ?>">
                        <img
                                src="<? if ($profile["pid"]) { ?>/imgserve?pid=<?= $profile["pid"] ?>&x=<?= $crop['x'] ?>&y=<?= $crop['y'] ?>&w=<?= !$crop['w']
                                    ? '165' : $crop['w'] ?>&h=<?= !$crop['h'] ? '165' : $crop['h'] ?>&z=crop<? } else { ?>/no_image.png<? } ?>"
                                style="width: 165px; height: 165px;"
                        />
                        <div id="member-tooltip-<?= $user_id ?>"
                             class="hide p10 bold"
                             style="position: absolute; background: black; color: white; border-radius: 5px;">
                            <?= profile_peer::get_name($profile) ?><br/>
                            <!--<span class="fs10"><?= profile_peer::get_location($profile) ?></span>
							<? if (intval(profile_peer::get_age($profile["birthday"])) > 1) { ?>
								<br />
								<span class="fs10"><?= profile_peer::get_age($profile['birthday']) ?></span>
							<? } ?>-->
                        </div>
                    </li>
                <? } ?>
            </ul>
            <div class="clear"></div>
        </div>

        <script type="text/javascript">
            $(document).ready(function () {
                let mouseDown = null;

                $("li[id^='member-blind']")
                    .mouseout(function () {
                        $(`#member-tooltip-${$(this).attr('id').split('-')[2]}`).hide();
                    })
                    .mousedown(function (e) {
                        mouseDown = new Date();
                    })
                    .mousemove(function (e) {
                        const element = $(`#member-tooltip-${$(this).attr('id').split('-')[2]}`);

                        if (mouseDown !== null) {
                            return element.hide();
                        }

                        element.show()
                            .css({
                                'top': e.pageY + 16,
                                'left': e.pageX + 16,
                                'zIndex': '999'
                            });
                    })
                    .click(function (e) {
                        if (new Date() - mouseDown < 150) {
                            window.location = `/profile?id=${$(this).attr('id').split('-')[2]}`;
                        }
                    });

                <? if(session::has_credential('admin')){ ?>
                $('#sortable, #sortable2')
                    .sortable({
                        connectWith: '.connectedSortable',
                        placeholder: "ui-state-highlight",
                        stop: function (event, ui) {
                            if ($(ui.item).next().length > 0)
                                $(ui.item).attr('rel', $(ui.item).next().attr('rel'))
                            else if ($(ui.item).prev().length > 0)
                                $(ui.item).attr('rel', parseInt($(ui.item).prev().attr('rel') + 1))

                            var data = new Array();

                            $.each($('#sortable > li'), function () {
                                var rel = parseInt($(this).attr('rel'));

                                data.push({
                                    'user_id': $(this).attr('id').split('-')[2],
                                    'rank': rel
                                });

                                if ($(this).next().length < 1)
                                    return;

                                var xrel = $(this).next().attr('rel')
                                if (rel != xrel - 1)
                                    $(this).next().attr('rel', rel + 1);
                            });

                            var hold = new Array();

                            $.each($('#sortable2 > li'), function () {
                                hold.push($(this).attr('id').split('-')[2]);
                            });

                            $.post('/people', {
                                'act': 'set_rank',
                                'data': data,
                                'hold': hold
                            }, function (resp) {
                                console.log(resp);
                            }, 'json');
                        }
                    })
                    .disableSelection();
                <? } ?>
            });
        </script>

        <? if (session::has_credential('admin')) { ?>
            <div style="width: 1000px; height: 197px; border: 1px solid #ccc; overflow: auto">
                <div class="pt5 pl5" style="width: 10000px;">
                    <ul id="sortable2" class="connectedSortable" style="height: 177px;">
                        <? foreach ($hold_people as $user_id) { ?>
                            <? $profile = profile_peer::instance()->get_item($user_id); ?>
                            <? $crop = unserialize($profile["ph_crop"]) ?>
                            <li class="ui-state-highlight" id="member-blind-<?= $profile['user_id'] ?>" rel="<?= $profile['rank'] ?>">
                                <img
                                        src="<? if ($profile["pid"]) { ?>/imgserve?pid=<?= $profile["pid"] ?>&x=<?= $crop['x'] ?>&y=<?= $crop['y'] ?>&w=<?= !$crop['w']
                                            ? '165' : $crop['w'] ?>&h=<?= !$crop['h'] ? '165'
                                            : $crop['h'] ?>&z=crop<? } else { ?>/no_image.png<? } ?>"
                                        style="width: 165px; height: 165px;"
                                />
                                <div id="member-tooltip-<?= $user_id ?>"
                                     class="hide p10 bold"
                                     style="position: absolute; background: black; color: white; border-radius: 5px;">
                                    <?= profile_peer::get_name($profile) ?><br/>
                                    <!--<span class="fs10"><?= profile_peer::get_location($profile) ?></span>
									<? if (intval(profile_peer::get_age($profile["birthday"])) > 1) { ?>
										<br />
										<span class="fs10"><?= profile_peer::get_age($profile['birthday']) ?></span>
									<? } ?>-->
                                </div>
                            </li>
                        <? } ?>
                    </ul>
                    <div class="clear"></div>
                </div>
            </div>
        <? } ?>

        <table width="100%">
            <tr>
                <td>
                    <? if (session::has_credential('admin')) { ?>
                        <a href="/people?status=successful"
                           style="font-size: 14px;font-weight: normal;text-decoration: underline;text-transform: none;"><?= t("Успешные") ?></a>
                        <a href="/people?status=perspective"
                           style="font-size: 14px;font-weight: normal;text-decoration: underline;text-transform: none;"><?= t("Перспективные") ?></a>
                        <a href="/people?status=new-face"
                           style="font-size: 14px;font-weight: normal;text-decoration: underline;text-transform: none;"><?= t("Новые лица") ?></a>
                        <a href="/search"
                           style="font-size: 14px;font-weight: normal;text-decoration: underline;text-transform: none;"><?= t("Поиск") ?></a>
                        <? //=$count_members?>
                    <? } ?>
                </td>
                <td align="right">
                    <div class="paginator">
                        <?= pager_helper::get_full($pager) ?>
                    </div>
                </td>
            </tr>
        </table>

    </div>

    <!--<div class="clear"></div>-->

</div>
