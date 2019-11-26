<?php

load::app("modules/updates/controller");
load::model("user/user_photos");
load::model("user/user_albums");
load::model("user/profile");

class updates_index_action extends updates_controller
{
	public function execute()
	{
		parent::execute();
		
		$act = request::get_string("act");
		if(in_array($act, array("get_description")))
		{
			$this->set_renderer("ajax");
			return $this->json["success"] = $this->$act();
		}
		
		$this->count_imgs_per_category = 6;
		
		$category = request::get_string("category");
		
		if($category != "")
		{
			$this->count_imgs_per_category = 0;
			
			if($category == "adv")
				$category = "'advertisement', 'catalogs'";
			else
				$category = "'".$category."'";
			
			$updates = db::get_rows("SELECT id, category FROM user_albums WHERE category IN (".$category.") AND images <> 'a:0:{}' AND user_id IN (SELECT id FROM user_auth WHERE hidden = false AND type = 2) ORDER BY modified DESC");
		}
		else
		{
			$updates = db::get_rows("SELECT id, category FROM user_albums WHERE category IN ('covers', 'fashion', 'defile', 'advertisement', 'catalogs') AND images <> 'a:0:{}' AND user_id IN (SELECT id FROM user_auth WHERE hidden = false AND type = 2) ORDER BY modified DESC");
		}
		
		$boxes = array(
			array(
				"category" => "covers",
				"updates" => array(),
				"images" => array()
			),
			array(
				"category" => "fashion",
				"updates" => array(),
				"images" => array()
			),
			array(
				"category" => "defile",
				"updates" => array(),
				"images" => array()
			),
			array(
				"category" => "adv",
				"updates" => array(),
				"images" => array()
			)
		);
		
		$this->users = array();
		
		foreach($updates as $update)
		{
			$index = 0;
			
			if($update["category"] == "fashion")
			{
				$index = 1;
			}
			elseif($update["category"] == "defile")
			{
				$index = 2;
			}
			elseif(in_array($update["category"], array("advertisement", "catalogs")))
			{
				$index = 3;
			}
			elseif($update["category"] != "covers")
			{
				continue;
			}
			
			$album = user_albums_peer::instance()->get_item($update["id"]);
			
			$boxes[$index]["images"] = array_merge($boxes[$index]["images"], $album["_i"]);
			
			foreach($album["_i"] as $pid)
			{
				$this->users[$pid] = $album["user_id"];
			}
			
			$boxes[$index]["updates"][] = $album;
		}
		
		if( ! $this->count_imgs_per_category)
			$this->count_imgs_per_category = count($boxes[$index]["images"]);
		
		if(request::get_int("count") > 0)
			$this->count_imgs_per_category = request::get_int("count");
		
		$this->boxes = $boxes;
	}
	
	public function get_description()
	{
		$id = request::get_int("id");
		$category = request::get_string("category");
		$uid = request::get_int("uid");
		
		if($category == "covers")
		{
			$item = user_photos_peer::instance()->get_item($id);
			$html = '
				<div class="mb5 fs18 bold" style="color: #000000">
					'.$item['name'].'
				</div>
				<div>'
					.($item['_a']['photographer'] ? '<span class="cgray">Фотограф: </span><span>'.$item['_a']['photographer'].'</span><br />' : '')
					.($item['_a']['visagist'] ? '<span class="cgray">Визажист: </span><span>'.$item['_a']['visagist'].'</span><br />' : '')
					.($item['_a']['stylist'] ? '<span class="cgray">Стилист: </span><span>'.$item['_a']['stylist'].'</span><br />' : '')
					.($item['_a']['designer'] ? '<span class="cgray">Одежда: </span><span>'.$item['_a']['designer'].'</span><br />' : '')
				.'</div>
			';
		}
		else
		{
			$cond = array("user_id" => $uid, "category" => $category);
			$list = user_albums_peer::instance()->get_list($cond);
			$item = user_albums_peer::instance()->get_item($list[0]);
			$html = '
				<div class="mb5 fs18 bold" style="color: #000000">
					'.$item['name'].'
				</div>
				<div>'
					.($item['_a']['photographer'] ? '<span class="cgray">Фотограф: </span><span>'.$item['_a']['photographer'].'</span><br />' : '')
					.($item['_a']['visagist'] ? '<span class="cgray">Визажист: </span><span>'.$item['_a']['visagist'].'</span><br />' : '')
					.($item['_a']['stylist'] ? '<span class="cgray">Стилист: </span><span>'.$item['_a']['stylist'].'</span><br />' : '')
					.($item['_a']['designer'] ? '<span class="cgray">Одежда: </span><span>'.$item['_a']['designer'].'</span><br />' : '')
				.'</div>
			';
		}
		
		$this->json['html'] = $html;
		
		return true;
	}
}

?>
