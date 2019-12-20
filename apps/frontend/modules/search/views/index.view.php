<style>
    #search_form td {
        text-align: right;
        color: #888C9B !important;
        font-size: 13px;
        padding: 4px;
    }

    #search_form td input, select {
        float: left;
        width: 350px;
        color: #000000;
    }

    #search_form td input[type="text"]:focus {
        float: left;
        width: 350px;
        color: #34373D;

    }

    div.sepa {
        background: none repeat scroll 0 0 #F3F4F9;
        font-weight: bold;
        height: 25px;
        line-height: 25px;
        padding-left: 20px;
        text-align: left;
        text-transform: uppercase;
        color: #838999;
    }

    .showhidelink {
        text-decoration: underline;
        font-size: 12px;
    }

    .pager {

    }

    .pager a {
        font-weight: bold;
        padding: 3px 7px;
        color: #000000;
    }

    .pager a:hover, .selected {
        font-weight: bold;
        padding: 3px 7px;
        background: #000000;
        color: white !important;
    }
</style>
<div class="search_form_box" style="">
    <div class="left pt10 pb5 pl10" style="width: 100%;">
        <div class="small-title left square_p pl10">
            <a href="/search"><?= t('Поиск') ?></a>
        </div>
        <div class="mr20 right">

            <a href="javascript:" onClick="$('#search_form, .showhidelink').toggleClass('hide');" class="cpurple showhidelink <?= ($users) ? ' ' : ' hide' ?>"><?= t('Развернуть форму') ?></a>
            <?php if ($users) { ?><a href="javascript:" onClick="$('#search_form, .showhidelink').toggleClass('hide');" class="cpurple showhidelink <?= ($users) ? ' hide' : '' ?>"><?= t('Свернуть форму') ?></a><?php } ?>
            <a href="javascript:" onClick="clear_form('search_form')" class="cpurple showhidelink ml10 <?= ($users) ? ' hide' : ' ' ?>"><?= t('Очистить форму') ?></a>
        </div>
    </div>
    <div class="clear"></div>
    <?php if (empty($users) && isset($users)) { ?>
        <div class="message_box acenter p20 mb20" style="color: #838999; border: 1px solid #ccc;">
            <?= t('По Вашему запросу ничего не найдено') ?>
        </div>
    <?php } ?>
    <div class="clear"></div>
    <form id="search_form" class="<?= ($users) ? 'hide' : '' ?>">
        <table style="width: 100%;">
            <tr>
                <td colspan="2">
                    <div class="sepa"><?= t('Основное') ?></div>
                </td>
            </tr>
            <tr>
                <td style="width: 36%;">
                    <?= t('Имя') ?>
                </td>
                <td>
                    <input type="text" name="first_name" value="<?= request::get('first_name') ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Фамилия') ?>
                </td>
                <td>
                    <input type="text" name="last_name" value="<?= request::get('last_name') ?>"/>
                </td>
            </tr>
            <tr>
                <td style="width: 36%;">
                    <?= t('Статус') ?>
                </td>
                <td>
                    <select id="status_filter"
                            multiple="multiple"
                            name="status_filter[]"
                            style="width: 350px; float: left;">
                        <?php
                        $statuses = profile_peer::get_types_list();
                        //						echo '<optgroup label="'.t('Статус не назначен').'" value="0">';
                        //						echo '<option value="0" selected>&mdash;</option></optiongroup>';
                        foreach ($statuses as $key => $value) {
                            echo '<optgroup label="'.t($value['type']).'" value="'.$key.'">';
                            //echo '<option value="'.$key.'" '.((profile_peer::get_type_by_user($profile['user_id'])==$key && !profile_peer::get_status_by_user ($profile['user_id'])) ? ' selected' : '').'><i>'.($value['type']).(!in_array($key,array(5,6)) ? ('&nbsp;(no status)') : '').'</i></option>';
                            if (is_array($value['status'])) {
                                foreach ($value['status'] as $k => $v) {
                                    echo '<option value="'.$k.'" '.(request::get_int('status_filter') == $k ? ' selected' : '').'>'.(t($v) ? t($v) : t($value['type'])).'</option>';
                                }
                            }
                            echo '</optgroup>';

                        }
                        ?>
                        <?php
                        function build_url($ex = [])
                        {
                            $ex  = array_diff(['q', 'status', 'filter', 'active', 'hidden'], $ex);
                            $url = '/adminka/user_manager';
                            foreach ($ex as $key) {
                                if (request::get($key)) {
                                    $url .= '&'.$key.'='.request::get($key);
                                }
                            }

                            return $url;
                        }

                        ?>
                    </select>
                </td>
            </tr>
            <tr id="independent_model_row" class="<?= (request::get_int('status_filter') > 20 && request::get_int('status_filter') < 30) ? '' : ' hide ' ?>">
                <td>
                    <?= t('Независимая модель') ?>
                </td>
                <td>
                    <select name="independent_model">
                        <option value="-1" <?= (!request::get('independent_model') || request::get('independent_model') == '-1') ? ' selected' : '' ?>>&mdash;</option>
                        <option value="1" <?= (request::get('independent_model') == '1') ? ' selected' : '' ?>><?= t('Да') ?></option>
                        <option value="0" <?= (request::get('independent_model') == '0') ? ' selected' : '' ?>><?= t('Нет') ?></option>
                    </select>

                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Активирован') ?>
                </td>
                <td>
                    <select name="active">
                        <option value="-1" <?= (!request::get('active') || request::get('active') == '-1') ? ' selected' : '' ?>>&mdash;</option>
                        <option value="1" <?= (request::get('active') == '1') ? ' selected' : '' ?>><?= t('Да') ?></option>
                        <option value="0" <?= (request::get('active') == '0') ? ' selected' : '' ?>><?= t('Нет') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Скрытый') ?>
                </td>
                <td>
                    <select name="hidden">
                        <option value="-1" <?= (!request::get('hidden') || request::get('hidden') == '-1') ? ' selected' : '' ?>>&mdash;</option>
                        <option value="1" <?= (request::get('hidden') == '1') ? ' selected' : '' ?>><?= t('Да') ?></option>
                        <option value="0" <?= (request::get('hidden') == '0') ? ' selected' : '' ?>><?= t('Нет') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Возраст') ?>
                </td>
                <td>
                    <input type="text" style="width: 170px;" name="birthday_from" class="birthday mr10" value="<?= request::get('birthday_from') ? request::get('birthday_from') : t('от') ?>"/>
                    <input type="text" style="width: 170px;" name="birthday_to" class="birthday" value="<?= request::get('birthday_to') ? request::get('birthday_to') : t('до') ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Страна') ?>
                </td>
                <td>
                    <select id="country" name="country" value="<?= request::get_int('country') ?>">
                        <option value="0">&mdash;</option>
                    </select>

                </td>
            </tr>
            <tr id="region_block" class="hide">
                <td>
                    <?= t('Регион') ?>
                </td>
                <td>
                    <select id="region" name="region">
                        <option value="0">&mdash;</option>
                    </select>
                </td>
            </tr>
            <tr id="city_block" class="hide">
                <td>
                    <?= t('Город') ?>
                </td>
                <td>
                    <select id="city" name="city">
                        <option value="0">&mdash;</option>
                    </select>
                    <input class="<?= request::get_int('city') != -1 ? ' hide' : '' ?>" type="text" name="another_city" value="<?= request::get('another_city') ?>" id="another_city" value=""/>
                </td>
            </tr>

            <!--            <tr>
                <td>
                    <?= t('Отчество') ?>
                </td>
                <td>
                    <input type="text" name="middle_name" value="<?= request::get('middle_name') ?>"/>
                </td>
            </tr>-->
            <tr>
                <td colspan="2">
                    <div class="sepa"><?= t('Параметры') ?></div>
                </td>
            </tr>


            <tr>
                <td>
                    <?= t('Бюст') ?>
                </td>
                <td>
                    <input type="text" style="width: 170px;" name="breast_from" class="mr10" value="<?= request::get('breast_from') ? request::get('breast_from') : t('от') ?>"/>
                    <input type="text" style="width: 170px;" name="breast_to" class="" value="<?= request::get('breast_to') ? request::get('breast_to') : t('до') ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Талия') ?>
                </td>
                <td>
                    <input type="text" style="width: 170px;" name="waist_from" class="mr10" value="<?= request::get('waist_from') ? request::get('waist_from') : t('от') ?>"/>
                    <input type="text" style="width: 170px;" name="waist_to" class="" value="<?= request::get('waist_to') ? request::get('waist_to') : t('до') ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Бедра') ?>
                </td>
                <td>
                    <input type="text" style="width: 170px;" name="hip_from" class="mr10" value="<?= request::get('hip_from') ? request::get('hip_from') : t('от') ?>"/>
                    <input type="text" style="width: 170px;" name="hip_to" class="" value="<?= request::get('hip_to') ? request::get('hip_to') : t('до') ?>"/>
                </td>
            </tr>


            <tr>
                <td>
                    <?= t('Рост') ?>
                </td>
                <td>
                    <input type="text" style="width: 170px;" name="growth_from" class="mr10" value="<?= request::get('growth_from') ? request::get('growth_from') : t('от') ?>"/>
                    <input type="text" style="width: 170px;" name="growth_to" class="" value="<?= request::get('growth_to') ? request::get('growth_to') : t('до') ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Вес') ?>
                </td>
                <td>
                    <input type="text" style="width: 170px;" name="weigth_from" class="mr10" value="<?= request::get('weigth_from') ? request::get('weigth_from') : t('от') ?>"/>
                    <input type="text" style="width: 170px;" name="weigth_to" class="" value="<?= request::get('weigth_to') ? request::get('weigth_to') : t('от') ?>"/>
                </td>
            </tr>
            <?php
            $params = profile_peer::instance()->get_params_list();

            ?>
            <tr>
                <td>
                    <?= t('Цвет глаз') ?>
                </td>
                <td>
                    <?= tag_helper::select('eye_color[]', $params['eye_color'], ['value' => request::get_int('eye_color'), ' multiple' => 'multiple']); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Цвет волос') ?>
                </td>
                <td>
                    <?= tag_helper::select('hair_color[]', $params['hair_color'], ['value' => request::get_int('hair_color'), ' multiple' => 'multiple']); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Длина волос') ?>
                </td>
                <td>
                    <?= tag_helper::select('hair_length[]', $params['hair_length'], ['value' => request::get_int('hair_length'), ' multiple' => 'multiple']); ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="sepa"><?= t('Агентство') ?></div>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Тип агенства') ?>
                </td>
                <td>
                    <select name="foreign_agency">
                        <option value="-1" <?= (!request::get('foreign_agency') || request::get('foreign_agency') == '-1') ? ' selected' : '' ?>>&mdash;</option>
                        <option value="1" <?= (request::get('foreign_agency') == '1') ? ' selected' : '' ?>><?= t('Украниское') ?></option>
                        <option value="0" <?= (request::get('foreign_agency') == '0') ? ' selected' : '' ?>><?= t('Иностранное') ?></option>
                    </select>
                </td>
            </tr>
            <script>
                $(function () {
                    $('[rel^="atype"]').hide();
                    $('select[name="foreign_agency"]').change(function () {

                        $('[rel^="atype"]').hide();
                        $('[rel^="atype' + $('select[name="foreign_agency"] option:selected').val() + '"]').show();

                        $('[rel^="atype"]')
                            .find('select', 'input')
                            .each(function () {
                                $(this)
                                    .find('option')
                                    .removeAttr('selected');
                            });

                        $('[rel^="atype"]')
                            .find('input')
                            .each(function () {
                                $(this)
                                    .val('');
                            });

                    });
                })

            </script>
            <tr rel="atype1">
                <td>
                    <?= t('Название') ?>
                </td>
                <td>
                    <?php
                    $alist    = agency_peer::get_agency();
                    $alist[0] = '&mdash;';
                    ksort($alist);
                    $alist[-1] = 'Другое';
                    ?>
                    <?= tag_helper::select('agency', $alist, ['id' => 'agency'], ['value' => request::get_int('agency')]); ?>
                    <input type="text" class="hide" id="another_agency" name="another_agency" style="width: 200px" value="<?= request::get('another_agency') ?>"/>
                </td>
            </tr>
            <tr rel="atype1">
                <td>
                    <?= t('Контракт') ?>
                </td>
                <td>
                    <select name="contract">
                        <option value="-1" <?= (!request::get('contract') || request::get('contract') == '-1') ? ' selected' : '' ?>>&mdash;</option>
                        <option value="1" <?= (request::get('contract') == '1') ? ' selected' : '' ?>><?= t('Да') ?></option>
                        <option value="0" <?= (request::get('contract') == '0') ? ' selected' : '' ?>><?= t('Нет') ?></option>
                    </select>
                </td>
            </tr>
            <tr rel="atype1">
                <td>
                    <?= t('Тип') ?>
                </td>
                <td>
                    <select name="contract_type">
                        <option value="-1" <?= (!request::get('contract_type') || request::get('contract_type') == '-1') ? ' selected' : '' ?>>&mdash;</option>
                        <option value="1" <?= (request::get('contract_type') == '1') ? ' selected' : '' ?>><?= t('Эксклюзивный') ?></option>
                        <option value="0" <?= (request::get('contract_type') == '0') ? ' selected' : '' ?>><?= t('Неэксклюзивный') ?></option>
                    </select>
                </td>
            </tr>
            <tr rel="atype1">
                <td>
                    <?= t('Материнское') ?>
                </td>
                <td>
                    <select name="agency_type">
                        <option value="-1" <?= (!request::get('agency_type') || request::get('agency_type') == '-1') ? ' selected' : '' ?>>&mdash;</option>
                        <option value="1" <?= (request::get('agency_type') == '1') ? ' selected' : '' ?>><?= t('Да') ?></option>
                        <option value="0" <?= (request::get('agency_type') == '0') ? ' selected' : '' ?>><?= t('Нет') ?></option>
                    </select>
                </td>
            </tr>
            <!--            <tr>
                <td colspan="2">
                    <div class="sepa"><?= t('Иностранное агентство') ?></div>
                </td>
            </tr>-->
            <tr rel="atype0">
                <td>
                    <?= t('Название') ?>
                </td>
                <td>
                    <input type="text" name="foreign_agency_name" value="<?= request::get('foreign_agency_name') ?>"/>
                </td>
            </tr>
            <tr rel="atype0">
                <td>
                    <?= t('Город') ?>
                </td>
                <td>
                    <input type="text" name="foreign_agency_city" value="<?= request::get('foreign_agency_city') ?>"/>
                </td>
            </tr>
            <tr rel="atype0">
                <td>
                    <?= t('Материнское') ?>
                </td>
                <td>
                    <select name="foreign_agency_type">
                        <option value="-1" <?= (!request::get('foreign_agency_type') || request::get('foreign_agency_type') == '-1') ? ' selected' : '' ?>>&mdash;</option>
                        <option value="1" <?= (request::get('foreign_agency_type') == '1') ? ' selected' : '' ?>><?= t('Да') ?></option>
                        <option value="0" <?= (request::get('foreign_agency_type') == '0') ? ' selected' : '' ?>><?= t('Нет') ?></option>
                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <div class="sepa"><?= t('Дополнительно') ?></div>
                </td>
            </tr>
            <?php
            $slist                       = profile_peer::instance()->get_additional_list();
            $slist['work_experience'][0] = '&mdash;';
            ksort($slist['work_experience']);
            ?>
            <tr>
                <td>
                    <?= t('Стаж работы моделью') ?>
                </td>
                <td>
                    <?= tag_helper::select('work_experience', $slist['work_experience'], ['value' => request::get_int('work_experience')]) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Наличие загранпаспорта') ?>
                </td>
                <td>
                    <select name="foreign_passport">
                        <option value="-1" <?= (!request::get('foreign_passport') || request::get('foreign_passport') == '-1') ? ' selected' : '' ?>>&mdash;</option>
                        <option value="1" <?= (request::get('foreign_passport') == '1') ? ' selected' : '' ?>><?= t('Да') ?></option>
                        <option value="0" <?= (request::get('foreign_passport') == '0') ? ' selected' : '' ?>><?= t('Нет') ?></option>
                        <option value="2" <?= (request::get('foreign_passport') == '2') ? ' selected' : '' ?>><?= t('Скоро будет') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Опыт работы за границей') ?>
                </td>
                <td>
                    <select name="foreign_work_experience">
                        <option value="-1" <?= (!request::get('foreign_work_experience') || request::get('foreign_work_experience')) ? ' selected' : '' ?>>&mdash;</option>
                        <option value="1" <?= (request::get('foreign_work_experience') == '1') ? ' selected' : '' ?>><?= t('Да') ?></option>
                        <option value="0" <?= (request::get('foreign_work_experience') == '0') ? ' selected' : '' ?>><?= t('Нет') ?></option>
                    </select>
                </td>
            </tr>


            <tr>
                <td>
                    <?= t('Семейное положение') ?>
                </td>
                <td>
                    <select name="marital_status">
                        <option value="-1" <?= (!request::get('marital_status') || request::get('marital_status') == '-1') ? ' selected' : '' ?>>&mdash;</option>
                        <option value="1" <?= (request::get('marital_status') == '1') ? ' selected' : '' ?>><?= t('Замужем') ?></option>
                        <option value="0" <?= (request::get('marital_status') == '0') ? ' selected' : '' ?>><?= t('Не замужем') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Дети') ?>
                </td>
                <td>
                    <select name="kids">
                        <option value="-1" <?= (!request::get('kids') || request::get('kids') == '-1') ? ' selected' : '' ?>>&mdash;</option>
                        <option value="1" <?= (request::get('kids') == '1') ? ' selected' : '' ?>><?= t('Есть') ?></option>
                        <option value="0" <?= (request::get('kids') == '0') ? ' selected' : '' ?>><?= t('Нет') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Курит') ?>
                </td>
                <td>
                    <select name="smoking">
                        <option value="-1" <?= (!request::get('smoking') || request::get('smoking') == '-1') ? ' selected' : '' ?>>&mdash;</option>
                        <option value="1" <?= (request::get('smoking') == '1') ? ' selected' : '' ?>><?= t('Да') ?></option>
                        <option value="0" <?= (request::get('smoking') == '0') ? ' selected' : '' ?>><?= t('Нет') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Е-почта') ?>
                </td>
                <td>
                    <input type="text" name="email" value="<?= request::get('email') ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Искать "в работе"') ?>
                </td>
                <td>
                    <select name="approve">
                        <option value="-1" <?= (!request::get('approve') || request::get('approve') == '-1') ? ' selected' : '' ?>>&mdash;</option>
                        <option value="1" <?= (request::get('approve') == '1') ? ' selected' : '' ?>><?= t('Да') ?></option>
                        <option value="0" <?= (request::get('approve') == '0') ? ' selected' : '' ?>><?= t('Нет') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Искать в резерве') ?>
                </td>
                <td>
                    <select name="reserv">
                        <option value="-1" <?= (!request::get('reserv') || request::get('reserv') == '-1') ? ' selected' : '' ?>>&mdash;</option>
                        <option value="1" <?= (request::get('reserv') == '1') ? ' selected' : '' ?>><?= t('Да') ?></option>
                        <option value="0" <?= (request::get('reserv') == '0') ? ' selected' : '' ?>><?= t('Нет') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <?= t('Искать в архиве') ?>
                </td>
                <td>
                    <select name="archive">
                        <option value="-1" <?= (!request::get('archive') || request::get('archive') == '-1') ? ' selected' : '' ?>>&mdash;</option>
                        <option value="1" <?= (request::get('archive') == '1') ? ' selected' : '' ?>><?= t('Да') ?></option>
                        <option value="0" <?= (request::get('archive') == '0') ? ' selected' : '' ?>><?= t('Нет') ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="submit" value="<?= t('Найти') ?>"/>
                </td>
            </tr>
        </table>
    </form>
