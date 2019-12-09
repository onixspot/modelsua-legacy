<div class="admin_left left pages_tree mr25" id="tree" style="width: 425px;">
    
</div>
<div class="left" style="width: 550px;">
    <form id="pages_form">
        <input type="hidden" name="id" value="">
        <input type="hidden" name="lang" value="ua">
        <table>
            <tr>
                <td>
                    <?=t('Язык')?>
                </td>
                <td>
                    <input type="button" class="" name="language" value="ua">
                    <input type="button" class="ml5" name="language" value="ru">
                    <input type="button" class="ml5" name="preview"onClick="window.location='/page?link=<?=request::get('link')?>'" value="Preview">
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
<script type="text/javascript" charset="utf-8">
window._data = {};
  
$().ready(function() {
       
        var opts = {
                cssClass : 'el-rte',
                lang     : 'ru',
                height   : 450,
                toolbar  : 'complete',
                cssfiles : ['https://css.<?=conf::get('server')?>/elrte.css']
        }
        $('#content').elrte(opts);
        build_tree();
        get_data();
    
});
$('input[name="language"]').click(function(){change_data($(this).val());});
function get_data() {
    $.post('/admin/pages',{link: '<?=request::get('link')?>','get_data': 1},
            function(resp) {
                window._data = resp;
                save_data('ua');

            },
            'json'
    );
}
function build_tree() { $.post('/admin/pages', {'tree': 1}, function(resp) {$('#tree').html(resp)}); }
function save_data(lang) {
    $('input[name="id"]').val(window._data['id']);
    $('input[name="lang"]').val(lang);
    $('input[name="url"]').val(window._data['link']);
    $('input[name="title"]').val(window._data['title'][lang]);
    if(window._data['content'][lang]) $('#content').elrte('val',window._data['content'][lang]);
    else $('#content').elrte('val',' ');
}
function change_data(lang) {
    _prev = $('input[name="lang"]').val();
    window._data['link'] = $('input[name="url"]').val();
    window._data['title'][_prev] = $('input[name="title"]').val();
    window._data['content'][_prev] = $('#content').elrte('val');
    save_data(lang);
}
$('input[name="save"]').click(function(){
    change_data($('input[name="lang"]').val());
    $.ajax({
        type: 'post',
        url: '/admin/pages',
        data: {
            id: window._data.id,
            act: 'save',
            'submit': 1,
            'content' : window._data.content,
            'title' : window._data.title,
            'link': window._data.link,
            'parent': $('select[name="parent"] option:selected').val()
        },
        success: function(resp) {
            resp = eval("("+resp+")");
            if(resp.success==1) {
                if(resp.id) {
                    $('input[name="id"]').val(resp.id);
                    window._data['id'] = resp.id;
                }
                $('.success').fadeIn(300,function() {$(this).fadeOut(1000,function(){build_tree();});});
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
    console.log(data);
    $.post('/admin/pages',data,function() { build_tree(); });
    
    
}

</script>