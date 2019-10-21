<?php

class content_views_peer extends db_peer_postgre
{
	
	protected $table_name = "content_views";
	protected $primary_key = "id";

        const TYPE_NEWS = 1;
        const TYPE_PAGES = 2;

	public static function instance()
	{
		return parent::instance("content_views_peer");
	}
        
        public static function modifyViewData($request) {
            
            $request['bInfo']['ip'] = self::getRealIpAddr();
            
            $userInfo = md5(serialize($request['bInfo']));
            
            
            
            $check = db::get_row("SELECT id,users,created_ts FROM content_views WHERE type=:type AND object_id=:oid LIMIT 1",array('type'=>$request['type'],'oid'=>$request['object_id']));
            
//            if((int)(time()-$check['created_ts'])>86400)
//                db::exec("DELETE FROM content_views WHERE id=:id",array('id'=>$check['id']));
//            else 
	    $sData = $check;
            
            if(($sData['users']))  {
                $usersData = unserialize($sData['users']);
                if(is_array($usersData)) {
                    if(in_array($userInfo, $usersData)) {
                            die(json_encode(array('success'=>false,'reason'=>'already visit'))); 
                    }
                    array_push ($usersData, $userInfo);
                    $sData['users'] = serialize($usersData);
                    $sData['views'] = count($usersData);
                    content_views_peer::instance()->update($sData);
                    $json = array('success'=>true,'reason'=>'updating...'); 
                }
            }
            else {
                content_views_peer::instance()->insert(array(
                    'type'=>  $request['type'],
                    'object_id'=>$request['object_id'],
                    'users'=>  serialize(array($userInfo)),
                    'views'=>1,
                    'created_ts'=>time()
                ));
                $json = array('success'=>1,'act'=>'insert');
            }
            return $json;
        }
        
        public static function getContentViews($object_id,$type) {
            return (int)db::get_scalar("SELECT views FROM content_views WHERE object_id=:oid AND type=:type",array('oid'=>$object_id,'type'=>$type));
        }
        
        public static function getRealIpAddr()
        {
            if (!empty($_SERVER['HTTP_CLIENT_IP']))   
              $ip=$_SERVER['HTTP_CLIENT_IP'];
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
              $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
            else
              $ip=$_SERVER['REMOTE_ADDR'];
            return $ip;
        }
}
?>
