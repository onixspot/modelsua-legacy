<div class="mt-3 fs12">
    <div id="window-smi" class="pt10 pl10 pr10 fs12 hide" style="position: absolute; background: #fff; box-shadow: 0px 0px 5px black; width: 380px;">
        <form id="form-smi" action="/profile?id=<?= $user_id ?>" method="post">
            <div>
                <div class="left pt5 mr5 aright" style="width: 145px;"><?= t('Название') ?>:</div>
                <div class="left">
                    <input type="text" id="name" style="width: 230px;"/>
                </div>
                <div class="clear"></div>
            </div>
            <div class="mt10">
                <div class="left pt5 mr5 aright" style="width: 145px"><?= t('Ссылка') ?>:</div>
                <div class="left">
                    <input type="text" id="link" style="width: 230px;"></textarea>
                </div>
                <div class="clear"></div>
            </div>
            <div id="msg-success-smi" class="mt10 acenter hide" style="color: #090;">
                <?= t('Данные сохранены успешно') ?>
            </div>
            <div id="msg-error-smi" class="mt10 acenter hide" style="color: #900;">
                <?= t('Ошибка: проверьте, все ли данные введены правильно') ?>
            </div>
            <div class="mt10">
                <div class="left pt5 mr5 aright" style="width: 145px">&nbsp;</div>
                <div class="left mr10">
                    <input type="button" id="submit" value="<?= t('Сохранить') ?>"/>
                </div>
                <div class="left">
                    <input type="button" value="<?= t('Отмена') ?>" onclick="$('#window-smi').hide();"/>
                </div>
                <div class="clear"></div>
            </div>
        </form>

    </div>

    <div>
        <div class="square_p pl15 mb10 fs12 ucase bold">
            <a class="cblack" href='javascript:void(0);'><?= t('СМИ') ?></a>
        </div>
        <?php if (session::has_credential('admin') || session::get_user_id() === $user_id) { ?>
            <div class="right">
                <a class="underline cgray"
                   href="javascript:void(0);"
                   onclick="$('#window-smi').show();"><?= t('Добавить') ?></a>
            </div>
        <?php } ?>
        <div class="clear"></div>
    </div>

    <?php if (count($smi) > 0) { ?>
        <?php $cnt = 0; ?>
        <?php foreach ($smi as $id) { ?>
            <?php $item = user_smi_peer::instance()->get_item($id); ?>
            <div id="smi-item-<?= $item['id'] ?>" class="pt10 pb5" style="<?php if ($cnt > 0) { ?>border-top: 1px solid #eee<?php } ?>">
                <div>
                    <?php if (session::has_credential('admin') || session::get_user_id() == $user_id) { ?>
                        <div class="fs10 pb5 aright">
                            <a id="remove-smi-<?= $item['id'] ?>" class="fs10" href="javascript:void(0);"><?= t('Удалить') ?></a>
                        </div>
                    <?php } ?>
                    <div>
                        <?= ($cnt + 1) ?> :: <a href="<?= $item['link'] ?>"><?= $item['name'] ?></a>
                    </div>
                </div>
            </div>
            <!--<div class="fs11 cgray pb10"><? //=$foreignWork['work_description']?></div>-->
            <?php $cnt++ ?>
        <?php } ?>
    <?php } else { ?>
        <div>
            <div class="acenter cgray" style="width: 400px; height: 57px; background: #eee; padding-top: 45px;">
                <?= t('Тут еще нет записей') ?>
            </div>
        </div>
    <?php } ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("a[id^='remove-smi']").click(function () {
            if (confirm('<?=t('Вы действительно хотите удалить запись?')?>')) {
                var id = $(this).attr('id').split('-')[2];
                $.post('/profile?id=<?=$user_id?>', {
                    'act': 'remove_smi',
                    'smi_id': id
                }, function (resp) {
                    if (resp.success) {
                        $('#smi-item-' + id).remove();
                    }
                }, 'json');
            }
        });

        var form = new Form("form-smi");
        form.onSuccess = function (resp) {
            if (resp.success) {
                $("#msg-success-smi")
                    .show()
                    .css("opacity", "0")
                    .animate({
                        "opacity": "1"
                    }, 256, function () {
                        setTimeout(function () {
                            $("#msg-success-smi").animate({
                                "opacity": "0"
                            }, 256, function () {
                                $(this).hide();
                                $('#window-smi').hide();
                                window.location = '<?=$_SERVER['REQUEST_URI']?>'
                            })
                        }, 1000);
                    });
            } else {
                $("#msg-error-smi")
                    .show()
                    .css("opacity", "0")
                    .animate({
                        "opacity": "1"
                    }, 256, function () {
                        setTimeout(function () {
                            $("#msg-error-smi").animate({
                                "opacity": "0"
                            }, 256, function () {
                                $(this).hide();
                            })
                        }, 2000);
                    });
            }
        }
        $('#form-smi #submit').click(function () {
            form.data['act'] = 'add_smi';
            form.send();
        });
    });
</script>