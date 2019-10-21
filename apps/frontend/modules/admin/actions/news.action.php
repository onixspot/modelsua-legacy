<?php
load::app('modules/admin/controller');
class admin_news_action extends admin_controller {
    
    public function execute() {
        
        load::model('news');
        load::system('storage/storage_simple');
        
        $this->disable_layout();
        $this->set_renderer('ajax');
        
        $id = request::get_int('id');
        $empty = array(
                            'id'=>0,
                            'title'=>array('ru'=>' ','ua'=>' '),
                            'anons'=>array('ru'=>' ','ua'=>' '),
                            'body'=>array('ru'=>' ','ua'=>' '),
                            'type'=>0,
                            'no_comments'=>0,
                            'created_ts'=>0,
                            'on_main'=>0,
                            'access'=>0,
                            'author'=>' '
                        );
        if(request::get('get_data')) {
            
            
                $data = news_peer::instance()->get_item($id);
                if($data) {
                    $data['body'] = unserialize($data['body']);
                    $data['title'] = unserialize($data['title']);
                    $data['anons'] = unserialize($data['anons']);
                    $data['body']['ru'] = stripslashes($data['body']['ru']);
                    $data['body']['ua'] = stripslashes($data['body']['ua']);
                    
                    $data['created_ts'] = ($data['created_ts']) ? date('d.m.Y',$data['created_ts']) : 0;
                    unset($data['salt']);
                }
                $json = ($data) ? $data : $empty;
                
                die(json_encode($json));
            
            
        }
        if(request::get_int('get_list')) {
            $list = db::get_rows("SELECT id,title,anons FROM news");
            if(!empty($list)) 
                foreach($list as $id=>$item) {
                    $list[$id]['title']=  unserialize($item['title']);
                    $list[$id]['anons']=  unserialize($item['anons']);
                }
                
            $json = (!empty($list)) ? $list : $empty[0]=$empty;
            die(json_encode($json));
        }
        
        if(request::get('submit')) {
            $action = request::get('act');
            switch($action) {
                case 'save':
                    
                    $body = serialize(request::get('body'));
                    $title = serialize(request::get('title'));
                    $type = request::get('type');
                    $on_main = request::get_int('on_main');
                    $comments = request::get_int('no_comments');
                    $anons = serialize(request::get('anons'));
                    $date = request::get('created_ts') ? strtotime(request::get('created_ts')) : time();
                    $just_reg = request::get_int('access');
                    $author = request::get('author');
                    
                    $data_arr = array(
                                        'title'=>$title,
                                        'anons'=>$anons,
                                        'body'=>$body,
                                        'type'=>$type,
                                        'no_comments'=>$comments,
                                        'created_ts'=>$date,
                                        'on_main'=>$on_main,
                                        'access'=>$just_reg,
                                        'author'=>$author
                                        
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
            }
        }
        if($_FILES) {
            $storage = new storage_simple();
            if(request::get('salt')) $key = request::get('salt');
            else $key = $id.substr(microtime(true),0,8);
            $storage->save_uploaded($key, $_FILES['file']);
            news_peer::instance()->update(array('id'=>$id,'salt'=>$key));
        }
    }
}
?>
