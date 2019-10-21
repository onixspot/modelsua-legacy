<?php

load::app('modules/adminka/controller');
load::model('lists');

class adminka_list_management_action extends adminka_controller
{
	public function execute()
	{
		parent::execute();
		
		$this->set_template('index');
		$this->adminka["frame"] = 'list_management';
		
		$act = request::get_string('act');
		$this->adminka["act"] = $act;
		
		if(in_array($act, array('add', 'modify', 'remove')))
		{
			$this->set_renderer('ajax');
			return $this->json['success'] = $this->$act;
		}
		
		$this->list = lists_peer::instance()->get_list();
	}
	
	private function add()
	{
		
	}
	
	private function modify()
	{
		
	}
	
	private function remove()
	{
		
	}
}

?>
