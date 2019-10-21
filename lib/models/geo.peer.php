<?php

class geo_peer extends db_peer_postgre
{
	protected $table_name = '';
	
	public static function instance()
	{
		return parent::instance('geo_peer');
	}
	
	public function get_list_by_table($table)
	{
		$this->table_name = $table;
		
		$list = array();
		
		foreach($this->get_list() as $id)
			$list[] = $this->get_item($id, true);
		
		return $list;
	}
	
	public function get_item_by_table($id, $table)
	{
		$this->table_name = $table;
		return $this->get_item($id);
	}
	
	/* COUNTRIES */
	public function get_country($country_id)
	{
		$this->table_name = 'countries';
		
		$cond = array(
			'country_id' => $country_id
		);
		
		$countries_id = $this->get_list($cond, array(), array(), 1);
		
		$country = $this->get_item($countries_id[0]);
		
		$ret['name'] = $country['name_'.session::get('language','ru')];
		
		return $ret['name'];
	}
	
	public function get_countries($cond = array())
	{
		$this->table_name = 'countries';
		
		if( ! isset($cond['hidden']))
			$cond['hidden'] = 'false';
		
		$countries_id = $this->get_list($cond, array(), array('id ASC'));
		
		$countries = array();
		foreach($countries_id as $country_id)
		{
			$countries[] = $this->get_item($country_id);
			$countries[count($countries)-1]['name'] = $countries[count($countries)-1]['name_'.session::get('language','ru')];
		}
		
		return $countries;
	}
	
	public function set_country($country)
	{
		$this->table_name = 'countries';
		
		$data = array(
			'country_id' => $country['country_id'],
			'name' => $country['name'],
		);
		
		if(is_bool($country['hidden']))
			$data['hidden'] = $country['hidden'];
		
		return $this->insert($data);
	}
	
	public function set_countries($countries)
	{
		$keys = array();
		foreach($countries as $country)
		{
			$keys[] = $this->set_country($country);
		}
		return $keys;
	}
	
	/* REGIONS */
	public function get_region($region_id)
	{
		$this->table_name = 'regions';
		
		$cond = array(
			'region_id' => $region_id
		);
		
		$regions_id = $this->get_list($cond, array(), array(), 1);
		
		$region = $this->get_item($regions_id[0]);
		
		$ret['name'] = $region['name_'.session::get('language','ru')];
		
		return $ret['name'];
	}
	
	public function get_regions($cond = array())
	{
		$this->table_name = 'regions';
		
		if( ! isset($cond['hidden']))
			$cond['hidden'] = 'false';
		
		$regions_id = $this->get_list($cond, array(), array('name_'.session::get('language','ru').' ASC'));
		
		$regions = array();
		foreach($regions_id as $region_id)
		{
			if( ! in_array($region_id, array(720))) {
				$regions[] = $this->get_item($region_id);
				$regions[count($regions)-1]['name'] = $regions[count($regions)-1]['name_'.session::get('language','ru')];
			}
		}
		
		return $regions;
	}
	
	public function set_region($region)
	{
		$this->table_name = 'regions';
		
		$data = array(
			'country_id' => $region['country_id'],
			'region_id' => $region['region_id'],
			'name' => $region['name'],
		);
		
		if(is_bool($region['hidden']))
			$data['hidden'] = $region['hidden'];
		
		return $this->insert($data);
	}
	
	public function set_regions($regions)
	{
		$keys = array();
		foreach($regions as $region)
		{
			$keys[] = $this->set_region($region);
		}
		return $keys;
	}
	
	/* CITIES */
	public function get_city($city_id)
	{
		$this->table_name = 'cities';
		
		$cond = array(
			'city_id' => $city_id
		);
		
		$cities_id = $this->get_list($cond, array(), array(), 1);
		
		$city = $this->get_item($cities_id[0]);
		
		$ret['name'] = $city['name_'.session::get('language','ru')];
		
		return $ret['name'];
	}
	
