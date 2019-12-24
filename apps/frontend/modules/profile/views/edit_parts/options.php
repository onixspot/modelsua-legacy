<div id="profile-edit-frame-options">

    <? if (profile_peer::get_type_by_user($profile['user_id']) == 2) { ?>
        <form id="profile-edit-form-options-subdomain" action="/profile/edit?id=<?= $profile['user_id'] ?>&group=options&ad=subdomain" method="post">

            <div class="mt20 mb5">
                <div class="left pt5 mr5 aright" style="width: 200px;"><?= t('Мой домен') ?>:</div>
                <div class="left">
                    <input type="text" id="subdomain" value="<?= $profile['subdomain'] ?>"/>
                </div>
                <div class="left pt5 ml5">.<?= conf::get('server') ?></div>
                <div id="profile-edit-form-options-subdomain-goto" class="left pt5 ml5 hide">
                    <a href="javascript:;"><?= t('Перейти') ?> &rarr;</a>
                </div>
                <div class="clear"></div>
            </div>
            <div>
                <div class="left pt5 mr5 aright" style="width: 200px">&nbsp;</div>
                <div class="left">
                    <input type="button" id="submit" value="<?= t('Сохранить') ?>"/>
                </div>
                <div id="msg-success-options-subdomain" class="left pt5 ml10 acenter hide" style="color: #090">
                    <?= t('Данные сохранены успешно') ?>
                </div>
                <div class="clear"></div>
            </div>

        </form>
    <? } ?>

    <form id="profile-edit-form-options" action="/profile/edit?id=<?= $profile['user_id'] ?>&group=options" method="post">
        <div class="mt20 mb5">
            <div class="left pt5 mr5 aright" style="width: 200px;"><?= t('Логин') ?>:</div>
            <div class="left">
                <input type="email" id="email" value="<?= $profile['email'] ?>" readonly="readonly"/>
            </div>
            <div class="clear"></div>
        </div>

        <div class="mb5">
            <div class="left pt5 mr5 aright" style="width: 200px;"><?= t('Новый пароль') ?>:</div>
            <div class="left">
                <input type="password" id="new_password" value=""/>
            </div>
            <div class="clear"></div>
        </div>

        <div class="mb5">
            <div class="left pt5 mr5 aright" style="width: 200px;"><?= t('Подтверждение') ?>:</div>
            <div class="left">
                <input type="password" id="new_password_confirmation" value=""/>
            </div>
            <div class="clear"></div>
        </div>

        <div>
            <div class="left pt5 mr5 aright" style="width: 200px">&nbsp;</div>
            <div class="left">
                <input type="button" id="submit" value="<?= t('Сохранить') ?>"/>
            </div>
            <div id="msg-success-options" class="left pt5 ml10 acenter hide" style="color: #090">
                <?= t('Данные сохранены успешно') ?>
            </div>
            <div class="clear"></div>
        </div>

    </form>

</div>
<script type="text/javascript">
    $(document).ready(function () {
        var fSub = new Form('profile-edit-form-options-subdomain');
        fSub.onSuccess = function (resp) {
            if (resp.success) {
                $('#profile-edit-form-options-subdomain-goto')
                    .show()
                    .click(function () {
                        window.location = 'https://' + $('#subdomain').val() + '.<?=conf::get('server')?>/';
                    });
                $("#msg-success-options-subdomain")
                    .show()
                    .css("opacity", "0")
                    .animate({
                        "opacity": "1"
                    }, 256, function () {
                        setTimeout(function () {
                            $("#msg-success-options-subdomain").animate({
                                "opacity": "0"
                            }, 256, function () {
                                $(this).hide();
                            })
                        }, 1000);
                    });
            }
        }
        $('#profile-edit-form-options-subdomain #submit').click(function () {
            fSub.send();
        });

        var form = new Form("profile-edit-form-options");
        form.onSuccess = function (data) {
            if (!data.success && data.error == 'password') {
                $("#old_password")
                    .animate({ "boxShadow": "0px 0px 8px #000000" }, 200, function () {
                        setTimeout(function () {
                            $("#old_password").animate({ "boxShadow": "0px 0px 5px #fff" }, 1000);
                        }, 1000);
                    });
                return false;
            }
            if (!data.success && data.error == 'confirmation') {
                $("#new_password")
                    .animate({ "boxShadow": "0px 0px 8px #000000" }, 200, function () {
                        setTimeout(function () {
                            $("#new_password").animate({ "boxShadow": "0px 0px 5px #fff" }, 1000);
                        }, 1000);
                    });
                $("#new_password_confirmation")
                    .animate({ "boxShadow": "0px 0px 8px #000000" }, 200, function () {
                        setTimeout(function () {
                            $("#new_password_confirmation").animate({ "boxShadow": "0px 0px 5px #fff" }, 1000);
                        }, 1000);
                    });
                return false;
            }
            if (data.success)
                $("#msg-success-options")
                    .show()
                    .css("opacity", "0")
                    .animate({
                        "opacity": "1"
                    }, 256, function () {
                        setTimeout(function () {
                            $("#msg-success-options").animate({
                                "opacity": "0"
                            }, 256, function () {
                                $(this).hide();
                            })
                        }, 1000);
                    });
        }
        $("#profile-edit-form-options #submit").click(function () {
            form.send();
        });
    });
</script>
