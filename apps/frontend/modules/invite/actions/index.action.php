<?php
class invite_index_action extends frontend_controller {
    
    protected $authorized_access=true;


    public function execute() {
	if(request::get('submit')) {
	    
	    $this->set_renderer('ajax');
	    
	    $emails = explode(',', request::get('emaillist'));
	    $skip = request::get_int('ignore');
	    $sender = profile_peer::instance()->get_item(session::get_user_id());
	    foreach ($emails as $k => $v) {
		if(trim($v)) {
		    $check = db::get_scalar("SELECT id FROM user_auth WHERE email=:email",array('email'=>trim($v)));
		    if($check) {
			$exist_emails[] = trim($v);
		    }
		    elseif(filter_var(trim($v),FILTER_VALIDATE_EMAIL)) {
			$checked_email[] = trim($v);
		    }
		    elseif(!$skip) {
			$bad_emails[] = trim($v);
		    }
//		    var_dump($v);
//		    exit;
		}
	    }
	    
	    if($checked_email) {
		$this->json['success'] = true;
		$this->json['checked_emails'] = $checked_email;
		$this->json['bad_emails'] = $bad_emails;
		$this->json['exist_emails'] = $exist_emails;
	    }
	    elseif(!$checked_email) {
		$this->json['success'] = false;
		$this->json['reason'] = t('Введенные електронные адреса не корректные или уже зарегестрированы');
		return;
	    }
	    
	    $body = request::get('body');
	    if(!$body) {
		$this->json['success'] = false;
		$this->json['reason'] = t('Нельзя отправлять пустое сообщение');
		return;
	    }
	    
	    load::system("email/email");
	    if($checked_email)
		foreach ($checked_email as $item) {
		    $email = new email($item, t("Приглашаю тебя на ModelsUa.org"), $body);
		    $email->setSender($sender['email'],$sender['first_name'].' '.$sender['last_name']);
		    $email->send();
		}
	    
	}
    }
}
?>
