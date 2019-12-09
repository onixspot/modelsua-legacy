<?php

class agencies_peer extends db_peer_postgre
{
	protected $table_name = "agencies";
	protected $primary_key = "id";

	public static function instance()
	{
		return parent::instance("agencies_peer");
	}
}
