<div id="news" class="frame">
    <div class="mt5">
<!--	<div class="mb5">
                <div class="left mr20">
                        <a href="javascript:void(0);" onclick="">Новости</a>
                </div>
                <div class="left mr20">
                        <a href="javascript:void(0);" onclick="">Публикации</a>
                </div>
                <div class="left">
                        <a href="javascript:void(0);" onclick="">Анонсы</a>
                </div>
        </div>-->
    </div>
    <div class="clear"></div>
    <input type="button" class="button mb5" style="width: 190px;" value="Создать контент" onClick="get_item_data(0);"/>
    <div class='clear'></div>
    <div style="width: 25%; padding-left: 1px; padding-right: 10px; height: 450px; overflow-y: scroll; overflow-x: hidden;"  id="news-list" class="left" >
        
    </div>
    <div class="left" id="news-form" style="width: 70%; margin-left: 15px;">
        <form id="news-edit-form">
            <input type="hidden" name="act" id="act" value="save">
            <input type="hidden" name="language" id="language" value="ru">
            <table style="width: 100%;">
                <tr>
                    <td>
                        Язык
                    </td>
                    <td>
                        <input name="lang" type="button" class="button" value="Ru"/>
                        <input name="lang" type="button" class="button" value="Ua"/>
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
                <tr>
                    <td>
                        Дата
                    </td>
                    <td>
                        <input name="created_ts" type="text" id="created_ts" value=""/>
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
                    <td colspan="2">
                        <textarea name="body" id="body" value=""></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="button" id="submit" class="button" value="Сохранить">
                    </td>
                </tr>
            </table>
        </form>
        <div class='clear'></div>
        <div class="success hide fs16">Изменения сохранены</div>
    </div>
    
</div>
<style>
    table td {
        background: transparent !important;
    }
</style>
<script>
window.data={save: 1};
$().ready(function() {
    var opts = {
            cssClass : 'el-rte',
            lang     : 'ru',
            height   : 200,
            toolbar  : 'complete',
            cssfiles : ['https://css.<?=conf::get('server')?>/elrte.css']
    }
    
    $('#body').elrte(opts);
    
//    var settings = {
//        changeMonth: true,
//        changeYear: true,
//        autoSize: true,
//        showOptions: {direction: 'left' },
//        dateFormat: 'dd.mm.yy',
//        yearRange: '2010:2012',
//        firstDay: true
//    };
//    $('#created_ts').datepicker(settings);
    
    get_item_data(7);
    get_news_list();
    
});
var news_form = new Form("news-edit-form");
news_form.onSuccess = function(data){
        if(data.success == true){
                
        }
}
function get_item_data(id) {$.post('/admin/news',{get_data:1, id: id},function(resp){ window.data = resp; show_data(window.data); }, "json");}

function show_data(data) {
    var lang = $('#language').val();
    for(i in data) {
        switch(i) {
            case 'on_main':
            case 'access':
            case 'no_comments':
                if(data[i]==1) $('#'+i).attr('checked',1).val('1');
                else $('#'+i).removeAttr('checked').val('0');
                break;
            case 'created_ts':
            case 'author': 
                $('#'+i).val(data[i]);
                break;
            case 'type':
                $('select option').removeAttr('selected');
                $('select[name="type"] option[value="'+data[i]+'"]').attr('selected','1');
                break;
            case 'body':
                if(data[i][lang]) $('#'+i).elrte('val',data[i][lang]);
                else $('#'+i).elrte('val',' ');
                break;
            case 'anons' :
            case 'title' :
               
                $('#'+i).val(data[i][lang]);
                break;
            default: 
                break;
        }
    }
}
$('input[type="checkbox"]').change(function(){
    if($(this).attr('checked')) $(this).val(1);
    else $(this).val(0);
})
function save_data(data) {
    var lang = $('#language').val();
    for(i in data) {
        switch(i) {
            case 'on_main':
            case 'access':
            case 'no_comments':
                data[i] = $('#'+i).val();
                break;
            case 'created_ts':
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
        url: '/admin/news'+post_params,
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
    window.data['act']='save';
    window.data['submit']=1;
    
    $.post('/admin/news',window.data,function(resp){
        if(resp.success==1) {
            window.data['id']=resp.id;
            show_data(window.data);
            if($("#file").val() && resp.id) {
                post_params = (resp.psalt) ? '&id='+resp.id+'&salt='+resp.psalt : '&id='+resp.id;
                post_file(post_params);
            }
            get_news_list();
            $('.success').fadeIn(300, function(){$(this).fadeOut(3000)});
        }
        else 
            console.log('error');
    },'json');
    return false;
    
});
//.replace('%title%', resp[i]['title']['ru']).replace('%description%', resp[i]['anons']['ru'])
function get_news_list() {
    $.post('/admin/news',{get_list: 1},function(resp) { show_list(resp) },'json');
}
function show_list(resp) {
    
    template = '<div class="uiTabBar p5" style="height: auto !important">\n\
                    <div class="left" style="width: 180px;">\n\
                        <div class="admin-news-title fs14 bold" style="margin-top: 5px;">\n\
                            <a href="javascript:void(0);" onClick="get_item_data(%id%)">%title%</a>\n\
                        </div>\n\
                        <div class="admin-news-description fs11" style="margin-top: 1px;">\n\
                            %description%\n\
                        </div>\n\
                    </div>\n\
                    <div class="left">\n\
                        <a href="javascript:void(0);" onClick="delete_news(%id%);">\n\
                            <img src="https://<?=conf::get('server')?>/ui/delete.png">\n\
                        </a>\n\
                    </div>\n\
                </div>';
        
    var html = '';
    for(i in resp) {
        html += template.replace('%title%', resp[i]['title']['ru']).replace('%description%', resp[i]['anons']['ru']).replace('%id%', resp[i]['id']).replace('%id%', resp[i]['id']);
    }
    $('#news-list').html(html);
    
}
function delete_news(id) {
    $.post('/admin/news', {submit: 1, act: 'delete', id: id}, function(resp) {get_news_list(); }, 'json');
}
</script>