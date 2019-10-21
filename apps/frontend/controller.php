<?

load::model('user/user_auth');
load::model('user/user_data');
load::model('user/profile');
load::model('user/user_albums');
load::view_helper('ui', FALSE);

abstract class frontend_controller extends basic_controller
{
	public function pre_init()
	{
		parent::pre_init();
		if($_GET['module'] == '' && $_GET['subdomain'] != '')
		{
			$tokens = db::get_scalar('SELECT COUNT(id) FROM user_data WHERE subdomain = :subdomain', array('subdomain' => $_GET['subdomain']));
			if($tokens > 0)
				$this->redirect('/profile');
			else
				$this->redirect('/agency');
		}
	}
	
	public function init()
	{
		parent::init();
		
		profile_peer::instance();
		user_albums_peer::instance();
		
		if( ! session::get_user_id())
		{
			if($uid = cookie::get('uid'))
			{
				if($profile = profile_peer::instance()->get_item($uid))
				{
					$credentials = unserialize($profile["credentials"]);
					if( ! is_array($credentials))
						$credentials = array();

					session::set_user_id($profile["user_id"], unserialize($profile["credentials"]));
					cookie::set('uid', $profile["user_id"], time()+60*60*24*30, '/', conf::get('server'));
				}
			}
		}
		
		client_helper::set_title(t("Ассоциация моделей Украины"));
		
		session::set('language',session::get('language','ru'));
		translate::set_lang(session::get('language','ru'));
		
		$this->menu_items = array(
			array(
				"href" => "/page?link=about",
				"html" => t('Ассоциация'),
				'static_link' => 'about'
			),
			array(
				"href" => "/people",
				"html" => t('Модели'),
				"children" => (session::get('language') == 'en') ? false : array(
					array(
						"link" => "/people",
						"title" => serialize(array(session::get('language') => t("Каталог моделей"))),
					),
					array(
						"link" => "/people",
						"title" => serialize(array(session::get('language') => t("Рейтинг успешных моделей"))),
						"hidden" => true,
					),
					array(
						"link" => "/polls/rating?type=1",
						"title" => serialize(array(session::get('language') => t("Рейтинг популярности"))),
						"hidden" => true
					),
					array(
						"link" => "/people",
						"title" => serialize(array(session::get('language') => t("Перспективные модели"))),
						"hidden" => true,
					),
					array(
						"link" => "/people",
						"title" => serialize(array(session::get('language') => t("Новые лица"))),
						"hidden" => true,
					),
					array(
						"link" => "/search",
						"title" => serialize(array(session::get('language') => t("Поиск"))),
						"hidden" => session::has_credential('admin') ? false : true,
					),
				)
			),
			array(
				"href" => "/agency/list",
				"html" => t('Агентства'),
			),
			array(
				"href" => "/journals/list",
				"html" => t('Журналы'),
			),
			array(
				"href" => "/page?link=profession",
				"html" => t('Полезное'),
				"hidden" => true,
				'static_link' => 'profession'
			),
			array(
				"href" => "/page?link=projects",
				"html" => t('Проекты'),
				'static_link' => 'projects'
			),
			array(
				"href" => "/page?link=discount",
				"html" => t('Дисконт')
			),
			array(
				"href" => "/home?id=2",
				"html" => t('Партнеры'),
				"hidden" => true,
			),
			array(
				"href" => "/library",
				"html" => t('Библиотека'),
				"hidden" => session::get('language') == 'ru' ? false : true
			),
			array("href" => "/contacts", "html" => t('Контакты')),
		);
		
		load::model('pages');
		$nodes = db::get_cols("SELECT * FROM pages WHERE parent_id=0");
		$this->menu_tree = pages_peer::instance()->build_tree($nodes,true);
	}
	
	public function post_action()
	{
		parent::post_action();
	}
}