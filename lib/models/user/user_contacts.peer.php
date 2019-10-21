<?php

class user_contacts_peer extends db_peer_postgre
{
	
	protected $table_name = "user_contacts";
	protected $primary_key = "id";

	public static function instance()
	{
		return parent::instance("user_contacts_peer");
	}
	
}

?>
