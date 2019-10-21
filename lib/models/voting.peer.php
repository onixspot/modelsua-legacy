<?
class voting_peer extends db_peer_postgre
{
        protected $table_name = 'voting';
        
        const VOTING_LIMIT = 10;
        const MODEL_RATING = 1;
        
	/**
	 * @return voting_peer
	 */
	public static function instance()
	{
		return parent::instance( 'voting_peer' );
	}
        
        public static function get_query_params($user_id=0) {
            $bind = array();
            $sqladd = '';
	    $uid = ((int)$user_id) ? $user_id : session::get_user_id();
            if($uid) {
                $sqladd = ' AND user_id=:uid';
                $bind['uid']=$uid;
            }
            else {
                $sqladd = ' AND ip=:ip AND user_id=0';
                $bind['ip'] = self::getRealIpAddr();
            }
            return array('sqladd'=>$sqladd,'bind'=>$bind);
        }

	public static function getModelsVoteList($by_user=false) {
	    $bind = array();
	    $sql = "SELECT  d.user_id,
			    d.pid,
			    d.ph_crop,
			    d.first_name,
			    d.last_name
			FROM user_data d 
			JOIN user_auth a 
			ON d.user_id=a.id 
			WHERE  
			    pid IS NOT NULL AND 
			    ph_crop IS NOT NULL AND 
			    a.hidden = false AND
			    d.status>20 AND d.status<30";
	     if(filter_var($by_user,FILTER_VALIDATE_INT)) {
		 $params = self::get_query_params($by_user);
		 $sql .= " AND d.user_id NOT IN (SELECT object_id FROM voting WHERE type=1 ".$params['sqladd'].")";
		 $bind = array_merge($bind,$params['bind']);
	     }
	     return db::get_rows($sql,$bind);
	    
	}

        public static function get_vote_times() {
            $params = self::get_query_params();
            return db::get_scalar("SELECT COUNT(id) FROM voting WHERE 1=1".$params['sqladd'],$params['bind']);;
        }
	
	public static function isPublicModel($user_id=0) {
	    if(!$user_id) return false;
	    $profile = profile_peer::instance()->get_item($user_id);
	    return ($profile['status']>20 && $profile['status']<30 && !$profile['hidden']);
	}
	
        public static function can_vote($oid=0,$type=0) {
            $params = self::get_query_params();
	    if($type == self::MODEL_RATING && !self::isPublicModel($oid)) return false;
            return !(db::get_scalar("SELECT COUNT(id) FROM voting WHERE object_id=:oid AND type=:type".$params['sqladd'],array_merge(array('oid'=>$oid,'type'=>$type),$params['bind']))); 
        }
	
        public static function vote($oid=0,$type=0,$votes=1) {
            if(!$oid|!$type|!$votes) return false;
            $insert_data = array(
                                    'object_id'=>$oid,
                                    'type'=>$type,
                                    'votes'=>$votes,
                                    'ip' => self::getRealIpAddr(),
                                    'user_id' => session::get_user_id (),
				    'date_ts'=>time()
                                );
            if(!self::can_vote($oid, $type))
                    return false;
            self::instance()->insert($insert_data);
            return true;
        }
        
        public static function getRealIpAddr()
        {
            if (!empty($_SERVER['HTTP_CLIENT_IP']))   
            {
              $ip=$_SERVER['HTTP_CLIENT_IP'];
            }
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
            {
              $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else
            {
              $ip=$_SERVER['REMOTE_ADDR'];
            }
            return $ip;
        }
        
        public static function calculateVotes($oid=0, $type=0) {
            return (int)db::get_scalar("SELECT SUM(votes) FROM voting WHERE object_id=:oid AND type=:type",array('oid'=>$oid,'type'=>$type));
        }
        
		public static function get_rating_position($user_id, $type = self::MODEL_RATING)
		{
			$sql = 'SELECT object_id FROM voting WHERE type=:type GROUP BY object_id ORDER BY SUM(votes) DESC';
			$arr = db::get_rows($sql, array('type' => $type));
			
			$pos = 1;
			foreach($arr as $item)
			{
				if($item['object_id'] == $user_id)
					break;
				$pos++;
			}
			
			return $pos;
		}
		
        public static function get_rating($type=0, $limit=false) {
            if(!$type) return false;
            $sqladd = (filter_var($limit,FILTER_VALIDATE_INT)) ? ' LIMIT '.$limit : '';
            return db::get_rows("SELECT object_id as user_id,SUM(votes) as sum FROM voting WHERE type=:type GROUP BY object_id ORDER BY SUM(votes) DESC".$sqladd,array('type'=>  $type));
        }
	
	public static function getVoters($type=0, $oid=0) {
	    if(!$type || !$oid) return false;
	    return db::get_cols("SELECT user_id FROM voting WHERE type=:type AND object_id=:oid",array('type'=>$type,'oid'=>$oid));
	    
	}
	
	public static function getVoteObjByUser($type=0, $user_id=0) {
	    if(!$type) return false;
	    $params = self::get_query_params ($user_id);
	    return db::get_cols("SELECT object_id FROM voting WHERE type=:type ".$params['sqladd'],array_merge(array('type'=>$type),$params['bind']));
	}
}
?>
