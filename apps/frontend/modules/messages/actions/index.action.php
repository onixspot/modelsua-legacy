<?php
load::app("./modules/messages/controller");
class messages_index_action extends messages_controller {
    public function execute() {
	
	parent::execute();
	
	load::action_helper('ac',false);
	$this->user_id = session::get_user_id();
	
	$profile = profile_peer::instance()->get_item($this->user_id);
	$credentials = unserialize($profile['credentials']);
	$this->user_type = (in_array('amu',$credentials)) ? 'administration' : (in_array('amu',$credentials) ? 'admin' : 'model');
	
	
	switch($this->user_type) {
	    case 'administration':
		$this->type = request::get('type','private');
		switch($this->type) {
		    case 'private':
			$list = db::get_rows("SELECT id,parent_id FROM messages WHERE (sender=:user_id OR receiver=:user_id) AND type=0 AND (sender_action<2 AND receiver_action<2) ORDER BY created_ts DESC",array('user_id'=>$this->user_id));
			break;
		    case 'public':
			$list = db::get_rows("SELECT id,parent_id FROM messages WHERE type=1 AND (sender_action<2 AND receiver_action<2) ORDER BY created_ts DESC");
			break;
		    default :
			$list = db::get_rows("SELECT id,parent_id FROM messages WHERE (sender=:user_id OR receiver=:user_id) AND type=0 AND (sender_action<2 AND receiver_action<2) ORDER BY created_ts DESC",array('user_id'=>$this->user_id));
			break;
		}
		break;
	    case 'admin':
	    case 'model':
		$this->type = request::get('type','public');
		$list = db::get_rows("SELECT id,parent_id FROM messages WHERE (sender=:user_id OR receiver=:user_id) AND (sender_action<2 AND receiver_action<2) ORDER BY created_ts DESC",array('user_id'=>$this->user_id));
		break;
	}
	
	if(!empty($list)) {
	    
	    $ret = array();
	    $parents = array();

	    foreach ($list as $id=>$item) 
		if($item['parent_id'] && !in_array($item['parent_id'], $parents) && !in_array($item['id'], $ret) && !in_array($item['id'], $ret)) {
			$ret[] = $item['id'];
			$parents[] = $item['parent_id'];
		}
		elseif(!in_array($item['id'], $ret) && !$item['parent_id'] && !in_array($item['id'],$parents)) 
			$ret[] = $item['id'];
//	    echo "<pre>";	
//	    var_dump($ret);
//	    exit;
	    $this->pager = new pager($ret, request::get_int('page'), 10);
	    $this->list = $this->pager->get_list();
	}
	
	if($resId=request::get_int('resend')) {
	    $message = messages_peer::instance()->get_item($resId);
	    if($message)
		$this->body = t("Пересланное сообщение").":\n\n".trim($message['body']);
	}
	
	if(request::get('ajax_search')) {
	    $this->set_renderer('ajax');
	    $this->json = self::get_users_list();
	}
	if(request::get('send_message')) {
	    $this->set_renderer('ajax');
	    $this->json = $this->send_message();
	}
	if(request::get('delete')) {
	    $this->set_renderer('ajax');
	    $mId = request::get('message_id');
	    if(is_array($mId))
		foreach ($mId as $messageId) 
		    $json[] = $this->delete($messageId);
	    else 
		$json = $this->delete($mId);
	    $this->json = $json;
	}
	if(request::get('mark_viewed')) {
	    $this->set_renderer('ajax');
	    $this->json = $this->mark_viewed();
	}
	
	
	
    }
    
    private function mark_viewed() {
	$mList = request::get('messages_list');
	if(is_array($mList)) {
	    foreach ($mList as $mId) {
		$message = messages_peer::instance()->get_item($mId);
		if($message['sender']==session::get_user_id()) 
		    $message['sender_action'] = messages_peer::VIEW_ACTION;
		else 
		    $message['receiver_action'] = messages_peer::VIEW_ACTION;
		messages_peer::instance()->update($message);
	    }
	    return array('success'=>1);
	}
	return array('success'=>0);
		
	    
    }
    
    private function delete($id) {
	    $message_data = messages_peer::instance()->get_item($id);
	    $json = array('success'=>1);
	    if($message_data['sender']==session::get_user_id()) 
		$message_data['sender_action'] = messages_peer::DELETE_ACTION;
	    elseif($message_data['receiver']==session::get_user_id())
		$message_data['receiver_action'] = messages_peer::DELETE_ACTION;
	    else
		$json = array('success'=>0);
	    messages_peer::instance()->update($message_data);
	    return $json;
    }
    
