<?php

load::app('modules/albums/controller');
load::model('journals/journals');

class albums_index_action extends albums_controller
{
	private $modify = false;
	
	public function execute()
	{
		parent::execute();
		
		$act = request::get('act');
		
		if(in_array($act, array('add_album', 'modify_album', 'remove_album', 'get_album')))
		{
			$this->set_renderer('ajax');
			return $this->json['success'] = $this->$act();
		}
		
		$this->category_key = $this->filter = request::get('filter');
		if( ! $this->category_key)
			$this->category_key = '';
		
		$this->show = request::get('show');
		
		$cond = array(
			'user_id' => $this->uid,
			'category' => $this->category_key
		);
		
		$this->albums = user_albums_peer::instance()->get_list($cond);
		
		$this->journals_list = journals_peer::instance()->get_list(array('public' => true), array(), array("name ASC"));
	}
	
	private function add_album()
	{
		$category = request::get('category');
		if( ! $category)
			$category = '';
		
		$additional = array();
		
		switch($category)
		{
			case 'contest':
				$name = stripslashes(request::get_string('event_title'));
				
				$location = array(
					'country' => request::get_int('country'),
					'region' => request::get_int('region'),
					'city' => request::get_int('city'),
					'another_city' => request::get('another_city')
				);
				
				$loc = profile_peer::get_location($location, ', ');
				
				if($loc != '')
					$name .= ', '.$loc;
				
				if(request::get_int('period_month') > 0 && request::get_int('period_year') > 0)
					$name .= ', '.mb_strtolower(date_peer::instance()->get_month(request::get('period_month')))
						.' '.request::get('period_year');
				
				$description = '';
				
				$additional = array(
					'event_title' => stripslashes(request::get('event_title')),
					'period_month' => request::get_int('period_month'),
					'period_year' => request::get_int('period_year'),
					'result_type' => request::get_bool('award') ? 'award' : 'participation',
					'result_position' => request::get_int('result_position'),
					'another_result_position' => request::get_string('another_result_position'),
					'link' => request::get('link'),
					'country' => request::get_int('country'),
					'region' => request::get_int('region'),
					'city' => request::get_int('city'),
					'another_city' => request::get('another_city'),
				);
				
				break;
			
			case 'catalogs':
				$name = stripslashes(request::get('name'));
				if(request::get_int('period_month') > 0 && request::get_int('period_year') > 0)
					$name .= ', '.mb_strtolower(date_peer::instance()->get_month(request::get('period_month')))
						.' '.request::get('period_year');
				
				$description = '';
				
				$additional = array(
					'name' => stripslashes(request::get('name')),
					'brand' => stripslashes(request::get('brand')),
					'company' => stripslashes(request::get('company')),
					'period_month' => request::get_int('period_month'),
					'period_year' => request::get_int('period_year'),
					'visagist' => stripslashes(request::get('visagist')),
					'stylist' => stripslashes(request::get('stylist')),
					'photographer' => stripslashes(request::get('photographer')),
					'designer' => stripslashes(request::get('designer')),
				);
				break;
			
			case 'advertisement':
				$name = stripslashes(request::get('brand'));
				if(request::get_int('period_month') > 0 && request::get_int('period_year') > 0)
					$name .= ', '.mb_strtolower(date_peer::instance()->get_month(request::get('period_month')))
						.' '.request::get('period_year');
				
				$description = '';
				
				$additional = array(
					'brand' => stripslashes(request::get('brand')),
					'company' => stripslashes(request::get('company')),
					'period_month' => request::get_int('period_month'),
					'period_year' => request::get_int('period_year'),
					'visagist' => stripslashes(request::get('visagist')),
					'stylist' => stripslashes(request::get('stylist')),
					'photographer' => stripslashes(request::get('photographer')),
					'designer' => stripslashes(request::get('designer')),
				);
				
				$additional['type'] = 'in_journal';
				if(request::get('outdoor'))
					$additional['type'] = 'outdoor';
				
				break;
			
			case 'defile':
				$location = array(
					'country' => request::get_int('country'),
					'region' => request::get_int('region'),
					'city' => request::get_int('city'),
					'another_city' => request::get('another_city')
				);
				
				$loc = profile_peer::get_location($location, ', ');
				
				$name = stripslashes(request::get('designer'));
				$name .= ', '.stripslashes(request::get('event_title'));
				if($loc != '')
					$name .= ', '.$loc;
				
				if(request::get_int('period_month') > 0 && request::get_int('period_year') > 0)
					$name .= ', '.mb_strtolower(date_peer::instance()->get_month(request::get('period_month')))
						.' '.request::get('period_year');
				
				$description = '';
				$additional = array(
					'event_title' => stripslashes(request::get('event_title')),
					'period_month' => request::get_int('period_month'),
					'period_year' => request::get_int('period_year'),
					'visagist' => stripslashes(request::get('visagist')),
					'stylist' => stripslashes(request::get('stylist')),
					'photographer' => stripslashes(request::get('photographer')),
					'designer' => stripslashes(request::get('designer')),
					'link' => request::get('link'),
					'country' => request::get_int('country'),
					'region' => request::get_int('region'),
					'city' => request::get_int('city'),
					'another_city' => request::get('another_city'),
					'open_show' => request::get_bool('open_show'),
					'close_show' => request::get_bool('close_show')
				);
				break;
			
			case 'fashion':
				$name = stripslashes(request::get('journal_name'));
				if(request::get_int('journal_number') > 0)
					$name .= ' №'.request::get_int('journal_number');
				
				if(request::get_int('journal_month') > 0 && request::get_int('journal_year') > 0)
					$name .= ', '.mb_strtolower(date_peer::instance()->get_month(request::get_int('journal_month')))
						.' '.request::get_int('journal_year');
				
				$description = '';
				
				$additional = array(
					'journal_name' => stripslashes(request::get('journal_name')),
					'journal_number' => request::get_int('journal_number'),
					'journal_month' => request::get_int('journal_month'),
					'journal_year' => request::get_int('journal_year'),
					'visagist' => stripslashes(request::get('visagist')),
					'stylist' => stripslashes(request::get('stylist')),
					'photographer' => stripslashes(request::get('photographer')),
					'designer' => stripslashes(request::get('designer')),
					'link' => request::get('link'),
				);
				
				$additional['printed'] = 'in_ukraine';
				if(request::get('in_other_country'))
					$additional['printed'] = 'in_other_country';
				elseif(request::get('in_few_countries'))
					$additional['printed'] = 'in_few_countries';
				
				break;
			
			case '':
			default:
				$name = stripslashes(request::get('album_name'));
				$description = request::get('album_description');
				break;
		}
		
		$data = array(
			'name' => $name,
			'description' => $description,
			'category' => $category,
			'additional' => serialize($additional),
			'user_id' => $this->uid,
			'pid' => '0'
		);
		
		if( ! $this->modify)
		{
			$data['images'] = serialize(array());
			if( ! $aid = user_albums_peer::instance()->insert($data))
				return false;
			$this->json['aid'] = $aid;
		}
		else
		{
			$data['id'] = request::get_int('aid');
			user_albums_peer::instance()->update($data);
		}
		
		return true;
	}
	
	private function modify_album()
	{
		$this->modify = true;
		return $this->add_album();
	}
	
	private function remove_album()
	{
		$aid = request::get_int('aid');
		
		if( ! $album = user_albums_peer::instance()->get_item($aid))
			return false;
		
		$album['images'] = unserialize($album['images']);
		
		foreach($album['images'] as $pid)
			user_photos_peer::instance()->delete_item($pid);
		
		user_albums_peer::instance()->delete_item($aid);
		
		return true;
	}
	
	private function get_album()
	{
		$aid = request::get_int('aid');
		$album = user_albums_peer::instance()->get_item($aid);
		
		$album['additional'] = unserialize($album['additional']);
		
		$this->json['album'] = $album;
		
		return true;
	}
}

?>
