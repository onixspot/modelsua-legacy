<?php
abstract class news_controller extends frontend_controller {
    public function execute()
	{
		load::model('content_views');
                load::model('news');
                load::view_helper('ui');
	}
}
?>
