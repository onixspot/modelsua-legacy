<? include 'partials/top_menu.php'?>
<div id="page-body" class="fs13 mt10">
<?include 'ucp/tabs.php';?>
    <div class="panel bg3">
	    <div class="inner">
		<span class="corners-top">
		    <span></span>
		</span>
		<div style="width: 100%;">
		    <div id="cp-menu">
			    <div id="navigation">
				    <?include 'ucp/menus/profile.php'?>
				    <?include 'ucp/menus/review.php'?>
			    </div>
		    </div>
		    <div class="ucp-main" id="cp-main">
			
			<?include 'ucp/forms/review/front.php'?>
			<?include 'ucp/forms/review/drafts.php'?>
			<?include 'ucp/forms/review/subscribed.php'?>
			
			<?include 'ucp/forms/profile/personal.php'?>
			<?include 'ucp/forms/profile/avatara.php'?>
			<?include 'ucp/forms/profile/registration.php'?>
			<?include 'ucp/forms/profile/signature.php'?>
			
		    </div>
		    <div class="clear"></div>
		</div>
		<span class="corners-bottom">
		    <span></span>
		</span>
	    </div>
    </div>
</div>
<script>
    $(function() {
	
	
	$('a[id^="ucp-tab-"]').click(function(){
	    var tab = $(this).attr('id').split('-')[2];
	    
	    $('#tabs > ul > li').removeClass('activetab');
	    $(this).parent().addClass('activetab');
	    
	    $('[id^="ucp-menu-"]').hide();
	    $('[class*="-box"]').hide();
	    
	    $('[id="ucp-menu-'+tab+'"]').show();
	    $('[class^="'+tab+'-"]:first').show();
	});
	
	$('a[id^="ucp-tab-"]:first').click();
    });
</script>
