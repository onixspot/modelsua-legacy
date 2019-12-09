<?php

load::app('modules/api/controller');

class api_index_action extends api_controller
{
    public function execute()
    {
        parent::execute();
        $this->json = [];
    }
}