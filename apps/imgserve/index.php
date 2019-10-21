<?php

load::system("http/request");

class imgserve
{
	
	public function execute()
	{
		$act = request::get('act');
		
		if(in_array($act, array('upload')))
		{
			if( ! $res = $this->$act())
				echo 0;
			else
				echo $res;
			return true;
		}
		
		$this->get_photo();
		return true;
	}
	
	/* PRIVATE FUNCTIONS */
	private function connect()
	{
		$db = conf::get("databases");
		
		$connection = array(
				'host=pgsql',
				'port=5432',
				'dbname='.$db['master']['dbname'],
				'user='.$db['master']['user'],
				'password='.$db['master']['password']
		);
		
		return pg_connect(implode(chr(32), $connection));
	}
	
	private function get_photo()
	{
		$pid = request::get_int('pid');
		
		$this->connect();
		
		$sql = "SELECT photo FROM user_photos WHERE id = ".(int) $pid;
		
		$result = pg_query($sql);
		$photo = pg_fetch_result($result, 'photo');
		
		pg_close();
		
		if( ! $photo)
			return false;
		
		$content = pg_unescape_bytea($photo);
		
		$filename = conf::get('project_root').'/data/temp/'.$pid;
		file_put_contents($filename, $content);
		
		$imagesize = getimagesize($filename);
		$file_mime = mime_content_type($filename);
		
		$image = new Imagick($filename);
		
		$crop = request::get_string('z') != 'crop' ? false : true;
		
		$w = request::get_int('w');
		if( ! $w)
			$w = $imagesize[0];
		
		$h = request::get_int('h');
		if( ! $h)
		{
			if( ! $crop)
			{
				$percent = ($w * 100) / $imagesize[0];
				$h = ($imagesize[1] * $percent) / 100;
			}
			else
				$h = $imagesize[1];
		}
		elseif( ! request::get_int('w'))
		{
			$percent = ($h * 100) / $imagesize[1];
			$w = ($imagesize[0] * $percent) / 100;
		}
		
		$w = $w > $imagesize[0] ? $imagesize[0] : $w;
		$h = $h > $imagesize[1] ? $imagesize[1] : $h;
		
		$x = request::get_int('x') ? request::get_int('x') : 0;
		$y = request::get_int('y') ? request::get_int('y') : 0;
		
		if(request::get('z') == 'crop')
//			imagecopy($crop, $image, 0, 0, $x, $y, $w, $h);
			$image->cropImage($w, $h, $x, $y);
		else
			$image->resizeImage($w, $h, Imagick::FILTER_LANCZOS, 1);
//			imagecopyresized($crop, $image, 0, 0, $x, $y, $w, $h, $imagesize[0], $imagesize[1]);
		
		$image->setImageCompressionQuality(50);
		
		header('Content-type: '.$mime);
		
		echo $image->getImageBlob();
//		$image->writeimage();
		$image->destroy();
		
//		echo file_get_contents($filename);
		
		unlink($filename);
		exit(0);
		
//		$imagesize = getimagesize($filename);
//		list($null, $type) = explode('/', $imagesize['mime']);
//		
//		unlink($filename);
//		
//		header('Content-type: '.$imagesize['mime'].';');
//		
//		$image = imagecreatefromstring($content);
//		
//		$crop = request::get('z') != 'crop' ? false : true;
//		
//		$w = request::get_int('w');
//		if( ! $w)
//			$w = $imagesize[0];
//		
//		$h = request::get_int('h');
//		if( ! $h)
//		{
//			if( ! $crop)
//			{
//				$percent = ($w * 100) / $imagesize[0];
//				$h = ($imagesize[1] * $percent) / 100;
//			}
//			else
//				$h = $imagesize[1];
//		}
//		elseif( ! request::get_int('w'))
//		{
//			$percent = ($h * 100) / $imagesize[1];
//			$w = ($imagesize[0] * $percent) / 100;
//		}
//		
//		$w = $w > $imagesize[0] ? $imagesize[0] : $w;
//		$h = $h > $imagesize[1] ? $imagesize[1] : $h;
//		
//		$x = request::get_int('x') ? request::get_int('x') : 0;
//		$y = request::get_int('y') ? request::get_int('y') : 0;
//		
//		$crop = imagecreatetruecolor($w, $h);
//		if(request::get('z') == 'crop')
//			imagecopy($crop, $image, 0, 0, $x, $y, $w, $h);
//		else
//			imagecopyresized($crop, $image, 0, 0, $x, $y, $w, $h, $imagesize[0], $imagesize[1]);
//		
//		switch($type)
//		{
//			case 'jpg':
//			case 'jpeg':
//				imagejpeg($crop);
//				break;
//			
//			case 'png':
//				imagepng($crop);
//				break;
//			
//			case 'gif':
//				imagegif($crop);
//				break;
//		}
	}
	
	private function upload()
	{
		$key = request::get('key');
		if( ! array_key_exists($key, $_FILES))
			return false;
		
		$filename = $_FILES[$key]["tmp_name"];
		
//		$fcontent = file_get_contents($filename);
		$imagesize = getimagesize($filename);
		
		$w = $imagesize[0] <= 1024 ? $imagesize[0] : 1024;
		$percent = ($w * 100) / $imagesize[0];
		$h = ($imagesize[1] * $percent) / 100;
		
		$image = new Imagick($filename);
		$image->resizeImage($w, $h, Imagick::FILTER_LANCZOS, 1);
		
		$fcontent = $image->getImageBlob();
		$image->destroy();
		
		$uid = request::get_int('uid');
		
		$this->connect();
		
		$sql = "INSERT INTO user_photos (user_id, photo, del) 
			VALUES (
				".$uid.",
				'".pg_escape_bytea($fcontent)."'::bytea,
				".(request::get_string('type') == 'deleted' ? time() : 0)."
			)";
		
		if(pg_query($sql))
			return pg_fetch_result(pg_query("SELECT id FROM user_photos ORDER BY id DESC LIMIT 1"), 'id');
		
		pg_close();
		
		return false;
	}
	
}

?>
