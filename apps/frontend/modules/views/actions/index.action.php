<?php
class views_index_action extends frontend_controller {
    
    public function execute() {
        
        load::model('content_views');
        load::model('news');
        load::model('pages');
        
        $this->set_renderer('ajax');
        $request = request::get_all();
        
        if($request['bInfo']){
            $json = content_views_peer::modifyViewData($request);
            die(json_encode($json));
            
        }
    }
    
    
}
?>
