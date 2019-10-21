<?php
class sign_autologin_action extends frontend_controller {
    public function execute() {
        
        $post_data = request::get_all();
        if(session::is_authenticated() && !session::has_credential('admin')) 
                $this->redirect ($_SERVER['HTTP_REFERER']);
        if($post_data['save_params']) {
            $this->set_renderer('ajax');
            $this->disable_layout();
            
            if($post_data['new_passwd']!=$post_data['new_passwd_confirm'])
                die(json_encode (array('success'=>0, 'reason'=>t('Введенные пароли не совпадают'))));
            
            $user_auth = db::get_row("SELECT id,email,password,credentials FROM user_auth WHERE security=:code",array('code'=>$post_data['code']));
//	    var_dump($user_auth);
//	    exit;
            if($user_auth['email']) 
                die(json_encode (array('success'=>0, 'reason'=>'Для Вашей учетной записи уже зарегестрирован e-mail: '.$user_auth['email'].'. <br/>Вы можете войти в систему или воспользоваться восстановлением пароля')));
            if($user_auth['id'] && filter_var($post_data['new_email'],FILTER_VALIDATE_EMAIL)) {
                $check = db::get_rows("SELECT id FROM user_auth WHERE email=:email",array('email'=>$post_data['new_email']));
                if(!empty($check))
                        die(json_encode (array('success'=>0, 'reason'=>t('Пользователь с таким адресом уже создан'))));
                $update_data = array(
                                        'id'=>$user_auth['id'],
                                        'email'=>$post_data['new_email'],
                                        'password'=>md5($post_data['new_passwd']),
					'active'=>1,
                                        'activated_ts'=>time()
                                    );
		$user_data = user_data_peer::instance()->get_item($user_auth['id']);
                user_auth_peer::instance()->update($update_data);
		session::set_user_id($user_auth["id"], unserialize($user_auth["credentials"]));
		load::system('email/email');
		$email = new email();
		$email->isHTML();
		$email->setBody(' <p>Здравствуйте, '.$user_data['first_name'].' '.$user_data['last_name'].'!</p><p>Благодарим за активацию Вашего профиля на сайте ModelsUA.org. Ваши данные для входа на сайт:</p>
				  <p>Логин:'.$post_data['new_email'].'<br/>
				  Пароль: '.$post_data['new_passwd'].'</p>
				  <p>С уважением,<br/>
				  Администрация ModelsUA.org</p>');
		$email->setReceiver($post_data['new_email']);
		$email->setSubject('Данные для входа на ModelsUA.org');
		$email->send();
                die(json_encode(array('success'=>1,'url'=> '/profile?id='.$user_auth['id'])));
            }
	    else 
		die(json_encode (array('success'=>0, 'reason'=>t('Введите електронный адрес'))));
        }
        
    }
}
?>
