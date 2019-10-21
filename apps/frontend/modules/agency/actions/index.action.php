<?php

load::app('modules/agency/controller');

class agency_index_action extends agency_controller
{
	public function execute()
	{
		parent::execute();
		
		$act = request::get_string('act');
		if(in_array($act, array('save_about', 'save_contacts', 'set_location', 'set_agency_rank')))
		{
			$this->set_renderer('ajax');
			return $this->json['success'] = $this->$act();
		}
		
		$sn = explode('.', $_SERVER['SERVER_NAME']);
		if(count($sn) > 2 && ! request::get_int("id"))
			$agency_id = db::get_scalar('SELECT id FROM agency WHERE link = :link LIMIT 1', array('link' => $sn[0]));
		else
			$agency_id = request::get_int('id');
		
		$this->user_profile = profile_peer::instance()->get_item(session::get_user_id());
		
		$this->access = ($this->user_profile['manager_agency_id'] == $agency_id || session::has_credential('admin')) ? true : false;
		
		if( ! $agency_id || ! $this->agency = agency_peer::instance()->get_item($agency_id))
			$this->redirect ("http://".conf::get('server')."/");
		
		if( ! $this->agency['page_active'])
			$this->redirect ("http://".conf::get('server')."/");
		
		$this->agency['location'] = profile_peer::get_location($this->agency);
		
		$this->agency['contacts']['_person'] = '';
		if($this->agency['contacts']['person'] > 0)
		{
			$this->agency['contacts']['_person'] = '<a href="/profile?id='.$this->agency['contacts']['person'].'">'.profile_peer::get_name(user_data_peer::instance()->get_item($this->agency['contacts']['person'])).'</a>';
		}
		elseif($this->agency['contacts']['person'] < 0)
			$this->agency['contacts']['_person'] = $this->agency['contacts']['another_person'];
		elseif(is_string($this->agency['contacts']['person']))
		{
			$this->agency['contacts']['_person'] = $this->agency['contacts']['another_person'] = $this->agency['contacts']['person'];
			$this->agency['contacts']['person'] = -1;
		}
		
		$sql = 'SELECT ua.id FROM 
				user_auth AS ua 
			INNER JOIN user_data AS ud ON ua.id = ud.user_id 
			INNER JOIN user_agency AS ug ON ug.user_id = ud.user_id 
			WHERE ug.agency_id = '.$this->agency['id'].' AND ua.type = 2 AND ud.status > 20 AND ua.hidden = false AND ud.agency_rank > 0 ORDER BY ud.agency_rank ASC';
		$this->models_list = db::get_cols($sql);
		
		$sql = 'SELECT ua.id FROM 
				user_auth AS ua 
			INNER JOIN user_data AS ud ON ua.id = ud.user_id 
			INNER JOIN user_agency AS ug ON ug.user_id = ud.user_id 
			WHERE ug.agency_id = '.$this->agency['id'].' AND ua.type = 2 AND ud.status > 20 AND ua.hidden = false AND ud.agency_rank = 0 ORDER BY ud.rank ASC';
		$this->models_list = array_merge($this->models_list, db::get_cols($sql));
		
		$this->managers_list = $this->get_managers_list();
		$this->albums_list = $this->get_albums_list();
		
		$this->hronology_list = array();
		if(count($this->models_list) < 1)
			return;
		
		$this->hronology_list = $this->get_hronology_list();
		
		$this->filter = request::get_string('filter');
		if($this->filter != '')
		{
			$sql = 'SELECT ua.id FROM user_auth AS ua WHERE ua.id IN ('.implode(', ', $this->models_list).') AND ';
			switch($this->filter)
			{
				case 'new_faces':
					$sql .= 'show_on_main > '.user_auth_peer::new_faces.' AND ';
					$sql .= 'show_on_main < '.user_auth_peer::perspective.' ';
					break;
				
				case 'perspective':
					$sql .= 'show_on_main > '.user_auth_peer::perspective.' ';
					break;
				
				case 'successful':
					$sql .= 'show_on_main > '.user_auth_peer::successful.' AND ';
					$sql .= 'show_on_main < '.user_auth_peer::new_faces.' ';
					break;
			}
			
			$sql .= 'ORDER BY show_on_main ASC';
			
			$this->models_list = db::get_cols($sql);
		}
	}
	
