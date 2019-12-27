<style>
    @font-face {
        font-family: 'Conv_EVROPA';
        src: url('/EVROPA.eot');
        src: local('☺'), url('/EVROPA.woff') format('woff'), url('/EVROPA.ttf') format('truetype'), url('/EVROPA.svg') format('svg');
        font-weight: normal;
        font-style: normal;
    }

    .s1 {
        min-height: 26px;
        text-align: center;
        font-size: 26px;
    }

    .s2 {
        text-align: center;
        font-size: 17px;

    }

    .card_box {
        border: 1px solid #a0a0a0;
        font-family: 'Conv_EVROPA';
        position: relative;
    }

    .card_box div {
        position: relative;
    }

    .card_box div span {
        position: relative;
    }
</style>
<? $contacts = profile_peer::instance()->get_contacts($card_profile["user_id"]); ?>
<?
$agency = db::get_rows("SELECT * FROM user_agency WHERE (type=1 AND user_id=:uid) AND (agency_id>0 OR name<>'') LIMIT 1", ['uid' => $card_profile['user_id']]);
if (empty($agency)) {
    $agency = db::get_rows("SELECT * FROM user_agency WHERE (agency_id>0 OR name!='') AND user_id=:uid AND foreign_agency=false LIMIT 1", ['uid' => $card_profile['user_id']]);
}
if (empty($agency)) {
    $agency = db::get_rows("SELECT * FROM user_agency WHERE name!='' AND user_id=:uid AND foreign_agency=true LIMIT 1", ['uid' => $card_profile['user_id']]);
}


if (!empty($agency)) {
    foreach ($agency as $key => $value) {
        if ($value['agency_id'] > 0) {
            $agency = db::get_scalar("SELECT name FROM agency WHERE id=:aid", ['aid' => $value['agency_id']]);
        } else {
            $agency = db::get_scalar("SELECT name FROM user_agency WHERE id=:aid", ['aid' => $value['id']]);
        }
    }
} else {
    $agency = '';
}
?>

<div id="profile-edit-frame-card">
    <div class="left">
        <table style="width: 100%;">
            <tr>
                <td>
                    <?= t('Язык') ?>
                </td>
                <td style="padding-bottom: 10px;">
                    <input type="button" name="lang[]" value="Рус" rel="ru"/>
                    <input type="button" name="lang[]" value="Англ" rel="en"/>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Имя') ?>,<?= t('Фамилия') ?>
                </td>
                <td>
                    <input type="text" id="name" value="<?= profile_peer::get_name($card_profile); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Агенство') ?>
                </td>
                <td>
                    <input type="text" id="agency" value="<?= $agency ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Мобильный') ?>
                </td>
                <td>
                    <input type="text" id="mobile" value="<?= ($contacts['phone']) ? $contacts['phone'] : 'xxx xxx-xxxx' ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Е-почта') ?>
                </td>
                <td>
                    <input type="text" id="email" value="<?= ($contacts['email'])
                        ? $contacts['email'] : ($card_profile['email'] ? $card_profile['email'] : 'example@sitename.domain'); ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Web') ?>-<?= t('страничка') ?>
                </td>
                <td style="padding-bottom: 10px;">
                    <input type="text" id="www" value="<?= ($card_profile['subdomain']) ? $card_profile['subdomain'] : 'example' ?>"
                           style="width: 90px;"/>.<?= conf::get('server') ?>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>
                    <input type="button" id="save-card" value="<?= t('Сохранить') ?>"/>
                </td>
            </tr>
        </table>
        <div class="success acenter hide"><?= t("Изменения сохранены") ?></div>

        <div class="left mt10" style="margin-left: 35px;">
            <div class="left mr10 mt5" style="">
                <img id="card_avatar" src="<? if ($preview['id']) { ?>/imgserve?pid=<?= $preview['id'] ?><? } else { ?>/no_image.png<? } ?>"
                     width="200"/>
            </div>
            <div class="clear"></div>
            <div class="left mt5">
                <div style="margin-left: 25px;">
                    <input type='hidden' id="pid" value="<?= $preview['id'] ?>"/>
                    <input type='hidden' id="language" value="<?= session::get('language', 'ru') ?>"/>
                    <input type="file" id="card_uploadify"/>

                </div>
                <div id="card_photo_crop" class="hide">
                    <div class="mt10" style="border: 1px solid black; width: 100px; height: 138px;">
                        <div id="card_avatar_small" style="width: 100px; height: 138px; overflow: hidden;">
                            <img src="<? if ($preview['id']) { ?>/imgserve?pid=<?= $preview['id'] ?><? } else { ?>/no_image.png<? } ?>" width="100px"
                                 height="138px"/>
                        </div>
                    </div>

                    <div class="mt10 ">
                        <div class="left">
                            <input type="button" value="<?= t("Сохранить") ?>" id="profile-card-save"/>
                        </div>
                        <div class="clear"></div>
                        <div id="msg-success-photo" class="left pt5 ml10 acenter hide">
                            <? //=t("Данные сохранены успешно")?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clear"></div>

        </div>
    </div>


    <div class="card_box left ml20" style="width: 300px; height: 536px; marginb-left: 90px;">
        <div id="photo_span" style="margin-left: 84px; margin-top: 60px;">
            <?php
            if ($preview['id']) {
                $src = '/imgserve?pid='.$preview['id'];
                if ($crop = unserialize($preview['description'])) {
                    $src .= '&x='.$crop['x'].'&y='.$crop['y'].'&w='.$crop['w'].'&h='.$crop['h'].'&z=crop';
                }
            } else {
                $src = '/no_image.png';
            }

            ?>
            <img src="<?= $src ?>" style="width: 133px; height: 183px;">
        </div>
        <div class="s1" style="margin-top: 75px;">
            <span id="name_span"><?= profile_peer::get_name($card_profile); ?></span>
        </div>
        <div class="s2" style="">
            <span id="status_span">модель</span>
        </div>

        <div class="s2" style="margin-top: 40px;">
            <span id="agency_span"><?= $agency ?></span>
        </div>
        <div class="s2">
            <span id="mobile_span"><?= ($contacts['phone']) ? $contacts['phone'] : 'xxx xxx-xxxx' ?></span>
        </div>
        <div class="s2" style="">
            <span id="email_span"><?= ($contacts['email']) ? $contacts['email']
                    : ($card_profile['email'] ? $profile['email'] : 'example@sitename.domain'); ?></span>
        </div>
        <div class="s2" style="margin-top: 15px;">
            <span id="www_span"><?= (($card_profile['subdomain']) ? $card_profile['subdomain'].'.'.conf::get('server')
                    : 'example.'.conf::get('server')); ?></span>
        </div>


    </div>
