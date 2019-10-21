<? if( ! $profile['hidden']){ ?>
	<div class="mb20 pt10 pb10 cblack fs11" style="border-bottom: 1px solid #eee;">
		<!--<div class="left" style="width: <?=$attr_key_width?>px;">
			<div class="left">
				<img src="/rating_star.png" />
			</div>
			<div class="left ml5 fs25">0</div>
			<div class="left ml5 italic" style="color: #aeb7c9;">
				<div>Профессиональный</div>
				<div>рейтинг (0 баллов)</div>
			</div>
			<div class="clear"></div>
		</div>-->
		<div class="left hide" style="width: <?=$attr_val_width?>px;">
			<div class="left mr5 italic"  style="color: #aeb7c9;">
				<div>Рейтинг</div>
				<div>популярности</div>
			</div>
			<div class="left">
					<img src="/rating_hand.png" <?if(voting_peer::can_vote($profile['user_id'], voting_peer::MODEL_RATING)) {?>class="pointer" onClick="App.vote('<?=$profile["user_id"]?>','<?=voting_peer::MODEL_RATING?>',1,'model_rating_votes'); $(this).removeAttr('onClick').removeClass('pointer');"<? } ?>/>
			</div>
			<div class="left ml5 fs25" id="model_rating_votes"><?=voting_peer::get_rating_position($profile['user_id'])?></div><?// voting_peer::calculateVotes($profile['user_id'], voting_peer::MODEL_RATING)?>
			<div class="left ml5 fs12"  style="color: black;">
				<div style="margin-top: 13px;">место</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>

<?
load::model('voting');
$voters = voting_peer::getVoters(voting_peer::MODEL_RATING, $profile['user_id']);
$unregistreg=0;
if(!empty($voters))
    foreach ($voters as $uid) {
	if($uid)
	    $user_info[] = db::get_rows("SELECT user_id,first_name,last_name,pid,ph_crop FROM user_data WHERE user_id=:uid",array('uid'=>$uid));
	else 
	    $unregistreg++;
    }

?>
<? if($user_info) { ?>
<div id="voters-tooltip" class="hide p10" style="position: absolute; background: #f0f0f0; color: white; border-radius: 5px;">
	<div class="voters-tooltip-header cblack">
	    Проголосовали
	</div>
	<div class="mt5">
	    <?
	    
		foreach ($user_info as $cnt=>$uid) 
		    foreach ($uid as $k => $udata) {
		    if($udata['ph_crop'] && $udata['pid']) {
			$crop = unserialize($udata['ph_crop']); ?>
			    <img style="margin-left: 0px;width: 30px;" src="/imgserve?pid=<?=$udata['user_id']?>&w=<?=$crop['w']?>&h=<?=$crop['h']?>&x=<?=$crop['x']?>&y=<?=$crop['y']?>&z=crop"/>
	    <? } } ?>	
	</div>
</div>
<? } ?>
<script>
$("#rating_hand")
    .mouseover(function(e){
	    $('#voters-tooltip')
			    .show()
			    .css({
				    'top': e.pageY-80,
				    'left': e.pageX,
				    'zIndex': '999'
	    });
    })
    .mouseout(function(){
	    $('#voters-tooltip').hide();
    })
</script>
<? } else { ?>
<hr style="border-color: #eee" />
<? } ?>

