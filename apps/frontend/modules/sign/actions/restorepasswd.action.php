<?php
class sign_restorepasswd_action extends frontend_controller {
    
    public function execute() {
	
	if(session::is_authenticated())
	    $this->redirect ($_SERVER['HTTP_REFERER']);
	
	$this->set_renderer('ajax');
	
	$email = request::get('restore_email');
	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		$this->json = array('success'=>false, 'reason'=>t('Некорректный електронный адрес'));
	elseif(!$profile = db::get_row("SELECT * FROM user_auth WHERE email=:email",array('email'=>$email)))
		$this->json = array('success'=>false, 'reason'=>t('Адрес не существует...'));
	else {
	    $newpasswd = substr(md5(time()+'ololo'),0,8);
	    
	    $profile['password'] = md5($newpasswd);
	    user_auth_peer::instance()->update($profile);
	    
	    load::system('email/email');
	    $mail = new email($email, 'Восстановление пароля на ModelsUA.org');
	    $mail->isHTML();
	    $body = 'Данные для входа на ModelsUA.org: <br/> Логин: <b>'.$email.'</b><br/> Пароль: <b>'.$newpasswd.'</b>';
	    $mail->setBody($body);
	    $mail->send();
	    
	    $this->json = array('success'=>true);
	}
	
	
	
    }
}
?>
