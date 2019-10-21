<?php

class user_smi_peer extends db_peer_postgre
{
	protected $table_name = "user_smi";
	
	public static function instance()
	{
		return parent::instance("user_smi_peer");
	}
}

?>
