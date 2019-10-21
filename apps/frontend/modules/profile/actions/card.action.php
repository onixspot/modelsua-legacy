<?php
load::app('/modules/profile/controller');
class profile_card_action extends profile_controller {
    
    public function execute() {
        
        if(request::get_int('upload_card_avatar')) 
            $this->upload_preview();
        
        parent::execute();
        
        load::model('user/user_photos');
        
        $this->disable_layout();
	$this->set_renderer('ajax');
        
//        var_dump(request::get_int('id'));
//        exit;
        
        $this->card_user = (session::has_credential('admin') && request::get_int('user_card')) ? request::get_int('user_card') : session::get_user_id();
        $this->card_profile = profile_peer::instance()->get_item($this->card_user);
        $this->preview = db::get_row("SELECT * FROM user_photos WHERE user_id=:uid AND type=:type",array('uid'=>$this->card_user, 'type'=>  user_photos_peer::TYPE_CARD_PREVIEW));
        
	$this->pWidth = 300;
	$this->iWidth = 900;
	
	$this->iHeight = 1.78*$this->iWidth;
	$this->pHeight = 1.78*$this->pWidth;
	
	$this->k = $this->iWidth/$this->pWidth;
        
        if(request::get('get_card')) 
	    $this->download_card($this->card_user,request::get('get_card'), request::get('type'));
        if(request::get_int('crop')) 
	    $this->ph_crop();
	if(request::get('data')) 
	    $this->make_card($this->card_user,  request::get('language'));
	if(request::get_int('delete_card'))
	    self::delete_card (request::get_int('delete_card'));
	
    }
    
    private function upload_preview() {
        
        $user_id = request::get('user_id');
        //$language = request::get('language');

        if(!in_array($language, array('ru','en'))) $language = 'ru';
        
        $image = new Imagick($_FILES['image']['tmp_name']);
        $this->connect();
        $check = "SELECT id FROM user_photos WHERE type=".(int)user_photos_peer::TYPE_CARD_PREVIEW." AND name='".$language."' AND user_id=".(int)$user_id;
        $exId = db::get_scalar($check);
        if($exId) 
            db::exec("DELETE FROM user_photos WHERE id=:pid",array('pid'=>$exId));
        $sql =  "INSERT INTO user_photos (user_id, type, name, photo) 
                    VALUES (
                            ".$user_id.",
                            ".user_photos_peer::TYPE_CARD_PREVIEW.",
                            '".$language."',
                            '".pg_escape_bytea($image->getImageBlob())."'::bytea
                    )";
        $json =  pg_query($sql) ? array('success'=>1,'pId'=>db::get_scalar($check),'ret'=>$ret) : array('success'=>false, 'reason'=>'Save data error...');
        pg_close();
        die(json_encode($json));
    }
    
    private function make_card($user_id, $language='ru') {
	
	$this->set_renderer('ajax');
        
	if(!in_array($language, array('ru','en'))) $language = 'ru';
        
	$card = new Imagick();
	$draw = new ImagickDraw();
	$pixel = new ImagickPixel( 'white' );
        
	$card->newImage($this->iWidth, $this->iHeight,$pixel );
	$card->setResolution(300,300);
        
	$pixel->setColor("#a0a0a0");
	$card->borderImage($pixel,2,2);
	
	$draw->setFillColor('black');
	$draw->setFont('/var/www/ukrmodels/public/fonts/EVROPA.ttf');
	
	$data = request::get_all();
	$data = $data['data'];
	if($data['name'])
	    foreach ($data as $key => $item) {
		    $font = ($key=='name') ? ceil($this->k*26) : ceil($this->k*18);
		    $draw->setFontSize( $font );
		    $card->annotateImage($draw, (int)($this->k*$item['x']), (int)($this->k*$item['y']), 0, (string)$item['text']);
		}
	$card->compositeImage(self::get_photo(request::get('pid'),$this->k), imagick::COMPOSITE_OVER, $this->k*84, $this->k*60);
	$card->setImageFormat('jpg');
	
	
	user_photos_peer::instance();
	$this->connect();
        $check = "SELECT id FROM user_photos WHERE type=".user_photos_peer::TYPE_CARD." AND user_id=".(int)$user_id." AND name='".$language."'";
        $exId = db::get_scalar($check);
        $sql = (!$exId) ? 
                "INSERT INTO user_photos (user_id, type, name, photo) 
                    VALUES (
                            ".$user_id.",
                            ".user_photos_peer::TYPE_CARD.",
                            '".$language."',    
                            '".pg_escape_bytea($card->getImageBlob())."'::bytea
                    )" : 
                "UPDATE user_photos SET photo='".pg_escape_bytea($card->getImageBlob())."'::bytea WHERE type=".user_photos_peer::TYPE_CARD." AND name='".$language."' AND user_id=".$user_id;
	$this->json = pg_query($sql) ? array('success'=>1) : array('success'=>false, 'reason'=>'Save data error...');
	pg_close();
    }
    
