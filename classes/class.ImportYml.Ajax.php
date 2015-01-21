<?php
/**
 * Класс отвечает за обработку Ajax запросов
 * 
 * @subpackage Ajax Class
 * @package Import Yml
 * @author Igor Bobko
 */

class ImportYml_Ajax {
	public function __construct()
	{
		global $wpdb;
		if (isset($_REQUEST['iy-ajax'])) {
			$projectId = $_REQUEST['iy-project-id'];
			$project = new ImportYml_Project($projectId);
			
			// Создаем или обновляем категории
			if ($_REQUEST['iy-project-action'] == "update")
			{
				new ImportYml_Update($projectId);
				exit();
			}
			
			// Создаем или обновляем категории
			if ($_REQUEST['iy-project-action'] == "createCategories")
			{
				$project->generateCategories();
				exit();
			}
			
			// Анализирование контента из файла YML и заполнение таблиц проекта
			if ($_REQUEST['iy-project-action'] == "analize")
			{
				$project->update();
				exit();
			}
			// Метод возвращает json ids всех товаров
			if ($_REQUEST['iy-project-action'] == "getOffers")
			{
				$this->getProjectOffers($project);
				exit();
			}
			
			// Метод возвращает json ids всех аттачей
			if ($_REQUEST['iy-project-action'] == "getAttachs")
			{
				$this->getProjectAttachs($project);
				exit();
			}
			
			if ($_REQUEST['iy-project-action'] == "getPosts")
			{
				$this->getProjectPosts($project);
				exit();
			}
			
			if ($_REQUEST['iy-project-action'] == "deletePosts")
			{
				$project->deletePosts($_REQUEST['post_id']);
				exit();
			}
			
			if ($_REQUEST['iy-project-action'] == "deletePosts1")
			{
				$project->deletePosts($_REQUEST['post_id']);
				exit();
			}
			
			if ($_REQUEST['iy-project-action'] == "createPost")
			{
				$project->createPost($_REQUEST['offer_id']);
				exit();
			}
			
			if ($_REQUEST['iy-project-action'] == "deleteContent")
			{
				$project->deleteContent();
				exit();
			}
			
			if ($_REQUEST['iy-project-action'] == "recalculate")
			{
				$project->recalculate();
			}
			
			echo "OK";
			exit();
		}
		
		if ($_REQUEST['iy-project-action'] == "crone")
			{
				$period = $_REQUEST['iy-time'];
				update_option('importyml_cron_interval', $period);
				update_option('importyml_cron_is_new', 1);
				exit();
			}
	}
	
	// Создаем или обновляем категории
			
	
	/**
	 * Возвращает в виде JSON информацию о товарах
	 * 
	 * @param ImportYml_Project $project
	 */
	private function getProjectOffers(ImportYml_Project $project)
	{
		$offers = $project->getOffers();
		$result = array();
		foreach($offers as $offer)
		{
			$t = &$result[];
			$t['id'] = $offer->id;
		}
		echo json_encode($result);
	}
	
	/**
	 * Возвращает в виде JSON информацию о товарах
	 * 
	 * @param ImportYml_Project $project
	 */
	private function getProjectAttachs(ImportYml_Project $project)
	{
		$attachs = $project->getAttachs();
		$result = array();
		foreach($attachs as $attach)
		{
			$t = &$result[];
			$t['id'] = $attach->ID;
		}
		echo json_encode($result);
	}
	
	private function getProjectPosts(ImportYml_Project $project)
	{
		$posts = $project->getPosts();
		$result = array();
		foreach($posts as $post)
		{
			$t = &$result[];
			$t['id'] = $post->ID;
		}
		echo json_encode($result);
	}
	
	
}
