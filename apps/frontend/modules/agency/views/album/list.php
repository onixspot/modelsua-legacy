<div id="images-list">
	
	<? $counter = 0; ?>
	<? $mt = 0; ?>
	<? foreach($agency_album['images'] as $pid){ ?>
		<?// $album = agency_albums_peer::instance()->get_item($pid); ?>
	
		<? if($counter == 0){ ?>
			<div class="mt<?=$mt?>">
		<? } ?>

		<div id="images-list-item-<?=$pid?>" class="left acenter" style="width: 250px;">
			<div class="p5">
				<? if($access){ ?>
					<div class="aright mb5">
						<a id="photos-list-item-remove-<?=$pid?>" href="javascript:;">
							<img src="/ui/delete2.png" style="height: 20px; ">
						</a>
					</div>
				<? } ?>
				<div id="photos-list-item-photo-<?=$pid?>" style="background: url('/imgserve?pid=<?=$pid?>&h=200') no-repeat center; width: 240px; height: 200px; cursor: pointer"></div>
			</div>
		</div>
		
		<? if($counter == 3){ ?>
				<div class="clear"></div>
			</div>
			<? $counter = 0; ?>
			<? $mt = 10; ?>
		<? } else { ?>
			<? $counter++ ?>
		<? } ?>
	
	<? } ?>
	
	<? if($counter <= 3){ ?>
			<div class="clear"></div>
		</div>
	<? } ?>

</div>