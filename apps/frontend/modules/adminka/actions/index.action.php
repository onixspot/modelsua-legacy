<?php

load::app("modules/adminka/controller");

class adminka_index_action extends adminka_controller
{
	
	public function execute()
	{
		parent::execute();
		$this->adminka["frame"] = "";
	}
	
}

?>
