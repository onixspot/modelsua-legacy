<li style="height:81px; width: 300px;" class="left ml10 mr10">
        <?if($profile['pid']) {
            $src ="/imgserve?pid=".$profile['pid'] ;
               if($profile['ph_crop']) {
                   $crop = unserialize ($profile['ph_crop']);
                   $src .= '&x='.$crop['x'].'&y='.$crop['y'].'&w='.$crop['w'].'&h='.$crop['h'].'&z=crop';
               }
            } else { 
                $src = '/no_image.png'; 
            }?>
        <img style="width: 65px; height: 65px;" class="left mr20" src="<?=$src?>"/>
        <span><a href="/profile?id=<?=$profile['user_id']?>"><?
            $name = profile_peer::get_name($profile);
            $name = explode(' ',$name);
            echo $name[0].' <span style="text-transform: uppercase; font-weight: bold;">'.strtoupper($name[1])."</span>";
        ?></a></span><br>
        <div style="line-height: 10px;" class="mt0">
        <?if($profile['birthday']) {?><span class="fs10"><?= profile_peer::getAge($profile) ?></span><br><? } ?>
        <?if($profile['country']) {?><span class="fs10"><?=geo_peer::instance()->get_country($profile['country'])?></span><? } ?>
        <?if($profile['city']) {?><span class="fs10">
                <? if($profile['city'] > 0){ ?>
                        <?=' / '.geo_peer::instance()->get_city($profile['city'])?>
                <? } else { ?>
                        <?=' / '.$profile['another_city']?>
                <? } ?>
        </span>
        <? } ?>
        </div>
        <div class="cblack fs11 bold" style="margin-top: 7px;">
                <div class="left">
                        <img src="/rating_star.png"  style="width: 14px;"/>
                </div>
                <div class="left ml5 fs13">0</div>
                <div class="left ml20">
                    <img src="/rating_hand.png" style="width: 14px;"/>
                </div>
                <div class="left ml5 fs13" id="model_rating_votes"><?=  voting_peer::calculateVotes($profile['user_id'], voting_peer::MODEL_RATING)?></div>
                <div class="clear"></div>
        </div>
        
        
</li>