<?php

require_once './apps/compressor/controller.php';

class compressor extends compressor_controller
{
    public function execute()
    {
        $this->initialize();
        $this->set_cache_time(1);
        $this->add_files(
            [
                'main.js' => [
                    './jquery/jquery-ui-1.8.13.custom.min.js',
                    './jquery/jquery.animate-shadow-min.js',
                    './jquery/jquery.corner-v2.13.js',
                    './jquery/multiselect.js',
                    './libraries/adminka.js',
                    './libraries/application.js',
                    './libraries/form.js',
                    './libraries/autocomplete.js',
                    './libraries/validators.js',
                    './libraries/ui.js',
                    './libraries/calendar.js',
                    './jquery/jquery.uploadify.v2.1.4.js',
                    './swfobject.js',
                    './menu/menu.js',
                    './jquery/jquery.imgareaselect.pack.js',
                    './libraries/popup.js',
                ],
                'erlte.js' => [
                    'erlte/elrte.min.js',
                    'erlte/elfinder.min.js',
                    './libraries/ajax.file.upload.js',
                    // './jquery/ui/jquery.ui.datepicker.js',
                ],
                'forum.js' => [
                    './libraries/forum.js',
                    './libraries/ajax.file.upload.js',
                ],
                'erlte.css' => [
                    'erlte/jquery-ui-1.8.13.custom.css',
                    'erlte/elrte.min.css',
                    'erlte/elfinder.css',
                ],
                'forum.css' => [
                    'forum.css',
                ],
                'main.css' => [
                    './tools.css',
                    './ui.css',
                    './style.css',
                    './adminka.css',
                    './jquery.ui.datepicker.css',
                    './uploadify.css',
                    './menu/menu.css',
                    './menu/menu-v.css',
                    './multiselect.css',
                    './imgareaselect-default.css',
                    './grid.css',
                    './style.v2.css',
                ],
            ]
        );

        $this->display();
    }
}
