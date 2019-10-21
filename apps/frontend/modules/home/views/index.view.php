<?php

/**
 * @var $successful
 */

?>
<div class="pt10 pb5" style="width: 1000px;">
    <div class="small-title left square_p pl10">
        <a href="/people"><?= t('Каталог моделей Украины') ?></a>
    </div>
    <?php if (!session::is_authenticated()) { ?>
        <div class="register-link right ">
            <a href="/sign/registration?set=member" class="cpurple"><?= t('Зарегистрироваться в каталоге') ?></a>
        </div>
        <div class="register-link mr30 right">
            <a href="/sign/registration" class="cpurple"><?= t('Хочу стать моделью') ?></a>
        </div>
    <?php } else { ?>
        <div class="register-link right">
            <a href="/invite" class="cpurple"><?= t('Пригласи подругу') ?></a>
        </div>
    <?php } ?>
    <div class="clear"></div>
</div>
<?php $most = $successful; ?>
<?php include 'partials/girls.php' ?>
<div class="clear"></div>
<div class="news-box" style="width: 1000px;">

    <div class="one-item mt5" style="margin-right: 57px">
        <div class="small-title square_p pl10 mb5">
            <a href="/news?type=1"><?= t('Новости') ?></a>
        </div>
        <div class="main-news-title">
            <a class="cblue" href="/news/view?id=<?= $news['id'] ?>">
                <div style="line-height: 40px; font-size: 50px;" class="left cpurple">
                    <?= mb_substr(trim($news['title']), 0, 1) ?>
                </div>
                <div style="width: 240px; margin-left: 3px;" class="left fs17 cgray bold">
                    <?= stripslashes(mb_substr(trim($news['title']), 1)) ?>
                </div>
                <div class="clear"></div>
            </a>
        </div>
        <div class="main-news-data-container">
            <div class="main-news-info">
                <span class="content_date"><?= date('d.m.Y', $news['created_ts']); ?>  </span>
                <!--<span><a class="comment_count" href="/">1 Comment</a></span>-->
            </div>
            <div class="main-news-content-box mt5" style="border-bottom: 0px;">
                <div class="news-text-box" style="width: 295px;">
                    <div>
                        <a href="/news/view?id=<?= $news['id'] ?>">
                            <img src="http://img.<?= conf::get('server') ?>/m/<?= $news['salt'].'.jpg' ?>" style="width: 295px; height: 390px;">
                        </a>
                    </div>
                    <div class="fs14 mt10 mb10 bold ucase acenter">
                        <a href='/news?type=1' class="cpurple underline"><?= t('Все новости') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="one-item mt5" style="margin-right: 57px">
        <div class="small-title square_p pl10 mb5">
            <a href="/news?type=2"><?= t('Публикации') ?></a>
        </div>
        <div class="main-news-title">
            <a class="cblue" href="/news/view?id=<?= $publication['id'] ?>">
                <div style="line-height: 40px; font-size: 50px;" class="left cpurple">
                    <?= mb_substr(trim($publication['title']), 0, 1) ?>
                </div>
                <div style="width: 235px; margin-left: 3px;" class="left fs17 cgray bold">
                    <?= stripslashes(mb_substr(trim($publication['title']), 1)) ?>
                </div>
                <div class="clear"></div>
            </a>
        </div>
        <div class="main-news-data-container">
            <div class="main-news-info">
                <span class="content_date"><?= date('d.m.Y', $publication['created_ts']); ?></span>
                <!--<span><a class="comment_count" href="/">1 Comment</a></span>-->
            </div>
            <div class="main-news-content-box mt5" style="border-bottom: 0px;">
                <div class="news-text-box" style="width: 295px;">
                    <div>
                        <a href="/news/view?id=<?= $publication['id'] ?>">
                            <img src="http://img.<?= conf::get('server') ?>/m/<?= $publication['salt'].'.jpg' ?>"
                                 style="width: 295px; height: 390px;">
                        </a>
                    </div>
                    <div class="fs14 mt10 mb10 bold ucase acenter">
                        <a href='/news?type=2' class="cpurple underline"><?= t('Все публикации') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="one-item mt5">
        <div class="small-title square_p pl10 mb5">
            <a href="/news?type=3"><?= t('Анонсы') ?></a>
        </div>
        <div class="main-news-title">
            <a class="cblue" href="/news/view?id=<?= $anons['id'] ?>">
                <div style="line-height: 40px; font-size: 50px;" class="left cpurple">
                    <?= mb_substr(trim($anons['title']), 0, 1) ?>
                </div>
                <div style="max-width: 240px; margin-left: 3px;" class="left fs17 cgray bold">
                    <?= stripslashes(mb_substr(trim($anons['title']), 1)) ?>
                </div>
                <div class="clear"></div>
            </a>
        </div>
        <div class="main-news-data-container">
            <div class="main-news-info">
                <span class="content_date"><?= date('d.m.Y', $anons['created_ts']); ?></span>
                <!--<span><a class="comment_count hide" href="/">1 Comment</a></span>-->
            </div>
            <div class="main-news-content-box mt5" style="border-bottom: 0px;">
                <div class="news-text-box" style="width: 295px;">
                    <div>
                        <a href="/news/view?id=<?= $anons['id'] ?>">
                            <img src="http://img.<?= conf::get('server') ?>/m/<?= $anons['salt'].'.jpg' ?>" style="width: 295px; height: 390px;">
                        </a>
                    </div>
                    <div class="fs14 mt10 mb10 bold ucase acenter">
                        <a href='/news?type=3' class="cpurple underline"><?= t('Все анонсы') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clear"></div>
