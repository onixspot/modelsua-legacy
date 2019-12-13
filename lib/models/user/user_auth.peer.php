<?php

class user_auth_peer extends db_peer_postgre
{
    const SUCCESSFUL  = 0;
    const NEW_FACES   = 1000;
    const PERSPECTIVE = 2000;
    const LEGENDARY   = 3000;

    const STATUS_TYPES = [
        self::SUCCESSFUL  => 'successful',
        self::NEW_FACES   => 'new_faces',
        self::PERSPECTIVE => 'perspective',
        self::LEGENDARY   => 'legendary',
    ];

    protected $table_name = 'user_auth';

    protected $primary_key = 'id';

    private $context;

    /**
     * @param string $peer
     *
     * @return \db_peer|object|\user_auth_peer
     */
    public static function instance($peer = 'user_auth_peer')
    {
        return parent::instance($peer);
    }

    public static function get_credentials($alias = 0)
    {
        $credentials = [
            'admin'      => 'Админ',
            'superadmin' => 'Суперадмин',
            'amu'        => 'Администрация МodelsUA',
            'moderator'  => 'Модератор',
        ];

        return ($alias) ? (isset($credentials[$alias]) ? $credentials[$alias] : false) : $credentials;
    }


}
