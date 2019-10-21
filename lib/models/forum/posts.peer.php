<?php
class forum_posts_peer extends db_peer_postgre
{

	protected $table_name = "forum_posts";
	protected $primary_key = "id";
    
	private static $per_page=20;
	
	public static function instance()
	{
		return parent::instance("forum_posts_peer");
	}
	
	public static function get_per_page() {
	    return self::$per_page;
	}
	
	public static function set_per_page($per_page) {
	    self::$per_page = $per_page;
	}
	
	public static function get_user_posts_count($user_id=0) {
	    return db::get_scalar("SELECT COUNT(".self::instance()->primary_key.") FROM ".self::instance()->table_name." WHERE user_id=:uid",array('uid'=>$user_id));
	}
	
	public static function get_posts_count($topic_id=0) {
	    return db::get_scalar("SELECT COUNT(".self::instance()->primary_key.") FROM ".self::instance()->table_name." WHERE topic_id=:tid",array('tid'=>$topic_id));
	}
	
	public static function get_last_post($topic_id=0) {
	    return db::get_row("SELECT * FROM ".self::instance()->table_name." WHERE topic_id=:tid",array('tid'=>$topic_id ));
	}
}

?>
