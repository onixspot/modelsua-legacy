<div id="news" class="fs12">
    <div class="left mt10" style="width: 220px;">
        <?include 'admin_menu.php'?>
        <div class='clear'></div>
<!--        <input type="button" class="button mb5 mt5" id="switch_button" style="width: 210px;" value="Создать контент" onClick="get_item_data(0);"/>-->
<!--        <div class='clear'></div>-->
        <div class="search_form mb5">
            <table>
                <tr>
                    <td>
                        Название
                    </td>
                    <td>
                        <input style="width: 150px;" type="text" name="search_text"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        Дата
                    </td>
                    <td>
                        <input style="width: 150px;" type="text" name="search_date"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        &nbsp;
                    </td>
                    <td>
                        <input type="button" name="search_button" value="Поиск" onClick="search();">
                    </td>
                </tr>
            </table>
        </div>
        <div class='clear'></div>
        
    </div>
    
    <div class="left mt10" style="width: 770px;">
        <div class="switcher hide">
            <div class="filters pt5 pb5" style="border: 1px solid #c9c9c9; margin: 0 0 5px 0;">
                <a href="javascript:void(0);" id="switch_button" onClick="get_item_data(0);$('.filters a').removeClass('selected');$(this).addClass('selected');">Создать контент</a>
                <a href="javascript:void(0);" onClick="get_news_list(0,'search',';');$('.filters a').removeClass('selected');$(this).addClass('selected');">Все</a>
                <a href="javascript:void(0);" onClick="get_news_list(0,'type',1);$('.filters a').removeClass('selected');$(this).addClass('selected');">Новости</a>
                <a href="javascript:void(0);" onClick="get_news_list(0,'type',2);$('.filters a').removeClass('selected');$(this).addClass('selected');">Публикации</a>
                <a href="javascript:void(0);" onClick="get_news_list(0,'type',3);$('.filters a').removeClass('selected');$(this).addClass('selected');">Анонсы</a>
            </div>
            <div style=""  id="news-list" class="left" >
            </div>
            <div class='clear'></div>
            <div id="paginator"></div>
        </div>
        <div class="clear"></div>
        <div class="switcher form_div">
            <div class="left" id="news-form" style="width: 100%;">
                <form id="news-edit-form">
                    <input type="hidden" name="act" id="act" value="save">
                    <input type="hidden" name="language" id="language" value="ru">
                    <table style="width: 100%;">
			<tr>
                            <td>
                                Язык
                            </td>
                            <td>
                                <input name="lang[]" type="button" class="button" value="Ru"/>
                                <input name="lang[]" type="button" class="button" value="En"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Название
                            </td>
                            <td>
                                <input name="title" type="text" id="title" value=""/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Автор
                            </td>
                            <td>
                                <input name="author" type="text" id="author" value=""/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Обложка
                            </td>
                            <td>
                                <input name="file" type="file" id="file" value=""/>
                            </td>
                        </tr>
						<?// if(session::get_user_id() == 4){ ?>
						<tr>
							<td></td>
							<td>
								<img id="new-cover" src="" style="width: 180px;" />
							</td>
						</tr>
						<?// } ?>
						<tr>
							<td>Модели:</td>
							<td>
								<div id="models-list" class="p5" style="border: 1px solid #ccc; width: 200px; height: 250px; overflow: auto;">
									<? foreach($models_list as $id){ ?>
										<? $user_data = user_data_peer::instance()->get_item($id); ?>
										<div id="models-item-<?=$id?>">
											<div class="left mr5">
												<input type="checkbox" id="models-item-checkbox-<?=$id?>" />
											</div>
											<div class="left">
												<label for="models-item-checkbox-<?=$id?>"><?=profile_peer::get_name($user_data)?></label>
											</div>
											<div class="clear"></div>
										</div>
									<? } ?>
								</div>
							</td>
						</tr>
                        <tr>
                            <td>
                                Тип
                            </td>
                            <td>
                                <?=tag_helper::select('type', array(1=>'Новости',2=>'Публикации',3=>'Анонсы'));?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Дата создания
                            </td>
                            <td>
                                <?=ui_helper::datefields("created_ts", time())?>
                            </td>
                        </tr>
                        <tr class="hide" id="end_date">
                            <td>
                                Дата окончания
                            </td>
                            <td>
                                <?=ui_helper::datefields("end_ts", time())?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Анонс
                            </td>
                            <td>
                                <textarea name="anons" id="anons" value=""></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Удерживать на главной
                            </td>
                            <td>
                                <input type="checkbox" name="on_main" id="on_main"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Без комментариев
                            </td>
                            <td>
                                <input type="checkbox" name="no_comments" id="no_comments"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Показивать только зарегестрированным
                            </td>
                            <td>
                                <input type="checkbox" name="access" id="access"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Скрытая
                            </td>
                            <td>
                                <input type="checkbox" name="hidden" id="hidden"/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <textarea name="body" id="body" value=""></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="button" id="submit" class="button" value="Сохранить">
                                <input type="button" onClick="switcher();" value="Отмена">
                            </td>
                        </tr>
                    </table>
                </form>
                <div class='clear'></div>
                <div class="success hide fs16">Изменения сохранены</div>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>
