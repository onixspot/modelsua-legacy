<?php

load::app("modules/geo/controller");

class geo_index_action extends geo_controller
{
	
	public function execute()
	{
		parent::execute();
		
		$act = request::get_string("act");
		
		switch($act)
		{
			case "get_cities":
				$cond = array('region_id' => request::get_int('region_id'));
				if( ! request::get_int('region_id'))
					$cond = array('country_id' => request::get_int('country_id'));
				
				$options = array();
				if(request::get_int('big_cities') > 0)
					$options['big-cities'] = true;
				
				$this->json['cities'] = geo_peer::instance()->get_cities($cond, $options);
				break;
			
			case "get_regions":
				$cond = array(
					'country_id' => request::get_int('country_id')
				);
				$this->json['regions'] = geo_peer::instance()->get_regions($cond);
				break;
			
			case "get_countries":
			default:
				$this->json['countries'] = geo_peer::instance()->get_countries($by);
				break;
		}
	}
	
}

?>
