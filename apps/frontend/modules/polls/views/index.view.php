<?if($access) {?>
    <div class="polling_content_box mt20">
        <div style="color: #838999; border: 1px solid #ccc;" class="message_box acenter p20 mb20">
            <?=t('Для участия в голосовании войдите или <a href="/sign/registration">зарегистрируйтесь</a> на сайте')?>
        </div>
    </div>
<? }elseif(!$access) {?>
<div class="polling_content_box mt20" style="width: 708px;">
    <div class="left">
        <div class="big-title fs20 cgray re_ru">
            <span style="margin-top: 10px;"><?=t('ейтинг')?></span>
        </div>
        <div class="clear"></div>
        <span style="text-transform: uppercase;" class="fs16 bold"><?=t('популярности')?></span>
    </div>
    
    <div class="right hide fs12 aright" style="width: 450px; color: #9CA1AE; font-style: italic; color: white !important" >
         Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type...
    </div>
    <div class="clear"></div>
    
    <div class="photos_box mt10">
        <table>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td colspan="2" id="image1" style="width: 340px;height: 340px;" class="acenter">
                                <img style="width: 340px;" src="/loading.gif" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="acenter pt10">
                                <center><div class="pointer vote_button" style="background: #000000; width: 200px; color: white;">
                                    <img src="/rating_white.png" onclick="">
                                    <span class="ml5 fs18" style="line-height: 33px;">
                                        <?=t('Голосовать')?>
                                    </span>
                                </div></center>
                            </td>
                        </tr>
                        
                    </table>
                </td>
                <td>
                    <table>
                        <tr>
                            <td colspan="2" class="acenter" id="image2" style="width: 340px;height: 340px;" class="acenter">
                                <img style="width: 340px;" src="/loading.gif" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="acenter pt10">
                            <center>
                                <div class="pointer vote_button" style="background: #000000; width: 200px; color: white;">
                                    <img src="/rating_white.png" onclick="" class="pointer">
                                    <span class="ml5 fs18" style="line-height: 33px;">
                                        <?=t('Голосовать')?>
                                    </span>
                                </div>
                            </center>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="acenter pt10 fs14" colspan="2">
                    <span class="votelinks"><i><span style="color: #9CA1AE;"><?=t('Не нравятся обе фотографии')?>,</span> <a href="javascript:void(0);" onClick="vote_for(0)" style="" class="bold"><?=t('пропустить')?></a><br/><br/>
		    <a href="/polls/rating?type=<?=  voting_peer::MODEL_RATING;?>" class="bold"><?=t('Смотреть рейтинг')?></a></i></span>
                </td>
            </tr>
        </table>


    </div>
</div>
<style>
    .votelinks a {
        background: url('/arrow_p.png') no-repeat 100% 50% scroll; 
        padding-right: 10px;
        text-decoration: underline;
    }
    .votelinks a:hover {
        text-decoration: none;
    }
</style>
<script>
window.data = 0;
function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}
function get_data() {
    $.post(
        '/polls/index',
        {
            get_pair: 1
        },
        function (resp) {
            window.data = resp;
            setTimeout("select_random_photos()",300);
        },
        'json'
    );
}
function rebuild_data(data) {
    var ret = [];
    if(data)
        for(i in data)
            if(data[i])
                ret.push(data[i]);
    return ret;
}
function select_random_photos() {
    if(window.data.length>1) {
        
        var id = getRandomInt(0,(window.data.length-1));
        set_data(id,1,window.data);
	
        var temp_data = rebuild_data(window.data);
	delete(temp_data[id]);
	temp_data = rebuild_data(temp_data);
	
	var id = getRandomInt(0,(temp_data.length-1));
        set_data(id,2,temp_data);
    }
    else window.location="/polls/rating?object=<?=voting_peer::MODEL_RATING?>";
        
    
}
function set_data(id,ident,data) {
    id = parseInt(id);
    var crop = data[id]['ph_crop'];
    var user_id = data[id]['user_id'];
    $('#image'+ident+' > img:eq(0)').attr({'src':'/imgserve?pid='+data[id]['pid']+'&w='+crop.w+'&h='+crop.h+'&x='+crop.x+'&y='+crop.y+'&z=crop'});
    $('.vote_button:eq('+parseInt(ident-1)+')')
    .click(function(){
	vote_for(user_id);
    });
}
function loading() {$('td[id^="image"] > img').each(function() {$(this).attr('src','/loading.gif')})}
function vote_for(id) {
    if(id) {
	$('.vote_button').unbind('click');
	App.vote(id,'<?= voting_peer::MODEL_RATING?>',1,'');
	window.data = delete_by_uid(window.data,id);
    } 
    loading();
    setTimeout("select_random_photos()",100);
}
function delete_by_uid(data,uid) {
    if(data)
	for(i in data) 
	    if(data[i]['user_id']==uid) {
		delete(data[i]);
		data = rebuild_data(data);
		break;
	    }
    return data;
}
get_data(); 

</script>
<? } ?>