<div id="finder">finder</div>

<style>
    table td {
        background: transparent !important;
    }
</style>
<script>
window.data={save: 1};
window.current_page=0;
window.filter_name='type';
window.filter_value = 0;

$().ready(function() {
    var opts = {
            cssClass : 'el-rte',
            lang     : 'ru',
            height   : 600,
            toolbar  : 'maxi',
            cssfiles : ['https://css.<?=conf::get('server')?>/erlte/elrte.min.css','https://css.<?=conf::get('server')?>/erlte/elfinder.css'],
            fmOpen : function(callback) {
                 $('#finder').elfinder({
                        url : '/connectors/elfinder',
                        lang : 'en',
                        dialog : { modal : true, title : 'elFinder - file manager for web' },
                        closeOnEditorCallback : true,
                        editorCallback : callback
                })
             }
    }
    
    $('#body').elrte(opts);
    get_item_data(0);
    <?if(request::get('edit_news')) { ?>
        get_item_data('<?=request::get('edit_news')?>');
    <? } ?> 

	$('input[name="lang[]"]').click(function(){
	    save_data(window.data);
	    $("#language").val($(this).val().toLowerCase());
	    show_data(window.data);
	    
	});
    $('select[name="type"]').change(function(){
        if($(this).val()==3)
            $('#end_date').removeClass('hide');
        else
            if(!$('#end_date').hasClass('hide'))
                $('#end_date').addClass('hide');
    })
    get_news_list(window.current_page,window.filter_name,window.filter_value);
});
var news_form = new Form("news-edit-form");
news_form.onSuccess = function(data){
        if(data.success == true){
                
        }
}
function get_item_data(id) {
    $.post('/adminka/news',{get_data:1, id: id},function(resp){ window.data = resp; show_data(window.data); switcher();}, "json");
}

function show_data(data) {
    var lang = $('#language').val();
    for(var i in data) {
        switch(i) {
            case 'on_main':
            case 'access':
            case 'no_comments':
            case 'hidden':
                if(data[i]==1) $('#'+i).attr('checked',1).val('1');
                else $('#'+i).removeAttr('checked').val('0');
                break;
            case 'created_ts':
            case 'end_ts':
                if(data[i]) {
                    $('select[name^="'+i+'"] option').removeAttr('selected');
                    $('select[name="'+i+'_day"] option[value="'+data[i].split('.')[0]+'"]').attr('selected','1');
                    $('select[name="'+i+'_month"] option[value="'+data[i].split('.')[1]+'"]').attr('selected','1');
                    $('select[name="'+i+'_year"] option[value="'+data[i].split('.')[2]+'"]').attr('selected','1');
                }
            case 'author': 
                $('#'+i).val(data[i]);
                break;
            case 'type':
                $('select[name="type"] option').removeAttr('selected');
                $('select[name="type"] option[value="'+data[i]+'"]').attr('selected','1');
                if(data[i]==3)
                    $('#end_date').removeClass('hide');
                else
                    if(!$('#end_date').hasClass('hide'))
                        $('#end_date').addClass('hide');
                break;
            case 'body':
                if(data[i][lang]) $('#'+i).elrte('val',data[i][lang]);
                else $('#'+i).elrte('val',' ');
                break;
            case 'anons' :
            case 'title' :
               
                $('#'+i).val(data[i][lang]);
                break;
			case 'models':
				$("#models-list input[id^='models-item-checkbox-']").attr("checked", false);
				for(var id in data[i])
				{
					$("#models-list #models-item-checkbox-"+data[i][id]).attr("checked", true);
				}
				break;
				
			case "salt":
				$("#new-cover").attr("src", "https://img.<?=conf::get('server')?>/pp/"+data[i]);
				break;
				
            default: 
                break;
        }
    }
}
$('input[type="checkbox"]').change(function(){
    if($(this).attr('checked')) $(this).val(1);
    else $(this).val(0);
});
function switcher(){
    $('.switcher').toggleClass('hide');
    if($('.form_div').hasClass('hide')) $('#switch_button').val('Создать контент');
    else $('#switch_button').val('Вернуться к списку');
    
}
function save_data(data) {
    var lang = $('#language').val();
    for(i in data) {
        switch(i) {
            case 'on_main':
            case 'access':
            case 'no_comments':
            case 'hidden':
                data[i] = $('#'+i).val();
                break;
            case 'created_ts':
            case 'end_ts':
                var date = new Array();
                date[0] = $('select[name="'+i+'_day"] option:selected').val();
                date[1] = $('select[name="'+i+'_month"] option:selected').val();
                date[2] = $('select[name="'+i+'_year"] option:selected').val();
                data[i] = date.join('.');
                break;
            case 'author':
                (data[i]) = $('#'+i).val();
                break;
            case 'type':
                data[i] =  $('select[name="type"] option:selected').val();
                break;
            case 'body':
                data[i][lang] = $('#'+i).elrte('val');
                break;
            case 'anons' :
            case 'title' :
                //console.log('#'+i+ '='+ $('#'+i).val());
                data[i][lang] = $('#'+i).val();
                break;
               
            default: 
                break;
        }
    }
}


