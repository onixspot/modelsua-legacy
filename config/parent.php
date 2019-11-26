<?php

$debugMode = 0;

error_reporting($debugMode ? E_ALL : $debugMode);
@ini_set('display_errors', $debugMode);

if (isset($_GET['phpinfo'])) {
    phpinfo();
    die;
}

$config = [
    /*
     * Set root path of project
     */
    'project_root'                => getenv('ROOT_PATH'),

    /*
     * Debugging
     */
    'enable_log'                  => true,
    'enable_web_debug'            => (bool) $debugMode,
    'error_handler'               => 'debug', // debug | html
    'error_handler_module'        => 'ooops', // only for html_error handler
    'javascript_debug'            => false, // only if web debug is available
    'debug_email_address'         => '',
    'debug_emails'                => false, // Send emails to debug storage also

    /*
     * Static servers
     */
    'static_servers'              => 1,

    /*
    * File server
    */
    'file_server'                 => 'http://f.models.org.ua',

    /*
     * Hostname
     */
    'server'                      => 'models.org.ua',

    /*
     * i18n
     */
    'i18n'                        => [
        'enabled'      => true,
        'default_lang' => getenv('LANGUAGE'),
    ],

    /*
     * Images server.
     * Usage:
     *		array(
     *			type => array(
     *				size => "width:int x height:int",
     *				crop => value:boolean,
     *				exact => value:boolean
     *			)
     *		)
     */
    'image_types'                 => [
        'm'  => ['size' => '295x295', 'crop' => false, 'fs' => 0],
        'pp' => ['size' => '100x100', 'crop' => false, 'fs' => 0],
        'np' => ['size' => '344x488', 'crop' => false, 'fs' => 0],
    ],

    /*
     * File storage
     */
    'file_storage_path'           => getenv('ROOT_PATH').'/data/storage',

    /*
     * Database settings
     */
    'database_default_connection' => 'master',
    'databases'                   => [
        'master' => [
            'driver'   => 'pgsql',
            'host'     => 'modelsua.db',
            // 'user'     => 'modelsua',
            'user'     => 'postgres',
            // 'password' => 'eLHHb19TQlDhXvBHo2AiPo9Cmujv8xar',
            'password' => 'postgres',
            'dbname'   => 'modelsua',
        ],
    ],

    /*
     * Memcached
     */
    'mamcached'                   => [
        'host'       => 'mem.modelsua',
        'port'       => 11211,
        'expiration' => 60 * 30,
        'hash'       => 'modelsua',
    ],

    /*
     * Redis
     */
    'redis'                       => [
        'host'  => 'redis.modelsua',
        'port'  => 6379,
        'space' => 'modelsua',
    ],

    /*
     * Image magick
     */
    'imagemagick'                 => [
        'convert' => 'convert',
    ],

    /*
     * Client side settings
     */
    'static_hash'                 => 1,

    /*
     * Emails
     */
    'default_email'               => 'info@models.org.ua',
    'contuct_us_email'            => 'contact@models.org.ua',

    /*
     * Project specific settings
     */
    'project_name'                => 'ModelsUA',
    'default_module'              => 'home',
];

if (defined('APP_CONSOLE') && APP_CONSOLE === true) {
    return $config;
}

conf::set_from_array($config);
