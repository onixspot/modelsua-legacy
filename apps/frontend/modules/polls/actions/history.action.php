<?php
class polls_history_action extends frontend_controller {
    
    public function execute() {
	load::model('voting');
	if(!session::has_credential('admin'))
		$this->redirect ('/');
	$this->vuser_id = request::get_int('user_id') ? request::get_int('user_id') : session::get_user_id();
	
	$this->vuser_data = user_data_peer::instance()->get_item($this->vuser_id);
//	var_dump($this->user_id);
//	var_dump($this->user_data);
//	exit;
	$this->list = voting_peer::getVoteObjByUser(voting_peer::MODEL_RATING, $this->vuser_id);
    }
    
}
?>
