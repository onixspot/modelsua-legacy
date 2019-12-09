<?php

load::model("geo");
//load::model('geodb');

abstract class geo_controller extends frontend_controller
{
	public function execute()
	{
		$this->disable_layout();
		$this->set_renderer("ajax");
	}
}

