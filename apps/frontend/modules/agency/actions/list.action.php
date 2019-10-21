<?php
load::app('modules/agency/controller');

class agency_list_action extends agency_controller
{
	public function execute()
	{
		parent::execute();
		
		$this->per_page = 25;
		$this->page = request::get_int('page',1);
		
		$list = agency_peer::instance()->get_list(array('page_active'=>true, 'public'=>true));
		$this->list = array();
		foreach($list as $id)
		{
			$sql = 'SELECT ud.pid FROM 
				user_auth AS ua 
			INNER JOIN user_data AS ud ON ua.id = ud.user_id 
			INNER JOIN user_agency AS ug ON ug.user_id = ud.user_id 
			WHERE ug.agency_id = '.$id.' AND ua.type = 2 AND ud.status > 20 AND ua.hidden = false AND ud.agency_rank >= 0 ORDER BY ud.agency_rank ASC';
			
			$this->list[$id] = db::get_cols($sql);
		}
		
		arsort($this->list);
		reset($this->list);
		
		$act = request::get('act');
		if(in_array($act, array('set_rank'))) {
			$this->set_renderer('ajax');
			$this->$act();
		}
		
	}
	
	private function set_rank() {
	    $data = request::get('data');
	    foreach ($this->list as $id=>$agency_id) 
		agency_peer::instance()->update(array('id'=>$agency_id, 'rank'=>(array_search($agency_id,$data)+(request::get_int('page',1)-1)*request::get_int('per_page'))        ));
	    
	    $this->json = ($data);
	}
}
?>
