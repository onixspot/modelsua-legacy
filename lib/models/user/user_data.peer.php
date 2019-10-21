<?php

/**
 * Description of user_data_peer
 *
 * @author Morozov Artem
 */

class user_data_peer extends db_peer_postgre
{
	
	protected $table_name = "user_data";
	protected $primary_key = "user_id";
	
	public static function instance()
	{
		return parent::instance("user_data_peer");
	}
}

?>
