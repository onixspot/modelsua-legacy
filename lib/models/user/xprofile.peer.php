<?php

load::model('user/user_auth');
load::model('user/user_data');

class xprofile_peer extends db_peer_postgre
{
	public static function instance()
	{
		return parent::instance('xprofile_peer');
	}
	
	public function get_item($primary_key)
	{
		$user_auth_item = user_auth_peer::instance()->get_item($primary_key);
		
		if( ! is_array($user_auth_item))
			return false;
		
		$user_data_item = user_data_peer::instance()->get_item($primary_key);
		
		if( ! is_array($user_data_item))
			return false;
		
		return array_merge($user_auth_item, $user_data_item);
	}
}

?>