	public function get_cities($cond = array(), $options = array())
	{
		$this->table_name = 'cities';
		
		if( ! isset($cond['hidden']))
			$cond['hidden'] = false;
		
		if(isset($options['big-cities']) && $options['big-cities'] != false)
			unset($cond['hidden']);
		
		$cities_id = $this->get_list($cond, array(), array('name_'.session::get('language','ru').' ASC'));
		
		$districts = array();
		$cities = array();
		$centers = array();
		
		$big_cities = array(
			array(10184, 10398, 10029, 9977, 10532, 10337, 10251, 10252),
			array(9916, 10076, 10119, 10151, 10214, 10299, 9955, 10340, 10430, 10452, 10475, 10501, 10108, 10556, 10579, 10603, 10631, 10647)
		);
		
		foreach($cities_id as $city_id)
		{
			$city = $this->get_item($city_id);
			$city['name'] = $city['name_'.session::get('language','ru')];
			switch($city['country_id'])
			{
				// Германия
				case 1012:
					if(in_array($city['city_id'], array(1014, 1107, 278190, 278154, 1117)))
						$cities[] = $city;
					break;
					
				// Испания
				case 1707:
					if(in_array($city['city_id'], array(1764, 1733)))
						$cities[] = $city;
					break;
					
				// Малайзия
				case 277563:
					if(in_array($city['city_id'], array()))
						$cities[] = $city;
					break;
					
				// ОАЭ
				case 582051:
					if(in_array($city['city_id'], array(2372615, 5000000)))
						$cities[] = $city;
					break;
					
				// Китай
				case 2374:
					if(in_array($city['city_id'], array(3503075)))
						$cities[] = $city;
					break;
					
				// Япония
				case 11060:
					if(in_array($city['city_id'], array(11267, 11125, 11199)))
						$cities[] = $city;
					break;
					
				// США
				case 5681:
					if(in_array($city['city_id'], array(7992, 279123, 278193, 6788, 6517, 8721, 9085, 9327)))
						$cities[] = $city;
					break;
					
				// Италия
				case 1786:
					if(in_array($city['city_id'], array(1835, 1820, 278193, 1863, 1853, 1822, 1886, 1875, 5911563)))
						$cities[] = $city;
					break;
				
				// Франция
				case 10668:
					if(in_array($city['city_id'], array(10805, 10685, 10822)))
						$cities[] = $city;
					break;
				
				// Украина
				case 9908:
					if(isset($options['big-cities']) && $options['big-cities'] != false)
					{
						if(in_array($city['city_id'], $big_cities[0]))
							$centers[] = $city;
						
						if(in_array($city['city_id'], $big_cities[1]))
							$cities[] = $city;
					}
					else
					{
						if( ! $city['center'])
							if($city['city_id'] >= 15789520)
									$districts[] = $city;
							else
									$cities[] = $city;
						else
							$centers[] = $city;
					}
					break;
			}
		}
		
		if(isset($options['big-cities']) && $options['big-cities'] != false)
		{
			$tmp_arr = array();
			foreach($big_cities[0] as $city_id)
			{
				foreach($centers as $center)
				{
					if($center['city_id'] == $city_id)
						$tmp_arr[] = $center;
				}
			}
			$centers = $tmp_arr;
		}
		
		return array_merge($centers, $cities, $districts);
	}
	
	public function set_city($city)
	{
		$this->table_name = 'cities';
		
		$data = array(
			'country_id' => $city['country_id'],
			'region_id' => $city['region_id'],
			'city_id' => $city['city_id'],
			'name' => $city['name'],
		);
		
		if(is_bool($city['hidden']))
			$data['hidden'] = $city['hidden'];
		
		return $this->insert($data);
	}
	
	public function set_cities($cities)
	{
		$keys = array();
		foreach($cities as $city)
		{
			$keys[] = $this->set_city($city);
		}
		return $keys;
	}
}

?>
