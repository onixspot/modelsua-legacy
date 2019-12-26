<div id="adminka" class="adminka">
	
	<div class="head">
		<div class="title">
			Админ панель
		</div>
		<div class="closeButton">
			<a href="javascript:void(0);">Закрыть</a>
		</div>
		<div style="clear: both"></div>
	</div>
	
	<div class="body">
		<div id="mainTabBar">
			<ul>
				<li id="user_managed">Менеджер участников</li>
				<li id="options">Настройки</li>
				<li id="news" class="selected">Новости</li>
			</ul>
		</div>
		
		<div class="frames">
			
			<div id="user_managed" class="frame">
				
				<div id="um-create_user-div" class="hide" style="position: absolute; width: 300px; margin-top: -5; margin-left: 400px; box-shadow: 0px 1px 2px black; background: #fff;">
					<div class="p5">
						<form id="um-create_user" action="/admin/user_manager?act=create_user" method="post">
							<div class="mb5">
								<div class="left mt5 mr5 aright" style="width: 100px;">Имя:</div>
								<div class="left">
									<input type="text" id="first_name" />
								</div>
								<div class="clear"></div>
							</div>
							<div class="mb5">
								<div class="left mt5 mr5 aright" style="width: 100px;">Фамилия:</div>
								<div class="left">
									<input type="text" id="last_name" />
								</div>
								<div class="clear"></div>
							</div>
							<div class="mb5">
								<div class="left mt5 mr5 aright" style="width: 100px;">Email:</div>
								<div class="left">
									<input type="text" id="email" />
								</div>
								<div class="clear"></div>
							</div>
							<div class="mb5">
								<div class="left mt5 mr5" style="width: 100px;">&nbsp;</div>
								<div class="left">
									<input type="checkbox" id="hidden" checked />
									<label for="hidden">Спрятанный</label>
								</div>
								<div class="clear"></div>
							</div>
							<div class="mb5">
								<div class="left mt5 mr5" style="width: 100px;">&nbsp;</div>
								<div class="left">
									<input type="button" id="submit" value="Сохранить" />
								</div>
								<div class="left ml5 mt5">
									<a href="javascript:void(0);" onclick="$('#um-create_user-div').hide();">Отмена</a>
								</div>
								<div class="clear"></div>
							</div>
						</form>
					</div>
				</div>
				
				<div class="mt5">
					
					<div class="mb5">
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="$(this).parent().parent().find('a').css('font-weight', 'normal'); $(this).css('font-weight', 'bold'); $('div[id*=um-menu]').hide(); $('#um-create_user-div').show();">Создать участника</a>
						</div>
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="$(this).parent().parent().find('a').css('font-weight', 'normal'); $(this).css('font-weight', 'bold'); $('div[id*=um-menu]').hide(); $('#um-menu-all').show(); upTable('all');">Все</a>
						</div>
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="$(this).parent().parent().find('a').css('font-weight', 'normal'); $(this).css('font-weight', 'bold'); $('div[id*=um-menu]').hide(); $('#um-menu-admin').show(); upTable('admin');">Созданные администратором</a>
						</div>
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="$(this).parent().parent().find('a').css('font-weight', 'normal'); $(this).css('font-weight', 'bold'); $('div[id*=um-menu]').hide(); $('#um-menu-self').show(); upTable('self');">Самозарегистрированные</a>
						</div>
						<div class="clear"></div>
					</div>
					<div id="um-menu-all" class="hide mb5 p5" style="box-shadow: inset 0px 0px 2px black; background: #ddd; border-radius: 2px;">
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="upTable('all-active');">Активные</a>
						</div>
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="upTable('all-noactive');">Неактивные</a>
						</div>
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="upTable('all-nomail');">Без e-mail</a>
						</div>
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="upTable('all-hidden');">Спрятанные</a>
						</div>
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="upTable('all-invited');">Все приглашенные</a>
						</div>
						<div class="clear"></div>
					</div>
					<div id="um-menu-admin" class="hide mb5 p5" style="box-shadow: inset 0px 0px 2px black; background: #ddd; border-radius: 2px;">
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="upTable('admin-active');">Активные</a>
						</div>
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="upTable('admin-noactive');">Неактивные</a>
						</div>
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="upTable('admin-nomail');">Без e-mail</a>
						</div>
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="upTable('admin-hidden');">Спрятанные</a>
						</div>
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="upTable('admin-invited');">Приглашенные</a>
						</div>
						<div class="clear"></div>
					</div>
					<div id="um-menu-self" class="hide mb5 p5" style="box-shadow: inset 0px 0px 2px black; background: #ddd; border-radius: 2px;">
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="upTable('self-new');">Новые</a>
						</div>
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="upTable('self-confirm');">На подтверждение</a>
						</div>
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="upTable('self-active');">Активные</a>
						</div>
						<div class="left mr20">
							<a href="javascript:void(0);" onclick="upTable('self-noactive');">Неактивные</a>
						</div>
						<div class="clear"></div>
					</div>
					
					<div style="box-shadow: inset 0px 0px 1px black; padding: 1px;">
						<div style="height: 485px; overflow: auto;">
							<table id="um-userList" cellspacing="2" cellpadding="2" style="width: 100%">
							</table>
						</div>
					</div>
				</div>
			</div>
			<?include 'frames/news.php'?>
			<div id="options" class="frame">
				<?=serialize(array());?>
			</div>
			
		</div>
		
	</div>
	
