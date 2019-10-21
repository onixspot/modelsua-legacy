<?php

class user_params_peer extends db_peer_postgre
{
	
	protected $table_name = "user_params";
	protected $primary_key = "id";
	
	public static function instance()
	{
		return parent::instance("user_params_peer");
	}
	
}

?>
