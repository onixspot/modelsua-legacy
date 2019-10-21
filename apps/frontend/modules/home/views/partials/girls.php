<div class="main-page-gallery">
    <div class="image-box" style="">
        <?if($most) {?>
            <?  foreach ($most as $k => $v) {
                      if($v['ph_crop']) {
                          $crop = unserialize($v['ph_crop']);
                          $src = "http://".conf::get('server')."/imgserve?pid=".$v['pid']."&w=".$crop['w']."&h=".$crop['h']."&x=".$crop['x']."&y=".$crop['y']."&z=crop";
                      }
                      else {
                          $src = "http://".conf::get('server')."/no_image.png";
                      }
                      
                      
                      
                ?>
            <a href="/profile?id=<?=$v['id']?>">
                <img src="<?=$src?>" <?=($k%10==0 && $k) ? 'style="margin-right: 0px;"' : (($i==0) ? 'style="margin-left: 0px;"' : '');?>>
            </a>
            <? } ?>
        <? } ?>
    </div>
</div>