</div>
<div class="clear"></div>


<script>

    $('input[type="text"]').keyup(function () {
        var obj = $('span[id="' + $(this).attr('id') + '_span"]');
        var domain = '';
        if ($(this).attr('id') == 'www') domain = '.<?=conf::get('server')?>';
        obj.parent().html("<span id='" + $(this).attr('id') + "_span'>" + $(this).val() + domain + "</span>");
    });

    function preparePostData(id) {
        return {
            "x": $('#' + id).position().left,
            "y": $('#name_span').height() + $('#' + id).parent().position().top + parseInt($('#' + id).parent().css('margin-top')),
            "text": $('#' + id).html()
        }
    }

    $('#save-card').click(function () {
        $.post(
            '/profile/edit',
            {
                "id": '<?=$card_profile['user_id']?>',
                "group": "options",
                "ad": "subdomain",
                "subdomain": $('#www').val()
            },
            function () {},
            'json'
        )
        $.post(
            '/profile/card',
            {
                "data": {
                    "name": preparePostData('name_span'),
                    "mobile": preparePostData('mobile_span'),
                    "email": preparePostData('email_span'),
                    "www": preparePostData('www_span'),
                    "agency": preparePostData('agency_span'),
                    "status": preparePostData('status_span')
                },
                "pid": $('#pid').val(),
                'user_card': '<?=$card_profile['user_id']?>',
                'language': $('#language').val()

            },
            function (resp) {
                if (resp.success)
                    $('.success').fadeIn(300, function () { $(this).fadeOut(3000); });
            },
            'json'
        )
    })
</script>


<script type="text/javascript">
    $(document).ready(function () {
        var ru = '<?=profile_peer::get_name($profile, '&fn &ln', 'ru')?>';
        var en = '<?=profile_peer::get_name($profile, '&fn &ln', 'en')?>';

        $('input[name="lang[]"]').click(function () {
            $('#language').val($(this).attr('rel'));
            $('#name').val(eval("(" + $(this).attr('rel') + ")"));
            $('#name_span').html(eval("(" + $(this).attr('rel') + ")"));
        });
        //$('#card_uploadify').uploadify({
        //    'uploader': '/uploadify.swf',
        //    'script': '/profile/card',
        //    'fileDataName': 'image',
        //    'scriptData': {
        //        'upload_card_avatar': 1,
        //        'user_id': <?//=$card_user?>//,
        //        'language': $('#language').val()
        //    },
        //    'cancelImg': '/cancel.png',
        //    'buttonImg': '/buttons/upload_photo.png',
        //    'width': '153',
        //    'transparent': true,
        //    'folder': '/',
        //    'fileDesc': 'jpg; gif; png; jpeg;',
        //    'fileExt': '*.jpg;*.gif;*.png;*.jpeg;',
        //    'auto': true,
        //    'multi': false,
        //    'onError': function (event, queueID, fileObj, response) {
        //        console.log(response);
        //    },
        //    'onComplete': function (event, queueID, fileObj, response, data) {
        //        var resp = eval("(" + response + ")");
        //        if (resp.success) {
        //            window.location = window.location;
        //        }
        //    }
        //});
    });
</script>
