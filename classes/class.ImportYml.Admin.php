<?php 
/**
 * Класс обеспечивающий страницу плагина
 * 
 * @package Import Yml
 * @subpackage Admin Class
 * @author Igor Bobko
 *
 */

class ImportYml_Admin extends wpmvc_controller
{
	public function __construct()
	{
		parent::__construct();
		add_action('admin_menu', array(&$this,'createMenu'));
	}
	
	public function createMenu()
	{
		add_submenu_page("tools.php", "Import YML" , "Import YML", "activate_plugins", "importyml_main",array(&$this,"router"));
	}
	
	/**
	 *  Функция действия необходимая для редактирование старого или добавления нового проекта 
	 */
	public function editProjectAction()
	{
		$projectId = $_POST['import-yml-project'];
		if (is_numeric($projectId))
		{
			$project = new ImportYml_Project($projectId);
			$project->save($_POST);
		}
		else
		{
			$project = ImportYml_Project::newProject($_POST['yi-projectName'],$_POST['yi-projectYML'],$_POST);
		}		
	}
	
	/**
	 * Решает какое действие выполнять
	 */
	public function router()
	{
		$term = get_term(11,'category');
		
		/* Если идет редактирование или добавление нового проекта*/
		if (isset($_POST['import-yml-project']))
		{
			$this->editProjectAction();
		}
		
		/* В случае, если необходима работа с конкретным проектом. */
		if (isset($_GET['project'])) {
			if (isset($_GET['action'])) {
				switch($_GET['action']) {
					case 'remove' : {
						$project = new ImportYml_Project($_GET['project']);
						$project->remove();
						break;
					}
				}
			} else {	
				$this->projectAction();
				return;
			}
		}
		/* Загрузка главной страницы проекта */
		$this->mainPageAction();
	}
	
	/**
	 * Действия для отображения главной страницы проекта
	 */
	public function mainPageAction()
	{
		global $wpdb;
		$r = $wpdb->get_results("SELECT `id` FROM `{$wpdb->prefix}importyml_project`");
		$this->view->projects = array();		
		foreach($r as $p)
		{
			$this->view->projects[] = new ImportYml_Project($p->id);
		}
		
		$r = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}importyml_offer` where project_id = 2");
		
		

		
		$this->view->render("main.php");		
	}
	
	public function projectAction() {
		global $wpdb;
		if (((int)$_GET['project']))
		{
			$this->view->project = new ImportYml_Project($_GET['project']);
			$changed = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}importyml_changed` WHERE `project_id` = {$_GET['project']} ORDER BY changed DESC LIMIT 10");						
			$this->view->changed = $changed;
		}
		
		/** Подключаем необходимые JavaScript библиотеки */
		wp_enqueue_script("jquery-ui-progressbar");
		wp_enqueue_style("jquery-ui",ImportYml_url."/ui/jquery-ui.css");
		
		$this->view->render("project.php");
	}
}

?>
