<?php

load::model('user/user_auth');
load::model('user/user_data');
load::model('user/profile');
load::model('pages');
load::model('news');
load::model('email_templates');
load::model('mailing');

load::system('email/email');
load::action_helper('pager');

class adminka_controller extends frontend_controller
{

    public function execute()
    {
        if (!session::has_credential('admin')) {
            $this->redirect('/');
        }

        if ($this->get_action() === 'index') {
            $this->redirect('/adminka/user_manager');
        }
        //$this->set_template("index");
    }

}
