<?php
load::app("modules/adminka/controller");
class adminka_email_templates_action extends adminka_controller {
    public function execute() {
	parent::execute();
	$this->set_template("index");
	$this->adminka["frame"] = "email_templates";
	$this->adminka["act"] = request::get("act");
	
	$list = email_templates_peer::instance()->get_list(array(),array(),array('id ASC'));
	$this->pager = new pager($list, request::get_int('page'), 20);
	$this->list = $this->pager->get_list();
	
	if($eId = request::get_int('edit_id'))
		$this->post_data = email_templates_peer::instance ()->get_item ($eId);
	
	if(request::get('body')) {
	    $this->set_renderer('ajax');
	    $request = request::get_all();
	    unset($request['module'],$request['action']);
	    $this->json = $this->modify_data($request);
	}
	
	if(request::get('delete')) {
	    $this->set_renderer('ajax');
	    $this->json = $this->delete_item(request::get_int('del_id'));
	}
    }
    
    private function modify_data($data) {
	$fields = array_keys(db::get_row("SELECT * FROM email_templates LIMIT 1"));
	foreach($data as $field=>$value) 
	    if($value && in_array($field, $fields))
		$email_data[$field] = stripslashes ($value);
	if(count($email_data)<6) return array('success'=>false, 'reason'=>"Не все поля заполнены");
	$check = email_templates_peer::get_email_template($email_data['alias']);
	if(!$email_data['id'] && !empty($check))
	    return array('success'=>false, 'reason'=>"Шаблон с таким псевдонимом уже создан");
	if($email_data['id']) {
	    email_templates_peer::instance ()->update ($email_data); 
	    $insId=$email_data['id'];
	}
	else 
	    $insId = email_templates_peer::instance()->insert($email_data);
	return array('success'=>1,'id'=>$insId);
    }
    
    private function delete_item($itemId) {
	email_templates_peer::instance()->delete_item($itemId);
	return array('success'=>1, 'id'=>$itemId);
    }
}
?>
