<?php

load::app('modules/agency/controller');

class agency_albums_action extends agency_controller
{
	public function execute()
	{
		parent::execute();
		
		$this->agency = agency_peer::instance()->get_item($this->agency_id);
		
		$cond = array(
			'agency_id' => $this->agency_id
		);
		$this->albums_list = agency_albums_peer::instance()->get_list($cond, array(), array('id DESC'));
		
		$act = request::get_string('act');
		
		if(in_array($act, array('get_album', 'add_album', 'modify_album', 'remove_album')))
		{
			$this->set_renderer('ajax');
			return $this->json['success'] = $this->$act();
		}
	}
	
	public function get_album()
	{
		$aid = request::get_int('aid');
		
		if( ! $album = agency_albums_peer::instance()->get_item($aid))
			return false;
		
		$this->json['data'] = $album['additional'];
		
		return true;
	}
	
	public function add_album()
	{
		$data = array(
			'agency_id' => $this->agency_id,
			'user_id' => session::get_user_id(),
			'additional' => array(
				'name' => request::get_string('album_name'),
				'description' => request::get_string('album_description')
			)
		);
		
		$this->json['aid'] = agency_albums_peer::instance()->insert($data);
		
		return true;
	}
	
	public function modify_album()
	{
		$aid = request::get_int('aid');
		
		if( ! $album = agency_albums_peer::instance()->get_item($aid))
			return false;
		
		$data = array(
			'id' => $aid,
			'additional' => array(
				'name' => request::get_string('album_name'),
				'description' => request::get_string('album_description')
			)
		);
		
		agency_albums_peer::instance()->update($data);
		
		return true;
	}
	
	public function remove_album()
	{
		$aid = request::get_int('aid');
		
		if( ! $album = agency_albums_peer::instance()->get_item($aid))
			return false;
		
		foreach($album['images'] as $pid)
		{
			user_photos_peer::instance()->delete_item($pid);
		}
		
		agency_albums_peer::instance()->delete_item($aid);
		
		return true;
	}
}

?>
