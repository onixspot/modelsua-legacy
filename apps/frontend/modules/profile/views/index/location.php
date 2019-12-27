<? $loc = profile_peer::get_location($profile) ?>

<div id="window-where_are_you" class="pt10 pl10 pr10 pb10 fs12 hide mt20" style="position: absolute; background: #fff; box-shadow: 0px 0px 5px black; width: 380px;">
    <form id="set_location" action="/profile?id=<?= $user_id ?>" method="POST">
        <div>
            <div class="left pt5 mr5 aright" style="width: 145px"><?= t("Страна") ?>:</div>
            <div class="left">
                <select id="country" style="width: 230px;">
                    <option value="0">&mdash;</option>
                </select>
            </div>
            <div class="clear"></div>
        </div>
        <div id="region_block" class="mt10 hide">
            <div class="left pt5 mr5 aright" style="width: 145px"><?= t("Регион / Город") ?>:</div>
            <div class="left">
                <select id="region" style="width: 230px;">
                    <option value="0">&mdash;</option>
                </select>
            </div>
            <div class="clear"></div>
        </div>
        <div id="city_block" class="hide mt10">
            <div class="left pt5 mr5 aright" style="width: 145px"><?= t("Город / Район") ?>:</div>
            <div class="left">
                <select id="city" style="width: 230px;">
                    <option value="0">&mdash;</option>
                </select>
                <input class="hide" type="text" id="another_city" value="<?= $profile["another_city"] ?>" style="width: 230px;"/>
            </div>
            <div class="clear"></div>
        </div>
        <div class="mt10">
            <div class="left pt5 mr5 aright" style="width: 145px">&nbsp;</div>
            <div class="left">
                <input type="button" id="submit" value="<?= t("Сохранить") ?>"/>
            </div>
            <div id="msg-success-location" class="left ml10 hide" style="color: #090; width: 128px;">
                <?= t("Данные сохранены успешно") ?>
            </div>
            <div class="clear"></div>
        </div>
    </form>
</div>

<div class="fs12 mt20<? if ($loc == ''
    && (!session::has_credential('admin')
        && session::get_user_id() != $user_id)) { ?> hide<? } ?>" style="width: 289px">
    <div>
        <span style="color: #000000"><?= t('Сейчас в') ?>:</span>
        <span id="span-where_are_you"><?= $loc ?></span>
        <a href="javascript:void(0);" id="where_are_you" class="italic cgray <? if (!session::has_credential('admin')
            && session::get_user_id()
            != $user_id) { ?>hide<? } ?>"><? if ($loc = '') { ?><?= t('Где ты сейчас') ?>?<? } else { ?><?= t('Изменить') ?><? } ?></a>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $('#where_are_you').click(function () {
            $('#window-where_are_you').show();
        });

        var form = new Form('set_location');
        form.onSuccess = function (resp) {
            if (resp.success) {
                $("#msg-success-location")
                    .show()
                    .css("opacity", "0")
                    .animate({
                        "opacity": "1"
                    }, 256, function () {
                        setTimeout(function () {
                            $("#msg-success-location").animate({
                                "opacity": "0"
                            }, 256, function () {
                                $(this).hide();
                                $('#window-where_are_you').hide();
                                $('#span-where_are_you').html(resp.location)
                                    .parent().parent().show();
                            })
                        }, 1000);
                    });
            }
        }
        $('#set_location #submit').click(function () {
            form.data['act'] = 'set_location';
            form.send();
        });

        $("#country").change(function () {
            var country_id = $(this).val();
            $("#region > option").remove();
            var option = $('<option />');
            $(option)
                .val(0)
                .html('&mdash;');
            $('#region').append($(option));
            // 9908 - country_id Украины
            if (country_id != 9908) {
                $('#region_block').hide();
                $("#region").change();
            } else {
                $('#region_block').show();
                $.post('/geo', {
                    'act': 'get_regions',
                    'country_id': country_id
                }, function (resp) {
                    $.each(resp.regions, function () {
                        var option = $('<option />');
                        $(option)
                            .val(this.region_id)
                            .html(this.name);
                        $('#region').append($(option));
                    });
                    $("#region").val('<?=$profile["region"]?>');
                    $("#region").change();
                }, 'json');
            }
        });

        $("#region").change(function () {
            var country_id = $("#country").val();
            var region_id = $(this).val();
            $("#city > option").remove();
            var option = $('<option />');
            $(option)
                .val(0)
                .html('&mdash;');
            $('#city').append($(option));
            if (region_id != 0) {
                $('#city_block').show();
                $.post('/geo', {
                    'act': 'get_cities',
                    'region_id': region_id
                }, function (resp) {
                    $.each(resp.cities, function () {
                        var option = $('<option />');
                        $(option)
                            .val(this.city_id)
                            .html(this.name);
                        $('#city').append($(option));
                    });
                    $("#city").val('<?=$profile["city"]?>');
                    $("#city").change();
                }, 'json');
            } else if (country_id != 0 && country_id != 9908) {
                $('#city_block').show();
                $.post('/geo', {
                    'act': 'get_cities',
                    'country_id': country_id
                }, function (resp) {
                    for (var i = 0; i <= resp.cities.length; i++) {
                        var option = $('<option />');
                        if (typeof resp.cities[i] != 'undefined') {
                            $(option)
                                .val(resp.cities[i].city_id)
                                .html(resp.cities[i].name);
                        } else {
                            $(option)
                                .val(-1)
                                .html('Другой');
                        }
                        $('#city').append($(option));
                    }
                    $("#city").val('<?=$profile["city"]?>');
                    $("#city").change();
                }, 'json')
            } else {
                $('#city_block').hide();
                $("#city").change();
            }
        });

        $("#city").change(function () {
            if ($(this).val() == -1) {
                $(this).hide();
                $("#another_city")
                    .show()
                    .focus();
            }
        });

        $("#another_city").blur(function () {
            if ($(this).val() == "") {
                $(this)
                    .val("")
                    .hide();
                $("#city")
                    .show()
                    .val(0)
            }
        });

        $.post("/geo", {
            "act": "get_countries"
        }, function (data) {
            $.each(data.countries, function () {
                var option = $("<option />");
                $(option)
                    .attr("value", this.country_id)
                    .html(this.name)
                $("#country").append($(option));
            });
            $("#country").val(<?=$profile["country"]?>);
            $("#country").change();
        }, "json");

    });
</script>
