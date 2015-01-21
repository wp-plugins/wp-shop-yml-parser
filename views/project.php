<?php 
$projectName = isset($this->project) ? $this->project->getName() : "";
$projectUrl = isset($this->project) ? $this->project->getUrl() : "";
$importTemplate = isset($this->project) ? $this->project->getTemplate() : "";
?>

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
	<div id="icon-tools" class="icon32"><br/></div>
	<h2>Импорт и синхронизация с источниками YML</h2>
	<?php if (isset($this->project)):?>
	<h3 class="title">Редактирование проекта</h3>
	<?php else:?>
	<h3 class="title">Добавить новый проект</h3>
	<?php endif;?>
	<form action="tools.php?page=importyml_main" method="post" name="yi-projectForm">
		<!-- Скрытое поле для сохранения шаблона импорта -->
		<input type="hidden" name="yi-import-template"/>
		<input type="hidden" name="import-yml-project" value="<?php echo $_GET['project'];?>"/>
		<table>
			<tr>
				<td>Имя проекта:</td>
				<td><input type="text" name="yi-projectName" value="<?php echo $projectName;?>" style="width:400px"/></td>
			</tr>
			<tr>
				<td>Адрес URL:</td>
				<td><input type="text" name="yi-projectYML" value="<?php echo $projectUrl;?>" style="width:400px"<?php if (isset($this->project)) echo " disabled"?>/></td>
			</tr>
		</table>
	</form>
	<div style="margin-top:30px">
		<input type="button" value="Сохранить" class="button" id="iySaveProject"/>
		<input type="button" value="Анализировать" class="button" id="yiAnalizeProject"/>
		<input type="button" value="Импортировать" class="button" id="yiShowImportView"/>
		<!--<input type="button" value="Сопоставить" class="button" id="yiCollateProject"/>-->
		<input type="button" value="Удалить контент" class="button" id="yiDeleteProjectContent"/>
	</div>
<br/><br/><br/>
<table class="widefat">
<tr>
	<th>Дата время</th>
	<th>Обновлено цен</th>
	<th>Выключено</th>
	<th>Включено</th>
	<th>Новые</th>
</tr>
<?php
if (isset($this->changed)){
foreach($this->changed as $change) {
	echo "<tr>";
	echo "<td>{$change->changed}</td><td>{$change->updated_price}</td><td>{$change->updated_off}</td><td>{$change->updated_on}</td><td>{$change->updated_new}</td>";
	echo "</tr>";
}
}
echo "</tr>";
?>
</table>

	<div id="importView" style="display:none" class="yi-actionView">
		<div style="width:630;float:left;">
			<h3>Шаблон</h3>
			<textarea name="importTemplate" style="width:600px;height:300px">
			<?php echo $importTemplate; ?>
			</textarea>
			<div>
				<div id="createPostBar"></div>
				<div id="createPostBarMessage" style="font-size:20px;margin:10px 0;">Обработано <span class="howmany">0</span> из <span class="total">0</span></div>
			</div>
			<input type="button" value="Импортировать" class="button" id="iyImportProject"/>
			<div class="console">
			</div>
		</div>
	</div>

</div>