</div>

<?php if (session::has_credential('admin')) { ?>
    <div class="small-title left square_p pl10 mb5 mt10">
        <a href="/"><?= t('Перспективные модели') ?></a>
    </div>
    <div class="clear"></div>
    <?php $most = $perspective; ?>
    <?php include 'partials/girls.php' ?>
    <div class="clear"></div>
<?php } ?>

<div class="top-row left hide">
    <div class="top-one-item left">
        <div class="big-title fs20 cgray re_<?= session::get('language', 'ru') ?>"/>
        <span><?= t('ейтинг') ?></span>
    </div>
    <div class="clear"></div>
    <span class="fs16 bold" style="text-transform: uppercase;"><?= t('Успешных моделей') ?></span>
    <div class="top-content-box" style="margin-top: 5px;">
        <?php
        $v = $successful[0];
        if ($v['ph_crop']) {
            $crop = unserialize($v['ph_crop']);
            $src  = "http://".conf::get('server')."/imgserve?pid="
                .$v['pid'];//."&w=".$crop['w']."&h=".$crop['h']."&x=".$crop['x']."&y=".$crop['y']."&z=crop";
        } else {
            $src = "http://".conf::get('server')."/no_image.png";
        }
        $fi = db::get_rows("SELECT first_name, last_name FROM user_data WHERE user_id=:uid", ['uid' => $v['id']]);

        ?>
        <div class="img-box left">
            <img src="<?= $src; ?>" style="height: 285px;"></img>
        </div>
        <div class="text-box left" style="margin-top: 100px; width: auto !important; margin-left: 5px;">
            <div class="top-title fs24 cpurple">
                <a href='/profile?id=<?= $v['id'] ?>'><?= $fi[0]['first_name'] ?><br/><b><?= $fi[0]['last_name'] ?></b></a>
            </div>
            <!--                <div class="top-text fs13 cblack">
                                ero. Vestibulum ultricies, erat eu commodo aliquet, nisi quam sollicitudin nunc, et vulputate nulla tortor non libero.
                            </div>-->
        </div>
    </div>
    <div class="top-detail-link hide arrow_p fs11 left bold" style="margin-top: 15px;">
        <a href='/rating' class="cpurple"><?= t('Смотреть рейтинг') ?></a>
    </div>
</div>


