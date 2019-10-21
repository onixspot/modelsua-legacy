<?php

load::app("modules/journals/controller");
load::model("journals/journals");
load::model("user/profile");

class journals_list_action extends journals_controller
{
	public function execute()
	{
		parent::execute();
		
		$this->countries = array(
			9908 => array(),
			3159 => array(),
			10668 => array(),
			1786 => array(),
			5681 => array(),
			277565 => array(),
			582040 => array(),
			1012 => array(),
			1707 => array(),
			3141 => array(),
			1258 => array(),
			277563 => array(),
			582050 => array(),
			277555 => array(),
			277559 => array()
		);
		
		$this->journals_list = journals_peer::instance()->get_list(array("public" => true), array(), array("name ASC"));
		
		foreach($this->journals_list as $id)
		{
			$country = journals_peer::instance()->get_item_country($id);
			$this->countries[$country][] = $id;
		}
	}
}
?>
