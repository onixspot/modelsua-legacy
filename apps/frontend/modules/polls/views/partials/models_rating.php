

<div class="models_rating_box">
    <div class="small-title left square_p pl10 mt10 mb10">
        <a href="/"><?=t('самые популярные')?></a>
    </div>
    <div class="clear"></div>
    <table border="0" style="width: 100%;" id="models_rating_table">
        <tr>
            <?$fp = db::get_row("SELECT user_id, pid, ph_crop, first_name, last_name FROM user_data WHERE user_id=:uid",array('uid'=>$list[0]['user_id']));?>
            <?$crop = unserialize($fp['ph_crop']);?>
            <td rowspan="2" style="vertical-align: top;">
                <div style='text-align: center;' class='fs18 bold left cpurple'>1</div><div class='left fs12' style='margin-left: 3px; margin-top: 6px;text-transforn: uppercase;'>место</div>
                <div class='clear'></div>
                <a href="/profile?id=<?=$list[0]['user_id']?>"><img style='width: 318px' src='/imgserve?pid=<?=$fp['pid']?>&w=<?=$crop['w']?>&h=<?=$crop['h']?>&x=<?=$crop['x']?>&y=<?=$crop['y']?>&z=crop'></a>
                <div class="fs20 left pt5 pb5" style="margin-left: 0px;border-bottom: 1px solid #ccc; width: 318px;  ">
                    <div class="left aleft" style="">
                        <span><?=$fp['first_name']?></span><br/>
                        <b><?=  mb_strtoupper($fp['last_name']);?></b>
                    </div>
                    <div class="right fs20 cpurple bold" style="margin-top: 5px;">
                        <img src="/rating_hand.png" class=""/>
                        <?=$list[0]['sum']?>
                    </div>
                </div>
            </td>
            <?generate_cols($list,1, 5,'',array('width: 160px;','width: 16px;'),array('fs22','fs14','fs12','fs17','mt'=>10));?>
        </tr>
        <tr>
            <td colspan="8">
                <table border="0" style="width: 100%;">
                    <tr>
                        <?generate_cols($list,5, 10,'',array('width: 130px;','width: 16px;'),array('fs20','fs12','fs10','fs15','mt'=>8));?>
                    </tr>
                </table>
            </td>
            
        </tr>
    </table>
    <table border="0" style="width: 100%">
        <tr>
            <?generate_cols($list,10, 20,'',array('width: 90px;','width: 10px;'),array('fs16','fs12','fs9','fs13','mt'=>4));?>
        </tr>
    </table>
    <div class="clear"></div>
    <div class="right" style="margin-top: 25px; margin-bottom: 25px;">
        <a href='/polls/rating?type=models-full' style='text-decoration: underline; padding-right: 10px; font-style: italic;' class="arrow_p right fs12"><?=t('Смотреть весь рейтинг')?></a>
    </div>
    <div class="clear"></div>
</div>