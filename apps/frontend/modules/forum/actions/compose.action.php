<?php
load::app('/modules/forum/controller');
class forum_compose_action extends forum_controller {
    
    public function execute() {
	parent::execute();
	
	
	
	
	$this->init_input_params();
	
	$edit_id = request::get_int('edit',0);
	if($edit_id && !$this->edit_data = forum_posts_peer::instance()->get_item($edit_id))
	    throw new public_exception('Нет такого сообщения');
	
	$action = request::get('act');
	
	if(in_array($action,array('preview', 'save_post', 'save_topic'))) {
	    $this->set_renderer('ajax');
	    $this->json = $this->$action();
	}
	
	
    }
    
    private function init_input_params() {
	
	$type = request::get('tp');
	$parent_id = request::get_int('pid');
	
	if(!$parent_id)
	    throw new public_exception('Input data error....');
	
	switch($type) {
	    case 'tpc':
		$this->title = t('Новая тема');
		$this->parent = forum_themes_peer::instance()->get_item($parent_id);
		if(!$this->parent) 
		    throw new public_exception('Родительские елемент не существует...');
		break;
	    case 'msg':
		$this->title = t('Ответить');
		$this->parent = forum_topics_peer::instance()->get_item($parent_id);
		$this->last_page = forum_posts_peer::get_posts_count($parent_id);
		if(!$this->parent) 
		    throw new public_exception('Родительские елемент не существует...');
		break;
	    default:
		throw new public_exception('Input data error....');
		break;
	}
    }
    
    
    
    
    
    private function preview() {
	$text = request::get_string('message','');
	$bb = new bbcode(stripslashes($text));
	$bb -> mnemonics = $this->smiles;
	return array('success'=>true, 'content'=>$bb -> get_html());
    }
    
    
    
    
    private function save_post() {
	
	$insert_data = array(
	    'topic_id'=>  request::get_int('pid'),
	    'body'=>  request::get_string('message'),
	    'subject'=>  request::get_string('subject'),
	    'created_ts'=>time(),
	    'user_id'=>  forum_helper::get_user_id()
	);
	
	if(!$insert_data['topic_id'] || (!$topic = forum_topics_peer::instance()->get_item($insert_data['topic_id'])))
	    return array('success'=>false, 'reason'=>t('Родительские елемент не существует...'));
	if(!$insert_data['body']) 
	    return array('success'=>false, 'reason'=>t('Нельзя отправлять пустое сообщение'));
	
	$edit_id = request::get_int('id');
	$exPost =  forum_posts_peer::instance()->get_item($edit_id);
	
	if($edit_id) {
	    if(!$exPost  || (forum_helper::get_user_id()!=$exPost['user_id'] && !session::has_credential('admin')))
		return array('success'=>false, 'reason'=>t('Невозможно отредактировать...'));
	    forum_posts_peer::instance()->update(array_merge(array('id'=>$edit_id),$insert_data));
	    $insId = $edit_id;
	} 
	else 
	    $insId = forum_posts_peer::instance()->insert($insert_data);
	
	return array('success'=>true, 'data'=>  array_merge(array('id'=>$insId),$insert_data));
    }
    
    
    
    
    private function save_topic() {
	
	$insert_data = array(
	    'forum_id'=>  request::get_int('pid'),
	    'body'=>  request::get_string('message'),
	    'subject'=>  request::get_string('subject'),
	    'created_ts'=>time(),
	    'user_id'=>forum_helper::get_user_id()
	);
	
	if(!$insert_data['forum_id'] || !$topic = forum_themes_peer::instance()->get_item($insert_data['forum_id']))
	    return array('success'=>false, 'reason'=>t('Родительские елемент не существует...'));
	if($insert_data['message']) 
	    return array('success'=>false, 'reason'=>t('Нельзя отправлять пустое сообщение'));
	
	$insId = forum_topics_peer::instance()->insert($insert_data);
	
	return array('success'=>true, 'data'=>  array_merge(array('id'=>$insId),$insert_data));
    }
    
    
    
    
    
    private function edit_message() {
	return array('success'=>true, 'data'=>  request::get_all());
    }
}
?>
