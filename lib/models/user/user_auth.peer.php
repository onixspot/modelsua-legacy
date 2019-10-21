<?php

/**
 * Description of user_auth_peer
 *
 * @author Morozov Artem
 */
class user_auth_peer extends db_peer_postgre
{
	
	protected $table_name = "user_auth";
	protected $primary_key = "id";
	
	const new_faces = 1000;
	const perspective = 2000;
	const successful = 0;


	public static function instance()
	{
		return parent::instance("user_auth_peer");
	}
	
	public static function get_credentials($alias=0) {
	    $credentials =array(
		'admin'=>'Админ',
		'superadmin'=>'Суперадмин',
		'amu'=>'Администрация МodelsUA',
		'moderator'=>'Модератор'
	    );
	    return ($alias) ? (isset($credentials[$alias]) ? $credentials[$alias] : false) : $credentials;
	}
	
}

?>
