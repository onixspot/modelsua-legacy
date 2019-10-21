<div class="fs12 mt10" style="width: 1000px;">
	
	<div class="left mr20" style="width: 370px;">
		
		<div class="fs25 bold"><?=$agency['name']?></div>
		<div>модельное агентство</div>
		
		<!-- START LOCATION -->
		<div class="mt20">
			<? include 'index/location.php'; ?>
		</div>
		<!-- END LOCATION -->
		
		<!-- START ABOUT -->
		<? if($access || $agency['about'] != ''){ ?>
			<div class="mt20">
				<? include 'index/about.php'; ?>
			</div>
		<? } ?>
		<!-- END ABOUT -->
		
		<!-- START CONTACTS -->
		<div class="mt20">
			<? include 'index/contacts.php'; ?>
		</div>
		<!-- END CONTACTS -->
		
		<!-- START MANAGERS -->
		<? if(session::has_credential('admin')){ ?>
			<div class="mt20">
				<? include 'index/managers.php'; ?>
			</div>
		<? } ?>
		<!-- END MANAGERS -->
		
		<!-- START HRONOLOGY -->
		<div class="mt20">
			<? include 'index/hronology.php'; ?>
		</div>
		<!-- END HRONOLOGY -->
		
	</div>
	
	
	<div class="left" style="width: 610px;">
		<!-- START MODELS -->
		<div>
			<? include 'index/models.php'; ?>
		</div>
		<!-- END MODELS -->
		
		<!-- START PHOTO ALBUMS -->
		<? if($access || count($albums_list) > 0){ ?>
			<div class="mt10">
				<? include 'index/albums.php'; ?>
			</div>
		<? } ?>
		<!-- END PHOTO ALBUMS -->
		
	</div>
	
	<div class="clear"></div>
	
</div>