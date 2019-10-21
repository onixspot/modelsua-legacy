<?php
load::app('./modules/forum/controller');
class forum_profile_action extends forum_controller {
    
    public function execute() {
	parent::execute();
	
	$action = request::get('act');
	
	if(in_array($action, array('save_profile', 'save_groups', 'preview'))) {
	    $this->set_renderer('ajax');
	    $this->json = $this->$action();
	}
    }
    
    private function preview() {
	$text = request::get_string('message','');
	$bb = new bbcode(stripslashes($text));
	$bb -> mnemonics = $this->smiles;
	return array('success'=>true, 'content'=>$bb -> get_html());
    }
    
    private function save_profile() {
	$subaction = request::get('subaction','rersonal');
	switch($subaction) {
	    case 'personal':
		return array('success'=>true, 'data'=>  request::get_all());
		break;
	    case 'avatara':
		
		if($filename = $_FILES['uploadfile']['tmp_name']) {
		   $image = new Imagick($filename);
		   $image->resizeImage(100, 0, Imagick::FILTER_LANCZOS, 1);
		   $image->setImageFormat('png');
		   
		   $db = conf::get("databases");
		   unset($db['master']['driver']);
		   foreach ($db['master'] as $key => $value)
		       $arr[] =$key.'='.$value;
		   
		   $connection = pg_connect(implode(chr(32),$arr));
		   $sql = 'UPDATE forum_users SET avatara="'.pg_escape_bytea($image->getImageBlob()).'"::bytea  WHERE id='.forum_helper::get_user_id();
		   $result = pg_exec($connection,$sql);
		   pg_close($connection);
		   
		   return array('success'=>true, 'file'=>  $userkey, 'dbres'=>$result);
		}
		
		return array('success'=>false, 'reason'=>  'no file...');
		break;
	    case 'delete_preview':
		$userkey = request::get('imagekey');
		if(file_exists('/var/www/ukrmodels/data/files/temp/'.$userkey))
		    unlink ('/var/www/ukrmodels/data/files/temp/'.$userkey);
		return array('success'=>TRUE);
		break;
	    case 'registration':
		return array('success'=>true, 'data'=>  request::get_all());
		break;
	    case 'signature':
		forum_users_peer::instance()->update(array('id'=>  forum_helper::get_user_id(), 'signature'=>  request::get('message')));
		return array('success'=>true);
		break;
	    default:
		return array('success'=>FALSE, 'reason'=>t('Ошибка данных...'));
		break;
	}
    }
    
}
?>
