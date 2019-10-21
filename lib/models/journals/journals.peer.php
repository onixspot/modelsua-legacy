<?php

load::model("user/profile");
load::model("user/user_photos");
load::model("user/user_albums");

class journals_peer extends db_peer_postgre
{
	protected $table_name = "journals";
	
	public static function instance()
	{
		return parent::instance("journals_peer");
	}
	
	public function get_item($primary_key)
	{
		if( ! $item = parent::get_item($primary_key))
			return false;
		
		$item["location"] = profile_peer::get_location($item);
		$item["contacts"] = unserialize($item["contacts"]);
		$item["covers"] = user_photos_peer::instance()->get_list(
			array("additional" => array("journal_id" => $item["id"]))
		);
		$item["fashion"] = user_albums_peer::instance()->get_list(
			array("additional" => array("journal_id" => $item["id"]))
		);
		
		return $item;
	}
	
	public function get_item_country($primary_key)
	{
		$sql = "SELECT country FROM ".$this->table_name." WHERE id = ".$primary_key;
		return db::get_scalar($sql);
	}
	
	public function update($data, $keys = null)
	{
		if(isset($data['contacts']) && is_array($data['contacts']))
			$data['contacts'] = serialize($data['contacts']);
		
		parent::update($data, $keys);
	}
	
	public function insert($data, $ignore_duplicate = false)
	{
		if(isset($data['contacts']) && is_array($data['contacts']))
			$data['contacts'] = serialize($data['contacts']);
		
		return parent::insert($data, $ignore_duplicate);
	}
}

?>
