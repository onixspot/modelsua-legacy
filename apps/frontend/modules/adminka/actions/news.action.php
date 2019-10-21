<?php
load::app('modules/adminka/controller');
load::model('user/profile');

class adminka_news_action extends adminka_controller {
    
    public function execute() {
        
        parent::execute();
        
        load::model('news');
        load::system('storage/storage_simple');
        
		$cond = array(
			"hidden" => false,
			"type" => 2
		);
		$$user_auth_list = user_auth_peer::instance()->get_list($cond);
		$user_data_list = user_data_peer::instance()->get_list(array(), array(), array("first_name, last_name ASC"));
		$this->models_list = array();
		foreach($user_data_list as $id)
		{
			if(in_array($id, $$user_auth_list))
			{
				$this->models_list[] = $id;
			}
		}
		
		
        $id = request::get_int('id');
        $empty = array(
                            'id'=>0,
                            'title'=>array('ru'=>' ','en'=>' '),
                            'anons'=>array('ru'=>' ','en'=>' '),
                            'body'=>array('ru'=>' ','en'=>' '),
                            'type'=>0,
                            'no_comments'=>0,
                            'created_ts'=>0,
                            'end_ts'=>0,
                            'on_main'=>0,
                            'access'=>0,
                            'hidden'=>0,
                            'author'=>' '
                        );
        
        if(request::get('get_data')) {
                $this->disable_layout();
                $this->set_renderer('ajax');
            
                $data = news_peer::instance()->get_item($id);
                if($data) {
                    $data['body'] = unserialize($data['body']);
                    $data['title'] = unserialize($data['title']);
                    $data['anons'] = unserialize($data['anons']);
					$data['models'] = unserialize($data['models']);
                    $data['title']['ru'] = stripslashes($data['title']['ru']);
                    $data['title']['en'] = stripslashes($data['title']['en']);
                    $data['anons']['ru'] = stripslashes($data['anons']['ru']);
                    $data['anons']['en'] = stripslashes($data['anons']['en']);
                    $data['body']['ru'] = stripslashes($data['body']['ru']);
                    $data['body']['en'] = stripslashes($data['body']['en']);
                    
                    $data['created_ts'] = ($data['created_ts']) ? date('j.n.Y',$data['created_ts']) : 0;
                    $data['end_ts'] = ($data['end_ts']) ? date('j.n.Y',$data['end_ts']) : 0;
                    //unset($data['salt']);
                }
                $json = ($data) ? $data : $empty;
                
                die(json_encode($json));
            
            
        }
        
        if(request::get('submit')) {
            $this->disable_layout();
            $this->set_renderer('ajax');
            $action = request::get('act');
            switch($action) {
                case 'save':
                    $body_arr = request::get('body');
                    $title = serialize(request::get('title'));
                    $type = request::get('type');
                    $on_main = request::get_int('on_main');
                    $comments = request::get_int('no_comments');
                    $anons = serialize(request::get('anons'));
                    $hidden = request::get_int('hidden');
                    $date = request::get('created_ts') ? strtotime(request::get('created_ts')) : time();
                    $date_end = request::get('end_ts') ? strtotime(request::get('end_ts')) : time();
                    $just_reg = request::get_int('access');
                    $author = request::get('author');
					$models_arr = request::get_array('models');
					$models = serialize($models_arr);
					
					foreach($models_arr as $iddddd)
					{
						$m = user_data_peer::instance()->get_item($iddddd);
						$m_name = profile_peer::get_name($m);
						foreach($body_arr as $key => $val)
						{
							if(mb_strpos($val, $m_name) !== false)
							{
								$body_arr[$key] = str_replace($m_name, "<a href=\"/profile?id=".$iddddd."\">".$m_name."</a>", $body_arr[$key]);
							}
						}
					}
					
					$body = serialize($body_arr);
					$data_arr = array(
						'title'=>$title,
						'anons'=>$anons,
						'body'=>$body,
						'type'=>$type,
						'no_comments'=>$comments,
						'created_ts'=>$date,
						'end_ts'=>$date_end,
						'on_main'=>$on_main,
						'access'=>$just_reg,
						'author'=>$author,
						'hidden'=>$hidden,
						'models'=>$models

					);
					
                    if($old_news = news_peer::instance()->get_item($id)) {
                        news_peer::instance()->update(array_merge(array('id'=>$id),$data_arr));
                        $this->json = array('success'=>1,'id'=>$id,'psalt'=>$old_news['salt']);
                    }
                    else {
                        $id = news_peer::instance()->insert($data_arr);
                        $this->json = array('success'=>1,'id'=>$id);
                    }
					
                    break;
                case 'delete':
                    news_peer::instance()->delete_item($id);
                    break;
                case 'get_list':
                    $bind = array();
                    $sqladd='';

                    $fn = (request::get('filter_name'));
                    $fv = (request::get('filter_value'));
                    
                    if($fn=='type' && $fv) {
                        $sqladd .= ' AND '.$fn.'=:'.$fn;
                        $bind[$fn] = $fv;
                    }
                    if($fn=='search') {
                        $filters = explode(';', $fv);
                        if(trim($filters[0])) 
                        {
                            $sqladd .= ' AND title ILIKE :title';
                            $bind['title'] = '%'.trim($filters[0]).'%';
                        }
                        if(strtotime(trim($filters[1]))) 
                        {
                            $sqladd .= ' AND created_ts=:ctd';
                            $bind['ctd'] = strtotime(trim($filters[1]));
                        }
                        
                    }
                    
                    $news_count = db::get_scalar("SELECT COUNT(id) FROM news WHERE 1=1 ".$sqladd,$bind);
                    $page = request::get('page',0);
                    $per_page = 20;
                    $bind['limit'] = $per_page;

                    if($page>=0) $offset = $per_page*$page; else $offset=0;
                    if(($offset-$per_page)>$news_count) $offset=0;
                    $bind['offset'] = $offset;

                    $sql = "SELECT id,title,anons,created_ts,type,hidden,models,salt FROM news WHERE 1=1 ".$sqladd." ORDER BY id DESC LIMIT :limit OFFSET :offset";
                    
                    $list = db::get_rows($sql,$bind);

                    if(!empty($list)) {
                        foreach($list as $id=>$item) {
                            $list[$id]['title']=  unserialize($item['title']);
                            $list[$id]['title']['ru'] = stripslashes($list[$id]['title']['ru']);
                            $list[$id]['anons']=  unserialize($item['anons']);
                            $list[$id]['anons']['ru'] = stripslashes($list[$id]['anons']['ru']);
                            $list[$id]['type'] = news_peer::get_types($item['type']);
                            $list[$id]['created_ts'] = ($list[$id]['created_ts']) ? date('d.m.Y',$item['created_ts']) : '-';
							$list[$id]['hidden'] = $item['hidden'];
							$list[$id]['models']=  unserialize($item['models']);
							$list[$id]['salt']= $item['salt'];
                        }
                        $list['pages'] = ceil($news_count/$per_page);
                    }

                    $json = (!empty($list)) ? $list : 0;
                    $this->json = $json;
                    break;
            }
        }
        if($_FILES) {
            
            $this->disable_layout();
            $this->set_renderer('ajax');
            
            $storage = new storage_simple();
            if(request::get('salt')) $key = request::get('salt');
            else $key = $id.substr(microtime(true),0,8);
            $storage->save_uploaded($key, $_FILES['file']);
            news_peer::instance()->update(array('id'=>$id,'salt'=>$key));
        }
    }
}
?>
