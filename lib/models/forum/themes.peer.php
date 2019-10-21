<?php
class forum_themes_peer extends db_peer_postgre
{

	protected $table_name = "forum_themes";
	protected $primary_key = "id";
    
	public static function instance()
	{
		return parent::instance("forum_themes_peer");
	}
}

?>
