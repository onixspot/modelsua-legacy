<?

load::model("user/profile");

class news_peer extends db_peer_postgre
{
        
        protected $table_name = 'news';
        public $html='';
        
        const NEWS_TYPE = 1;
        const PUBLICATIONS_TYPE =2;
        const ANONS_TYPE = 3;
        
	/**
	 * @return user_auth_peer
	 */
	public static function instance()
	{
		return parent::instance( 'news_peer' );
	}
        public static function get_types($id=null) {
            $ret = array(
                            self::NEWS_TYPE=>t('Новости'),
                            self::PUBLICATIONS_TYPE=>t("Публикации"),
                            self::ANONS_TYPE=>t("Анонсы")
                        );
            return ($id) ? (isset($ret[$id]) ? $ret[$id] : false ) : $ret;
        }
        public function get_content($id) {
            $data = self::instance()->get_item($id);
            if($data) {
                $tmp =  unserialize($data['title']);
                $data['title'] = stripslashes($tmp[session::get('language','ru')]);
                $tmp =  unserialize($data['body']);
                $data['body'] = stripslashes($tmp[session::get('language','ru')]);
                $tmp =  unserialize($data['anons']);
                $data['anons'] = stripslashes($tmp[session::get('language','ru')]);
				$data['models'] = unserialize($data['models']);
                return $data;
            }
            return false;
        }
}
?>
