<?php
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinder.class.php';

class connectors_elfinder_action extends frontend_controller {
    
    public function execute() {
        
        $opts = array(
            'root'            => '/var/www/ukrmodels/data/files',                       // path to root directory
            'URL'             => '/uploads/', // root directory URL
            'rootAlias'       => 'Home',       // display this instead of root directory name
            'uploadAllow' => array('image'),
            'uploadDeny'  => array('application', 'text'),
            'uploadOrder' => 'deny,allow',
        );
        
        $fm = new elFinder($opts); 
        $fm->run();
        
        parent::execute();
    }
}
?>
