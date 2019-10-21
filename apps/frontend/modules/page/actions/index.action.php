<?php
class page_index_action extends frontend_controller {
    
    public function execute() {
        load::model('pages');
        load::model('content_views');
        pages_peer::set_template('<div style="padding: 5px 0;" class="left %hidden%">
                                    <div style="">
                                        <div class="" style="margin-left: %margin%px; line-height: 16px; font-size: 16px;" class="%selected%">
                                            <a href="/page?link=%link%" style=" text-decoration: underline;">%title%</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear"></div>');
        $url = request::get('link', 1);
        $this->id = pages_peer::instance()->get_by_url($url);
        $this->content = pages_peer::instance()->get_content($url,session::get('language'));
        $nodes = db::get_cols("SELECT id FROM pages WHERE parent_id=:pid AND show=1 AND \"on\"=1 ORDER BY weight",array('pid'=>$this->content['id']));
        $this->tree = pages_peer::instance()->build_tree($nodes);
        
        $content = $this->content;
        if($content['parent_id']) {
            while($content['parent_id']) {
                $this->crumbs[] = array('title'=>$content['title'],'link'=>$content['link']);
                $content = pages_peer::instance()->get_content($content['parent_id']);
                if(!$content['parent_id'])
                    $this->crumbs[] = array('title'=>$content['title'],'link'=>$content['link']);
            }
            $this->crumbs = array_reverse($this->crumbs);
        }
        client_helper::set_title($this->content['title'].' - Ассоциация моделей Украины');

    }
}
?>
