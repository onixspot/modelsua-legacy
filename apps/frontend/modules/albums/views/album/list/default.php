<div id="photos-list">
	
	<? $counter = 0; ?>
	<? $mt = 0; ?>
	<? foreach($album['images'] as $pid){ ?>
		
		<? if($counter == 0){ ?>
			<div class="mt<?=$mt?>">
		<? } ?>

			<div id="photos-list-item-<?=$pid?>" class="left acenter" style="width: 250px">
				<div class="mr5" style="background: #eee">
					<div class="p5 aright" style="height: 20px;">
						<? if(session::has_credential('admin') || session::get_user_id() == $uid){ ?>
							<? if(in_array($category_key, array('covers'))){ ?>
								<a id="photos-list-item-modify-<?=$pid?>" class="mr5" href="javascript:;">
									<img src="/ui/edit2.png" height="12" />
								</a>
							<? } ?>
							<a id="photos-list-item-remove-<?=$pid?>" href="javascript:;">
								<img src="/ui/delete2.png" height="20" />
							</a>
						<? } ?>
					</div>
					<div class="pl10 pr10 pb10">
						<div id="photos-list-item-photo-<?=$pid?>" style="height: 180px; background: url('/imgserve?pid=<?=$pid?>&h=180') no-repeat center; cursor: pointer"></div>
					</div>
					<div style="height: 20px;"></div>
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
