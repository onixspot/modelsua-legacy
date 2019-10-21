<?php

load::app('modules/admin/controller');
load::model('user/user_albums');
load::model('user/user_photos');

class admin_test_action extends admin_controller
{
	public function execute()
	{
		parent::execute();
		
		$fashion = user_albums_peer::instance()->get_list(array('category' => 'fashion'));
		
		$this->fashions = array();
		$buffer = array();
		foreach($fashion as $id)
		{
			$item = user_albums_peer::instance()->get_item($id);
			$additional = unserialize($item['additional']);
			
			if( ! is_array($additional))
				continue;
			
			if($additional['journal_name'] == '' || in_array($additional['journal_name'], $buffer))
				continue;
			
			$buffer[] = $additional['journal_name'];
			
			$this->fashions[] = array(
				'id' => $item['id'],
				'name' => $additional['journal_name']
			);
		}
		
		$covers = user_albums_peer::instance()->get_list(array('category' => 'covers'));
		
		$this->covers = array();
		
		$buffer = array();
		foreach($covers as $id)
		{
			$item = user_albums_peer::instance()->get_item($id);
			$images = unserialize($item['images']);
			
			if( ! is_array($images))
				continue;
			
			foreach($images as $img)
			{
				$imgItem = user_photos_peer::instance()->get_item($img);
				
				$additional = unserialize($imgItem['additional']);
				
				if( ! is_array($additional))
					continue;

				if($additional['journal_name'] == '' || in_array($additional['journal_name'], $buffer))
					continue;
				
				$buffer[] = $additional['journal_name'];
				
				$this->covers[] = array(
					'id' => $img,
					'name' => $additional['journal_name']
				);
			}
		}
		
		$this->list = array_merge($this->fashions, $this->covers);
	}
}

?>
