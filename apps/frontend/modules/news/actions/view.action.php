<?php
load::app("modules/news/controller");
class news_view_action extends news_controller {
    public function execute() {
        parent::execute();
        
        
        $types = news_peer::get_types();
        $id = request::get_int('id',0);
        
        $news = news_peer::instance()->get_item($id);
        if($news) $this->news = news_peer::instance ()->get_content($id);
        else throw new public_exception('Контент не существует');
	
	client_helper::set_title($this->news['title'].' - '.$types[$this->news['type']]. ' - Ассоциация моделей Украины');
	
    }
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
