<?php

load::view_helper("ui", false);

load::model("geo");
load::model("agency");
load::model("user/profile");
load::model('user/user_albums');
load::model('user/user_photos');

abstract class profile_controller extends frontend_controller
{
	
	public function execute()
	{
		$sn = explode('.', $_SERVER['SERVER_NAME']);
		if(count($sn) > 2 && ! request::get_int("id"))
		{
			if( ! $ud_list = user_data_peer::instance()->get_list(array('subdomain' => $sn[0])))
				$this->user_id = false;
			
			$ud = user_data_peer::instance()->get_item($ud_list[0]);
			$this->user_id = $ud['user_id'];
		}
		elseif($code = request::get('code')) {
		    $this->user_id = profile_peer::get_by_security($code);
		    if($this->user_id) {
			$profile = profile_peer::instance()->get_item($this->user_id);
			if(!$profile['email'])
			    $this->by_code = true;
		    }
		}
		else	
		    $this->user_id = request::get_int("id") ? request::get_int("id") : session::get_user_id();
		
		if( ! $this->user_id)
			$this->redirect ("http://".conf::get('server')."/");
		
		$this->profile = profile_peer::instance()->get_item($this->user_id);
		
		$learned_about = $this->profile['learned_about'];
		switch($learned_about)
		{
			case 'facebook':
				$learned_about = 'Facebook';
				break;
			
			case 'vkontakte':
				$learned_about = 'Vkontakte';
				break;
			
			case 'odnoklassniki':
				$learned_about = 'Odnoklassniki';
				break;
			
			case 'friends':
				$learned_about = 'Друзья, знакомые';
				break;
			
			case 'banners':
				$learned_about = 'Фирменные материалы, визитки';
				break;
			
			default:
				$learned_about = 'неизвестно';
				break;
		}
		
		$this->learned_about = $learned_about;
		
		if(!$this->by_code && $this->profile['hidden'] && ! $this->profile['active'] && ! session::has_credential('admin') && $this->user_id != session::get_user_id())
			$this->redirect ("http://".conf::get('server')."/");

		if(!$this->by_code && !session::is_authenticated() && $this->profile['hidden'])
			$this->redirect ("http://".conf::get('server')."/");
		
//		if($this->profile['approve'] < 1)
//			$this->redirect ("http://".conf::get('server')."/");
		
		$sys_albums = array('covers', 'portfolio');
		foreach($sys_albums as $album)
		{
			if( ! user_albums_peer::instance()->get_list(array('user_id' => $this->user_id, 'category' => $album)))
			{
				user_albums_peer::instance()->insert(array(
					'user_id' => $this->user_id,
					'category' => $album,
					'additional' => serialize(array()),
					'images' => serialize(array()),
				));
			}
		}

	}
	
}

?>