</div>
<div class="clear"></div>
<? if ($users) { ?>
    <ul class="search-results" style="border-top: 1px solid #e5e8ee; ">
        <li class="pt10 pb10"><span style="color: #888C9B;" class="fs13 ml10"><i><?= t('Количеств результатов') ?>:&nbsp;<b><?= $count ?></b> </i></span></li>

        <? foreach ($users as $id) {
            $profile = profile_peer::instance()->get_item($id);
            ?>
            <?php include 'partials/list.php' ?>
        <?php } ?>
    </ul>
    <div class="clear"></div>
    <div class="acenter fs14 pager mb10"><?= pager_helper::get_full($pages, null, null, 4) ?></div>
<?php } ?>
<script>
    $(document).ready(function () {

        $('select[id="status_filter"],select[name="hair_length[]"],select[name="hair_color[]"],select[name="eye_color[]"]').removeAttr('selected').multiselect();
        $('.ui-multiselect').addClass('left');

        $('select[id="status_filter"]').change(function () {

            var model = true;

            for (var i in $(this).val())
                if ($(this).val()[i] > 30 || $(this).val()[i] < 20)
                    model = false;
            if (!$(this).val())
                model = false;

            if (model)
                $('#independent_model_row').removeClass('hide');
            else if (!$('#independent_model_row').hasClass('hide'))
                $('#independent_model_row').addClass('hide');
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
                    $("#region").val('<?=$profile['region']?>');
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
                    $("#city").val('<?=$profile['city']?>');
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
                    $("#city").val('<?=$profile['city']?>');
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
                    .html(this.name);
                $("#country").append($(option));
            });
            $("#country").val(<?=$profile['country']?>);
            $("#country").change();
        }, "json");
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#agency").change(function () {
            if ($(this).val() == -1) {
                $(this).hide();
                $("#another_agency")
                    .show()
                    .focus();
            }
        });

        $("#another_agency").blur(function () {
            if ($(this).val() == "") {
                $(this)
                    .val("")
                    .hide();
                $("#agency")
                    .val(0)
                    .show();
            }
        });
        $("#another_agency").val("<?=request::get_int('another_agency')?>");

        $("#agency")
            .val("<?=request::get_int('agency')?>")
            .change();
    });

    function clear_form(id) {
        $('#' + id)
            .find('input')
            .each(
                function () {
                    exeptions = ['submit', 'button'];
                    if ($.inArray($(this).attr('type'), exeptions) && $(this).val() != 'до' && $(this).val() != 'от') {
                        $(this).val('');
                    }
                });
        $('#' + id).find('select option').removeAttr('selected');
        $('#' + id).find('select').each(function () {
            $(this).find('option:first').attr('selected', '1');
        });
    }
</script>