<div class="top-one-item left ml20">
    <div class="big-title fs20 cgray ka_<?= session::get('language', 'ru') ?>" style="width: auto;">
        <span><?= t('аталог') ?></span>
    </div>
    <div class="clear"></div>
    <span class="fs16 bold" style="text-transform: uppercase;"><?= t('украинских моделей') ?></span>
    <div class="mt5">
        <a href="/people"><img src="/ava.jpg" style="width: 285px;"/></a>
    </div>
    <div class="hide">
        <!--            <div class="fs12 cgray" style="font-style: italic">
                        Кто красивее?
                    </div>-->
        <div class="clear"></div>
        <table style="width: 100%; margin-top: 8px;">
            <tr>
                <td class="left">
                    <?php $crop = unserialize($model1['ph_crop']); ?>
                    <img src="http://<?= conf::get('server') ?>/imgserve?pid=<?= $model1['pid'] ?>&w=<?= $crop['w'] ?>&h=<?= $crop['h'] ?>&x=<?= $crop['x'] ?>&y=<?= $crop['y'] ?>&z=crop"
                         style="width: 145px;"></img>
                </td>
                <td class="acenter">
                    <?php $crop = unserialize($model2['ph_crop']); ?>
                    <img src="http://<?= conf::get('server') ?>/imgserve?pid=<?= $model2['pid'] ?>&w=<?= $crop['w'] ?>&h=<?= $crop['h'] ?>&x=<?= $crop['x'] ?>&y=<?= $crop['y'] ?>&z=crop"
                         style="width: 145px;"></img>
                </td>
            </tr>
            <tr class="hide">
                <td style="color: black;" class="fs14 acenter">
                    <?= $model1['first_name'] ?><br/><b style="text-transform: uppercase;"><?= $model1['last_name'] ?></b>
                </td>
                <td style="color: black;" class="fs14 acenter">
                    <?= $model2['first_name'] ?><br/><b style="text-transform: uppercase;"><?= $model2['last_name'] ?></b>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="acenter">

                    <center>
                        <div class="mt5 pointer left" id="vote_button" onClick="<?= ($check_vote) ? "window.location='/polls'" : 'show_info()' ?>   "
                             style="background: #B95383; width: 200px; color: white; margin-left: 60px;" class="pointer" onclick="vote_for('109')">
                            <img class="left hide" src="/rating_white.png">
                            <span class="fs16 left" style="line-height: 30px; paddin-bottom: 3px; margin-left: 35px">
                                        <?= t('Голосовать здесь') ?>
                                    </span>
                        </div>
                    </center>
                </td>
            </tr>
        </table>

    </div>
</div>


<div class="top-one-item left ml20">
    <div class="big-title fs20 cgray me_<?= session::get('language', 'ru') ?>"/>
    <span><?= t('одельный рейтинг') ?></span>
</div>
<div class="clear"></div>
<span class="fs16 bold" style="text-transform: uppercase;"><?= t('глянцевых журналов') ?></span>
<div class="top-content-box" style="margin-top: 5px;">
    <?php
    $winner         = voting_peer::get_rating(voting_peer::MODEL_RATING, 1);
    $winner_profile = profile_peer::instance()->get_item($winner[0]['user_id']);
    if ($winner_profile['ph_crop']) {
        $crop = unserialize($winner_profile['ph_crop']);
        $src  = "http://".conf::get('server')."/imgserve?pid=".$winner_profile['pid']."&w=".$crop['w']."&h=".$crop['h']."&x=".$crop['x']."&y="
            .$crop['y']."&z=crop";
    } else {
        $src = "http://".conf::get('server')."/no_image.png";
    }

    ?>
    <div class="img-box left pointer">
        <img src="/journal.jpg" style="height: 285px;"></img>
    </div>
    <div class="text-box left" style="margin-top: 105px; width: auto !important;">
        <div class="top-title fs24 cpurple pointer">
            <a href="http://elle.com.ua"><b>ELLE</b></a>
        </div>
        <!--                <div class="top-text fs13 cblack">
                            ero. Vestibulum ultricies, erat eu commodo aliquet, nisi quam sollicitudin nunc, et vulputate nulla tortor non libero.
                        </div>-->
    </div>
</div>
<div class="top-detail-link hide fs11 left  arrow_p bold" style="margin-top: 15px;">
    <a href='/rating' class="cpurple"><?= t('Смотреть рейтинг') ?></a>
</div>
</div>
</div>
<div class="clear"></div>

<style type="text/css">
    div.updates div.update {
        float: left;
        margin-right: 35px;
    }

    div.updates div.update:last-child {
        margin-right: 0px;
    }

    div.updates div.update div.photo {
        width: 220px;
        height: 295px;
    }

    div.updates div.update div.desc {
        margin-top: 0px;
        padding: 10px;
        background: rgba(0, 0, 0, 0.75);
        font-size: 12px;
        font-weight: bold;
    }
