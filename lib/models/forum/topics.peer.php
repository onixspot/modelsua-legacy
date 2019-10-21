<?php
class forum_topics_peer extends db_peer_postgre
{

	protected $table_name = "forum_topics";
	protected $primary_key = "id";
    
	public static function instance()
	{
		return parent::instance("forum_topics_peer");
	}
}

?>
