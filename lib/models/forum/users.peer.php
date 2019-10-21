<?php
class forum_users_peer extends db_peer_postgre
{

	protected $table_name = "forum_users";
	protected $primary_key = "id";
    
	public static function instance()
	{
		return parent::instance("forum_users_peer");
	}
}

?>