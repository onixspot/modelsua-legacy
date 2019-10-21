<?php

load::action_helper('pager');
load::model('agency');
load::model('agency/agency_albums');
load::model('user/profile');
load::model('user/user_photos');
load::model('user/user_albums');

abstract class agency_controller extends frontend_controller
{
	public function execute()
	{
		$this->agency_id = request::get_int('id');
		
		$this->show = request::get_string('show');
		
		$profile = profile_peer::instance()->get_item(session::get_user_id());
		
		$this->access = (($profile['manager_agency_id'] > 0 && $profile['manager_agency_id'] == $this->agency_id) || session::has_credential('admin')) ? true : false;
	}
}

?>
