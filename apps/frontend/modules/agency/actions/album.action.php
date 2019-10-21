<?php

load::app('modules/agency/controller');

class agency_album_action extends agency_controller
{
	public function execute()
	{
		parent::execute();
		
		$act = request::get_string('act');
		
		if(in_array($act, array('add_photo', 'get_photos', 'remove_photo')))
		{
			$this->set_renderer('ajax');
			return $this->json['success'] = $this->$act();
		}
		
		if( ! $album_id = request::get_int('aid'))
			$this->redirect('/');
		
		if( ! $this->agency_album = agency_albums_peer::instance()->get_item($album_id))
			$this->redirect('/');
		
		if( ! $this->agency_id)
			$this->agency_id = $this->agency_album['agency_id'];
		
		$this->agency = agency_peer::instance()->get_item($this->agency_id);
	}
	
	public function add_photo()
	{
		$album_id = request::get_int('aid');
		
		if( ! $agency_album = agency_albums_peer::instance()->get_item($album_id))
			return false;
		
		$images_id = explode(',', request::get_string('uploadify-photos-list'));
		
		$data = array(
			'id' => $album_id,
			'images' => array_merge($images_id, $agency_album['images'])
		);
		
		agency_albums_peer::instance()->update($data);
		
		return true;
	}
	
	public function remove_photo()
	{
		$album_id = request::get_int('aid');
		
		if( ! $agency_album = agency_albums_peer::instance()->get_item($album_id))
			return false;
		
		$photo_id = request::get_int('pid');
		
		$images = array();
		foreach($agency_album['images'] as $pid)
		{
			if($pid != $photo_id)
				$images[] = $pid;
			else
				user_photos_peer::instance()->delete_item($pid);
		}
		
		$data = array(
			'id' => $album_id,
			'images' => $images
		);
		
		agency_albums_peer::instance()->update($data);
		
		return true;
	}
	
	public function get_photos()
	{
		$album_id = request::get_int('aid');
		
		if( ! $agency_album = agency_albums_peer::instance()->get_item($album_id))
			return false;
		
		$this->json['photos'] = $agency_album['images'];
		
		$this->json['additional'] = array();
		
		return true;
	}
}

?>
