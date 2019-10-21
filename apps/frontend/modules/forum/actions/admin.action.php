<?php
load::app('./modules/forum/controller');
class forum_admin_action extends forum_controller {
    
    public function execute() {
	parent::execute();
	
	$frame = request::get_string('frame', 'sections');
	
	
	switch($frame) {
	    case 'sections':
		$this->post_data = forum_sections_peer::instance()->get_item(request::get_int('id'));
		$list = forum_sections_peer::instance()->get_list();
		break;
	    case 'forums':
		$this->post_data = forum_themes_peer::instance()->get_item(request::get_int('id'));
		$sections = forum_sections_peer::instance()->get_list();
		$this->parents = array();
		if(!empty($sections))
		    foreach ($sections as $key=>$section_id) 
			$this->parents[$section_id] = db::get_scalar("SELECT name FROM forum_sections WHERE id=:id",array('id'=>$section_id));
		$list = forum_themes_peer::instance()->get_list();
		break;
	}
	
	$this->pager = new pager($list, request::get_int('page'), 20);
	$this->list = $this->pager->get_list();
	
	$act = request::get('act');
	if(in_array($act,array('save_section','delete_section', 'get_child_forums', 'save_forum', 'delete_forum'))) {
	    $this->set_renderer('ajax');
	    $this->json = $this->$act();
	}
    }
    
    private function get_child_forums() {
	$list = db::get_rows("SELECT id,subject FROM forum_themes WHERE section_id=:sid",array('sid'=>request::get_int('section_id')));//forum_themes_peer::instance()->get_list(array('section_id'=>$sid));
	return (!empty($list)) ? array('success'=>true, 'data'=>$list) : array('success'=>false);
    }
    
    private function save_section() {
	$insert_data = array(
	    'name'=>  request::get_string('section_name'),
	    'user_id'=>session::get_user_id(),
	    'created_ts'=>time()
	);
	
	$id = request::get_int('id');
	$exSection = forum_sections_peer::instance()->get_item($id);
	
	if(!$insert_data['name'])
	    return array('success'=>false, 'reason'=>t('Название темы не может быть пустым'));
	
	if($id) {
	    if($exSection) {
		forum_sections_peer::instance()->update(array_merge(array('id'=>$id,'weight'=>$exSection['weight']),$insert_data));
		return array('success'=>true, 'act'=>'update');
	    }
	    else {
		return array('success'=>false, 'reason'=>'Input data error...');
	    }
	}
	else {
	    $insert_data['weight'] = ((int)db::get_scalar("SELECT MAX(weight) FROM forum_sections")+1);
	    forum_sections_peer::instance()->insert($insert_data);
	    return array('success'=>true, 'act'=>'insert');
	}
    }
    
    private function delete_section() {
	
	$section_id = request::get_int('section');
	$section = forum_sections_peer::instance()->get_item($section_id);
	
	if(!$section) return array('success'=>false, 'reason'=>"Input data error");
	if(!session::has_credential('admin')) return array('success'=>false, 'reason'=>t("Не достаточно прав..."));
	
	//forum_sections_peer::instance()->delete_item($section_id);
	
	return array('success'=>true);
	
    }
    
    private function save_forum() {
	
	$insert_data = array(
	    'subject'=>  request::get_string('forum_name'),
	    'body'=>  request::get_string('forum_body'),
	    'parent_id'=>  request::get_int('parent_id'),
	    'section_id'=>  request::get_int('section_id'),
	    'user_id'=>session::get_user_id(),
	    'created_ts'=>time()
	);
	
	$id = request::get_int('id');
	$exSection = forum_themes_peer::instance()->get_item($id);
	
	if(!$insert_data['subject'])
	    return array('success'=>false, 'reason'=>t('Название форума не может быть пустым'));
	if(!$insert_data['body'])
	    return array('success'=>false, 'reason'=>t('Описание форума не может быть пустым'));
	
	if($id) {
	    if($exSection) {
		forum_themes_peer::instance()->update(array_merge(array('id'=>$id,'weight'=>$exSection['weight']),$insert_data));
		return array('success'=>true, 'act'=>'update');
	    }
	    else {
		return array('success'=>false, 'reason'=>'Input data error...');
	    }
	}
	else {
	    $insert_data['weight'] = ((int)db::get_scalar("SELECT MAX(weight) FROM forum_sections")+1);
	    forum_themes_peer::instance()->insert($insert_data);
	    return array('success'=>true, 'act'=>'insert');
	}
    }
    
    private function delete_forum() {
	
	$section_id = request::get_int('section');
	$section = forum_themes_peer::instance()->get_item($section_id);
	
	if(!$section) return array('success'=>false, 'reason'=>"Input data error");
	if(!session::has_credential('admin')) return array('success'=>false, 'reason'=>t("Не достаточно прав..."));
	
	//forum_themes_peer::instance()->delete_item($section_id);
	
	return array('success'=>true);
	
    }
}
?>
