<?php

class agency_peer extends db_peer_postgre
{
	protected $table_name = "agency";
	protected $primary_key = "id";

	public static function instance()
	{
		return parent::instance("agency_peer");
	}
	
	public function get_item($primary_key)
	{
		$item = parent::get_item($primary_key);
		
		$item['contacts'] = unserialize($item['contacts']);
		if( ! is_array($item))
			$item['contacts'] = array();
		
		return $item;
	}
	
	public function update($data, $keys = null)
	{
		if(isset($data['contacts']) && is_array($data['contacts']))
			$data['contacts'] = serialize($data['contacts']);
		
		parent::update($data, $keys);
	}


	public function is_exists($agency_name)
	{
		$sql = "SELECT * FROM " . $this->table_name . " WHERE name = '" . $agency_name . "' LIMIT 1;";
		return db::get_row($sql);
	}

	public static function get_agency($id = false)
	{
		$data = db::get_rows("SELECT * FROM agency");
		if (!empty($data))
		{
			foreach ($data as $k => $v)
			{
				$ret[$v['id']] = $v['name'];
			}
			return ($id) ? (isset($ret[$id]) ? $ret[$id] : false) : $ret;
		}
		return false;
	}

}

?>
