<?php

load::app('modules/admin/controller');
load::model("user/user_auth");
load::model("user/user_data");

class admin_user_manager_action extends admin_controller
{
	
	public function execute()
	{
		parent::execute();
		
		$this->set_renderer("ajax");
		$this->json = array("success" => true);
		
		$act = request::get("act");
		
		switch($act)
		{
			case "remove_user":
				$user_id = request::get_int("id");
				user_auth_peer::instance()->delete_item($user_id);
				user_data_peer::instance()->delete_item($user_id);
				break;
			
			case "create_user":
				$ua = array(
						"email" => request::get_string("email"),
						"active" => false,
						"hidden" => request::get_bool("hidden"),
						"registrator" => 1
				);
				if( ! $user_id = user_auth_peer::instance()->insert($ua))
				{
					$this->json["success"] = false;
					$this->json["msgErr"] = "Creating error.";
					return false;
				}
				
				$ud = array(
						"user_id" => $user_id,
						"first_name" => request::get_string("first_name"),
						"last_name" => request::get_string("last_name"),
						"email" => request::get_string("email"),
				);
				if( ! user_data_peer::instance()->insert($ud))
				{
					$this->json["success"] = false;
					$this->json["msgErr"] = "Creating error.";
					return false;
				}
				
				$this->json["data"] = array_merge($ua, $ud);
				
				break;
				
			case "get_list":
			default:
				switch(request::get("filter"))
				{
					case "self-noactive":
						$ua = user_auth_peer::instance()->get_items("registrator = 0 AND active = false");
						$ud = user_data_peer::instance()->get_items();
						break;
					
					case "self-active":
						$ua = user_auth_peer::instance()->get_items("registrator = 0 AND active = true");
						$ud = user_data_peer::instance()->get_items();
						break;
					
					case "self":
						$ua = user_auth_peer::instance()->get_items("registrator = 0");
						$ud = user_data_peer::instance()->get_items();
						break;
					
					case "self":
						$ua = user_auth_peer::instance()->get_items("registrator = 0");
						$ud = user_data_peer::instance()->get_items();
						break;
					
					case "admin-hidden":
						$ua = user_auth_peer::instance()->get_items("registrator > 0 AND hidden = true");
						$ud = user_data_peer::instance()->get_items();
						break;
					
					case "admin-nomail":
						$ua = user_auth_peer::instance()->get_items("registrator > 0 AND email = ''");
						$ud = user_data_peer::instance()->get_items("email = ''");
						break;
					
					case "admin-noactive":
						$ua = user_auth_peer::instance()->get_items("registrator > 0 AND active = false");
						$ud = user_data_peer::instance()->get_items();
						break;
					
					case "admin-active":
						$ua = user_auth_peer::instance()->get_items("registrator > 0 AND active = true");
						$ud = user_data_peer::instance()->get_items();
						break;
					
					case "admin":
						$ua = user_auth_peer::instance()->get_items("registrator > 0");
						$ud = user_data_peer::instance()->get_items();
						break;
					
					case "all-hidden":
						$ua = user_auth_peer::instance()->get_items("hidden = true");
						$ud = user_data_peer::instance()->get_items();
						break;
					
					case "all-nomail":
						$ua = user_auth_peer::instance()->get_items("email = ''");
						$ud = user_data_peer::instance()->get_items("email = ''");
						break;
					
					case "all-noactive":
						$ua = user_auth_peer::instance()->get_items("active = false");
						$ud = user_data_peer::instance()->get_items();
						break;
					
					case "all-active":
						$ua = user_auth_peer::instance()->get_items("active = true");
						$ud = user_data_peer::instance()->get_items();
						break;
					
					case "all":
					default:
						$ua = user_auth_peer::instance()->get_items();
						$ud = user_data_peer::instance()->get_items();
						break;
				}
				
				$data = array();
				foreach($ua as $item)
				{
					$ua = user_auth_peer::instance()->get_item($item);
					if($ud = user_data_peer::instance()->get_item($item))
						$data[] = array_merge($ua, $ud);
				}
				
				$this->json["data"] = $data;
				break;
		}
	}
	
}

?>
