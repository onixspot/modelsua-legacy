<div class="fs12 mt10" style="width: 1000px;">
	
	<div class="left mr20" style="width: 370px;">
		<div class="bold fs25">
			<div class="left mr10">
				<?=$journal['name']?>
			</div>
			<div id="block-journal-location" class="left">
				<? if($journal['location'] != ''){ ?>
					<div class="bold" style="color: #888"><?=$journal['location']?></div>
				<? } else { ?>
					<div class="cgray">Место нахождения</div>
				<? } ?>
				<? if($access){ ?>
					<div class="fs12 acenter" style="font-weight: normal;"><a id="journal-location-button-edit" href="javascript:void(0);" class="cgray">[<?=t('Редактировать')?>]</a></div>
				<? } ?>
			</div>
			<div class="clear"></div>
		</div>
		
		<!-- START LOCATION -->
		<div class="mt20">
			<? include 'index/location.php'; ?>
		</div>
		<!-- END LOCATION -->
		
		<!-- START ABOUT -->
		<? if($access || $journal['about'] != ''){ ?>
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
	</div>
	
	<div class="left" style="width: 610px;">
		<!-- START COVERS -->
		<!--<div>
			<?// include 'index/models.php'; ?>
		</div>-->
		<!-- END COVERS -->
		
		<!-- START COVERS -->
		<div>
			<? include 'index/covers.php'; ?>
		</div>
		<!-- END COVERS -->
		
		<?// if(session::get_user_id() == 4){ ?>
		<!-- START FASHION -->
		<div class="mt20">
			<!--<pre><?// var_dump($journal["fashion"]) ?></pre>-->
			<? include 'index/fashion.php'; ?>
		</div>
		<!-- END FASHION -->
		<?// } ?>
	</div>
	
	<div class="clear"></div>
	
</div>