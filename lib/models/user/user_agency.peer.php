<?php

class user_agency_peer extends db_peer_postgre
{
	
	protected $table_name = "user_agency";
	protected $primary_key = "id";
	
	public static function instance() {
		return parent::instance("user_agency_peer");
	}
	
}

?>
