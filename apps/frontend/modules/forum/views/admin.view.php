<style>
    .admin_forum_table tr th {
	background: none repeat scroll 0 0 #CCCCCC;
    }
    .admin_forum_menu ul {
	background: #eeeeee;
	border: 1px solid #e7e7e7;
    }
    .admin_forum_menu ul li:hover{
	background: #e0e0e0;
	cursor: pointer;
    }
</style>
<div class="admin_forum_box mt20 fs14">
    <div class="admin_forum_menu left" style="width: 200px;">
	<ul>
	    <li>
		<a href="/forum/admin?frame=sections">Разделы</a>
	    </li>
	    <li style="border-bottom: 1px solid #ccc;">
		<a href="/forum/admin?frame=forums">Темы</a>
	    </li>
	    <li>
		<a href="/forum">Вернуться к форуму</a>
	    </li>
	</ul>
    </div>
    <div class="forum_admin_content left ml20" style="width: 750px;">
    <?
    switch(request::get('frame')) {
	case 'sections':
	    include 'admin_parts/sections.php';
	    break;
	case 'forums':
	    include 'admin_parts/forums.php';
	    break;
	default:
	    break;
    }
    ?>
    </div>
</div>