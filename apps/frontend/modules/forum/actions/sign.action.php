<?php
load::app('./modules/forum/controller');
class forum_sign_action extends forum_controller {
    
    public function execute() {
	parent::execute();
	$act = request::get('act');
	if(in_array($act, array('register','login','logout', 'check_username', 'check_email')))
	{
	    $this->set_renderer('ajax');
	    $this->json = $this->$act();
	}
    }
    
    private function register() {
	
	$password = request::get('passwd');
	$request = array(
	    'login'=>  request::get('username'),
	    'email'=>  request::get('email'),
	    'password'=>md5($password)
	);
	
	if(!$request['login']) return array('success'=>false, 'reason'=>t('Введите логин'));
	if(!filter_var($request['email'],FILTER_VALIDATE_EMAIL)) return array('success'=>false, 'reason'=>t('Некорректный електронный адрес'));
	if($request['passwd']!=request::get('passwd_confirm')) return array('success'=>false, 'reason'=>t('Введенные пароли не совпадают'));
	
	$check_login = $this->check_username();
	$check_email = $this->check_email();
	
	if(!$check_login['success']) return $check_login;
	if(!$check_email['success']) return $check_email;
	
	forum_users_peer::instance()->insert($request);
	
	if(!($mail_send = $this->send_invite())) return $mail_send;
	
	return array('success'=>true, 'data'=>$request);
    }
    
    private function send_invite() {
	if(!filter_var(request::get('email'),FILTER_VALIDATE_EMAIL))
		return array('success'=>false, 'reason'=>t('Некорректный електронный адрес'));
	$mail = new email(request::get('email'), 'Регистрация на форуме МоделсУА.орг', "Логин: ".request::get('username')."\r\n Пароль: ".request::get('password'));
	//$mail->send();
	return array('success'=>true);
    }
    
    private function check_username() {
	$check_login = db::get_scalar("SELECT id FROM forum_users WHERE login=:login",array('login'=>  request::get('username')));
	return ($check_login) ? array('success'=>false, 'reason'=>t('Указаный логин уже занят')) : array('success'=>true);
    }
    
    private function check_email() {
	$check_email = db::get_scalar("SELECT id FROM forum_users WHERE email=:email",array('email'=>  request::get('email')));
	return ($check_email) ? array('success'=>false, 'reason'=>t('Указаный email уже зарегестрирован')) : array('success'=>true);
    }
    
    private function login() {
	
	$username = request::get('username');
	$passwd = request::get('password');
	$error = array('success'=>false, 'reason'=>'Не правильный логин или пароль');
	
	$forum_user = db::get_row("SELECT * FROM forum_users WHERE login=:login AND password=:passwd",array('login'=>$username,'passwd'=>md5($passwd)));
	
	if(!$forum_user) return $error;
	
	session::set('forum_user_id', $forum_user['id']);
	
	if($forum_user['active']) 
	    forum_users_peer::instance()->update(array('id'=>$forum_user['id'],'active'=>TRUE));
	
	if(request::get_int('autologin'))
	    cookie::set('ufid', $forum_user["id"], time()+60*60*24*30, '/forum', conf::get('server'));
	
	return array('success'=>true);
    }
    
    private function logout() {
	forum_helper::unset_user();
	cookie::set('ufid', 0, time()+10, '/forum', conf::get('server'));
	return array('success'=>true);
    }
}
?>
