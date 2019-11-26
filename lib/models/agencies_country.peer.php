<?php

class agencies_country_peer extends db_peer_postgre
{
    protected $table_name  = 'agencies_country';

    protected $primary_key = 'id';

    public static function instance($peer = 'agencies_country_peer')
    {
        return parent::instance($peer);
    }
}
