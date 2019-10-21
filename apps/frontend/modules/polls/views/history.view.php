
<div class="small-title left square_p pl10 mt10 mb10">
    <a href="#"><?=profile_peer::get_name($vuser_data).':&nbsp;'.t('История голосования')?></a>
</div>
<div class="clear"></div>
<?
    if(!empty($list)) {
	foreach ($list as $model_id) {
	    $model_data = user_data_peer::instance()->get_item($model_id);?>


    <div class="left" style="width: 300px;">
	<table>
	    <tr>
		<td style="width: 50px;">
		    <?$c = unserialize($model_data['ph_crop']);?>
		    <a class="fs14 ml5" href="/profile?id=<?=$model_data['user_id']?>">
			<img src ="/imgserve?pid=<?=$model_data['pid']?>&w=<?=$c['w']?>&h=<?=$c['h']?>&x=<?=$c['x']?>&y=<?=$c['y']?>&z=crop" style="width: 50px;"/>
		    </a>
		</td>
		<td>
		    <a class="fs14 ml5" href="/profile?id=<?=$model_data['user_id']?>"><?=profile_peer::get_name($model_data)?></a>
		</td>
	    </tr>
	</table>
    </div>


<?}
    } else {?>
    <div class="message_box acenter p20 mb20" style="color: #838999; border: 1px solid #ccc;">
	<?=profile_peer::get_name($vuser_data)?> <?=t('еще не принимал участия в голосовании')?>
    </div>
<? } ?>
    

