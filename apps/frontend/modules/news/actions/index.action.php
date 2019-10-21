<?php
load::app("modules/news/controller");
class news_index_action extends news_controller {
    public function execute() {
        parent::execute();
        
        $types = news_peer::get_types();
        $type = isset($types[request::get_int('type')]) ? request::get_int('type') : 1; 
        $this->list = news_peer::instance()->get_list(array('type'=>$type),array(),array('created_ts DESC'));
        $this->title = $types[$type];
        
        
        
    }
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
