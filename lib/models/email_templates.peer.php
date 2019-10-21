<?
class email_templates_peer extends db_peer_postgre
{
        
        protected $table_name = 'email_templates';
        /**
	 * @return email_templates_peer
	 */
	public static function instance()
	{
		return parent::instance( 'email_templates_peer' );
	}
	
	public static function get_email_template($alias='') {
	    return db::get_row("SELECT * FROM email_templates WHERE alias=:alias",array('alias'=>$alias));
	}
}
?>
