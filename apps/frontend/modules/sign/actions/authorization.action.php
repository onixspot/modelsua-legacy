<?php

/**
 * Description of sign_authorization_action
 *
 * @author Morozov Artem
 */

load::model("user/profile");

class sign_authorization_action extends frontend_controller
{
	protected $authorized_access = false;
	public function execute()
	{
		if(request::get_string('act') == 'redirect')
			$this->redirect ('/');
		
		$this->set_renderer("ajax");
		$this->json = array("success" => true);
		
		if( ! profile_peer::instance()->is_exists(array("email" => request::get("login"))))
		{
			$this->json["success"] = false;
			return false;
		}
		
		$user_id = user_auth_peer::instance()->get_list(array("email" => request::get("login")));
		$profile = profile_peer::instance()->get_item($user_id[0]);
		
		
		
		if($profile["password"] != md5(request::get("password")))
			return $this->json["success"] = false;
		
		$credentials = unserialize($profile["credentials"]);
		if( ! is_array($credentials))
			$credentials = array();
                
		if(!$profile['active']) {
		    $max = db::exec("UPDATE user_data SET rank=((SELECT MAX(rank) FROM user_data)+1) WHERE user_id=:uid",array('uid'=>$profile['user_id']));
		    user_auth_peer::instance()->update(array('id'=>$profile['user_id'],'active'=>TRUE,'activated_ts'=>time()));
		}
		
                session::set_user_id($profile["user_id"], unserialize($profile["credentials"]));
		cookie::set('uid', $profile["user_id"], time()+60*60*24*30, '/', conf::get('server'));
		
	}
	
}

?>
