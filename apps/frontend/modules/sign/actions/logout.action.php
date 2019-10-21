<?php

/**
 * Description of sign_logout_action
 *
 * @author Morozov Artem
 */
class sign_logout_action extends frontend_controller
{
	
	public function execute()
	{
		$this->set_renderer("ajax");
		$this->json["success"] = true;
		
		session::unset_user();
		cookie::set('uid', 0, time()+10, '/', conf::get('server'));
	}
	
}

?>
