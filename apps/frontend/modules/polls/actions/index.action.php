<?php
class polls_index_action extends frontend_controller {
    
    protected $users_list;
    
    public function execute() {
        
	load::model('voting');
        
        if(request::get_int('get_pair')) {
            $this->set_renderer('ajax');
            $this->set_user_list();
            $this->get_pair();
        }
    }
    
    private function set_user_list() {
	$params = voting_peer::get_query_params();
	$sql = "SELECT d.user_id,d.pid,d.ph_crop, a.hidden 
		FROM user_data d 
		JOIN user_auth a 
		ON d.user_id=a.id 
		WHERE  
		    pid IS NOT NULL AND 
		    ph_crop IS NOT NULL AND 
		    a.hidden = false AND
		    d.status>20 AND d.status<30 AND 
		    d.user_id NOT IN 
			(SELECT object_id FROM voting WHERE type=:type ".$params['sqladd'].") LIMIT 100";
        $list = db::get_rows($sql,array_merge(array('type'=>  voting_peer::MODEL_RATING),$params['bind']));
	if(!empty($list))
            foreach ($list as $key => $item) {
                $item['ph_crop'] = unserialize($item['ph_crop']);
                $this->users_list[] = $item;
            }
	    else $this->users_list = array();
		
    }
    
    private function get_pair() {
        $this->json = $this->users_list;
        
    }
}
?>
