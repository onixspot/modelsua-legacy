<?php

load::model('pages');
abstract class admin_controller extends frontend_controller
{
    
    protected $authorized_access = false;
    protected $credentials = array();
    
    public function execute() {}
}

?>