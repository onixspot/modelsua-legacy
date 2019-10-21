<tr>
    <th>№</th>
    <th>ID</th>
    <th>Фамилия Имя</th>
    <?if(!in_array($filter[1],array('applications','refuse'))) {?>
    <th>
        Статус:
        <select id="adminka-status_filter" name="showtimes" style="width: 100px;">
        <?
                $statuses = profile_peer::get_types_list();
                echo '<optgroup label="Статус не назначен" value="0">';
                echo '<option value="'.build_url(array('status')).'" selected>&mdash;</option></optiongroup>';
                foreach ($statuses as $key => $value) {
                echo '<optgroup label="'.$value['type'].'" value="'.$key.'">';
                //echo '<option value="'.$key.'" '.((profile_peer::get_type_by_user($profile['user_id'])==$key && !profile_peer::get_status_by_user ($profile['user_id'])) ? ' selected' : '').'><i>'.($value['type']).(!in_array($key,array(5,6)) ? ('&nbsp;(no status)') : '').'</i></option>';
                    if(is_array($value['status']))
                        foreach ($value['status'] as $k => $v) 
                                echo '<option value="'.(build_url(array('status')).'&status='.$k).'" '.(request::get_int('status')==$k ? ' selected' : '').'>'.($v ? $v : $value['type']).'</option>';
                    echo '</optgroup>';

                }
        ?>
            </select>
    </th>
    <? } ?>
    <th>
        Активный:
        <select id="adminka-active-filter">
            <option value="<?=build_url(array('active'))?>" <?=(!request::get('active')) ? ' selected' : ''?>>&mdash;</option>
            <option value="<?=build_url(array('active')).'&active=true'?>" <?=(request::get('active')=='true') ? ' selected' : ''?>>Да</option>
            <option value="<?=build_url(array('active')).'&active=false'?>" <?=(request::get('active')=='false') ? ' selected' : ''?>>Нет</option>
        </select>
    </th>

    <th>
        Скрытый:
        <select id="adminka-public-filter">
            <option value="<?=build_url(array('hidden'))?>" <?=(!request::get('hidden')) ? ' selected' : ''?>>&mdash;</option>
            <option value="<?=build_url(array('hidden')).'&hidden=true'?>" <?=(request::get('hidden')=='true') ? ' selected' : ''?>>Да</option>
            <option value="<?=build_url(array('hidden')).'&hidden=false'?>"<?=(request::get('hidden')=='false') ? ' selected' : ''?>>Нет</option>
        </select></th>
    <? if($show) { ?>
    <th>Номер п/п</th>
    <? } ?>
    <th>Действия</th>
</tr>