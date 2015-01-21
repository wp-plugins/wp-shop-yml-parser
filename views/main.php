<style type="text/css">
#parser_shadow_window {
	position: fixed;
	height: 100%;
	width: 100%;
	background-color: black;
	z-index: 1000;
	opacity: 0.7;
}

#parser_background_message {
	width: 100%;
	z-index: 1001;
	position: fixed
}

#parser_background_message > div{
	margin: 100px auto;
	padding: 30px;
	width: 400px;
	height: 220px;
	background-color: white;
	text-align:center;
	position: relative;
}

#parser_background_message #continueButton {
	position: absolute;
	top: 0px;
	right: 0px;
	background: gray;
	width: 20px;
	height: 20px;
	color: #fff;
	text-align: center;
	line-height: 20px;
	cursor: pointer;
}

</style>
<div class="wrap">
<div id="icon-tools" class="icon32"><br></div><h2>Импорт и синхронизация с источниками YML</h2>

<p class="pressthis"><a href="tools.php?page=importyml_main&project=new"><span>Добавить новый проект</span></a></p>

<table class="wp-list-table widefat fixed">
	<thead>
	<tr>
		<th style="width:30px">#</th>
		<th>Наименование проекта</th>
		<th>Дата добавления</th>
		<th>Последние обновление</th>
		<th>Изменить</th>
		<th>Обновить</th>
		<th>Удалить</th>
	</tr>
	</thead>
	<tbody>
			<?php foreach($this->projects as $project):?>
			<tr>
			<td><?php echo $project->getID();?></td>
			<td><?php echo $project->getName();?></td>
			<td><?php echo $project->getDateAdded();?></td>
			<td><?php echo $project->getDateUpdated();?></td>
			<td><input type="button" value="Изменить" class="button" onclick="document.location='tools.php?page=importyml_main&project=<?php echo $project->getID(); ?>';"/></td>
			<td><input type="button" value="Обновить" class="button" onclick="window.iy.updateProject(<?php echo $project->getID(); ?>,'<?php echo ImportYml_url; ?>');"/></td>
			<td><input type="button" value="Удалить" class="button" onclick="document.location='tools.php?page=importyml_main&project=<?php echo $project->getID(); ?>&action=remove';"/></td>
			</tr>
			<?php endforeach;?>
	</tbody>
	<!--<tfoot>
			<tr>
			<td colspan="3">Установить переодичность выполнения</td>
			<td colspan="4">
				
				<?php /* $inter = get_option('importyml_cron_interval');
				if ($inter == 'none') { $s1 = ' selected="selected"'; $s2 = '';$s3 = '';$s4 = '';$s5 = '';$s6 = '';}
				if ($inter == '1minute') { $s1 = ''; $s2 = ' selected="selected"';$s3 = '';$s4 = '';$s5 = '';$s6 = '';}
				if ($inter == 'half') { $s1 = ''; $s2 = '';$s3 = ' selected="selected"';$s4 = '';$s5 = '';$s6 = '';}
				if ($inter == 'hour') { $s1 = ''; $s2 = '';$s3 = '';$s4 = ' selected="selected"';$s5 = '';$s6 = '';}
				if ($inter == 'day') { $s1 = ''; $s2 = '';$s3 = '';$s4 = '';$s5 = ' selected="selected"';$s6 = '';}
				if ($inter == 'month') { $s1 = ''; $s2 = '';$s3 = '';$s4 = '';$s5 = '';$s6 = ' selected="selected"';} */
				?>
				<select name='crone_period' id="yiCronProject">
					<option value='none' <?php //echo $s1;?>>не обновлять</option>
					<option value='1minute' <?php// echo $s2;?>>раз в минуту</option>
					<option value='half' <?php //echo $s3;?>>раз в 30 минут</option>
					<option value='hour' <?php// echo $s4;?>>раз в час</option>
					<option value='day' <?php// echo $s5;?>>раз в день</option>
					<option value='month' <?php //echo $s6;?>>раз в месяц</option>
				</select>
			</td>
			</tr>
	</tfoot>-->
</table>

