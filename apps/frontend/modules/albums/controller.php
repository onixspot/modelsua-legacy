<?php

load::model('date');
load::model('geo');
load::model('user/profile');
load::model('user/user_albums');
load::model('user/user_photos');
load::model("journals/journals");

abstract class albums_controller extends frontend_controller
{
	public function execute()
	{
		$this->uid = request::get_int('uid');
		
		if( ! $this->uid)
			$this->uid = session::get_user_id();
		
		if( ! $this->uid)
			$this->redirect('/');
		
		$this->profile = profile_peer::instance()->get_item($this->uid);
	}
}

?>
