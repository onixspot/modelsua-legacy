<?php

class user_albums_peer extends db_peer_postgre
{
	
	protected $table_name = 'user_albums';
	protected $primary_key = 'id';
	
	private static $categories;
	
	public static function instance() {
		self::set_categories();
		return parent::instance('user_albums_peer');
	}
	
	public function get_list($where = array(), $bind = array(), $order = array(), $limit = '', $cache_key = null)
	{
		$img_array = null;
		if(isset($where["images"]))
		{
			$img_array = array();
			
			$list = db::get_rows("SELECT id, images FROM ".$this->table_name." WHERE images IS NOT NULL");
			
			foreach($list as $item)
			{
				$item["images"] = unserialize($item["images"]);
				
				if( ! is_array($item["images"]))
					continue;
				
				if(count(array_intersect($item["images"], $where["images"])) > 0)
					$img_array[] = $item["id"];
			}
			
			unset($where["images"]);
		}
		
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
		
		if(is_array($img_array))
		{
			return array_values(array_intersect(parent::get_list($where, $bind, $order, $limit, $cache_key), $img_array));
		}
		
		if(is_array($add_array))
		{
			return array_values(array_intersect(parent::get_list($where, $bind, $order, $limit, $cache_key), $add_array));
		}
		
		return parent::get_list($where, $bind, $order, $limit, $cache_key);
	}
	
	public function get_item($primary_key)
	{
		$item = parent::get_item($primary_key);
		
		$item['_a'] = unserialize($item['additional']);
		if( ! is_array($item['_a']))
			$item['_a'] = array();
		
		$item['_i'] = unserialize($item['images']);
		if( ! is_array($item['_i']))
			$item['_i'] = array();
		
		
		$fields = array('created', 'modified');
		foreach($item as $key => $value)
		{
			if(in_array($key, $fields))
			{
				$time = strtotime($value);
				$item[$key] = date("Y.m.d", $time);
			}
		}
		
		return $item;
	}
	
	public function update($data, $keys = null)
	{
		if( ! isset($data["modified"]))
			$data["modified"] = date('Y-m-d h:i:s');
		
		parent::update($data, $keys);
	}
	
	public function insert($data, $ignore_duplicate = false)
	{
		if( ! isset($data["modified"]))
			$data["modified"] = date('Y-m-d h:i:s');
		
		return parent::insert($data, $ignore_duplicate);
	}
	
	private static function set_categories() {
	    self::$categories = array(
		    'covers' => t('Обложки'),
		    'fashion' => t('Fashion stories'),
		    'defile' => t('Показы'),
			'adv' => t('Реклама'),
		    'advertisement' => t('Печатная реклама'),
		    'contest' => t('Конкурсы'),
		    'catalogs' => t('Календари, каталоги'),
		    'portfolio' => t('Портфолио')
	    );
	}
	
	public static function get_categories_keys()
	{
		return array_keys(self::$categories);
	}
	
	public static function get_categories()
	{
		return self::$categories;
	}
	
	public static function get_category($category_key)
	{
		return self::$categories[$category_key];
	}
	
}

?>
