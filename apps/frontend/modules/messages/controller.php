<?php

load::view_helper("ui", false);
load::action_helper('pager');
load::model('messages');

abstract class messages_controller extends frontend_controller
{
    protected  $authorized_access = true;
    
    public function execute()
	{
	    
	    $user_id = session::get_user_id();
	    $user_data = profile_peer::instance()->get_item($user_id);
	    
	    
	    
	    if(
		    ($user_data['can_write']) || 
		    (session::has_credential('admin') || session::has_credential('amu'))
		) {}
	    else {
		$this->redirect ('/');
	    }
	}
}
?>
