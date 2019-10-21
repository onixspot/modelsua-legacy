<?php

load::app("modules/adminka/controller");

class adminka_statistic_action extends adminka_controller
{
	public function execute()
	{
		parent::execute();
		
		$list = user_data_peer::instance()->get_list();
		
		$this->cnt = 0;
		$this->learned_about = array();
		foreach($list as $item_id)
		{
			$item = user_data_peer::instance()->get_item($item_id);
			if($item['learned_about'] != '')
			{
				if( ! isset($this->learned_about[$item['learned_about']]['cnt']))
					$this->learned_about[$item['learned_about']]['cnt'] = 0;
				
				$this->learned_about[$item['learned_about']]['cnt']++;
				$this->cnt++;
			}
		}
	}
}

?>
