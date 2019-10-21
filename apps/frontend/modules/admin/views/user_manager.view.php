<div class="fs12 mt20 mb20" style="width: 700px;">
	
	<div class="p5 mb5" style="border: 1px solid #ccc; border-radius: 2px;">
		Менеджер
	</div>
	
	<div>
		
		<div class="left" style="width: 100px;">
			<ul>
				<li><a href="/admin/user_manager">Список участников</a></li>
				<li><a href="/admin/user_manager?frame=create_user">Создать участника</a></li>
			</ul>
		</div>
		<div class="left" style="width: 598px; border: 1px solid #ccc;">
			
			<? if($frame == "list"){ ?>
				<div class="p5">
					<table>
						<tr>
							<th>#</th>
							<th>Фамилия Имя Отчество</th>
							<th>Дата рождения</th>
						</tr>
					</table>
				</div>
			<? } ?>
			
			<? if($frame == "create_user"){ ?>
				<div class="p10 mb10 bold" style="background: #5C7FC7; color: #fff;">
					Создание участника
				</div>
				<form id="create_user" action="/admin/user_manager" method="post">
					<div class="mb5">
						<div class="left mt5 mr5 aright" style="width: 100px;">Фамилия:</div>
						<div class="left">
							<input type="text" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="mb5">
						<div class="left mt5 mr5 aright" style="width: 100px;">Имя:</div>
						<div class="left">
							<input type="text" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="mb5">
						<div class="left mt5 mr5 aright" style="width: 100px;">Пол:</div>
						<div class="left">
							<input type="radio" id="male" name="sex[]" />
							<label for="male">Мужской</label>
							<input type="radio" id="female" name="sex[]" />
							<label for="female">Женский</label>
						</div>
						<div class="clear"></div>
					</div>
					<div class="mb5">
						<div class="left mt5 mr5 aright" style="width: 100px;">Email:</div>
						<div class="left">
							<input type="text" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="mb5">
						<div class="left mt5 mr5 aright" style="width: 100px;">Пароль:</div>
						<div class="left">
							<input type="password" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="mb5">
						<div class="left mt5 mr5 aright" style="width: 100px;">&nbsp;</div>
						<div class="left">
							<input type="checkbox" id="hidden" name="hidden" />
							<label for="hidden">Скрытый</label>
						</div>
						<div class="clear"></div>
					</div>
					<div class="mb5">
						<div class="left mt5 mr5 aright" style="width: 100px;">&nbsp;</div>
						<div class="left">
							<input type="button" class="mr5" id="submit" value="Создать" />
							<a href="/admin/user_manager">Отмена</a>
						</div>
						<div class="clear"></div>
					</div>
				</form>
				<script type="text/javascript">
					
					$(document).ready(function(){
						
						var form = new Form("create_user");
						form.onSuccess = function(response)
						{
							console.log(response);
						}
						$("#submit").click(function(){
							form.data["submit"] = true;
							form.send();
						});
						
					});
					
				</script>
			<? } ?>
		</div>
		<div class="clear"></div>
		
	</div>
	
</div>