</div>

<script type="text/javascript">
	
	var adminka;
	var upTable;
	
	$(document).ready(function(){
		
		adminka = new Adminka("adminka");
		
		var uiTabBar = adminka.uiTabBar("mainTabBar");
		uiTabBar.onClick = function(){
			$("div.frames").find("div.frame").hide();
			$("div.frames").find("div[id='"+this.selected+"']").show();
		};
		uiTabBar.init();
		
		var userList = adminka.uiTable("um-userList");
		userList.setColumns(
			[
				{name: "ID", align: "center"},
				{name: "Фамилия Имя", align: "left"},
				{name: "Email", align: "left"},
				{name: "Активированные", align: "center"},
				{name: "Непубличные", align: "center"},
				{name: "Action", align: "center"}
			]
		);
		
		var form = new Form("um-create_user");
		form.onSuccess = function(data){
			if(data.success == true){
				$("#um-create_user-div").hide();
				upTable();
			}
		}
		
		$("#um-create_user #submit").click(function(){
			form.send();
		});
		
		upTable = function(filter)
		{
			data = {
				"act": "get_list"
			}
			
			data["filter"] = filter;
			
			userList.removeAllRows();
			
			$.post(
				"/admin/user_manager",
				data,
				function(data){
					var data = data["data"];
					for(var i = 0; i < data.length; i++)
					{
						userList.addRow(
							[
								data[i].user_id,
								$("<a />")
									.attr("href", "/profile?id="+data[i].user_id)
									.html(data[i].last_name+" "+data[i].first_name), 
								data[i].email, 
								$("<input />").attr({
									"type": "checkbox",
									"checked": data[i].active == true ? "checked" : false
								}),
								$("<input />").attr({
									"type": "checkbox",
									"checked": data[i].hidden == true ? "checked" : false
								}),
								$("<div />")
									.append(
										$("<a />")
											.attr("href", "/profile/edit?id="+data[i].user_id)
											.html("Редактировать")
									)
									.append($("<br />"))
									.append(
										$("<a />")
											.attr("href", "javascript:void(0);")
											.attr("rel", data[i].user_id)
											.click(function(){
												if(confirm("Точно удалить?")){
													$.post("/admin/user_manager",
														{
															"act": "remove_user",
															"id": $(this).attr("rel")
														},
														function(data){
														},
														"json"
													);
													$(this).parent().parent().parent().remove();
												}
											})
											.html("Удалить")
								)
							]
						);
					}
				},
				"json"
			);
		}
		
		upTable();
		
		
	});
</script>