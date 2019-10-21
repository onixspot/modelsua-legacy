<?php
load::app("./modules/messages/controller");
class messages_view_action extends messages_controller {
    public function execute() {
	parent::execute();
	
	$id = request::get_int('id');
	if(!$message = messages_peer::instance()->get_item($id))
		throw new public_exception (t('Сообщение не существует'));
	
	
	if($message['receiver']==-1) {
	    
	    if(session::has_credential('amu'))
		if(db_key::i()->exists('message_'.$id)) {
		    $ids = unserialize(db_key::i()->get('message_'.$id));
		    if(in_array(session::get_user_id(),$ids)) 
			    unset($ids[array_search (session::get_user_id (), $ids)]);
		    if(empty($ids)) 
			db_key::i ()->delete ('message_'.$id);
		    else 
			db_key::i ()->set('message_'.$id,  serialize ($ids));
		}
		$messager = $message['sender'];
		$message['receiver_action'] = messages_peer::VIEW_ACTION;
	}
	elseif($message['type']==messages_peer::ADMIN_TYPE) {
	    $messager = $message['receiver'];
	    $message['receiver_action'] = messages_peer::VIEW_ACTION;
	}
	elseif($message['sender']==session::get_user_id()) {
	    $messager = $message['receiver'];
	    $message['sender_action'] = messages_peer::VIEW_ACTION;
	} else {
	    $messager =  $message['sender'];
	    $message['receiver_action'] = messages_peer::VIEW_ACTION;
	}
	
	if($message['parent_id']) {
	    $parents = db::get_rows("SELECT * FROM messages WHERE parent_id=:pid AND receiver_action<2",array('pid'=>$message['parent_id']));
	    if(!empty($parents)) 
		foreach ($parents as $key => $value) {
		    if($value['receiver']==-1) {
			if(db_key::i()->exists('message_'.$value['id']) && session::has_credential('amu')) {
			    $ids = unserialize(db_key::i()->get('message_'.$value['id']));
			    if(in_array(session::get_user_id(),$ids)) 
				    unset($ids[array_search (session::get_user_id (), $ids)]);
			    if(empty($ids)) 
				db_key::i ()->delete ('message_'.$value['id']);
			    else
				db_key::i()->set('message_'.$value['id'],  serialize($ids));
			}
		    }
		    $parents[$key]['receiver_action'] = messages_peer::VIEW_ACTION;
		    messages_peer::instance()->update($parents[$key]);
		}
	    messages_peer::instance()->update(array('id'=>$message['parent_id'],'receiver_action'=>1));
	}
	
	
	messages_peer::instance()->update($message);
	$this->message_data = $message;
	$this->messager = user_data_peer::instance()->get_item($messager);
	$list = messages_peer::get_messages_by_users($message);
	$this->pager = new pager($list, request::get_int('page'),10);
	$this->list = $this->pager->get_list();
    }
}
?>
