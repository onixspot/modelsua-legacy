<?php

load::app("modules/home/controller");

class home_help_action extends home_controller
{
	public function execute()
	{
		parent::execute();
		$this->set_template("index");
	}
}

?>
