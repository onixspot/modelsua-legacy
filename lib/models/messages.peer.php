<?
class messages_peer extends db_peer_postgre
{
        protected $table_name = 'messages';
        
	const VIEW_ACTION = 1;
	const DELETE_ACTION = 2;
	
	const PRIVATE_TYPE = 0;
	const ADMIN_TYPE = 1;
	/**
	 * @return messages_peer
	 */
	public static function instance() {
	    return parent::instance( 'messages_peer' );
	}
	
	private static function get_user_id($user_id=0) {
	    return ($user_id) ? $user_id : session::get_user_id();
	}
	
	public static function get_messages_by_users($message) {
	    if($message['type']==self::ADMIN_TYPE) {
		$user_id = ($message['receiver']!=-1) ? $message['receiver'] : $message['sender'];
		return db::get_cols("SELECT id FROM ".self::instance()->table_name." WHERE (sender=:user_id OR receiver=:user_id) AND type=1 AND (receiver_action<2 AND sender_action<2) ORDER BY created_ts ASC",array('user_id'=>$user_id));
	    }
	    else {
		return db::get_cols("SELECT id FROM ".self::instance()->table_name." WHERE ((receiver=:receiver AND sender=:sender) OR (receiver=:sender AND sender=:receiver)) AND (receiver_action<2 AND sender_action<2) AND type=0 ORDER BY created_ts ASC",array('sender'=>$message['sender'], 'receiver'=>$message['receiver']));
	    }
	}
	
	public static function get_not_viewed($user_id=0) {
	    return db::get_cols("SELECT id FROM ".self::instance()->table_name." WHERE (receiver=:user AND receiver_action=0)",array('user'=>self::get_user_id($user_id)));
	}
	
	public static function get_sended_admin_messages() {
	    
	}
}
?>