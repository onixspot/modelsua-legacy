<?php
load::app('/modules/forum/controller');
class forum_index_action extends forum_controller {
    
    public function execute() {
	parent::execute();
	
	$this->sections = forum_sections_peer::instance()->get_list(array(),array(),array('id ASC'));
	if(!empty($this->sections))
	    foreach ($this->sections as $section_id) {
		$forums = forum_themes_peer::instance()->get_list(array('section_id'=>$section_id));
		if(!empty($forums))
		    foreach ($forums as $forum_id) {
			$this->forums_data[$section_id][$forum_id]['data'] = forum_themes_peer::instance()->get_item($forum_id);
			$this->forums_data[$section_id][$forum_id]['topics_count'] = db::get_scalar("SELECT COUNT(id) FROM forum_topics WHERE forum_id=:fid",array('fid'=>$forum_id));
			$this->forums_data[$section_id][$forum_id]['posts_count'] = db::get_scalar("SELECT COUNT(id) FROM forum_posts WHERE topic_id IN (SELECT id FROM forum_topics WHERE forum_id=:fid)",array('fid'=>$forum_id));
			$last_post = db::get_row("SELECT * FROM forum_posts WHERE topic_id IN ( SELECT id FROM forum_topics WHERE forum_id=:fid ORDER BY id DESC ) ORDER BY created_ts DESC LIMIT 1",array('fid'=>$forum_id));
			if (!$last_post) $last_post = db::get_row("SELECT * FROM forum_topics WHERE forum_id=:fid ORDER BY id DESC LIMIT 1",array('fid'=>$forum_id)); 
			if($last_post) {
			    $lp_user = profile_peer::instance()->get_item($last_post['user_id']);
			    $this->forums_data[$section_id][$forum_id]['last_post'] =array(
				    'user_id'=>$lp_user['user_id'],
				    'user_name'=> profile_peer::get_name($lp_user),
				    'time'=>$last_post['created_ts'] ? date('d.m.Y H:i:s',$last_post['created_ts']) : ''	
				    );
			}
		    }
			    
		    
	    }
//	    echo "<pre>";
//	    print_r($this->forums_data);
//	    exit;
    }
}
?>
