<?php
load::app('/modules/adminka/controller');
class adminka_mailer_action extends adminka_controller {
    public function execute() {
	
	parent::execute();
	
 	$act = request::get('act');
	
	switch(request::get('frame')) {
	    case 'drafts':
		$list = db::get_cols("SELECT id FROM mailing WHERE type=2");
		break;
	    case 'active':
		$list = db::get_cols("SELECT id FROM mailing WHERE complete=false AND sended>0 AND type<2");
		break;
	    case 'in_queue':
		$list = db::get_cols("SELECT id FROM mailing WHERE complete=false AND sended=0 AND type<2");
		break;
	    case 'complete':
		$list = db::get_cols("SELECT id FROM mailing WHERE complete=true AND type<2");
		break;
	    default: 
		if(request::get_int('id')) 
		    $this->post = mailing_peer::instance()->get_item(request::get_int('id'));
		break;
	}
	
	if($list)
	{
	    $this->pager = new pager($list, request::get_int('page'), 15);
	    $this->list = $this->pager->get_list();
	}
	
	if(in_array($act, array('send','delete_draft','test_send')))
	{
	    $this->set_renderer('ajax');
	    $this->json = $this->$act();
	}
    }
    
    private function test_send() {
	$receivers = explode(',',request::get('test_receivers'));
	if(is_array($receivers) && !empty($receivers))
	    foreach ($receivers as $receiver) {
		if(filter_var($receiver,FILTER_VALIDATE_EMAIL)) {
		    $email = new email($receiver, stripslashes(request::get('subject')), stripslashes(request::get('body')));
		    $email->setSender(request::get('sender_email'), request::get('sender_name'));
		    $email->send();
		}
	    }
	return array('success'=>true);
    }
    
    private function delete_draft() {
	$id = request::get_int('draft_id');
	mailing_peer::instance()->delete_item($id);
	return array('success'=>true);
    }
    
    private function send() {
	
	$this->set_renderer('ajax');
	$users_list =  mailing_peer::prepare_list($this->prepare_filters());

	$insert_data = array(
	    'sender_name'=>  request::get('sender_name'),
	    'sender_email'=>  request::get('sender_email'),
	    'body'=> str_replace("'", '"', request::get('body')),
	    'subject'=>  request::get('subject'),
	    'filters'=>$this->prepare_filters(),
	    'receivers'=>count($users_list),
	    'sended'=>0,
	    'complete'=>false,
	    'user_id'=>session::get_user_id(),
	    'type'=>  request::get_int('maillist_type')
	);
	
	if(!$insert_data['body'])
	    return array('success'=>false, 'reason'=>'Пустое сообщение...');
	elseif(!$insert_data['sender_name'])
	    return array('success'=>false, 'reason'=>'Введите имя отправителя');
	elseif(!filter_var($insert_data['sender_email'], FILTER_VALIDATE_EMAIL))
	    return array('success'=>false, 'reason'=>'Некорректная електронная почта отпраителя');
	elseif(!$insert_data['subject'])
	    return array('success'=>false, 'reason'=>'Введите тему письма');
	elseif(empty($users_list))
	    return array('success'=>false, 'reason'=>'Пользователей, удовлетворяющих заданым фильтрам, не найдено...');
	elseif(request::get_int('mail_id')) {
	    mailing_peer::instance()->update(array_merge(array('id'=>  request::get_int('mail_id')),$insert_data));
	    return array('success'=>true,'action'=>'update');
	} else {
	    mailing_peer::instance ()->insert ($insert_data);
	    return array('success'=>true,'action'=>'insert');
	}   
	
	    
    }
    
    private function prepare_filters() {
	
	$request = request::get_all();
	$filters = array();
	
	foreach ($request as $key => $value) {
	    
	    switch($key) {
		case 'status':
		    if(is_array($request['status-id'])) {
			foreach ($request['status-id'] as $key=>$id) 
			   $request['status-id'][$key] = (int)$id; 
			$filters['status'] = serialize($request['status-id']);
		    }
		    break;
		case 'agency':
		    if(is_array($request['agency-id'])) {
			foreach ($request['agency-id'] as $key=>$id) 
			   $request['agency-id'][$key] = (int)$id; 
			$filters['agency'] = serialize($request['agency-id']);
		    }
		    break;
		case 'location':
		    if(request::get_int('country')) {
			$loc['country'] = request::get_int('country');
		    }
		    if(request::get_int('region')) {
			$loc['region'] = request::get_int('region');
		    }
		    if(request::get_int('city')) {
			$loc['city'] = request::get_int('city');
		    }
		    if(request::get('another_city')) {
			$loc['another_city'] = request::get_int('another_city');
		    }
		    $filters['location'] = serialize($loc);
		    break;
		case 'extended':
		    $filters['extended'] = serialize(array(
			    'not_approved'=>(request::get_int('extended-not_approved')) ? 1 : 0,
			    'active'=>(request::get_int('extended-active')) ? 1 : 0,
			    'reserv'=>(request::get_int('extended-reserv')) ? 1 : 0,
			    'public'=>(request::get_int('extended-public')) ? 1 : 0,
			    'in_work'=>(request::get_int('extended-in_work')) ? 1 : 0,
			    'archive'=>(request::get_int('extended-archive')) ? 1 : 0
		    ));
		    break;
	    }
	}
	return serialize($filters);
    }
}
?>
