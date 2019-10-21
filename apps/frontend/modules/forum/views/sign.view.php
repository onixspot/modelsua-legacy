<?include 'partials/top_menu.php'?>
<div class="clear"></div>
<?
switch(request::get('mode','login')) {
    case 'login':
	include 'sign_parts/login.php';
	break;
    case 'registration':
	include 'sign_parts/registration.php';
	break;
}
?>