	public function save_about()
	{
		$id = request::get_int('id');
		
		if( ! agency_peer::instance()->get_item($id))
			return false;
		
		$data = array(
			'id' => $id,
			'about' => request::get_string('value')
		);
		
		agency_peer::instance()->update($data);
		
		return true;
	}
	
	public function save_contacts()
	{
		$id = request::get_int('id');
		
		if( ! agency_peer::instance()->get_item($id))
			return false;
		
		$contacts = request::get_array('data');
		
		if( ! is_array($contacts))
			return false;
		
		$data = array(
			'id' => $id,
			'contacts' => $contacts
		);
		
		if(isset($contacts['link']))
		{
			$contacts['link'] = str_replace(array('.', ' ', '/'), '', $contacts['link']);
			
			$agency_tokens = db::get_scalar('SELECT COUNT(id) FROM agency WHERE id <> :id AND link = :link', array('id' => $id, 'link' => $contacts['link']));
			$users_tokens = db::get_scalar('SELECT COUNT(id) FROM user_data WHERE subdomain = :subdomain', array('subdomain' => $contacts['link']));
			if($agency_tokens > 0 || $users_tokens > 0)
			{
				$this->json['errors'][] = 'link-exists';
				return false;
			}
			
			$data['link'] = $contacts['link'];
			unset($contacts['link']);
			
			$this->json['link'] = $data['link'];
		}
		
		agency_peer::instance()->update($data);
		
		$this->json['person'] = '';
		if($contacts['person'] > 0)
		{
			$this->json['person_link'] = '/profile?id='.$contacts['person'];
			$this->json['person'] = profile_peer::get_name(user_data_peer::instance()->get_item($contacts['person']));
		}
		elseif($contacts['person'] < 0)
			$this->json['person'] = $contacts['another_person'];
		
		return true;
	}
	
	public function set_location()
	{
		$id = request::get_int('id');
		
		if( ! agency_peer::instance()->get_item($id))
			return false;
		
		$country = request::get_int('country');
		$region = request::get_int('region');
		$city = request::get_int('city');
		$another_city = request::get_string('another_city');
		
		$data = array(
			'id' => $id,
			'country' => $country,
			'region' => $region,
			'city' => $city,
			'another_city' => $another_city
		);
		
		agency_peer::instance()->update($data);
		
		$this->json['location'] = profile_peer::get_location($data);
		
		return true;
	}
	
