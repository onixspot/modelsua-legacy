<?php


header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH');

define('APP_START_TS', microtime(true));
session_set_cookie_params(10800);

// header("Access-Control-Allow-Origin: http://models.org.ua");
// header("Access-Control-Allow-Credentials: true");
// header("Access-Control-Allow-Methods: GET, POST");
// header("Access-Control-Allow-Headers: Content-Type, *");

require_once getenv('FRAMEWORK_PATH').'/library/kernel/load.php';

/*
 * Init loader
 */
load::system('kernel/conf');

/*
 *  Load project configuration
 */
require_once './config/'.getenv('ENVIRONMENT').'.php';

/*
 * Init main application
 */
$sn = explode('.', $_SERVER['SERVER_NAME']);
if (empty($_GET['module']) && count($sn) > 3) {
    $_GET['subdomain'] = $sn[0];
}

require_once './apps/application.php';
$app = new project_application();
$app->execute('frontend');
