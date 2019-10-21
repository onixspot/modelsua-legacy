<script type="text/javascript" src="http://js.<?= conf::get('server') ?>/libraries/ajaxupload.js"></script>
<div class="fs12 mb10 mt10" style="width: 700px;">
    <!--<div class="mb10">
	    <a href="/page?link=membership" class="fs14 underline bold">
		<?= t('Кто может стать Членом или Кандидатом в члены Ассоциации моделей Украины') ?> >></a>
	</div>-->
    <div class="p5 bold fs12 cwhite" style="background: #b95383;">
        <?= t('Регистрация') ?> (только для моделей и девушек, которые хотят стать моделями)
    </div>

    <div id="registration-collection" class="p10" style="border: 1px solid #b95383;">
        <form id="registration" action="/sign/registration" method="post">

            <div class="mb10 p5 bold hide" style="background: #eee;">
                <?= t('Прошу') ?>:
            </div>
            <div class="mb10 hide">
                <div class="left mr5 aright" style="width: 128px">&nbsp;</div>
                <div class="left" style="width: 512px">
                    <div class="left"><input type="radio" id="model" name="statement_type[]"
                                             <? if (request::get('set') != 'asso'){ ?>checked<? } ?> /></div>
                    <div class="left ml5" style="width: 480px"><label for="model"><?= t('включить меня в Каталог моделей Украины') ?></label></div>
                    <div class="clear"></div>
                    <div class="left"><input type="radio" id="association_member" name="statement_type[]"
                                             <? if (request::get('set') == 'asso'){ ?>checked<? } ?> /></div>
                    <div class="left ml5" style="width: 480px"><label
                                for="association_member"><?= t('включить меня в Каталог моделей Украины и принять в Ассоциацию моделей Украины') ?></label>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>


            <!--<div class="mb10 p5 bold" style="background: #eee;">
				<?= t('Я хочу') ?>:
			</div>-->
            <div class="mb10">
                <div class="left mr5 aright" style="width: 128px">&nbsp;</div>
                <div class="left" style="width: 512px">
                    <div>
                        <div class="left"><input type="radio" id="iwantbemodel" name="iwant[]"/></div>
                        <div class="left ml5" style="width: 480px">
                            <label for="iwantbemodel"><?= t('Хочу стать моделью') ?></label><br/><a
                                    href="/page?link=parameters"><?= t('Ознакомьтесь с требованиями модельной внешности') ?></a>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div id="block-whydoyouwantbemodel">
                        <div class="hide"><?= t('Почему хотите стать моделью?') ?></div>
                        <div class="mt5 hide"><textarea id="whydoyouwantbemodel" style="width: 400px; height: 100px"></textarea></div>
                    </div>
                    <div class="mt10">
                        <div class="left"><input type="radio" id="iwantbemember" name="iwant[]"/></div>
                        <div class="left ml5" style="width: 480px"><label
                                    for="iwantbemember"><?= t('Хочу попасть в Каталог моделей Украины, т.к. я уже модель') // , т.к. я уже модель ?></label>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div id="block-whydoyouwantbemember" class="mt10 mb10 pl30">
                        <div class="mb5">
                            <div class="left pt5" style="width: 200px"><?= t('Опыт работы моделью') ?>:</div>
                            <div class="left mt5">
                                <?
                                $slist                        = profile_peer::instance()->get_additional_list();
                                $slist['work_experience'][0]  = t('Нет опыта');
                                $slist['work_experience'][-1] = '&mdash;';
                                ksort($slist['work_experience']);
                                ?>
                                <?= tag_helper::select('work_experience', $slist['work_experience'], ['style' => 'width: 200px;']) ?>
                            </div>
                            <div id="msgErr-work_experience-empty" class="hide left pt10 ml5 bold" style="color: #f00">
                                <?= t('Укажите стаж работы') ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div><?= t('С какими агенствами работали или работаете сейчас?') ?></div>
                        <div class="mt5"><textarea id="workedwith" style="width: 400px; height: 100px"></textarea></div>
                    </div>
                </div>
                <div class="clear"></div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('#iwantbemodel').click(function () {
                            $('#block-whydoyouwantbemodel').show();
                            $('#block-whydoyouwantbemember').hide();
                        });

                        $('#iwantbemember').click(function () {
                            $('#block-whydoyouwantbemodel').hide();
                            $('#block-whydoyouwantbemember').show();
                        });

                        <? if(request::get('set') != 'member'){ ?>
                        $('#iwantbemodel')
                            .attr('checked', true)
                            .click();
                        <? } else { ?>
                        $('#iwantbemember')
                            .attr('checked', true)
                            .click();
                        <? } ?>
                    });
                </script>
            </div>


            <div class="mb10 p5 bold hide" style="background: #eee;">
                <?= t('Членство в АМУ') ?>:
            </div>
            <div class="mb10 hide">
                <div class="left mr5 aright" style="width: 128px">&nbsp;</div>
                <div class="left" style="width: 512px">
                    <div class="left"><input type="checkbox" id="application" name="application"/></div>
                    <div class="left ml5" style="width: 480px"><label
                                for="application"><?= t('Хочу стать Членом или Кандидатом в Члены АМУ') ?></label></div>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>


            <div class="mb10 p5 bold" style="background: #eee;">
                <?= t('Об о мне') ?>
            </div>

            <div class="mb10 hide">
                <div class="left mr5 aright" style="width: 128px"><?= t('Пол') ?>:</div>
                <div class="left">
                    <input type="radio" id="male" name="sex[]" value="male"/>
                    <label for="male">Мужской</label><br/>
                    <input type="radio" id="female" name="sex[]" value="female" checked/>
                    <label for="female">Женский</label>
                </div>
                <div class="clear"></div>
            </div>

            <div class="mb10">
                <div class="left pt5 mr5 aright" style="width: 128px"><?= t('Фамилия') ?>:</div>
                <div class="left">
                    <input type="text" id="last_name" name="last_name" value=""/>
                </div>
                <div id="msgErr-last_name-empty" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Это поле должно быть заполнено') ?>
                </div>
                <div class="clear"></div>
            </div>

            <div class="mb10">
                <div class="left pt5 mr5 aright" style="width: 128px"><?= t('Имя') ?>:</div>
                <div class="left">
                    <input type="text" id="first_name" name="first_name" value=""/>
                </div>
                <div id="msgErr-first_name-empty" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Это поле должно быть заполнено') ?>
                </div>
                <div class="clear"></div>
            </div>

            <div class="mb10">
                <div class="left pt5 mr5 aright" style="width: 128px"><?= t('Дата рождения') ?>:</div>
                <div class="left">
                    <? load::view_helper('ui'); ?>
                    <?= ui_helper::datefields('birthday', 0, false, [], true) ?>
                </div>
                <div id="msgErr-birthday-empty" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Укажите свою дату рождения') ?>
                </div>
                <div class="clear"></div>
            </div>

            <!--<div class="mb10">
				<div class="left mr5 aright" style="width: 128px"><?= t('Опыт работы моделью') ?>: </div>
				<div class="left mt5">
					<?
            $slist                        = profile_peer::instance()->get_additional_list();
            $slist['work_experience'][0]  = t('Нет опыта');
            $slist['work_experience'][-1] = '&mdash;';
            ksort($slist['work_experience']);
            ?>
					<?= tag_helper::select('work_experience', $slist['work_experience']) ?>
				</div>
				<div id="msgErr-work_experience-empty" class="hide left pt10 ml5 bold" style="color: #f00">
					<?= t('Укажите стаж работы') ?>
				</div>
				<div class="clear"></div>
			</div>-->

            <div class="mb10 p5 bold" style="background: #eee;">
                <?= t('Текущее место проживания') ?>
            </div>

            <div class="mb10">
                <div class="left pt5 mr5 aright" style="width: 128px"><?= t('Страна') ?>:</div>
                <div class="left">
                    <select id="country" style="width: 256px;">
                        <option value="0">&mdash;</option>
                    </select>
                </div>
                <div id="msgErr-country-empty" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Сделайте выбор') ?>
                </div>
                <div class="clear"></div>
            </div>

            <div id="region_block" class="mb10 hide">
                <div class="left pt5 mr5 aright" style="width: 128px"><?= t('Регион / Город') ?>:</div>
                <div class="left">
                    <select id="region" style="width: 256px;">
                        <option value="0">&mdash;</option>
                    </select>
                </div>
                <div class="clear"></div>
            </div>

            <div id="city_block" class="mb10 hide">
                <div class="left pt5 mr5 aright" style="width: 128px"><?= t('Город / Район') ?>:</div>
                <div class="left">
                    <select id="city" style="width: 256px;" e>
                        <option value="0">&mdash;</option>
                    </select>
                    <input class="hide" type="text" id="another_city" value="" style="width: 256px;"/>
                </div>
                <div id="msgErr-city-empty" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Сделайте выбор') ?>
                </div>
                <div class="clear"></div>
            </div>

            <div class="mb10 p5 bold" style="background: #eee;">
                <?= t('Параметры') ?>
            </div>

            <div class="mb10">
                <div class="left pt5 mr5 aright" style="width: 128px"><?= t('Рост') ?>:</div>
                <div class="left">
                    <select id="growth">
                        <option value="0" selected>&mdash;</option>
                        <? for ($i = 165; $i <= 190; $i++) { ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <? } ?>
                    </select>
                </div>
                <div class="left pt5 ml5"><?= t('см') ?></div>
                <div id="msgErr-growth-empty" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Сделайте выбор') ?>
                </div>
                <div class="clear"></div>
            </div>

            <div class="mb10">
                <div class="left pt5 mr5 aright" style="width: 128px"><?= t('Вес') ?>:</div>
                <div class="left">
                    <select id="weigth">
                        <option value="0" selected>&mdash;</option>
                        <? for ($i = 35; $i <= 70; $i++) { ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <? } ?>
                    </select>
                </div>
                <div class="left pt5 ml5"><?= t('кг') ?></div>
                <div id="msgErr-weigth-empty" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Сделайте выбор') ?>
                </div>
                <div class="clear"></div>
            </div>

            <div class="mb10">
                <div class="left pt5 mr5 aright" style="width: 128px"><?= t('Грудь') ?>:</div>
                <div class="left">
                    <input type="text" id="breast" style="width: 32px;"/>
                </div>
                <div class="left pt5 ml5"><?= t('см') ?></div>
                <div id="msgErr-breast-empty" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Это поле должно быть заполнено') ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="mb10">
                <div class="left pt5 mr5 aright" style="width: 128px"><?= t('Талия') ?>:</div>
                <div class="left">
                    <input type="text" id="waist" style="width: 32px;"/>
                </div>
                <div class="left pt5 ml5"><?= t('см') ?></div>
                <div id="msgErr-waist-empty" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Это поле должно быть заполнено') ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="mb10">
                <div class="left pt5 mr5 aright" style="width: 128px"><?= t('Бедра') ?>:</div>
                <div class="left">
                    <input type="text" id="hip" style="width: 32px;"/>
                </div>
                <div class="left pt5 ml5"><?= t('см') ?></div>
                <div id="msgErr-hip-empty" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Это поле должно быть заполнено') ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="mb10">
                <div class="left pt5 mr5 aright" style="width: 128px"><?= t('Цвет глаз') ?>:</div>
                <div class="left">
                    <select id="eye_color">
                        <option value="0">&mdash;</option>
                        <? foreach (profile_peer::$params['eye_color'] as $param_id => $param) { ?>
                            <option value="<?= $param_id ?>"><?= $param ?></option>
                        <? } ?>
                    </select>
                </div>
                <div id="msgErr-eye_color-empty" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Это поле должно быть заполнено') ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="mb10">
                <div class="left pt5 mr5 aright" style="width: 128px"><?= t('Цвет волос') ?>:</div>
                <div class="left">
                    <select id="hair_color">
                        <option value="0" selected>&mdash;</option>
                        <? foreach (profile_peer::$params['hair_color'] as $param_id => $param) { ?>
                            <option value="<?= $param_id ?>"><?= $param ?></option>
                        <? } ?>
                    </select>
                </div>
                <div id="msgErr-hair_color-empty" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Это поле должно быть заполнено') ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="mb10">
                <div class="left pt5 mr5 aright" style="width: 128px"><?= t('Длина волос') ?>:</div>
                <div class="left">
                    <select id="hair_length">
                        <option value="0" selected>&mdash;</option>
                        <? foreach (profile_peer::$params['hair_length'] as $param_id => $param) { ?>
                            <option value="<?= $param_id ?>"><?= $param ?></option>
                        <? } ?>
                    </select>
                </div>
                <div id="msgErr-hair_length-empty" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Это поле должно быть заполнено') ?>
                </div>
                <div class="clear"></div>
            </div>


            <div class="mb10 p5 bold" style="background: #eee;">
                <?= t('Контакты') ?>
            </div>

            <div class="mb10">
                <div class="left mr5 aright" style="width: 128px; padding-top: 3px">
                    <?= t('E-Mail') ?>:
                </div>
                <div class="left">
                    <input type="text" id="email" value=""/>
                </div>
                <div class="left pt5 ml5 bold" style="color: #900">*</div>
                <div id="msgErr-email-exists" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Такой email уже существует') ?>
                </div>
                <div id="msgErr-email-incorrect" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Некорректный електронный адрес') ?>
                </div>
                <div id="msgErr-email-empty" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Это поле должно быть заполнено') ?>
                </div>
                <div class="clear"></div>
            </div>

            <div class="mb10">
                <div class="left mr5 aright" style="width: 128px; padding-top: 3px">
                    <?= t('Телефон') ?>:
                </div>
                <div class="left">
                    <input type="text" id="phone" value=""/>
                </div>
                <div class="left pt5 ml5 bold" style="color: #900">*</div>
                <div id="msgErr-phone-empty" class="hide left pt5 ml5 bold" style="color: #f00">
                    <?= t('Это поле должно быть заполнено') ?>
                </div>
                <div class="clear"></div>
            </div>

            <div class="mb10">
                <div class="left mr5 aright" style="width: 128px; padding-top: 3px">
                    Skype:
                </div>
                <div class="left">
                    <input type="text" id="skype" value=""/>
                </div>
                <div class="clear"></div>
            </div>

            <div class="mb10">
                <div class="left mr5 aright" style="width: 128px; padding-top: 3px">
                    Icq:
                </div>
                <div class="left">
                    <input type="text" id="icq" value=""/>
                </div>
                <div class="clear"></div>
            </div>

            <div class="mb10">
                <div class="left pt5 mr5 aright" style="width: 128px">
                    Facebook:
                </div>
                <div class="left">
                    <input type="text" id="facebook" value=""/>
                </div>
                <div class="clear"></div>
            </div>

            <div class="mb10">
                <div class="left pt5 mr5 aright" style="width: 128px">
                    Napodiume:
                </div>
                <div class="left">
                    <input type="text" id="napodiume" value=""/>
                </div>
                <div class="clear"></div>
            </div>

            <div class="mb10">
                <div class="left pt5 mr5 aright" style="width: 128px">
                    Vkontakte:
                </div>
                <div class="left">
                    <input type="text" id="vkontakte" value=""/>
                </div>
                <div class="clear"></div>
            </div>

            <div class="mb10">
                <div class="left mr5 aright" style="width: 128px; padding-top: 3px">
                    <?= t('Сайт') ?>:
                </div>
                <div class="left">
                    <input type="text" id="website" value=""/>
                </div>
                <div class="left ml5 fs10 cgray"
                     style="width: 300px"><?= t('ссылка на Вашу страничку на сайте модельного агентства или другой сайт, где есть ваши фотографии') ?></div>
                <div class="clear"></div>
            </div>

            <div class="mb10 p5 bold" style="background: #eee;">
                <?= t('Откуда Вы узнали об Ассоциации моделей Украины') ?>?
            </div>

            <div class="mb10">
                <div class="left mr5 aright cgray" style="width: 128px; padding-top: 3px">&nbsp;</div>
                <div class="left">
                    <div>
                        <input type="radio" id="from_facebook" name="learned_about[]"/>
                        <label for="from_facebook">Facebook</label>
                    </div>
                    <div class="mt5">
                        <input type="radio" id="from_vkontakte" name="learned_about[]"/>
                        <label for="from_vkontakte">Vkontakte</label>
                    </div>
                    <div class="mt5">
                        <input type="radio" id="from_odnoklassniki" name="learned_about[]"/>
                        <label for="from_odnoklassniki">Odnoklassniki</label>
                    </div>
                    <div class="mt5">
                        <input type="radio" id="from_google" name="learned_about[]"/>
                        <label for="from_google">Google</label>
                    </div>
                    <div class="mt5">
                        <input type="radio" id="from_friends" name="learned_about[]"/>
                        <label for="from_friends"><?= t('Друзья, знакомые') ?></label>
                    </div>
                    <div class="mt5">
                        <input type="radio" id="from_banners" name="learned_about[]"/>
                        <label for="from_banners"><?= t('Флаеры, визитки') ?></label>
                    </div>
                </div>
                <div class="clear"></div>
            </div>

            <div class="mb10 p5 bold" style="background: #eee;">
                <?= t('Ваши фотографии') ?>
            </div>

            <div class="mb10">
                <!--<div class="mb10 cgray">Загрузите две Ваши портретные фотографии и две фотографии, на которых Вы сняты в полный рост</div>-->
                <div id="msgErr-images-empty" class="bold mb10 hide" style="color: #f00">
                    <?= t('Ошибка: загрузите фотографии') ?>
                </div>
                <div>
                    <div class="left mr10 aright" style="width: 128px; height: 200px;">
                        <?= t('Лицо крупным<br/>планом') ?>
                    </div>
                    <div id="uploader-photo-preview-0" class="left mr10" style="width: 160px; height: 200px; border: 1px solid #ccc;">
                        <div class="acenter" style="margin-top: 88px;">
                            <input type="button" id="uploader-photo-button-add-0" value="<?= t('Добавить') ?>"/>
                            <!--<input type="button" id="uploader-photo-button-remove-0" value="Х" class="hide" style="color: #a00; font-weight: bold" />-->
                        </div>
                    </div>
                    <div id="uploader-photo-preview-1" class="left mr10" style="width: 160px; height: 200px; border: 1px solid #ccc;">
                        <div class="acenter" style="margin-top: 88px;">
                            <input type="button" id="uploader-photo-button-add-1" value="<?= t('Добавить') ?>"/>
                            <!--<input type="button" id="uploader-photo-button-remove-1" value="Х" class="hide" style="color: #a00; font-weight: bold" />-->
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="mt10">
                    <div class="left mr10 aright" style="width: 128px; height: 200px;">
                        <?= t('В полный рост и желательно в купальнике') ?>
                    </div>
                    <div id="uploader-photo-preview-2" class="left mr10" style="width: 160px; height: 200px; border: 1px solid #ccc;">
                        <div class="acenter" style="margin-top: 88px;">
                            <input type="button" id="uploader-photo-button-add-2" value="<?= t('Добавить') ?>"/>
                            <!--<input type="button" id="uploader-photo-button-remove-0" value="Х" class="hide" style="color: #a00; font-weight: bold" />-->
                        </div>
                    </div>
                    <div id="uploader-photo-preview-3" class="left mr10" style="width: 160px; height: 200px; border: 1px solid #ccc;">
                        <div class="acenter" style="margin-top: 88px;">
                            <input type="button" id="uploader-photo-button-add-3" value="<?= t('Добавить') ?>"/>
                            <!--<input type="button" id="uploader-photo-button-remove-1" value="Х" class="hide" style="color: #a00; font-weight: bold" />-->
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="mt10">
                    <div class="left mr10 aright" style="width: 128px; height: 200px;">
                        <?= t('Любые две фотографии, на которых вы себе нравитесь') ?>
                    </div>
                    <div id="uploader-photo-preview-4" class="left mr10" style="width: 160px; height: 200px; border: 1px solid #ccc;">
                        <div class="acenter" style="margin-top: 88px;">
                            <input type="button" id="uploader-photo-button-add-4" value="<?= t('Добавить') ?>"/>
                            <!--<input type="button" id="uploader-photo-button-remove-0" value="Х" class="hide" style="color: #a00; font-weight: bold" />-->
                        </div>
                    </div>
                    <div id="uploader-photo-preview-5" class="left mr10" style="width: 160px; height: 200px; border: 1px solid #ccc;">
                        <div class="acenter" style="margin-top: 88px;">
                            <input type="button" id="uploader-photo-button-add-5" value="<?= t('Добавить') ?>"/>
                            <!--<input type="button" id="uploader-photo-button-remove-1" value="Х" class="hide" style="color: #a00; font-weight: bold" />-->
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

            <div class="mb10 p5 bold" style="background: #eee;">
                <?= t('О себе') ?>
            </div>

            <div class="mb10">
                <div class="left mr10 aright" style="width: 128px; height: 200px;">
                    <?= t('Интересы, хобби и другая информация, которую Вы хотите сообщить') ?>
                </div>
                <div class="left">
                    <textarea id="about_self" style="width: 400px; height: 200px;"></textarea>
                </div>
                <div class="clear"></div>
            </div>

            <div class="mb10 acenter">
                <!--<div class="left pt5 mr5 aright" style="width: 128px">&nbsp;</div>
                <div class="left">-->
                <input type="button" id="submit" value="<?= t('Регистрация') ?>"/>
                <!--</div>
                <div class="clear"></div>-->
            </div>
        </form>
    </div>

    <div id="registration-success" class="p20 hide">
        <p class="fs16"><?= t('Спасибо за регистрацию') ?>.</p>
        <p><?= t('В ближайшее время Ваша заявка будет рассмотрена модераторами') ?>.</p>
        <p><?= t('В случае утверждения заявки на указаный Вами адрес электронной почты будут высланы логин и пароль для доступа к Вашей анкете') ?>
            . </p>
        <div class="hide">
            <?= t('На Ваш email') ?>: <label id="email-label" class="bold"></label> <?= t('отправлено письмо') ?>.
        </div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function () {

        var uploaders = new Array();

        var uploaded_images = new Array();

        for (var i = 0; i < 6; i++) {
            uploaders.push(new AjaxUpload($('#uploader-photo-button-add-' + i), {
                'action': '/imgserve',
                'name': 'image',
                'data': {
                    'act': 'upload',
                    'key': 'image',
                    'uid': '0'
                },
                'autoSubmit': true,
                //								'responseType': 'json',
                'onSubmit': function (file, ext) {
                },
                'onComplete': function (file, response) {
                    var id = this._button.id.split('-')[4];

                    if (typeof uploaded_images[id] != 'undefined') {
                        $.post('/sign/registration', {
                            'act': 'remove_photo',
                            'pid': uploaded_images[id]
                        }, function (resp) {
                        }, 'json');
                    }

                    uploaded_images[id] = response;
                    $('#uploader-photo-preview-' + id).css('background', "url('/imgserve?pid=" + response + "&h=200') no-repeat center");
                    $('#uploader-photo-button-add-' + id).val('Заменить');
                    $('#uploader-photo-button-add-' + id) //+', #uploader-photo-button-remove-'+id
                        .show()
                        .css('opacity', '0')
                        .animate(
                            {
                                'opacity': '1'
                            },
                            1024
                        )
                        .parent()
                        .css('margin-top', '172px');
                }
            }));
        }

//		$("input[id^='uploader-photo-button-remove']").click(function(){
//			var id = $(this).attr('id').split('-')[4];
//			uploaded_images.splice(id, 1);
//			$('#uploader-photo-preview-'+id).css('background', "#fff");
//			$('#uploader-photo-button-remove-'+id).hide();
//			$('#uploader-photo-button-add-'+id)
//				.val('Добавить')
//				.parent()
//				.css('margin-top', '88px');
//		});

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
                    .html(this.name)
                $("#country").append($(option));
            });
            $("#country").val(<?=$profile['country']?>);
            $("#country").change();
        }, "json");

        var form = new Form("registration");
        form.onSuccess = function (data) {
            $("div[id^='msgErr']").hide();
            if (!data.success) {
                $("#msgErr-" + data.msgErr).show();
                var top = $("#msgErr-" + data.msgErr).position().top;
                $(window).scrollTop(top - 50);
//				console.log($("#msgErr-"+data.msgErr).position().top);
            } else {
                $("#registration-collection").hide();
                $("#registration-success").show();
//				$("#email-label").html(data.email);
                $(window).scrollTop(0);
            }
        }

        $("#registration #submit").click(function () {
            form.data.submit = 1;
            form.data.images = uploaded_images;
            form.send();
        });

    });
</script>