</style>

<script type="text/javascript">
    $(document).ready(function () {
        $("div.update").mouseenter(function (e) {
            var desc = $("div.desc", this);

            if (typeof $(desc).attr("h") == "undefined")
                $(desc).attr("h", $(desc).height());

            var height = $(desc).attr("h");
            var margin = parseInt(height) + parseInt($(desc).css("padding-top")) * 2;

            $(desc)
                .animate({
                    "margin-top": "-" + margin + "px"
                }, 256);
        })
            .mouseleave(function (e) {
                var desc = $("div.desc", this);

                $(desc)
                    .animate({
                        "margin-top": "0px"
                    }, 256);
            });

        $("div.update div.photo").click(function () {
            var photo = $(this);
            var category = $(this).attr("category");
            var step = parseInt($(this).attr("step")) + 1;
            $(this).attr("step", step);

            $.post("/home", {
                "act": "get_next_update",
                "category": category,
                "step": step
            }, function (response) {
                if (response.success) {
                    $(photo).css("background", "url('/imgserve?pid=" + response.image + "&h=295') center");
                    $(photo).next().html(response.user_name);
                }
            }, "json");
        });
    });
</script>

<div class="small-title square_p pl10 mb10 mt10">
    <a href="/updates"><?= t('Новые фотографии') ?></a>
</div>
<div class="mb20 updates" style="width: 100%;">
    <?php foreach ($boxes as $box) { ?>
        <?php rsort($box["images"]) ?>
        <div class="update">
            <div style="width: 220px; height: 295px; overflow: hidden;">
                <div class="photo" category="<?= $box["category"] ?>" step="0"
                     style="background: url('/imgserve?pid=<?= $box["images"][0] ?>&h=295') center;"></div>
                <?php $user_data = profile_peer::instance()->get_item($users[$box["images"][0]]); ?>
                <div class="desc"><a class="cwhite"
                                     href="/profile?id=<?= $users[$box["images"][0]] ?>"><?= profile_peer::get_name($user_data, "&fn") ?> <span
                                class="ucase"><?= profile_peer::get_name($user_data, "&ln") ?></span></a></div>
            </div>
            <div class="acenter ucase fs14 bold mt10">
                <a class="underline"
                   href="/updates?category=<?= $box["category"] == "advertisement" || $box["category"] == "catalogs" ? "adv" : $box["category"] ?>">
                    <?= user_albums_peer::get_category($box["category"] == "advertisement" || $box["category"] == "catalogs" ? "adv"
                        : $box["category"]) ?>
                </a>
            </div>
        </div>
    <?php } ?>
    <div class="clear"></div>
</div>


<?php if (session::has_credential('admin')) { ?>
    <div class="small-title left square_p pl10 mb5">
        <a href="/"><?= t('Новые лица') ?></a>
    </div>
    <div class="clear"></div>
    <?php $most = $new_faces ?>
    <?php include 'partials/girls.php' ?>
<?php } ?>
<div id="popup_image_box" class="hide" style="position:absolute; background: #fff; padding: 5px; -moz-box-shadow: 0 0 5px 1px #9CA1AE;">
    <a href="/"><img src="" style="width: 90px;"></a>
</div>
<script>
    jQuery(document).ready(function ($) {
        $('.image-box').find('img').hover(
            function () {
                $('#popup_image_box img').attr('src', $(this).attr('src'));

                $('#popup_image_box a').attr('href', $(this).parent().attr('href'));
                $('#popup_image_box').css('left', ($(this).position().left - 10)).css('top', ($(this).position().top - 7));
                $('#popup_image_box').removeClass('hide');
            },
            function () {}
        );
        $('#popup_image_box').hover(function () {}, function () {
            $(this).addClass('hide');
            $('#popup_image_box img').attr('src', '');
        });
    });

    function show_info() {
        Popup.show();
        Popup.setHtml('Вы уже за всех проголосовали<br/><div class="mt5 acenter"><a href="/polls/rating?type=1" class="mt5 fs12"><i>Смотреть рейтинг</i></a></div><input value="Закрыть" type="button" class="mt10" onClick="Popup.close(300)"/>');
        Popup.position();
    }
</script>
