<?php

class user_additional_peer extends db_peer_postgre
{
	
	protected $table_name = "user_additional";

	public static function instance()
	{
		return parent::instance("user_additional_peer");
	}
	
}

?>
