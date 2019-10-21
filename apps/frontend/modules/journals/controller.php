<?php

class journals_controller extends frontend_controller
{
	public function execute()
	{
		$this->access = session::has_credential("admin") ? true : false;
	}
}

?>
