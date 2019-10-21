<?php

class agency_albums_peer extends db_peer_postgre
{
	protected $table_name = 'agency_albums';
	
	public static function instance()
	{
		return parent::instance('agency_albums_peer');
	}
	
	public function get_item($primary_key)
	{
		$item = parent::get_item($primary_key);
		
		$fields = array('images', 'additional');
		foreach($fields as $field)
		{
			$item[$field] = unserialize($item[$field]);
			if( ! is_array($item[$field]))
				$item[$field] = array();
		}
		
		return $item;
	}
	
	public function insert($data, $ignore_duplicate = false)
	{
		if(isset($data['images']))
			$data['images'] = serialize($data['images']);
		
		if(isset($data['additional']))
			$data['additional'] = serialize($data['additional']);
		
		return parent::insert($data, $ignore_duplicate);
	}

	public function update($data, $keys = null)
	{
		if(isset($data['images']))
			$data['images'] = serialize($data['images']);
		
		if(isset($data['additional']))
			$data['additional'] = serialize($data['additional']);
		
		parent::update($data, $keys);
	}
}

?>