    private function get_users_list() {
	    $data = ac_helper::ajax_search(request::get('ajax_search'));
	    if(!empty ($data)) {
		foreach ($data as $key => $value) {
		    $src = ($value['pid']) ? '/imgserve?pid='.$value['pid'] : '/no_image.png';
		    if($value['ph_crop']) {
			$c = unserialize($value['ph_crop']);
			$src .= '&w='.$c['w'].'&h='.$c['h'].'&x='.$c['x'].'&y='.$c['y'].'&z=crop';
		    }
		    $json[$key]['name'] =profile_peer::get_name($value);
		    $json[$key]['src'] =$src;
		    $json[$key]['id'] = $value['user_id'];
		}
		return $json;
	    }
	    else 
		return array();
    }
    
    private function send_message() {
	$from = session::get_user_id();
	$to = request::get_int('receiver_id');
	$body = request::get_string('body');
	
	$allowed = db::get_cols("SELECT d.user_id FROM user_data d JOIN user_auth a ON a.id=d.user_id WHERE 1=1".ac_helper::set_messages_restrictions());
	if(!in_array($to, $allowed) && $to!=-1)
		return array('success'=>0, 'reason'=>t('Недопустимый получатель'));
	
	if($to) {
	    if($body) {
		$insert_data = array(
		    'sender'=>$from,
		    'receiver'=>$to,
		    'subject'=>  request::get_string('subject'),
		    'body'=>$body,
		    'parent_id'=>  request::get_int('parent_id'),
		    'created_ts'=>time(),
		    'type'=>1
		);
		
		$rec_data = user_auth_peer::instance()->get_item($to);
		$receiver_credentials = ($rec_data) ? unserialize($rec_data['credentials']) : array();
		
		switch ($this->user_type) {
		    case 'administration':
			if(in_array('admin',$receiver_credentials) || in_array('amu',$receiver_credentials)) 
				$insert_data['type']=0;
			if(session::has_credential('superadmin') && !request::get_int('from_amu'))
				$insert_data['type']=0;
//			var_dump($insert_data);
//			exit;
			break;
		    case 'admin':
			if(in_array('admin',$receiver_credentials) || in_array('amu',$receiver_credentials)) 
				$insert_data['type']=0;
			else {
			    $insert_data['sender_action'] = 3;
			    $insert_data['receiver_action'] = 3;
			}
			break;
		    case 'model':
			if(in_array('superadmin',$receiver_credentials))
			    $insert_data['type']=0;
			else 
			    $insert_data['receiver'] = -1;
			break;
		}
		
		if($insert_data['type']==1) 
		    if($to==-1)
			$last = db::get_scalar("SELECT id FROM messages WHERE ((sender=".(int)$insert_data['sender']." AND receiver=-1) OR receiver=".(int)$insert_data['sender'].")  AND type=1 ORDER BY id ASC LIMIT 1");
		    else
			$last = db::get_scalar("SELECT id FROM messages WHERE ((sender=".(int)$insert_data['receiver']." AND receiver=-1) OR receiver=".(int)$insert_data['receiver'].")  AND type=1 ORDER BY id ASC LIMIT 1");
		else 
		    $last = db::get_scalar("SELECT id FROM messages WHERE ((sender=:sender AND receiver=:receiver) OR (sender=:receiver AND receiver=:sender)) AND (receiver_action<2 AND sender_action<2) AND type=".(int)$insert_data['type']." ORDER BY id ASC LIMIT 1",array('sender'=>$insert_data['sender'],'receiver'=>$insert_data['receiver']));

		$insert_data['parent_id'] = ($last) ? $last : 0;
		
		$insId = messages_peer::instance()->insert($insert_data);
		
		if($to==-1) {
		    $administration = db::get_cols("SELECT id FROM user_auth WHERE credentials LIKE '%amu%'");
		    db_key::i()->set('message_'.$insId,  serialize($administration));		
		}
		
		return array('success'=>1);
	    }
	    else 
		return array('success'=>0, 'reason'=>t('Нельзя отправлять пустое сообщение'));
	}
	else 
	    return array('success'=>0, 'reason'=>t('Получатель не указан'));
	
    }
    

}
?>
