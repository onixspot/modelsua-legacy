<?php
abstract class forum_controller extends frontend_controller {
    
    
    public function execute() {
	
	if(!session::has_credential('admin')) 
	    $this->redirect ('/');
//	var_dump(md5('qwerty123'));
//	exit;
	load::model('forum/bbcode');
	load::model('forum/posts');
	load::model('forum/topics');
	load::model('forum/sections');
	load::model('forum/themes');
	load::model('forum/users');
	
	load::system('email/email');
	
	load::action_helper('pager');
	load::action_helper('forum', FALSE);
	
	if(!forum_helper::is_authenticated()) {
	    if($user_fid = cookie::get('ufid')) {
		if($this->forum_user = forum_users_peer::instance()->get_item($user_fid)) {
		    forum_helper::set_user($user_fid);
		    cookie::set('ufid', $this->forum_user["id"], time()+60*60*24*30, '/forum', conf::get('server'));
		}
	    }
	}
	else {
	    $this->forum_user = forum_users_peer::instance()->get_item(forum_helper::get_user_id());
	}
	
	$pak = file(conf::get('project_root').'/public/img/forum/smilies/Set_Smiles_YarNET.pak');
	$this->smiles = array();
	
	foreach ( $pak as $val ) {
	    $val = trim($val);
	    if (! $val || '#' == $val{0}) { continue; }
	    list($gif,$alt,$symbol) = explode('=+:',$val);
	    $this->smiles[$symbol] = '<img id="code_'.$symbol.'" src="/forum/smilies/'.htmlspecialchars($gif)
		.'" alt="'.htmlspecialchars($alt).'" />';
	}	
	
	$this->set_layout('forum');
    }
}
?>
