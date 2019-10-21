<?php

class lists_peer extends db_peer_postgre
{
	protected $table_name = 'lists';
	
	public static function instance()
	{
		return parent::instance('lists_peer');
	}
	
	public function get_item($primary_key)
	{
		$item = parent::get_item($primary_key);
		
		$item['list'] = unserialize($item['list']);
		if( ! is_array($item['list']))
			$item['list'] = array();
		
		return $item;
	}
	
	public function update($data, $keys = null)
	{
		if( ! is_array($data['list']))
			$data['list'] = array();
		
		$data['list'] = serialize($data['list']);
		
		parent::update($data, $keys);
	}
	
	public function insert($data, $ignore_duplicate = false)
	{
		if( ! is_array($data['list']))
			$data['list'] = array();
		
		$data['list'] = serialize($data['list']);
		
		return parent::insert($data, $ignore_duplicate);
	}
}

?>
