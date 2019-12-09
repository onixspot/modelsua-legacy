<div class="left pt5 mb5 fs12">
    <?include 'admin_menu.php'?>
    <div class="clear"></div>
    <div class="add_content">
        <input type="button" class="button mt10 mb10" style="width: 92%" value="Добавить страницу" onClick="window.location='/adminka/pages'">
    </div>
    <div class="admin_left left pages_tree mr25" id="tree" style="width: 360px;"></div>
</div>

<div class="left" style="width: 615px;">
    <form id="pages_form">
        <input type="hidden" name="id" value="">
        <input type="hidden" name="lang" value="ru">
        <table>
            <tr>
                <td>
                    <?=t('Язык')?>
                </td>
                <td>
                    <input type="button" class="" name="language" value="en">
                    <input type="button" class="ml5" name="language" value="ru">
                </td>

            </tr>
            <tr>
                <td>
                    <?=t('Родитель')?>
                </td>
                <td>
                    <?$all_pages[0] = t('Нет')?>
                    <?=tag_helper::select('parent', $all_pages,array('value'=>$parent))?>
                </td>
            </tr>
            <tr>
                <td>
                    <?=t('Адрес')?>
                </td>
                <td>
                    <input type="text" name="url" value="" rel="<?=t('Введите адрес страници')?>">
                </td>
            </tr>
            <tr>
                <td>
                    <?=t('Название')?>
                </td>
                <td>
                    <input type="text" name="title" value="" rel="<?=t('Введите название')?>">
                </td>
            </tr>
            <tr>
                <td>
                    <?=t('Включить страницу')?>
                </td>
                <td>
                    <input type="radio" name="on" value="1">Да
                    <input type="radio" name="on" value="0">Нет
                </td>
            </tr>
            <tr>
                <td>
                    <?=t('Отображать в "детях"')?>
                </td>
                <td>
                    <input type="radio" name="show" value="1">Да
                    <input type="radio" name="show" value="0">Нет
                </td>
            </tr>
            <tr>
                <td>
                    <?=t('Возможность расшаривать в соц.сетях')?>
                </td>
                <td>
                    <input type="radio" name="share" value="1">Да
                    <input type="radio" name="share" value="0">Нет
                </td>
            </tr>
            <tr>
                <td>
                    <?=t('Возможность печати')?>
                </td>
                <td>
                    <input type="radio" name="print" value="1">Да
                    <input type="radio" name="print" value="0">Нет
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="text" name="content" id="content" value="" rel="<?=t('Введите текст')?>">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="button" name="save" value="<?=t('Сохранить')?>">
                </td>
            </tr>
        </table>
    </form>
    <div class="clear"></div>
    <div class="success hide"><?=t('Изменения сохранены')?></div>
    <div class="error hide"></div>
</div>
<div class="clear"></div>
<div id="finder"></div>
<script type="text/javascript" charset="utf-8">
window._data = {};
  
$().ready(function() {
       
        var opts = {
                cssClass : 'el-rte',
                lang     : 'ru',
                height   : 450,
                toolbar  : 'complete',
                cssfiles : ['https://css.<?=conf::get('server')?>/elrte.css','https://css.<?=conf::get('server')?>/elfinder.css'],
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
        $('#content').elrte(opts);
        $('input[type="checkbox"]').change(function(){
            if($(this).attr('checked')) $(this).val(1);
            else $(this).val(0);
        });
        build_tree();
        get_data();
});
$('input[name="language"]').click(function(){change_data($(this).val());});
function get_data() {
    $.post('/adminka/pages',{link: '<?=request::get('link')?>','get_data': 1},
            function(resp) {
                window._data = resp;
                save_data('ru');

            },
            'json'
    );
}
function build_tree() { $.post('/adminka/pages', {'tree': 1}, function(resp) {$('#tree').html(resp)}); }
function save_data(lang) {
    $('input[name="id"]').val(window._data['id']);
    $('input[name="lang"]').val(lang);
    $('input[name="url"]').val(window._data['link']);
    $('input[name="title"]').val(window._data['title'][lang]);
    
    $('input:radio').each(function(){
        if($(this).val()==window._data[$(this).attr('name')])
            $(this).attr('checked',1);
    });
    
    
    if(window._data['content'][lang]) $('#content').elrte('val',window._data['content'][lang]);
    else $('#content').elrte('val',' ');
}
function change_data(lang) {
    _prev = $('input[name="lang"]').val();
    window._data['link'] = $('input[name="url"]').val();
    window._data['title'][_prev] = $('input[name="title"]').val();
    window._data['content'][_prev] = $('#content').elrte('val');
    window._data['on'] = $('input[name="on"]:checked').val();
    window._data['show'] = $('input[name="show"]:checked').val();
    window._data['print'] = $('input[name="print"]:checked').val();
    window._data['share'] = $('input[name="share"]:checked').val();
    save_data(lang);
}
$('input[name="save"]').click(function(){
    change_data($('input[name="lang"]').val());
    $.ajax({
        type: 'post',
        url: '/adminka/pages',
        data: {
            'id': window._data.id,
            'act': 'save',
            'submit': 1,
            'content' : window._data.content,
            'title' : window._data.title,
            'link': window._data.link,
            'show': $('input[name="show"]:checked').val(),
            'on': $('input[name="on"]:checked').val(),
            'print': $('input[name="print"]:checked').val(),
            'share': $('input[name="share"]:checked').val(),
            'parent': $('select[name="parent"] option:selected').val()
        },
        success: function(resp) {
            resp = eval("("+resp+")");
            if(resp.success==1) {
                if(resp.id) {
                    $('input[name="id"]').val(resp.id);
                    window._data['id'] = resp.id;
                }
                $('.success').fadeIn(300,function() {$(this).fadeOut(1000,function(){window.location="/page?link="+window._data.link});});
            }
            if(resp.success==0) {
                $('.error').html(resp.reason);
                $('.error').fadeIn(300,function() { $(this).fadeOut(3000);});
            }
        }
    });
});
function change_item(id, action, params) {
    switch(action) {
        case 'move':
            for(i in params) 
                if(i=='direction')
                    direction = params[i];
            if(!direction) direction = 0;
            data = {id: id, act: action, direct: direction, submit: 1}
            break;
        case 'delete':
            data = {id: id, act: action, submit: 1}
            break;
        case 'change_property':
            for(i in params) 
                if(i=='property')
                    property = params[i];
                else 
                    if(i=='value')
                        value=params[i];
            data = {id: id, act: action, property: property, value: value,  submit: 1}
            break;
    }
    $.post('/adminka/pages',data,function() { build_tree(); });
}


</script>