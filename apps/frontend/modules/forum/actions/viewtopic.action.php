<?php
load::app('/modules/forum/controller');
class forum_viewtopic_action extends forum_controller {
    
    public function execute() {
	parent::execute();
	
	load::view_helper('ui', FALSE);
	load::model('geo');
	
	$action = request::get('act');
	if(in_array($action, array('delete_post'))) {
	    $this->set_renderer('ajax');
	    $this->json = array();
	    die(json_encode(array_merge(array('success'=>$this->$action()),$this->json)));
	}
	
	$topic_id = request::get_int('t');
	if(	(!$this->topic = forum_topics_peer::instance()->get_item($topic_id)) || 
		(!$this->forum = forum_themes_peer::instance()->get_item($this->topic['forum_id']))
	  )	    
	    $this->redirect ($_SERVER['HTTP_REFERER']);
	
	$tbb = new bbcode($this->topic['body']);
	$tbb->mnemonics=$this->smiles;
	$this->topic['body'] = $tbb->get_html();
	
	if(!request::get_int('page'))
	    forum_topics_peer::instance()->update(
		    array(
			'id'=>$this->topic['id'], 
			'views'=>((int)$this->topic['views']+1)
			)
		    );
	
	forum_posts_peer::set_per_page(2);
	$posts = forum_posts_peer::instance()->get_list(array('topic_id'=>$topic_id),array(),array('id ASC'));
	
	$this->pager = new pager($posts, request::get_int('page'), forum_posts_peer::get_per_page());
	$posts = $this->pager->get_list();
	$this->posts = array();
	
	if(!empty($posts)) {
	    foreach ($posts as $pkey=>$pid) {
		$this->posts[$pkey] = forum_posts_peer::instance()->get_item($pid);
		$bb = new bbcode($this->posts[$pkey]['body']);
		$bb -> mnemonics = $this->smiles;
		$this->posts[$pkey]['body'] = $bb -> get_html();
	    }
	}
	
	
    }
    
    private function delete_post() {
	$id = request::get_int('post_id');
	$post_data = forum_posts_peer::instance()->get_item($id);
	if(forum_helper::get_user_id()==$post_data['user_id'] || session::has_credential('admin')) {
	    //forum_posts_peer::instance()->delete_item($id);
	    return true;
	}
	return false;
    }
}
?>
