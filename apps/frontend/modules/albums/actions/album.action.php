<?php

load::app('modules/albums/controller');

class albums_album_action extends albums_controller
{
	private $modify = false;
	
	public function execute()
	{
		parent::execute();
		
		$this->aid = request::get_int('aid');
		$this->album = user_albums_peer::instance()->get_item($this->aid);
		$this->album['images'] = unserialize($this->album['images']);
		
		$this->journals_list = journals_peer::instance()->get_list(array('public' => true), array(), array("name ASC"));
		
		if(request::get_string('filter') == 'deleted')
		{
			$sql = 'SELECT id FROM user_photos WHERE user_id = :user_id AND del > 0';
			$bind = array('user_id' => $this->profile['user_id']);
			
			$this->album = array(
				'name' => t('Удаленные фотографии'),
				'images' =>  db::get_cols($sql, $bind)
			);
		}
		
		$act = request::get('act');
		if(in_array($act, array('add_photo', 'modify_photo', 'remove_photo', 'get_photo', 'get_photos')))
		{
			$this->set_renderer('ajax');
			return $this->json['success'] = $this->$act();
		}
		
		$this->show = request::get('show');
		
		$this->category_key = $this->album['category'];
		$this->information = $this->get_album_info();
	}
	
	private function add_photo()
	{
		$photos = array();
		
		switch($this->album['category'])
		{
			case 'covers':
				$photos[] = request::get_int('pid');
				$photo_additional_data = array(
					'journal_id' => request::get_int('journal_id'),
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
				
				$photo_additional_data['printed'] = 'in_ukraine';
				if(request::get('in_other_country'))
					$photo_additional_data['printed'] = 'in_other_country';
				elseif(request::get('in_few_countries'))
					$photo_additional_data['printed'] = 'in_few_countries';
				
				$name = stripslashes(request::get('journal_name'));
				if(request::get_int('journal_number') > 0)
					$name .= ' №'.request::get_int('journal_number');
				
				if(request::get_int('journal_month') > 0 && request::get_int('journal_year') > 0)
					$name .= ', '.mb_strtolower(date_peer::instance()->get_month(request::get_int('journal_month'))).' '.request::get_int('journal_year');
				
				user_photos_peer::instance()->update(array(
					'id' => request::get_int('pid'),
					'name' => $name,
					'additional' => serialize($photo_additional_data)
				));
				break;
			
			case '':
			default:
				$photos = explode(',', request::get('uploadify-photos-list'));
				break;
		}
		
		$data = array(
			'id' => $this->aid
		);
		
		if( ! $this->modify)
		{
			$data['images'] = serialize(array_merge($photos, $this->album['images']));
		}
		else
		{
			for($i = 0; $i < count($this->album['images']); $i++)
				if($this->album['images'][$i] == request::get_int('old_pid'))
					$this->album['images'][$i] = request::get_int('pid');
			
			$data['images'] = serialize($this->album['images']);
		}
		
		user_albums_peer::instance()->update($data);
		
		$this->json['aid'] = $this->aid;
		
		return true;
	}
	
	private function modify_photo()
	{
		$this->modify = true;
		return $this->add_photo();
	}
	
	private function remove_photo()
	{
		$pid = request::get_int('pid');
		
		user_photos_peer::instance()->update(array('id' => $pid, 'del' => time()));
		
		$photos = array();
		foreach($this->album['images'] as $img)
		{
			if($img != $pid)
			{
				$photos[] = $img;
			}
		}
		
		$data = array(
			'id' => $this->album['id'],
			'images' => serialize($photos)
		);
		
		user_albums_peer::instance()->update($data);
		
		return true;
	}
	
	private function get_photo()
	{
		$pid = request::get_int('pid');
		
		if( ! $photo = user_photos_peer::instance()->get_item($pid))
			return false;
		
		$photo['additional'] = unserialize($photo['additional']);
		
		$this->json['photo'] = $photo;
		
		return true;
	}
	
	private function get_photos()
	{
		$this->json['photos'] = $this->album['images'];
		
		if(request::get_string('type') == 'deleted')
		{
			$sql = 'SELECT id FROM user_photos WHERE user_id = :user_id AND del > 0';
			$bind = array('user_id' => $this->profile['user_id']);
			
			$this->json['photos'] = db::get_cols($sql, $bind);
		}
		
		$this->json['additional'] = array();
		foreach($this->json['photos'] as $pid)
		{
			$photo = user_photos_peer::instance()->get_item($pid);
			$photo['additional'] = unserialize($photo['additional']);
			if($this->album['category'] == 'covers')
			{
				$this->json['additional'][$pid]['html'] = '
					<div class="mb5 fs18 bold" style="color: #000000">
						'.$photo['name'].'
					</div>
					<div>'
						.($photo['additional']['photographer'] ? '<span class="cgray">Фотограф: </span><span>'.$photo['additional']['photographer'].'</span><br />' : '')
						.($photo['additional']['visagist'] ? '<span class="cgray">Визажист: </span><span>'.$photo['additional']['visagist'].'</span><br />' : '')
						.($photo['additional']['stylist'] ? '<span class="cgray">Стилист: </span><span>'.$photo['additional']['stylist'].'</span><br />' : '')
						.($photo['additional']['designer'] ? '<span class="cgray">Одежда: </span><span>'.$photo['additional']['designer'].'</span><br />' : '')
					.'</div>
				';
			}
		}
		
		return true;
	}
	
	private function get_album_info()
	{
		$info = unserialize($this->album['additional']);
		
		$_info = array(
			'covers' => array(),
			'fashion' => array(
				'label' => '{&journal_name;}{ №&journal_number;}{, &journal_month; &journal_year;}',
				'text' => '{<span class="cgray">Визажист:</span> &visagist;}{ <span class="cgray">Стилист:</span> &stylist;}{ <span class="cgray">Фотограф:</span> &photographer;}{ <span class="cgray">Дизайнер(-ы) одежды:</span> &designer;}',
				'link' => '{Fashion story в интернете: <a href="&link;">&link;</a>}'
			),
			'defile' => array(
				'label' => '{&designer;, &event_title;}{, &country;}{, &another_city;}{, &period_month; &period_year;}',
				'text' => '{<div class="mb5">&open_show; показ</div>}{<span class="cgray">Визажист:</span> &visagist;}{ <span class="cgray">Стилист:</span> &stylist;}',
				'link' => '{Фото показа в интернете: <a href="&link;">&link;</a>}'
			),
			'advertisement' => array(
				'label' => '{&brand;}{, &period_month; &period_year;}',
				'text' => '{<div class="mb5"><span class="cgray">Тип:</span> &type;}{<span class="cgray"> Заказчик:</span> &company;</div>}{<span class="cgray">Визажист:</span> &visagist;}{ <span class="cgray">Стилист:</span> &stylist;}{ <span class="cgray">Фотограф:</span> &photographer;}{ <span class="cgray">Дизайнер(-ы) одежды:</span> &designer;}',
				'link' => ''
			),
			'contest' => array(
				'label' => '{&event_title;}',
				'text' => '{&another_city;, }{&country;, }{&period_month; &period_year;}{, &result_type;}',
				'link' => '{Фото в интернете: <a href="&link;">&link;</a>}'
			),
			'catalogs' => array(
				'label' => '{&brand;, }{&name;}',
				'text' => '{<div class="mb5"><span class="cgray">Компания:</span> &company;}{<span class="cgray"> Период съемок:</span> &period_month; &period_year;</div>}{<span class="cgray">Визажист:</span> &visagist;}{ <span class="cgray">Стилист:</span> &stylist;}{ <span class="cgray">Фотограф:</span> &photographer;}{ <span class="cgray">Дизайнер(-ы) одежды:</span> &designer;}',
				'link' => ''
			)
		);
		
		if( ! is_array($info))
			return false;
		
		if( ! is_array($_info[$this->album['category']]))
			return false;
		
		foreach($_info[$this->album['category']] as $field_caption => $field_template)
		{
			foreach($info as $key => $value)
			{
				if($value != '')
				{
					if($key == 'open_show')
					{
						if($info['open_show'] == 1 && $info['close_show'] == 1)
							$value = 'Открывала и закрывала';
						elseif($info['open_show'] == 1)
							$value = 'Открывала';
						elseif($info['close_show'] == 1)
							$value = 'Закрывала';
						else
							$value = '';
					}
					
					if($key == 'result_type')
					{
						if($info['result_type'] == 'participation')
							$value = 'Участие';
						
						if($info['result_type'] == 'award')
						{
							if($info['result_position'] > 0)
								$value = $info['result_position'].' место';
							elseif($info['result_position'] < 0)
								$value = $info['another_result_position'];
							else
								$value = 'Есть награда';
						}
					}
						
					if($key == 'type' && $value == 'in_journal')
						$value = 'в журнале';
					
					if($key == 'type' && $value == 'outdoor')
						$value = 'наружная';
					
					if(in_array($key, array('country')))
						$value = profile_peer::get_location(array('country' => $value));
					
					if(in_array($key, array('journal_month', 'period_month')))
						$value = mb_strtolower(date_peer::instance ()->get_month($value));
					
					$_info[$this->album['category']][$field_caption] = str_replace(
						'&'.$key.';', 
						$value, 
						$_info[$this->album['category']][$field_caption]
					);
				}
			}
			
			$str = $_info[$this->album['category']][$field_caption];
			
			$tokens = preg_split('({|})', $str);
			$_str = '';
			foreach($tokens as $token)
			{
				if(mb_strpos($token, '&') !== false && mb_strpos($token, ';') !== false)
				{
					$token = '';
				}
				$_str .= $token;
			}
			
			$_info[$this->album['category']][$field_caption] = $_str;
		}
		
		return $_info;
	}
}

?>
