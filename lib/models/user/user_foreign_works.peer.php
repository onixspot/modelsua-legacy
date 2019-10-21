<?php

load::view_helper('ui', false);

class user_foreign_works extends db_peer_postgre
{
	protected $table_name = 'user_foreign_works';
	protected $primary_key = 'id';

	public static function instance()
	{
		return parent::instance('user_foreign_works');
	}
	
	public function get_item($primary_key)
	{
		$item = parent::get_item($primary_key);
		
		$item['from_ts'] = strtotime($item['from_ts']);
		$item['to_ts'] = strtotime($item['to_ts']);
		
		$item['from_month'] = mb_strtolower(date_peer::instance()->get_month((int) date('m', $item['from_ts'])));
		$item['to_month'] = mb_strtolower(date_peer::instance()->get_month((int) date('m', $item['to_ts'])));
		
		$item['from_year'] = (int) date('Y', $item['from_ts']);
		$item['to_year'] = (int) date('Y', $item['to_ts']);
		
		return $item;
	}
}

?>
