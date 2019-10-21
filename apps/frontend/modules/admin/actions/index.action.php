<?php

load::app("modules/admin/controller");
class admin_index_action extends admin_controller
{
	public function execute()
	{       
            
            
            $reg = db::get_rows("SELECT name_ru,region_id FROM regions WHERE country_id=9908");
            $cities = db::get_rows("SELECT name_ru,region_id FROM cities WHERE country_id=9908 AND city_id<15789520 ORDER BY name_ru");
            
            foreach($cities as $id=>$city) {
                foreach ($reg as $id=>$region) 
                    if(preg_match('/'.$city['name_ru'].'/', $region['name_ru']) && $city['region_id']==$region['region_id'])
                            db::exec("UPDATE cities SET center=1 WHERE name_ru='".$city['name_ru']."'");
            }
            
            
            echo "<pre>";
            print_r($matches);
//            print_r($cities);
            exit;
	    $c = db::get_cols('SELECT name_ru FROM cities');
//		    
//	    $step = 10;
	    $count = count($c);
//	    $iter = floor($count/$step);
//	    $counter =0;
//	    var_dump($count);
//	    exit;
//	    for($i=0; $i<=$iter; $i++) {
//		$aS = ($counter>count($c)) ? implode(' / ',array_slice($c,$counter-$step)) : implode(' / ',array_slice($c,$counter,$step));
//		$counter += $step;
//		$this->translate($aS, $i);
//		var_dump($aS);
//		echo "<br/><b>".$i."</b><br/>";
//		echo "<br/><br/>";
//	    }
	    
//	    exit;
//	    $this->translate(,0);
	    $this->update_regions_data();
	    exit;
	}
	
	
	private function translate($query,$i){
                
                $query = urlencode($query);
                
                $url = "http://translate.google.ru/translate_a/t?client=x&text=".$query."&sl=ru&tl=en&ie=UTF8";
                
                file_put_contents('/var/www/ukrmodels/public/js/t('.$i.').js',iconv('KOI8-R', 'UTF8', file_get_contents($url)));
        }
	
	private function get_translate() {
	    $c = db::get_cols('SELECT name FROM cities');
		    
		    $step = 30;
		    $count = count($c);
		    $iter = floor($count/$step);
		    $counter =0;
		    
		    for($i=0; $i<=$iter; $i++) {
			$aS = ($counter>count($c)) ? implode(' / ',array_slice($c,$counter-$step)) : implode(' / ',array_slice($c,$counter,$step));
			$counter += $step;
			var_dump($aS);
			exit;
		    }
	}
	
	private function update_regions_data() {
	    for($i=0; $i<1147;$i++) {
		$handle = fopen('/var/www/ukrmodels/public/js/t('.$i.').js', 'r');
		while ($s = fgets($handle)) {
		    $data = json_decode($s);
		}
		
		foreach ($data->sentences as $id => $item) {
		    $new[$id]['trans'] = explode(' / ',$item->trans);
		    $new[$id]['orig'] = explode(' / ', $item->orig);
		}
		foreach ($new as $id => $item) {
		    foreach($item['trans'] as $tid=>$trans)
			if(trim(str_replace(array("/"),array(""),$item['orig'][$tid])))
			    $res[trim(str_replace(array("/"),array(""),$item['orig'][$tid]))] = trim(str_replace(array("/"),array(""),$trans));
		}
		fclose($handle);
	    }
//	    echo "<pre>";
//	    print_r($res);
//	    var_dump(count($res));
//	    exit;
	    foreach ($res as $key => $value) 
			db::exec("UPDATE cities SET name_en=:nen WHERE name_ru=:nua",array('nen'=>$value,'nua'=>$key));
	}
	
