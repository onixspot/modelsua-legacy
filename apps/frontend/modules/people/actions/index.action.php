<?php

load::app("modules/people/controller");

class people_index_action extends people_controller
{

	public function execute()
	{
		parent::execute();
		
		$act = request::get('act');
		if(in_array($act, array('set_limit', 'set_rank')))
		{
			$this->set_renderer('ajax');
			return $this->json['success'] = $this->$act();
		}
		
		switch(request::get('status')){
		    case 'new-face':
			$sqladd = ' AND show_on_main>'.user_auth_peer::new_faces.' AND show_on_main<'.user_auth_peer::perspective;
			break;
		    case 'perspective':
			$sqladd = ' AND show_on_main>'.user_auth_peer::perspective;
			break;
		    case 'successful':
			$sqladd = ' AND show_on_main>'.user_auth_peer::successful.' AND show_on_main<'.user_auth_peer::new_faces;
			break;
		    default:
			$sqladd = '';
			break;
		}
		
		
		$this->filter = request::get("filter");
		if( ! $this->filter)
			$this->filter = "model";

		$this->type_key = profile_peer::get_type_key($this->filter);
		$sql = "SELECT id FROM user_auth WHERE type=:type AND hidden=:hidden AND del=:del AND reserv=:reserv";
		$coditional = array(
				"type" => $this->type_key,
//				'active' => true,
				"hidden" => false,
				"del"=>0,
				"reserv"=>0
		);	
		if(session::has_credential('admin')) {
			$sql = "SELECT id FROM user_auth WHERE type=:type AND del=:del AND hidden=:hidden AND reserv=:reserv";
			$coditional = array(
					"type" => $this->type_key,
					"hidden" => 0,
					"del"=>0,
					"reserv"=>0
			);
		}
//		echo "<pre>";
//		var_dump($sql);
//		var_dump($coditional);
//		exit;
		$ua_list = db::get_cols($sql.$sqladd,$coditional);
		$ud_list = user_data_peer::instance()->get_list(array(), array(), array('rank ASC'));
		
		$hold = session::get('hold_people', array());
		
		$this->hold_people = $hold;
		
		$this->list = array();
		foreach($ud_list as $ud_item)
			if(in_array($ud_item, $ua_list) && !in_array($ud_item, $hold))
				$this->list[] = $ud_item;
		
		$page = request::get("page");
		//$this->limit = session::get('people.limit');
		$this->limit = 24;
		/*if( ! $this->limit)
			$this->limit = 10;*/
		$this->pager = pager_helper::get_pager($this->list, $page, $this->limit);
		$this->count_members = $this->pager->get_total();
		$this->count_pages = $this->pager->get_pages();
		$this->list = $this->pager->get_list();
		
	}
	
	private function set_limit()
	{
		$limit = request::get_int('limit');
		session::set('people.limit', $limit);
		return $limit;
	}
	
	private function set_rank()
	{
		if( ! session::has_credential('admin'))
			return false;
		
		$data = request::get_array('data');
		$hold = request::get_array('hold');
		
		session::set('hold_people', $hold);
		
		foreach($data as $_data)
		{
			user_data_peer::instance()->update($_data);
//			$this->json['data'][] = $_data;
		}
		
		return true;
	}
}

?>
