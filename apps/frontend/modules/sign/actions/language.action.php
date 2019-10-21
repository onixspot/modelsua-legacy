<?

class sign_language_action extends frontend_controller
{
	public function execute()
	{
		$codes = array('ru', 'en');
		$code = request::get('code');
                
		if ( !in_array($code, $codes) )
		{
			$code = 'ru';
		}

		session::set('language', $code);
		$this->redirect($_SERVER['HTTP_REFERER']);
	}
}