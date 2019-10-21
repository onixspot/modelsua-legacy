<?php
load::system("email/email");
class feedback_index_action extends frontend_controller {
    public function execute() {
        if(request::get('send')) {
            $this->set_renderer('ajax');
            $this->disable_layout();
            
            $email = request::get('email');
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) die(json_encode (array('success'=>0, 'reason'=>t('Не корректный адрес електронной почты'))));
            
            $first_name = request::get('name');
            if(!$first_name) die(json_encode (array('success'=>0, 'reason'=>t('Введите имя'))));
            
            $text = request::get('text');
            if(!$text) die(json_encode (array('success'=>0, 'reason'=>t('Введите текст сообщения'))));
            
            $last_name = request::get('last_name');
            if(!$last_name) die(json_encode (array('success'=>0, 'reason'=>t('Введите фамилию'))));
            
            $email_obj = new email();
            $email_obj->setSender($email);
            $email_obj->setSubject($first_name.' '.$last_name);
            $send_text = t('Отправитель').': <b>'.$first_name.' '.$last_name.'</b><br/>'.t('E-mail').': <b>'.$email.'</b><br/>'.t('Телефон').': <b>'.((request::get('phone')) ? request::get('phone') : t('не указан')).'</b><br/><br/>'.$text;
            $email_obj->setBody($send_text);
            $email_obj->isHTML();
            $email_obj->setReceiver('feedback@modelsua.org');
            $email_obj->send();
            
            
            die(json_encode(array('success'=>1,'data'=>'send')));
        }
    }
}
?>
