<?php

define('APP_START_TS', microtime(true));

require_once getenv('FRAMEWORK_PATH').'/library/kernel/load.php';

/*
 * Init loader
 */
load::system('kernel/conf');

/*
 *  Load project configuration
 */
require_once './config/'.getenv('ENVIRONMENT').'.php';

require_once './apps/imgserve/index.php';

$imgserve = new imgserve();
$imgserve->execute();