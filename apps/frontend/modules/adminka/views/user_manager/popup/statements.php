<div id="statements-<?=$profile['user_id']?>" class="p5 hide" style="background: #fff; position: absolute; box-shadow: 0px 0px 5px black">
    <div class="p5 aright fs10">
            <a
                    href="javascript:void(0);"
                    onclick="
                            $('#statements-<?=$profile['user_id']?>').hide()
                    "
                    >Закрыть</a>
    </div>
    <div>
            <table cellpadding="5" cellspacing="0">
                    <? $cnt = 1; ?>
                    <? $flag = false; ?>
                    <? foreach($profile['statement_type'] as $statement){ ?>
                            <? $flag = $flag ? false : true; ?>
                            <tr style="background: <?= $flag ? '#eee' : '#fff' ?>">
                                    <td width="200px">
                                            <? if($statement['key'] != 'association_member'){ ?>
                                                    <?=$cnt++?>. Регистрация в каталоге моделей ModelsUA.org
                                            <? } else { ?>
                                                    <?=$cnt++?>. Вступление в Ассоциацию моделей Украины
                                            <? } ?>
                                    </td>
                                    <td id="statement-act-<?=$statement['key']?>-<?=$profile['user_id']?>" align="center" width="100px">
                                            <? if($statement['status'] > 0){ ?>
                                                    <span style="color: #0a0">Заявка подтверждена</span>
                                            <? } elseif($statement['status'] < 0) { ?>
                                                    <span style="color: #a00">Заявка отклонена</span>
                                            <? } else { ?>
                                                    <span style="color: #aaa">Новая</span>
                                            <? } ?>
                                    </td>
                                    <td width="200px" align="right">
                                                    <input type="button" id="statement-<?=$statement['key']?>-approve-<?=$profile['user_id']?>" value="Подтвердить" />
                                                    <input type="button" id="statement-<?=$statement['key']?>-cancel-<?=$profile['user_id']?>" value="Отклонить" />
                                    </td>
                            </tr>
                            <script type="text/javascript">
                                    approved_statements[<?=$profile['user_id']?>] = {
                                            'key': '<?=$statement['key']?>',
                                            'status': <?=$statement['status']?>
                                    };
                            </script>
                    <? } ?>
            </table>
    </div>
    <div class="pt5">
            <? if($filter[1] == 'new'){ ?>
                    <div class="acenter">
                            <input type="button" id="adminka-item-approve-<?=$item_id?>" value="На подтверждение" />
                    </div>
            <? } elseif($filter[1] == 'confirm') { ?>
                    <div class="left">
                            <input type="button" id="adminka-item-arhive-<?=$item_id?>" value="В архив" />
                            <input type="button" id="adminka-item-reserv-<?=$item_id?>" value="В резерв" />
                    </div>
                    <div class="right">
                            <input type="button" id="adminka-item-approve-<?=$item_id?>" value="Утвердить" />
                    </div>
                    <div class="clear"></div>
            <? } ?>
    </div>
</div>