	private function verifySoM() {
	    $offsets = array(user_auth_peer::successful,  user_auth_peer::new_faces, user_auth_peer::perspective);
	    foreach ($offsets as $index=>$offset) {
		$sql = "SELECT * FROM user_auth WHERE show_on_main>".$offset;
		if(isset($offsets[($index+1)]))
		    $sql .= " AND show_on_main<".$offsets[($index+1)];
		$sql .=" ORDER BY show_on_main ASC";
		echo $sql."<br/>";
		$list = db::get_rows($sql);
		foreach ($list as $key => $item) {
		    $rVal = ($key+$offset+1);
		    echo $rVal." => ".$item['show_on_main']."<br/>";
		    if($rVal!=$item['show_on_main']) {
//			$item['show_on_main'] = $rVal;
//			user_auth_peer::instance()->update($item);

			echo $item['show_on_main']." = ".$rVal."<br/>";
			echo 'updating....<br/>';
		    }
		}
	    }
	    db_key::i()->set('verifyingSoM',time());
	}
	
	private function generate_security() {
	    $list = user_auth_peer::instance()->get_list();
	    foreach ($list as $id) {
		$item = user_auth_peer::instance()->get_item($id);
		if(!$item['security']) {
		    $item['security'] = substr (md5(microtime ()), 0,8);
		    user_auth_peer::instance()->update($item);
		}
	    }
	}
	
	private function updateDB() {
	    load::model('user/user_data');
	    $list = user_data_peer::instance()->get_list();
	    foreach ($list as $key=>$id) {
		$item = user_data_peer::instance()->get_item($id);
		$item['rank'] = ($key+1);
		user_data_peer::instance()->update($item);
	    }
	}
	private function parse() {
                $url = 'http://www.topuniversities.com/university-rankings/world-university-rankings/2011?page=';
                $uni_name = '#<a href="/institution/(.*)\">(.*)</a>#';
                $country_name = '#\<td\sclass\=\"views-field\sviews-field-name\">(.*)\<\/td\>#sUi';
                $full = time();
                echo 'STARTED AT '.$full."<br/><br/>";
                for($page=0; $page<14; $page++) {
                    $start = time();
                    echo '<b class="fs20">PAGE '.$page.' STARTED AT '.$start.'</b><br/>';
                    echo '<span class="fs14">try to get file content '.$url.$page.'....</span>';
                    $file_content = file_get_contents($url.$page);
                    echo '<span class="fs14 bold">OK</span><br/><br/>';
                    preg_match_all($uni_name, $file_content, $matches_uni);
//                    echo "<pre>";
//                    var_dump($matches_uni[2]);
//                    exit;
                    preg_match_all($country_name, $file_content, $matches_country);
                    echo '<span class="fs14">Checking matches array sizes....</span>';
                    if(count($matches_country)==(count($matches_uni)-1)) {
                        echo '<span class="fs14"><b>OK</b></span><br/><br/>';
                        for ($j=1; $j<51; $j++) {
                            if(isset ($matches_country[1][$j]) && isset ($matches_uni[2][$j-1])) {
                                $cntry = trim($matches_country[1][$j]);
                                $exist = db::get_scalar("SELECT id FROM education_country WHERE name=:name",array('name'=>$cntry));
                                if($exist) {
                                    echo '<span class="fs14">Country <b>'.$cntry.'</b> is already exists <b>(ID='.$exist.')</b></span><br/>';
                                }
                                else {
                                    echo '<span class="fs14">Inserting <b>'.$cntry.'</b>....';
                                    $exist = education_country_peer::instance()->insert(array('name'=>$cntry));
                                    echo 'OK <b>(ID='.$exist.')</b></span><br/>';
                                }
                                echo '<span class="fs14">Inserting <b>'.$matches_uni[2][$j-1].'</b> with parent='.$exist.'....';
                                $insId = univers_peer::instance()->insert(array('name'=>trim($matches_uni[2][$j-1]),'country_id'=>$exist));
                                echo 'OK <b>(ID='.$insId.')</b></span><br/><br/>-------------------------------------------------<br/>';
                            }
                        }
                    }
                    else {
                        echo '<span class="fs14 bold">FALSE</span><br/>-------------------------------------------------<br/>';
                    }
                    echo '<span><b class="fs20">PAGE UPLOADED AT '.(time()-$start).'</b></span><br/>';
                    unset($matches_country);
                    unset($matches_uni);
                }
                 echo '<span><b class="fs20">STOP AT '.(time()-$full)."</b></span><br/><br/>";
        }
}

?>
