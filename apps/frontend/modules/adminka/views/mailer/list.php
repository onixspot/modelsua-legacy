<style>
    table.maillist_table  td {
	text-align: center;
	padding: 5px;
    }
    table.maillist_table  th {
	background: #ddd;
	height: 30px;
	padding: 0 5px;
    }
</style>
<table style="width: 100%;" class="mt10 maillist_table">
    <tr>
	<th>
	    Тема
	</th>
	<th>
	    Фильтр
	</th>
	<th>
	    Получателей 
	</th>
	<th>
	    Отправлено
	</th>
	<th>
	    Начало
	</th>
	<th>
	    Конец
	</th>
    </tr>
    <?if($list) {?>
	<?foreach($list as $k=>$id) {
	    $mailing = mailing_peer::instance()->get_item($id);
	    $css = !(($k+1)%2) ? 'background: #f0f0f0;' : 'background: #ffffff;'
	    ?>
    <tr style="<?=$css?>">
	<td>
	    <?=  stripslashes($mailing['subject'])?>
	</td>
	<td style="width: 200px; text-align: left;">
	    <?$filters = mailing_peer::parse_filters($mailing['filters']);?>
	    <?
		    foreach ($filters as $key => $filter) { 
			echo "<span class='fs11 bold'>".$filter['type']."</span>";
			if(!empty($filter['values'])) {
			    echo '<br/><i class="fs10">'.implode(', ',$filter['values'])."</i><br/>";
			}
		    }
	    ?>
	</td>
	<td>
	    <?=$mailing['receivers']?>
	</td>
	<td>
	    <?=$mailing['sended']?>
	</td>
	<td>
	    <?=($mailing['start']) ? date('d.m.Y H:i:s',$mailing['start']) : '?'?>
	</td>
	<td>
	    <?=($mailing['end']) ? date('d.m.Y H:i:s',$mailing['end']) : '?'?>
	</td>
    </tr>
	<? } ?>
    <? } else { ?>
    <tr>
	<td colspan="6">
	    <div style="color: #838999; border: 1px solid #ccc;" class="message_box acenter p20 mb20">
		Здесь пока пусто...
	    </div>
	</td>
    </tr>
    <? } ?>
</table>