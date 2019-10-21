<?php

load::app("modules/adminka/controller");
load::model("journals/journals");
load::model("user/profile");

class adminka_journals_manager_action extends adminka_controller
{
	public function execute()
	{
		parent::execute();
		
		$act = request::get_string("act");
		
		if(in_array($act, array("save_journal", "remove_journal", "publicate_journal")))
		{
			$this->set_renderer("ajax");
			return $this->json["success"] = $this->$act();
		}
		
		$this->set_template("index");
		
		$this->adminka["frame"] = "journals_manager";
		$this->adminka["act"] = $act;
		
		if(in_array($act, array("add", "edit")))
		{
			$this->journal = array();
			if($id = request::get_int("id"))
				$this->journal = journals_peer::instance ()->get_item($id);
			
			return;
		}
		
		$this->journals_list = journals_peer::instance()->get_list(array(), array(), array("id DESC")); // journals_peer::instance()->get_list(array(), array(), array("id DESC"))
	}
	
	public function save_journal()
	{
		$id = request::get_int("id");
		$name = stripslashes(request::get_string("name"));
		$country = stripslashes(request::get_int("country"));
		$description = stripslashes(request::get_string("description"));
		
		$data = array(
			"name" => $name,
			"country" => $country,
			"region" => 0,
			"city" => 0,
			"another_city" => '',
			"description" => $description,
		);
		
		if($id > 0)
		{
			$this->json["id"] = $data["id"] = $id;
			journals_peer::instance()->update($data);
		}
		else 
		{
			if( ! $this->json["id"] = journals_peer::instance()->insert($data))
				return false;
		}
		
		return true;
	}
	
	public function remove_journal()
	{
		$id = request::get_int("id");
		
		journals_peer::instance()->delete_item($id);
		
		return true;
	}
	
	public function publicate_journal()
	{
		$id = request::get_int("id");
		$state = request::get_int("state");
		
		$data = array(
			"id" => $id,
			"public" => $state
		);
		
		journals_peer::instance()->update($data);
		
		return true;
	}
}

?>
