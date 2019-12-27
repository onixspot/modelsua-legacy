<?php
/**
 * @var array $profile
 * @var int   $user_id
 */
?>
<div id="profile-edit-frame-photo">

    <div class="left mr10" style="border: 1px solid black;">
        <img id="photo_avatar" src="<? if ($profile['pid']) { ?>/imgserve?pid=<?= $profile['pid'] ?><? } else { ?>/no_image.png<? } ?>" width="200"/>
    </div>

    <div class="left">
        <div class="mb10"><?= t('Загрузите свою фотографию') ?></div>
        <div>

            <input type="file" id="photo" hidden="hidden">
            <input type="button" id="upload" value="<?= t('Загрузить ...') ?>">

            <script type="text/javascript">
                (function () {
                    const inputFile = document.querySelector('input[type="file"]#photo');
                    const uploadBtn = document.querySelector('input[type="button"]#upload');

                    function uploadFile(file) {
                        const request = new XMLHttpRequest();
                        const formData = new FormData();

                        request.open('POST', '/imgserve', true);
                        request.upload.onprogress = function (event) {
                            console.log(`Отправлено ${event.loaded} из ${event.total} байт`);
                        };
                        request.onreadystatechange = function () {
                            if (request.readyState === 4 && request.status === 200) {
                                const formData = new FormData();
                                formData.set('uid', '<?=$user_id?>');
                                formData.set('pid', request.responseText);

                                fetch('/profile/edit?group=photo', {method: 'POST', body: formData})
                                    .then(response => response.json())
                                    .then(json => {
                                        document.querySelector('img#photo_avatar').setAttribute('src', `/imgserve?pid=${json.pid}`);
                                        document.querySelector('div#photo_avatar_preview > img').setAttribute('src', `/imgserve?pid=${json.pid}`);
                                    })
                                    .catch(error => console.error('error:', error));
                            }
                        };

                        formData.append('act', 'upload');
                        formData.append('key', 'image');
                        formData.append('uid', '<?=$user_id?>');
                        formData.append('image', file);

                        request.send(formData);
                    }

                    function handleChange() {
                        for (const file of this.files) {
                            uploadFile(file);
                        }
                    }

                    function handleClick() {
                        inputFile.click();
                    }

                    uploadBtn.addEventListener('click', handleClick, false);
                    inputFile.addEventListener('change', handleChange, false);
                })();
            </script>

        </div>
        <div id="photo_crop">
            <div class="mt10" style="border: 1px solid black; width: 100px; height: 100px;">
                <div id="photo_avatar_preview" style="width: 100px; height: 100px; overflow: hidden;">
                    <img src="<? if ($profile['pid']) { ?>/imgserve?pid=<?= $profile['pid'] ?><? } else { ?>/no_image.png<? } ?>" width="100px"
                         height="100px"/>
                </div>
            </div>

            <div class="mt10">
                <div class="left">
                    <input type="button" value="<?= t('Сохранить') ?>" id="profile-photo-save"/>
                </div>
                <div id="msg-success-photo" class="left pt5 ml10 acenter hide" style="color: #090">
                    <?= t('Данные сохранены успешно') ?>
                </div>
            </div>
        </div>
    </div>

    <div class="clear"></div>

</div>

