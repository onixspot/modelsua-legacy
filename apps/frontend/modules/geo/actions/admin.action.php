<?php

load::app('modules/geo/controller');

class geo_admin_action extends geo_controller
{
	public function execute()
	{
		parent::execute();
		
		$act = request::get_string("act");
		
		$table = $act;
		$field = $act;
		
		$state = 0;
		
		if($act == "cities")
		{
			$state = 1;
		}
		
		if($act == "districts")
		{
			$table = "cities";
			$field = "districts";
			$state = 2;
		}
		
		$list = array();
		
		foreach(geo_peer::instance()->get_list_by_table($table) as $item)
		{
			if($state == 1)
			{
				if($item["city_id"] >= 15789520)
					continue;
			}
			elseif($state == 2)
			{
				if( ! ($item["city_id"] >= 15789520))
					continue;
			}
			
			$list[] = $item;
		}
		
		$this->json[$field] = $list;
	}
}