    private function download_card($uid, $lang='ru',$type='card') {
        
        if(!in_array($lang, array('ru','en'))) $lang = 'ru';
	
        self::connect();
        $sql = "SELECT photo FROM user_photos WHERE user_id =".(int)$uid." AND name='".$lang."' AND type=".user_photos_peer::TYPE_CARD;
        $result = pg_query($sql);
        $photo = pg_fetch_result($result, 'photo');
        pg_close();

        header ("Content-type: application/image");
        header ("Content-Disposition: attachment; filename=business_card_".$lang.'.jpg');
        
        if($photo) {
            switch($type) {
                case 'blank':
                    
                    $format = array(
                            'h'=>  request::get_int('h',2480),
                            'w'=>request::get_int('w',3508)
                    );
                    
                    $blank = new Imagick();
                    $blank->newImage($format['w'],$format['h'],new ImagickPixel( 'white' ));
                    $blank->setResolution(300,300);
                    $blank->setImageFormat('jpg');

                    $draw = new ImagickDraw();
                    $draw->setstrokecolor(new ImagickPixel( 'white' ));
                    $draw->setStrokeDashArray(array(30));
                    
                    $filename = conf::get('project_root').'/data/temp/'.$uid."_tmp".'.jpg';
                    file_put_contents($filename, pg_unescape_bytea($photo));
                    $card = new Imagick($filename);
                    unlink($filename);

                    $rows = request::get_int('rows',2);
                    $cols = request::get_int('cols',5);
                    
                    $margin_left = request::get_int('ml',275);
                    $margin_top = request::get_int('mt',175);
                    
                    $card->resizeImage(floor(($format['w']-2*$margin_left)/$cols),floor(($format['h']-2*$margin_top)/$rows),Imagick::FILTER_LANCZOS, 1);
                    $card->cropImage($card->getImageWidth()-4,$card->getImageHeight()-4,2,2); //crop border
                    
                    for($i=0; $i<$rows; $i++) {
                        
                        $draw->line(0, $margin_top+$i*$card->getImageHeight(), $blank->getImageWidth(), $margin_top+$i*$card->getImageHeight());
                        $blank->drawImage($draw);
                        
                        for($j=0; $j<$cols; $j++) {
                            
                            $draw->line($margin_left+$j*$card->getImageWidth(), 0, $margin_left+$j*$card->getImageWidth(), $blank->getImageHeight());
                            $blank->drawImage($draw);
                            
                            $blank->compositeImage($card, imagick::COMPOSITE_ATOP, $margin_left+$j*$card->getImageWidth(), $margin_top + $i*$card->getImageHeight() );
                            
                        }
                    }
                    $draw->line(0, $margin_top+$i*$card->getImageHeight(), $blank->getImageWidth(), $margin_top+$i*$card->getImageHeight());
                    $blank->drawImage($draw);
                    
                    $draw->line($margin_left+$j*$card->getImageWidth(), 0, $margin_left+$j*$card->getImageWidth(), $blank->getImageHeight());
                    $blank->drawImage($draw);    

                    echo $blank->getImageBlob();
                    break;
                case 'card':
                    echo pg_unescape_bytea($photo);
                    break;
                default:
                    echo pg_unescape_bytea($photo);
                    break;
            }
        }
    }
    
    public function delete_card($user_id=NULL) {
	
	$item = user_photos_peer::instance()->get_item($user_id);
        if($item['user_id']==session::get_user_id()) {
            db::exec("DELETE FROM user_photos WHERE id=:uid",array('uid'=>$user_id));
            $this->json = array('success'=>1);
        }
        else 
            $this->json = array('success'=>false);
    }
    
    private function ph_crop()
    {
            $this->set_renderer('ajax');
            $ph_crop = array(
                    'x' => request::get_int('x'),
                    'y' => request::get_int('y'),
                    'w' => request::get_int('w'),
                    'h' => request::get_int('h'),
            );
            
            db::exec("UPDATE user_photos SET description='".serialize($ph_crop)."' WHERE id=:uid AND type=".user_photos_peer::TYPE_CARD_PREVIEW,array('uid'=>  request::get_int('pid')));
            $this->json = array('success'=>1,'pid'=>request::get_int('pid'), 'crop'=>$ph_crop);
            
            
    }
    
    private static function get_photo($pid,$k)
	{
		self::connect();
		
		$sql = "SELECT photo FROM user_photos WHERE id = ".(int) $pid;
		
		$result = pg_query($sql);
		$photo = pg_fetch_result($result, 'photo');
		
		
		
		if($photo) {
                        $tmp = db::get_cols("SELECT description FROM user_photos WHERE id=:pid",array('pid'=>$pid));
                        $crop = unserialize($tmp[0]);
                        $filename = conf::get('project_root').'/data/temp/'.$pid.'jpg';
			file_put_contents($filename, pg_unescape_bytea($photo));
			$image = new Imagick($filename);
                        if($crop) $image->cropimage($crop['w'], $crop['h'], $crop['x'], $crop['y']);
                        unlink($filename);
		}
		else {
			$image = new Imagick('/var/www/ukrmodels/public/img/no_image.png');
		}
                pg_close();
                
		$image->resizeImage(ceil($k*133),0, Imagick::FILTER_LANCZOS, 1);
                $image->setImageFormat('jpg');
		return $image;
	}
	
	private static function connect()
	{
		$db = conf::get("databases");

		$connection = array(
				'host=localhost',
				'port=5432',
				'dbname='.$db['master']['dbname'],
				'user='.$db['master']['user'],
				'password='.$db['master']['password']
		);

		return pg_connect(implode(chr(32), $connection));
	}
}
?>
