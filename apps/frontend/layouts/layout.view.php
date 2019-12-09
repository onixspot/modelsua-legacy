<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>

    <?= client_helper::get_meta() ?>
    <title><?= client_helper::get_title() ?></title>

    <script type="text/javascript" src="https://js.<?= conf::get('server') ?>/jquery-latest.min.js"></script>
    <script type="text/javascript" src="https://js.<?= conf::get('server') ?>/main.js"></script>
    <script type="text/javascript" src="https://js.<?= conf::get('server') ?>/erlte.js"></script>

    <link href="https://f.<?= conf::get('server') ?>/public/css/flag-icon.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link href="https://css.<?= conf::get('server') ?>/main.css" rel="stylesheet"/>
    <link href="https://css.<?= conf::get('server') ?>/erlte.css" rel="stylesheet"/>

    <?php include __DIR__.'/_js_static.php' ?>

    <script type="text/javascript" src="https://apis.google.com/js/plusone.js">
        {
            lang: 'uk'
        }
    </script>

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
</head>
<body style="overflow-x: hidden">
<div id="opaco" class="hide"></div>
<div class="canvas tahoma p5 pl20" style="min-height: 1000px; background: #fff;">

    <div style="position: absolute; width: 1801px; margin: 0px auto; top: 0px; margin-left: -398px; z-index: -2;">
        <div class="left" style="background: url(/left.png); width: 378px; height: 1138px;"></div>
        <div class="right" style="background: url(/right.png); width: 378px; height: 1138px;"></div>
        <div class="clear"></div>
    </div>

    <!--<div style="position: absolute; cursor: pointer; width: 42px; height: 286px; background-image: url('/banners/UFW.png'); margin-left: 1025px; margin-top: 140px;" onclick="window.open('https://fashionweek.ua/');"></div>-->

    <!--<div style="position: absolute; cursor: pointer; width: 42px; height: 115px; background: url('/banners/elle.png'); margin-left: 1025px; margin-top: 456px;" onclick="window.open('https://elle.com.ua/');""></div>-->

    <script type="text/javascript">
        $(function () {
            var sw = screen.width;
            var ww = $(window).width();
            var dw = 1020;
            var zoom = sw * 100 / ww;


            dw = dw * zoom / 100;

            var offset = (ww - dw) / 2;

            // if ($.browser.msie || $.browser.safari) {
            // $('.canvas, .footer_box').css('margin-left', (offset+'px'));
            //$(window).scrollLeft(ww/2);
            $('.footer').css('width', $(document).width() + 'px');
            // }
        });


    </script>

    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-30049596-1']);
        _gaq.push(['_setDomainName', 'modelsua.org']);
        _gaq.push(['_trackPageview']);

        (function () {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'https://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();

    </script>

    <!-- START HEAD -->
    <?php include 'partials/logo.php' ?>
    <!-- END HEAD -->

    <!-- START MENU -->
    <?php include 'partials/menu.php' ?>
    <!-- END MENU -->

    <div>
        <div class="content">
            <?php include $controller->get_template_path(); ?>
        </div>
    </div>

</div>
<div class="footer tahoma">
    <?php include 'partials/footer.php' ?>
</div>
<script src="https://www.google.com/recaptcha/api.js?render=6LdO1MUUAAAAAPeYMtLpiZdoJVXwq-jEXhXpp6oM"></script>
<script>
    grecaptcha.ready(function () {
        grecaptcha.execute('6LdO1MUUAAAAAPeYMtLpiZdoJVXwq-jEXhXpp6oM', {action: 'homepage'});
    });
</script>
</body>
</html>
