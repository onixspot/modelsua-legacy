<?php
//$step=20;

    $dir=library_files_dirs_peer::instance()->get_item($dir_id);
      if ($dir_id==0) $dir['title']=t('Разное');
      $dircounter++; // счетчик "двигателя" по позициям ?>
            <? /*  <div id="s_<?=$dir_id?>" class="pointer folder left"> </div> */ ?>
            
            <div id="<?=$dir_id?>" class="pointer folder_title left mt5 cbrown bold " style="width:<?=(708-$step)?>px; margin-left: <?=$step?>px; <?=(!$step) ? 'background-color:#000000;' : ''?>  line-height: 24px;">
                <div class="left ml10 dir_title" style="<?=($step) ? 'color:#000000;' : 'color: #FFD8EA;'?>">
                <?=stripslashes(htmlspecialchars($dir['title']))?></div>
                <div style="margin-top: 1px;" class="right aright mr5">
                    <span style="line-height: 24px; color: #FFD8EA;"><?=count($files[$dir_id])?></span>
                    
<? if (session::has_credential('admin') and $dir_id!=0)   { ?>
                <? if ($dircounter!=1) { ?>
                        <img style="width: 16px; height: 16px; margin-top: 3px;" src="/ui/up.png" onclick="window.location='/library/up_dir?dir_id=<?=$dir_id?>';">
                <? }  if($dir_id!=$last_parent_dir) { ?>
                    
                        <img style="width: 16px; height: 16px; margin-top: 3px;" src="/ui/down.png" onclick="window.location='/library/down_dir?dir_id=<?=$dir_id?>';">
                <? } ?>
                        <img style="width: 16px; height: 16px; margin-top: 3px;" src="/ui/edit.png" onclick="window.location='/library/file_edit?dir_id=<?=$dir_id?>';">
                        <img style="width: 16px; height: 16px; margin-top: 3px;" src="/ui/delete.png" onclick="if(confirm('Вы уверены?')) window.location='/library/filedir_delete?id=<?=$dir_id?>';">
                <? } ?>
                    
                </div>
            </div>
            <div class="clear"></div>
            
            <div id="files_<?=$dir_id?>" class="<?=($dir_id==request::get_int('dir_id',null) and $dir_id!=0) ? '' : 'hidden'?>">
            
            <? if ( $files[$dir_id]) {
                $counter=0;  
                ?>
            <div class="left" style="margin-left:<?=(20+$step)?>px;width:<?=(688-$step)?>px;">
                
            <? foreach ( $files[$dir_id] as $id ) {
                $counter++;
                $file=library_files_peer::instance()->get_item($id);
                if (isset($file['files']))
                $arr=unserialize($file['files']);
                ?>
                <div class="mt5" style="border-bottom: 1px solid #f7f7f7;">
                    <div class="left">
                        <div class="ml5 file_link" >
                            <? if(isset($file['files'])){ ?>
                            <a target="_blank" style="color: black;" href="<?=conf::get('file_server').'/'.$file['id'].'/'.$arr[0]['salt']."/".$arr[0]['name']?>"><?=stripslashes(htmlspecialchars($file['title']))?></a>
                            <? }else{ ?>
                            <?='<a href="'.$file['url'].'">'.stripslashes(htmlspecialchars($file['title'])).'</a>';?>
                            <? } ?>
                        </div>
                        <div class="left ml5 fs12 cgray"><?=$file['author']?></div>
                    </div>
                    <? if (isset($file['files'])) { ?>
                        <? foreach ($arr as $f) { ?>
                            <? $ext=end(explode('.', $f['name'])) ?>
                            <div class="left ml5 <?//=$file['author'] ? 'mt15' : ''?>">
                                <a href="<?=conf::get('file_server').$file['id'].'/'.$f['salt']."/".$f['name']?>" class="dib ico<?=strtolower(str_replace(array('-','.png'),'',library_files_peer::instance()->get_icon($ext)))?>"></a>
                            </div>
                        <? }} else { ?>
                            <div class="left ml5 icoie <?//=$file['author'] ? 'mt15' : ''?>"></div>
                        <? } ?>
                        <? if ($file['lang']=='ua' or $file['lang']=='en') { ?>
                            <div class="left ml5 ico<?=$file['lang']?>" style="margin-top:  1<?//=$file['author'] ? '17' : '2'?>px;"></div>
                        <? } ?>
                        <div class="right aright mr5" style="border-bottom: 1px solid #d7d7d7; color:#565656;"> <?=$file['size'] ? $file['size'] : '' //$file['exts'] ? library_files_peer::formatBytes(filesize($file['url'])) : ''?>
                                <img id="<?=$id?>" class="info pointer <?=!$file['describe'] ? ' hide' : '' ?>" title="Інформація" src="/ui/on.png">
                            <? if (session::has_credential('admin'))   { ?>
                            <? if ($counter!=1) { ?>
                                <img style="width: 16px; height: 16px; margin-top: 3px;" class="pointer" src="/ui/up.png" onclick="window.location='/library/up_file?id=<?=$id?>';">
                            <? }  if($counter!=count($files[$dir_id])) { ?>
                                <img style="width: 16px; height: 16px; margin-top: 3px;" class="pointer" src="/ui/down.png" onclick="window.location='/library/down_file?id=<?=$id?>';">

                            <? } ?>
                                <img style="width: 16px; height: 16px; margin-top: 3px;" class="pointer" src="/ui/edit.png" onclick="window.location='/library/file_edit?id=<?=$id?>';">
                                <img style="width: 16px; height: 16px; margin-top: 3px;" class="pointer" src="/ui/delete.png" onclick="if(confirm('<?=t("Вы уверены")?>?')) window.location='/library/file_delete?id=<?=$id?>';">

                            <? } ?>
                        </div>
                <div class="clear"></div>
                <div id="file_describe_<?=$id?>" class="ml10 fs11 hide cgray"><?=stripslashes(htmlspecialchars($file['describe']))?></div>
                </div>
                <div class="clear"></div>
                <? } ?>
           </div>
            <? }
            if (is_array($array) && count ($array)>0) 
            {
                
                $step+=20;
                foreach ( $array as $dir_id=>$array ) 
                    {
                        include 'listing.php';
                    }
                $step-=20;
            }
            //else 
            ?>
            <? if ((is_array($array) && count ($array)>0) and !is_array($files[$dir_id])) { ?>
                <div class="acenter"><?//=t('Папка пуста')?></div>
                <div class="clear"></div>
            <? } ?>
            <div class="clear"></div>
            </div>
            <style>
                .file_link a {
                    color: black;
                }
                .file_link a:hover {
                    font-weight: bold;
                }
                .dir_title:hover {
                    text-decoration: underline !important;
                }
            </style>