$('input[name="lang"]').click(function(){
    save_data(window.data);
    $('#language').val($(this).val().toLowerCase());
    show_data(window.data);
});

function post_file(post_params) {
    $.ajaxFileUpload({
        url: '/adminka/news'+post_params,
        secureuri:false,
        fileElementId:'file',
        dataType: 'json',
        success: function (data, status)
        {
                console.log('ok');
        },
        error: function (data, status, e)
        {
                console.log(e);
        }
        
    });
}
$("#news-edit-form #submit").click(function(){

    save_data(window.data);
	
	window.data["models"] = new Array();
	$.each($("#models-list > div[id^='models-item-']"), function(){
		var id = $(this).attr("id").split("-")[2];
		var state = $("#models-item-checkbox-"+id, this).attr("checked") ? true : false;
		if(state == true)
		{
			window.data["models"].push(id);
		}
	});
	
    window.data['act']='save';
    window.data['submit']=1;
    
    $.post('/adminka/news',window.data,function(resp){
        if(resp.success==1) {
            window.data['id']=resp.id;
            show_data(window.data);
            if($("#file").val() && resp.id) {
                post_params = (resp.psalt) ? '&id='+resp.id+'&salt='+resp.psalt : '&id='+resp.id;
                post_file(post_params);
            }
            get_news_list(window.current_page,window.filter_name,window.filter_value);
            $('.success').fadeIn(300, function(){$(this).fadeOut(3000)});
			window.open('/news/view?id='+resp.id);
        }
        else 
            console.log('error');
    },'json');
    return false;
    
});
function get_news_list(page,filter_name,filter_value) {
    window.current_page= page;
    window.filter_name=filter_name;
    window.filter_value=filter_value;
    $.post('/adminka/news',{submit: 1, act: 'get_list', page: page, filter_name: filter_name, filter_value: filter_value},function(resp) { show_list(resp) },'json');
}

var preview_page = function(id)
{
	window.open ("/news/view?id="+id);
}

function show_list(resp) {
	//onClick="get_item_data(%id%);"
    template = '<tr>\n\
                    <td style="width: 100px; text-align: center">\n\
                        <div style="background: #%color%;">%date%</div>\n\
                    </td>\n\
                    <td style="width: 100px;">\n\
                        %type%\n\
                    </td>\n\
                    <td style="width: 525px;">\n\
                        <div class="admin-news-title fs14 bold" style="margin-top: 5px;">\n\
                            <a href="javascript:void(0);" onClick="preview_page(%id%)">%title%</a>\n\
                        </div>\n\
                        <div class="admin-news-description fs11" style="margin-top: 1px;">\n\
                            %description%\n\
                        </div>\n\
                    </td>\n\
                    <td>\n\
                        <a href="javascript:void(0);" onClick="get_item_data(%id%);">\n\
                            <img src="https://<?=conf::get('server')?>/ui/edit.png">\n\
                        </a>\n\
                        <a href="javascript:void(0);" onClick="delete_news(%id%);">\n\
                            <img src="https://<?=conf::get('server')?>/ui/delete.png">\n\
                        </a>\n\
                    </td>\n\
                </tr>';
        
    var html = '';
    for(var i in resp)
	{
        if(i!='pages') 
            html += template.replace('%title%', resp[i]['title']['ru']).replace('%date%', resp[i]['created_ts']).replace('%type%', resp[i]['type']).replace('%description%', resp[i]['anons']['ru']).replace('%id%', resp[i]['id']).replace('%id%', resp[i]['id']).replace('%id%', resp[i]['id']).replace('%color%', resp[i]['hidden'] != 1 ? 'fff': 'ccc');
	}
        
    
    page_count = resp['pages'];
    var page_html = '';
    if(page_count>1) {
        page_html = '<div class="paginator"><a href="javascript:void(0);" onClick="get_news_list(0,\''+window.filter_name+'\','+window.filter_value+')">&larr;</a>';
        for(var j=0; j<page_count; j++)
            page_html += '<a onClick="get_news_list('+j+',\''+window.filter_name+'\',\''+window.filter_value+'\');" class="'+((window.current_page==j) ? 'selected' : ' ')+'" href="javascript:void(0);">'+(j+1)+'</a>';
        page_html += '<a href="javascript:void(0);" onClick="get_news_list('+(page_count-1)+',\''+window.filter_name+'\',\''+window.filter_value+'\')">&rarr;</a></div>';
    }
    
    $('#news-list').html('<table>'+html+"</table>");
    $('#paginator').html(page_html);
    
}
function delete_news(id) {
    $.post('/adminka/news', {submit: 1, act: 'delete', id: id}, function(resp) {get_news_list(window.current_page,window.filter_name,window.filter_value); }, 'json');
}
function search() {
    var text = $('input[name="search_text"]').val();
    var date = $('input[name="search_date"]').val();
    get_news_list(0,'search',text+';'+date);
    
}

</script>
