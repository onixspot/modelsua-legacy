<?php
class polls_rating_action extends frontend_controller {
    public function execute() {
        load::model('voting');
        load::action_helper('pager');
        $type = request::get('type',1);
        switch($type) {
            case voting_peer::MODEL_RATING;
                $this->list = voting_peer::get_rating($type, 20);
                break;
            case 'models-full':
                $this->list = voting_peer::get_rating(voting_peer::MODEL_RATING,10000);
                $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 40);
                $this->list = $this->pager->get_list();
                break;
        }
        
        
    }
}
?>
