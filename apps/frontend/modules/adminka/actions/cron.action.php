<?php
class adminka_cron_action extends basic_controller {
    
    const EMAILS_ONCE = 3;
    const MESSAGES_ONCE = 3;
    
    public function execute() {
	$this->disable_layout();
	$this->set_renderer('ajax');
	
	load::model('mailing');
	load::system('email/email');
	
	$list = db::get_cols("SELECT id FROM mailing WHERE complete=false ORDER BY id ASC");
	
	if(!empty($list))
	    foreach ($list as $id) {
		$maillist = mailing_peer::instance()->get_item($id);
		if($maillist['type']==mailing_peer::TYPE_EMAIL)
		    $this->send_email ($maillist);
		elseif($maillist['type']==mailing_peer::TYPE_MESSAGE)
		    $this->send_message ($maillist);
		
	    }
    }
    
    private function send_email($maillist) {
	
	$sended = (int)$maillist['sended'];
	$users = array_slice(mailing_peer::prepare_list($maillist['filters']),$sended);
	if(!$sended) $maillist['start'] = time();
	
	if(!empty($users))
	    foreach ($users as $key=>$receiver_id) {
		$receiver_data = profile_peer::instance()->get_item($receiver_id);

		$mail = new email($receiver_data['email'], stripslashes($maillist['subject']), stripslashes($maillist['body']));
		$mail->setSender(stripslashes($maillist['sender_email']), stripslashes($maillist['sender_name']));
		$mail->isHTML();
		$mail->send();

		$sended++;

		$maillist['sended'] = $sended;
		mailing_peer::instance()->update($maillist);

		if($sended==$maillist['receivers']) {
		    $maillist['complete'] = true;
		    $maillist['end'] = time();
		    mailing_peer::instance()->update($maillist);
		    die();
		}

		if($key==(self::EMAILS_ONCE-1))
		    die();
	    }
	else {
	    $maillist['complete'] = true;
	    $maillist['end'] = time();
	    mailing_peer::instance()->update($maillist);
	    die();
	}
    }
    
    private function send_message($maillist) {
	
	load::model('messages');
	
	$sended = (int)$maillist['sended'];
	$users = array_slice(mailing_peer::prepare_list($maillist['filters']),$sended);
	if(!$sended) $maillist['start'] = time();
	
	if(!empty($users))
	    foreach ($users as $key=>$receiver_id) {
		
		$insert_data = array(
		    'sender'=>$maillist['user_id'],
		    'receiver'=>$receiver_id,
		    'body'=>  stripslashes($maillist['body']),
		    'subject'=>stripslashes($maillist['subject']),
		    'type'=>1,
		    'created_ts'=>time()
		);
		messages_peer::instance()->insert($insert_data);
		$sended++;

		$maillist['sended'] = $sended;
		mailing_peer::instance()->update($maillist);

		if($sended==$maillist['receivers']) {
		    $maillist['complete'] = true;
		    $maillist['end'] = time();
		    mailing_peer::instance()->update($maillist);
		    die();
		}

		if($key==(self::MESSAGES_ONCE-1))
		    die();
	    }
	else {
	    $maillist['complete'] = true;
	    $maillist['end'] = time();
	    mailing_peer::instance()->update($maillist);
	    die();
	}
    }
    
}
?>
