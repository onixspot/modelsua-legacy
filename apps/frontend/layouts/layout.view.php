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
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon"/>
</head>
<body>
<div class="container">

    <div class="grid-container">
        <div></div>
        <div>
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
        <div></div>
    </div>

    <!--<div style="position: absolute; width: 1801px; top: 0; margin: 0 auto 0 -398px; z-index: -2;">-->
    <!--    <div class="left" style="background: url(/left.png); width: 378px; height: 1138px;"></div>-->
    <!--    <div class="right" style="background: url(/right.png); width: 378px; height: 1138px;"></div>-->
    <!--    <div class="clear"></div>-->
    <!--</div>-->







</div>
<div class="footer">
    <?php include 'partials/footer.php' ?>
</div>

<script src="https://www.google.com/recaptcha/api.js?render=6LdO1MUUAAAAAPeYMtLpiZdoJVXwq-jEXhXpp6oM" type="text/javascript"></script>
<script type="text/javascript">
    grecaptcha.ready(function () {
        grecaptcha.execute('6LdO1MUUAAAAAPeYMtLpiZdoJVXwq-jEXhXpp6oM', {action: 'homepage'});
    });
</script>
</body>
</html>
