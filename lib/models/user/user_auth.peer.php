<?php

class user_auth_peer extends db_peer_postgre
{
    const successful  = 0;
    const new_faces   = 1000;
    const perspective = 2000;
    const legendary   = 3000;

    protected $table_name  = 'user_auth';

    protected $primary_key = 'id';

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
