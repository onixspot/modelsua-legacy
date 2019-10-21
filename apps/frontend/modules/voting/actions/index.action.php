<?php
class voting_index_action extends frontend_controller {
    public function execute() {
        
        load::model('voting');
        
        $this->disable_layout();
        $this->set_renderer('ajax');
        
        $vc = request::get_int('votes');
        $oid = request::get_int('object_id');
        $type = request::get_int('type');
        
        if(voting_peer::vote($oid, $type, $vc) && $vc)
            $this->json = array('success'=>1,'votes'=>  voting_peer::calculateVotes($oid, $type));
        else 
            $this->json = array('error: to many attempts...');
        
    }
}

?>
