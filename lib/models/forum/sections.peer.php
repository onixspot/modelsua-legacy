<?php
class forum_sections_peer extends db_peer_postgre
{

	protected $table_name = "forum_sections";
	protected $primary_key = "id";
    
	public static function instance()
	{
		return parent::instance("forum_sections_peer");
	}
}

?>
