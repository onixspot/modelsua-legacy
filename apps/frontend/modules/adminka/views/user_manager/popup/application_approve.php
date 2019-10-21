<div id="application-<?=$profile['user_id']?>" class="p5 hide" style="margin-left: -450px; background: #fff; position: absolute; box-shadow: 0px 0px 5px black">
    <table>
        <tr>
            <td>
                <select id="application-status-<?=$profile['user_id']?>" name="showtimes" >
                    <option value="22" selected>Модель, Член Ассоциации</option>
                    <option value="24">Модель, Кандидат в Члены Ассоциации</option>
                </select>
            </td>
        </tr>
        <tr> 
            <td colspan="1" class="acenter pt5">
                <input type="button" class="mr10 ml10"  id="adminka-approve-status-<?=$profile['user_id']?>" value="Утвердить"/>
                <input type="button" value="Отменить" onclick="$('#application-<?=$profile['user_id']?>').hide()"/>
            </td>
        </tr>
    </table>
</div>