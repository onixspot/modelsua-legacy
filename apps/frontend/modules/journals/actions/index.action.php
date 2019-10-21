<?php

load::app("modules/journals/controller");
load::model("user/profile");
load::model("user/user_photos");
load::model("user/user_albums");
load::model("journals/journals");

class journals_index_action extends journals_controller
{
	public function execute()
	{
		parent::execute();
		
		$act = request::get_string("act");
		if(in_array($act, array("set_location", "save_about", "save_contacts")))
		{
			$this->set_renderer("ajax");
			return $this->json["success"] = $this->$act();
		}
		
		if( ! ($this->journal_id = request::get_int("id")) || ! ($this->journal = journals_peer::instance()->get_item($this->journal_id)))
		{
			$this->redirect("/journals/list");
			exit(0);
		}
	}
	
	public function set_location()
	{
		$id = request::get_int("id");
		
		if( ! journals_peer::instance()->get_item($id))
		{
			return false;
		}
		
		$data = array(
			"id" => $id,
			"country" => request::get_int("country"),
			"region" => request::get_int("region"),
			"city" => request::get_int("city"),
			"another_city" => request::get_string("another_city"),
		);
		
		journals_peer::instance()->update($data);
		$this->json["location"] = profile_peer::get_location($data);
		
		return true;
	}
	
	public function save_about()
	{
		$id = request::get_int("id");
		
		if( ! journals_peer::instance()->get_item($id))
		{
			return false;
		}
		
		$data = array(
			"id" => $id,
			"about" => stripcslashes(request::get_string("value"))
		);
		
		journals_peer::instance()->update($data);
		
		return true;
	}
	
	public function save_contacts()
	{
		$id = request::get_int('id');
		
		if( ! journals_peer::instance()->get_item($id))
			return false;
		
		$contacts = request::get_array('data');
		
		if( ! is_array($contacts))
			return false;
		
		$data = array(
			'id' => $id,
			'contacts' => $contacts
		);
		
		journals_peer::instance()->update($data);
		
		return true;
	}
}

?>
