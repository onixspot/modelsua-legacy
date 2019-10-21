<?php
load::app('/modules/forum/controller');
class forum_view_action extends forum_controller {
    
    public function execute() {
	parent::execute();
	
	$forum_id = request::get_int('f');
	if(!$this->forum = forum_themes_peer::instance()->get_item($forum_id))
	    $this->redirect ('/forum');
	
	$this->children = forum_themes_peer::instance()->get_list(array('parent_id'=>$forum_id));
	
	$topics = forum_topics_peer::instance()->get_list(array('forum_id'=>$forum_id));
	
	$this->pager = new pager($topics, request::get_int('page'), 20);
	$this->topics = $this->pager->get_list();
	
	
	if(!empty($this->topics)) {
	    $tmp = array();
	    forum_posts_peer::set_per_page(2);
	    foreach ($this->topics as $tkey=>$tid) {
		$tmp[$tkey]['topic_id'] = $tid;
		$pager = new pager(forum_posts_peer::instance()->get_list(array('topic_id'=>$tid)), 0, forum_posts_peer::get_per_page());
		$pager->set_external_uri('/forum/viewtopic?t='.$tid);
		if($pager->get_pages()>1)
		    $tmp[$tkey]['pager'] = pager_helper::get_full($pager);
		$tmp[$tkey]['last_post'] = db::get_row("SELECT * FROM forum_posts WHERE topic_id=:pid ORDER BY created_ts DESC LIMIT 1",array('pid'=>$tid));
		
	    }
	    $this->topics = $tmp;
	}
	
    }
}
?>
