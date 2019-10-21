<?php
class ac_helper
{
	public static function ajax_search($fio='') {
	    return db::get_rows(self::build_search_query(self::prepare_search_params($fio)));
	}
	
	private function prepare_search_params($params) {
	    $min_sym = 2;
	    $params = explode(' ',$params);
	    foreach ($params as $k => $v) 
		if(trim($v) && (strlen(trim($v))>2 && $k==1)) 
		    $temp[] = trim($v);
	    $params = ($temp) ? array_slice($temp, 0,2) : $params;
	    return $params;
	    
	}
	
	public static function get_not_viewed() {
	    
	    $user_id = session::get_user_id();
	    $profile = profile_peer::instance()->get_item($user_id);
	    
	    if(session::has_credential('amu') || session::has_credential('admin')) {
		$private = (int)db::get_scalar("SELECT COUNT(id) FROM messages WHERE receiver=:user AND receiver_action=0",array('user'=>$profile['user_id']));
		$public = 0;
		$list = db::get_cols("SELECT id FROM messages WHERE receiver=-1 AND (receiver_action<2)");
		foreach ($list as $id) {
		    if(db_key::i()->exists('message_'.$id)) {
			$ids = unserialize(db_key::i()->get('message_'.$id));
			if(in_array(session::get_user_id(), $ids))
				$public++;
		    }
		}
	    	return 
		    array(
			'private'=>$private, 
			'public'=>$public, 
			'all'=>($public+$private)
			);
	    }
	    elseif($profile['can_write']) {
		return array(
		    'public'=>(int)db::get_scalar("SELECT COUNT(id) FROM messages WHERE receiver=:user AND receiver_action=0",array('user'=>$profile['user_id'])),
		    'private'=>0,
		    'all'=>(int)db::get_scalar("SELECT COUNT(id) FROM messages WHERE receiver=:user AND receiver_action=0",array('user'=>$profile['user_id']))
		    );
	    }
	    else {
		return array(
		    'public'=>0,
		    'private'=>0,
		    'all'=>0
		);
	    }
	}
	
	public static function set_messages_restrictions() {
	    
	    $user_id = session::get_user_id();
	    $profile = profile_peer::instance()->get_item($user_id);
	    
	    if(session::has_credential('amu')) {
		return ' ';
	    }
	    elseif(session::has_credential('admin')){
		return " AND a.id IN (SELECT id FROM user_auth WHERE credentials ILIKE '%admin%')";
	    }
	    elseif($profile['can_write']==1) {
		return " AND a.id IN (SELECT id FROM user_auth WHERE credentials ILIKE '%amu%')";
	    }
	    else {
		return " AND a.id<0";
	    }
	}


	private function build_search_query($params, $limit=10, $object='messages') {
	    
	    if(!$params[0]) return "SELECT * FROM user_data WHERE user_id<0";
	    $sql = "SELECT d.user_id, d.first_name, d.last_name, d.pid, d.ph_crop FROM user_data d JOIN user_auth a ON d.user_id=a.id WHERE 1=1";
	    
	    if(isset($params[1])) $sqladd = " AND ((d.first_name ILIKE '".$params[0]."%' AND d.last_name ILIKE '".$params[1]."%') OR (d.last_name ILIKE '".$params[0]."%' AND d.first_name ILIKE '".$params[1]."%'))";
	    else $sqladd = " AND (d.first_name ILIKE '%".$params[0]."%' OR d.last_name ILIKE '%".$params[0]."%')";
	    
	    switch($object) {
		case 'messages':
		    $sqladd .= self::set_messages_restrictions()." AND a.active=true LIMIT ".$limit;
		    break;
		default:
		    $sqladd .= " AND a.active=true LIMIT ".$limit;
		    break;
	    }
	    
	    return $sql.$sqladd;
	}
}
?>
