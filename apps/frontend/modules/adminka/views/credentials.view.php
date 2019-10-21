
<div class="mt5 mb5 fs12 p10" style="border: 1px solid #b95383;">
	
	<?include 'admin_menu.php'?>
	<?
	$credentials = user_auth_peer::get_credentials();
	
	?>
	<div class="left" style="width: 758px">
	    <table style="width: 100%;" border="1" cellpadding="4" cellspacing="0">
		<tr>
		    <th style="border: 0">Id</th>
		    <th style="border: 0">Имя Фамилия</th>
		    <?  foreach ($credentials as $alias=>$name) {?>
		    <th style="border: 0">
			<?=$name?>
		    </th>
		    <? } ?>
		</tr>
		<?  foreach ($admin_list as $key=>$data) {
			$user_credentials = unserialize($data['credentials']);
		?>
		<tr>
		    <td class="acenter">
			<?=$data['id']?>
		    </td>
		    <td>
			<?
			if($data['ph_crop'] && $data['pid']) {
			    $c = unserialize($data['ph_crop']);
			    $src = '/imgserve?pid='.$data['pid'].'&w='.$c['w'].'&h='.$c['h'].'&x='.$c['x'].'&y='.$c['y'].'&z=crop';
			}
			else $src='/no_image.png';
			$name = profile_peer::get_name($data);
			?>
			<img src="<?=$src?>" class="left mr5" style="width: 50px;"/>
			<a href="/profile?id=<?=$data['id']?>" class="left fs14"><?=$name?></a>
		    </td>
		     <?  foreach ($credentials as $alias=>$name) {?>
			<td class="acenter">
			    <input type="checkbox" value="<?=$alias?>" id="adminka-credentials_change-<?=$data['id']?>" name="credentials[]" <?=(in_array($alias, $user_credentials)) ? ' checked' : ''?>/>
			</td>
		    <? } ?>
		    </tr>
		<? } ?>
	    </table>
	    <form method="post" id="add_admin" class="mt10">
		<input type="hidden" value="add" name="act">
		<table>
		    <tbody>
			<tr>
			    <td>
				Id користувача:
			    </td>
			    <td>
				<input type="text" rel="Введіть АйДі користувача" id="user_id" class="text">
			    </td>
			    <td>
				<input type="button" id="adminka-add-admin" value="Додати" class="button" name="add_user">
			    </td>
			</tr>
		    </tbody>
		</table>
	    </form>
	    <div class="clear"></div>
	    <div class="error fs16 bold"></div>
	</div>
	<div class="clear"></div>
</div>
<script>
    $('input[name="credentials[]"]').change(function(){
	var set = ($(this).attr('checked')) ? 1 : 0;
	$.post(
	    '/adminka/credentials',
	    {
		"submit": 1,
		"act": $(this).attr("id").split("-")[1],
		"set": set,
		"user_id": $(this).attr("id").split("-")[2],
		"value": $(this).val()
	    },
	    function(resp) {
		console.log(resp);
	    },
	    'json'
	);
    });
    
    $('input[id="adminka-add-admin"]').click(function(){
	
	$.post(
	    '/adminka/credentials',
	    {
		"submit": 1,
		"act": 'add',
		"user_id": $('#user_id').val()
	    },
	    function(resp) {
		if(resp.success) window.location=window.location;
		else {
		    $('.error').html(resp.reason);
		    $('.error').fadeIn(300,function(){ $(this).fadeOut(5000)})
		}
	    },
	    'json'
	);
    });
</script>