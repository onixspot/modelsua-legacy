<?
$template = "<td %attr%>
                <div style='text-align: center;' class='%fs% bold left cpurple'>%place%</div>
		<div class='left %fs2%' style='margin-left: 3px; margin-top: %mt%px;text-transforn: uppercase;'>
		    место
		</div>
		<div class='right %fs4% cpurple bold' style='margin-top: 5px;'>
		    <img src='/rating_hand.png' style='%img2style%' class=''/>
		    %sum%
		</div>
                <div class='clear'></div>
                <a href='/profile?id=%user_id%'><img style='%imgstyle%' src='%src%'></a>
                <div class='%fs3% pt5 pb5'>
                    <div class='acenter'>
                        <span>%first_name%</span><br/>
                        <b>%last_name%</b>
                    </div>
                    <div class='clear'></div>
                </div>
            </td>";
?>
<table>
    <tr>
	
    
    
<?  foreach ($list as $k=>$v){ 
        $data = db::get_row("SELECT user_id, pid, ph_crop FROM user_data WHERE user_id=:id",array('id'=>$v['user_id']));
        $crop = unserialize($data['ph_crop']);
	if($data) {
	    generate_cols($list,$k, ($k+1),'',array('width: 95px;','width: 0px;'),array('fs16','fs12','fs10','fs0','mt'=>6),$template);
	}
	if(($k+1)%10==0) 
	    echo "</tr><tr>";
	?>
	

    <? } ?>
    </tr>
</table>
<div class='clear'></div>
<div class="acenter fs14 paginator mb10">
    <?=pager_helper::get_full($pager,null,null,4)?>
</div>