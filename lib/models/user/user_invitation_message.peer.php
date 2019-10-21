<?php

class user_invitation_message_peer extends db_peer_postgre
{
    protected $table_name = 'user_invitation_messages';

    public static function instance($peer = 'user_invitation_message_peer')
    {
        return parent::instance($peer);
    }
}
