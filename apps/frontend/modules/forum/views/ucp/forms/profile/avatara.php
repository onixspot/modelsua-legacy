<div class="profile-avatara-box <?=(request::get('tab')=='profile' && request::get('form')=='avatara') ? '' : ' hide'?>">
    <form enctype="multipart/form-data" action="/forum/profile" method="post" id="profile-avatara-form">

	<input type="hidden" name="act" value="save_profile"/>
	<input type="hidden" name="subaction" value="avatara"/>
	
	<h2>Аватара</h2>

	<div class="panel">
		<div class="inner"><span class="corners-top"><span></span></span>


		<fieldset>

		<dl>
			<dt><label>Текущее изображение:</label><br><span>Максимальные размеры в пикселах; ширина: 80, высота: 80, размер файла: 62.50 КБ.</span></dt>
			<dd>
			    <img id="avapreview" rel="" alt="" src="/no_image.png" class="left" style="width: 100px;">
			    <a href="javascript:;" onclick="$.post('/forum/profile', {'act': 'save_profile', 'subaction' : 'delete_preview', 'imagekey' : $('#avapreview').attr('rel') }, function(resp) { $('#avapreview').attr('src','/no_image.png'); $('#FileName').hide(); }, 'json');"><img src="/ui/delete.png"/></a>
			</dd>
<!--			<dd><label for="delete"><input type="checkbox" id="delete" name="delete"> Удалить изображение</label></dd>-->
		</dl>


			<dl>
				<dt><label for="uploadfile">Загрузить с вашего компьютера:</label></dt>
				<dd>
				    <div  id="wrapper" style="margin-left: 100px;">
					<input type="file" class="inputbox autowidth" id="uploadfile" name="uploadfile"/>
				    </div>
				</dd>
			</dl>

			<dl class="hide">
				<dt><label for="uploadurl">Загрузить с URL:</label><br><span>Введите URL, по которому находится файл с изображением. Оно будет скопировано на этот сайт.</span></dt>
				<dd><input type="text" class="inputbox" value="" id="uploadurl" name="uploadurl"></dd>
			</dl>

		</fieldset>



		<span class="corners-bottom"><span></span></span></div>
	</div>

	<fieldset class="submit-buttons">
		<input type="button" class="button1" value="<?=t('Сохранить')?>" name="save-profile-ava" id="save-profile-ava">
		<div class="profile-ava-success hide"><?=t('Изменения сохранены')?></div>
		<div class="profile-ava-error acenter fs16 hide"></div>
	</fieldset>
    </form>
</div>
<script>
    window.onload = WindowOnLoad;
    var fileInput = document.getElementById('uploadfile');
    var fileName = document.createElement('div');
    fileName.style.display = 'none';
    fileName.style.background = 'url(/forum/icons/icons.png)';
    var activeButton = document.createElement('div');
    var bb = document.createElement('div');
    var bl = document.createElement('div');
    function WindowOnLoad()
    {
	var wrap = document.getElementById('wrapper');
	fileName.setAttribute('id','FileName');
	activeButton.setAttribute('id','activeBrowseButton');
	fileInput.value = '';
	fileInput.onchange = HandleChanges;
	fileInput.onmouseover = MakeActive;
	fileInput.onmouseout = UnMakeActive;
	fileInput.className = 'customFile';
	bl.className = 'blocker';
	bb.className = 'fakeButton';
	activeButton.className = 'fakeButton';
	wrap.appendChild(bb);
	wrap.appendChild(bl);

	wrap.appendChild(activeButton);

	wrap.appendChild(fileName);


    };
    function HandleChanges()
    {
	
	file = fileInput.value;
	reWin = /.*\\(.*)/;
	var fileTitle = file.replace(reWin, "$1"); //выдираем название файла
	reUnix = /.*\/(.*)/;
	fileTitle = fileTitle.replace(reUnix, "$1"); //выдираем название файла
	fileName.innerHTML = fileTitle;

	var RegExExt =/.*\.(.*)/;
	var ext = fileTitle.replace(RegExExt, "$1");//и его расширение

	var pos;
	if (ext){
	    switch (ext.toLowerCase())
	    {
		case 'doc': pos = '0'; break;
		case 'bmp': pos = '16'; break;                       
		case 'jpg': pos = '32'; break;
		case 'jpeg': pos = '32'; break;
		case 'png': pos = '48'; break;
		case 'gif': pos = '64'; break;
		case 'psd': pos = '80'; break;
		case 'mp3': pos = '96'; break;
		case 'wav': pos = '96'; break;
		case 'ogg': pos = '96'; break;
		case 'avi': pos = '112'; break;
		case 'wmv': pos = '112'; break;
		case 'flv': pos = '112'; break;
		case 'pdf': pos = '128'; break;
		case 'exe': pos = '144'; break;
		case 'txt': pos = '160'; break;
		default: pos = '176'; break;
	    };
	    fileName.style.display = 'block';
	    fileName.style.background = 'url(/forum/icons/icons.png) no-repeat 0 -'+pos+'px';
	};
	ajax_file_upload();
	fileInput = document.getElementById('uploadfile');
	activeButton = document.createElement('div');
	WindowOnLoad();
	

    };
    function MakeActive()
    {
	activeButton.style.display = 'block';
    };
    function UnMakeActive()
    {
	activeButton.style.display = 'none';
    };
    
    var ajax_file_upload = function () {
	$.ajaxFileUpload({
	    url: '/forum/profile&act=save_profile&subaction=avatara',
	    secureuri:false,
	    fileElementId:'uploadfile',
	    dataType: 'json',
	    success: function (data, status)
	    {
		    if(data.success) 
			$('img[id="avapreview"]').attr({
			    'src' : '/uploads/temp/'+data.file,
			    'rel' : data.file
			});
	    },
	    error: function (data, status, e)
	    {
		    console.log(data);
		    console.log(status);
		    console.log(e);

	    }
	});
    }
    $(function() {
	
	var profile_ava_form = new Form('profile-avatara-form');
	$('input[id="save-profile-ava"]').click(function(){

	    if($('#uploadfile')[0].files.length) 
		$.ajaxFileUpload({
		    url: '/forum/profile&act=save_profile&subaction=avatara',
		    secureuri:false,
		    fileElementId:'uploadfile',
		    dataType: 'json',
		    success: function (data, status)
		    {
			    if(data.success) {
				$('img[id="avapreview"]').attr('src','/uploads/temp/'+data);
			    }
				
		    },
		    error: function (data, status, e)
		    {
			    console.log(data);
			    console.log(status);
			    console.log(e);

		    }

		});
	    
	    profile_ava_form.onSuccess = function(resp) {
		if(resp.success)
		    $('.profile-ava-success')
			.fadeIn(300, function() { 
			    $(this).fadeOut(3000); 
			});
		else     
		    $('.profile-ava-error')
			.html(resp.reason)
			.fadeIn(300, function() { 
			    $(this).fadeOut(3000); 
			});
	    }
	    profile_ava_form.send();
	});
    });
</script>