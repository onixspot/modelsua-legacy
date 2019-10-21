<?php

class user_photos_peer extends db_peer_postgre
{
	protected $table_name = 'user_photos';
	protected $primary_key = 'id';

	const TYPE_CARD = 1;
        const TYPE_CARD_PREVIEW = 2;
	
	public static function instance() {
		return parent::instance('user_photos_peer');
	}
	
	public function get_item($primary_key)
	{
		$item = parent::get_item($primary_key);
		
		$item['_a'] = unserialize($item['additional']);
		if( ! is_array($item['_a']))
			$item['_a'] = array();
		
		return $item;
	}
	
	public function get_list($where = array(), $bind = array(), $order = array(), $limit = '', $cache_key = null)
	{
		$add_array = null;
		if(isset($where["additional"]))
		{
			$add_array = array();
			
			$list = db::get_rows("SELECT id, additional FROM ".$this->table_name." WHERE additional IS NOT NULL");
			
			foreach($list as $item)
			{
				$item["additional"] = unserialize($item["additional"]);
				
				if( ! is_array($item["additional"]))
					continue;
				
				foreach($where["additional"] as $key => $value)
				{
					if(isset($item["additional"][$key]) && $item["additional"][$key] == $value)
					{
						$add_array[] = $item["id"];
					}
				}
			}
			
			unset($where["additional"]);
		}
		
		if(is_array($add_array))
		{
			return array_values(array_intersect(parent::get_list($where, $bind, $order, $limit, $cache_key), $add_array));
		}
		
		return parent::get_list($where, $bind, $order, $limit, $cache_key);
	}
}
