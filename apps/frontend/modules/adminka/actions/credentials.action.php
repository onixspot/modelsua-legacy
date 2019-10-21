<?php

load::app("modules/adminka/controller");

class adminka_credentials_action extends adminka_controller
{
	
	public function execute()
	{
		parent::execute();
		$this->user_id = request::get_int('user_id');
		
		$this->admin_list = db::get_rows('SELECT a.id, d.first_name, d.pid,d.ph_crop, d.last_name, a.credentials FROM user_auth a JOIN user_data d ON a.id=d.user_id WHERE a.credentials<>:cred',array('cred'=>serialize(array())));
		if(request::get('submit')) {
		    $this->set_renderer('ajax');
		    $act = request::get('act');
		    
		    switch($act) {
			case 'credentials_change':
			    $set = request::get_int('set');
			    $credential = request::get('value');
			    $user_auth = user_auth_peer::instance()->get_item($this->user_id);
			    if($user_auth && in_array($credential, array_keys(user_auth_peer::get_credentials()))) {
				$credentials = unserialize($user_auth['credentials']);
				if($set && !in_array($credential, $credentials)) 
				{
				    $this->json = array('success'=>'push');
				    array_push ($credentials, $credential);
				}
				elseif(!$set) {
				    unset($credentials[array_search($credential, $credentials)]);
				    $this->json = array('success'=>'unset');
				}
				$user_auth['credentials'] = serialize($credentials);
				user_auth_peer::instance()->update($user_auth);
			    }
			    else 
				$this->json = array('success'=>0,'reason'=>'Input data error');
			    break;
			case 'add':
			    $user_auth = user_auth_peer::instance()->get_item($this->user_id);
			    $credentials = unserialize($user_auth['credentials']);
			    if(!$user_auth) 
				$this->json = array('success'=>0,'reason'=>'Пользователь не существует');
			    elseif(empty($credentials)) {
				$user_auth['credentials'] = serialize(array('admin'));
				user_auth_peer::instance()->update($user_auth);
				$this->json = array('success'=>1);
			    }
			    else 
				$this->json = array('success'=>0,'reason'=>'Этот пользователь уже в списке');
			    break;
			default :
			    $this->json = array('success'=>0);
			    break;
		    }
		}
		
	}
	
}

?>
