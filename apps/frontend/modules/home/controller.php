<?php

abstract class home_controller extends frontend_controller
{
	public function execute()
	{
		$this->module = "home";
		$this->options = request::get_all();
	}
}

?>
