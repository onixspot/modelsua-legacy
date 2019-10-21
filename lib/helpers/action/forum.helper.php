<?php
class forum_helper {
    
    public static function set_user( $id, $access = array() )
    {
	    session::set('forum_user_id', $id);
	    self::set_access($access);
    }
    
    public static function set_access( $access )
    {
	    $set = (array)session::get('forum_access');

	    foreach ( (array)$access as $item )
	    {
		    $set[] = $item;
	    }

	    session::set('forum_access', $set);
    }
    
    public static function unset_user( )
    {
	    session::set('forum_user_id', null);
	    self::set_access(array());
    }
    
    public static function get_user_id()
    {
	    $id = session::get('forum_user_id');
	    return ($id)?$id:0;
    }
    
    public static function is_authenticated()
    {
	    return (bool)self::get_user_id();
    }
    
    
    
}
?>