	private function get_hronology_list()
	{
		$sql = 'SELECT id FROM user_albums WHERE user_id IN ('.implode(', ', $this->models_list).');';
		$albums_list = db::get_cols($sql);
		
		$hronology_list = array();
		foreach($albums_list as $id)
		{
			$hronology = array();
			$item = user_albums_peer::instance()->get_item($id);
			
			if( ! in_array($item['category'], array('covers', 'fashion', 'defile', 'advertisement', 'contest', 'catalogs')))
				continue;
			
			switch($item['category'])
			{
				case 'fashion':
					$hronology['name'] = $item['_a']['journal_name'];
					$hronology['month'] = $item['_a']['journal_month'];
					$hronology['year'] = $item['_a']['journal_year'];
					break;
				
				case 'defile':
					$hronology['name'] = '';
					if($item['_a']['designer'] != '')
						$hronology['name'] .= $item['_a']['designer'].', ';
					$hronology['name'] .= $item['_a']['event_title'];
					if($loc = profile_peer::get_location($item['_a'], ', '))
						$hronology['name'] .= ', '.$loc;
					$hronology['month'] = $item['_a']['period_month'];
					$hronology['year'] = $item['_a']['period_year'];
					break;
					
				case 'advertisement':
					$hronology['name'] = $item['_a']['brand'];
					$hronology['month'] = $item['_a']['period_month'];
					$hronology['year'] = $item['_a']['period_year'];
					break;
				
				case 'contest':
					$hronology['name'] = '';
					if($loc = profile_peer::get_location($item['_a'], ', '))
						$hronology['name'] = ', '.$loc;
					$hronology['month'] = $item['_a']['period_month'];
					$hronology['year'] = $item['_a']['period_year'];
					break;
					
				case 'catalogs':
					$hronology['name'] = $item['_a']['name'];
					$hronology['month'] = $item['_a']['period_month'];
					$hronology['year'] = $item['_a']['period_year'];
					break;
			}
			
			$user_data = user_data_peer::instance()->get_item($item['user_id']);
			
			if($item['category'] == 'covers')
			{
				foreach($item['_i'] as $pid)
				{
					$photo = user_photos_peer::instance()->get_item($pid);
					
					$hronology['name'] = $photo['_a']['journal_name'];
					if($photo['_a']['journal_number'] > 0)
						$hronology['name'] .= ' №'.$photo['_a']['journal_number'];
					
					$hronology['link'] = '/albums/album?aid='.$item['id'].'&uid='.$item['user_id'].'&show=viewer&pid='.$pid;
					$hronology['month'] = $photo['_a']['journal_month'];
					$hronology['year'] = $photo['_a']['journal_year'];
					
					$hronology_list[] = $hronology;
					$hronology_list[count($hronology_list) - 1]['user_id'] = $item['user_id'];
					$hronology_list[count($hronology_list) - 1]['user_name'] = profile_peer::get_name($user_data);
					$hronology_list[count($hronology_list) - 1]['category'] = user_albums_peer::get_category($item['category']);
					$hronology_list[count($hronology_list) - 1]['date'] = mktime(0, 0, 0, intval($hronology['month']+1), 0, intval($hronology['year']));
					
				}
				
				continue;
			}
			
			$hronology_list[] = $hronology;
			$hronology_list[count($hronology_list) - 1]['link'] = '/albums/album?aid='.$item['id'].'&uid='.$item['user_id'];
			$hronology_list[count($hronology_list) - 1]['user_id'] = $item['user_id'];
			$hronology_list[count($hronology_list) - 1]['user_name'] = profile_peer::get_name($user_data);
			$hronology_list[count($hronology_list) - 1]['category'] = user_albums_peer::get_category($item['category']);
			$hronology_list[count($hronology_list) - 1]['date'] = mktime(0, 0, 0, intval($hronology['month']+1), 0, intval($hronology['year']));
		}
		
		usort($hronology_list, array('agency_index_action', 'hronology_sorting'));
		
		return $hronology_list;
	}
	
	public function set_agency_rank()
	{
		$models_list = request::get_array('rank');
		
		$rank = 1;
		foreach($models_list as $id)
		{
			if( ! user_data_peer::instance()->get_item($id))
				continue;
			
			$data = array(
				'user_id' => $id,
				'agency_rank' => $rank
			);
			
			user_data_peer::instance()->update($data);
			
			$rank++;
		}
		
		return true;
	}
	
	private function get_managers_list()
	{
		$cond = array(
			'manager_agency_id' => $this->agency['id']
		);
		$list = user_data_peer::instance()->get_list($cond);
		
		return $list;
	}
	
	private function get_albums_list()
	{
		$cond = array(
			'agency_id' => $this->agency['id']
		);
		$list = agency_albums_peer::instance()->get_list($cond);
		
		return $list;
	}
	
	public static function hronology_sorting($x, $y)
	{
		return $x['date'] < $y['date']; 
	}
}

?>
