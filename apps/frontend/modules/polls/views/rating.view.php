<?

function generate_cols($list,$start,$count,$attr,$imgsizes,$fs, $template = "<td %attr%>
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
										    <div class='left acenter'>
											<span>%first_name%</span>&nbsp;
											<b>%last_name%</b>
										    </div>
										    <div class='clear'></div>
										</div>
									    </td>") {
    for($i=$start; $i<$count; $i++) {
        $data = db::get_row("SELECT user_id,first_name,last_name, pid, ph_crop FROM user_data WHERE user_id=:id",array('id'=>$list[$i]['user_id']));
        $crop = unserialize($data['ph_crop']);
        if($data) {
	    $arr = array(
			'/%mt%/'=>$fs['mt'],
			'/%attr%/'=>$attr,
			'/%fs%/'=>$fs[0],
			'/%fs2%/'=>$fs[1],
			'/%fs3%/'=>$fs[2],
			'/%fs4%/'=>$fs[3],
			'/%place%/'=>(($i+1)+(request::get_int('page',1)-1)*40),
			'/%user_id%/'=>$data['user_id'],
			'/%imgstyle%/'=>$imgsizes[0],
			'/%img2style%/'=>$imgsizes[1],
			'/%src%/'=> "/imgserve?pid=".$data['pid']."&w=".$crop['w']."&h=".$crop['h']."&x=".$crop['x']."&y=".$crop['y']."&z=crop",
			'/%first_name%/'=>$data['first_name'],
			'/%last_name%/'=>$data['last_name'],
			'/%sum%/'=>($list[$i]['sum'])
		    );
	    echo preg_replace (array_keys ($arr), array_values ($arr), $template);
	}
    }
}
?>
<style>
    .fs0 {
	font-size: 0px;
    }
    table td {
        text-align: center;

    }
</style>
<div class="rating_content_box">
    <?
        switch(request::get_int('type',  voting_peer::MODEL_RATING)) {
            case voting_peer::MODEL_RATING:
                include 'partials/models_rating.php';
                break;
            case 'models-full':
                include 'partials/full_rating.php';
                break;
            default :
                include 'partials/models_rating.php';
                break;
        }
    ?>
</div>
