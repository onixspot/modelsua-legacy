<div class="left pt5 mb5 fs12">
	<?include 'admin_menu.php'?>
</div>

<div class="left mt5 fs12" style="width: 778px;">
	
	<div>
		<div class="left mr10">
			<a href="javascript:void(0);">Создать список</a>
		</div>
		<div class="clear"></div>
	</div>
	
	<div>
		<table>
			<tr>
				<th>ID</th>
				<th>Наименование</th>
				<th>Автор</th>
				<th>Действия</th>
			</tr>
			<? foreach($list as $id){ ?>
				<? $item = lists_peer::instance()->get_item($id); ?>
				<tr>
					<td align="center"></td>
					<td></td>
					<td></td>
					<td align="center">
						<div><a href="javascript:void(0);">Изменить</a></div>
						<div><a href="javascript:void(0);">Удалить</a></div>
					</td>
				</tr>
			<? } ?>
		</table>
	</div>
	
</div>

<div class="clear"></div>