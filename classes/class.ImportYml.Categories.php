<?php

class ImportYml_Categories
{
	private $projectID;
	public function __construct($projectID)
	{
		$this->projectID = $projectID;
	}
	
	/**
	 * Возвращает категории проекта.
	 * @return stdClass
	 */
	public function getCategories()
	{
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}importyml_category` where `project_id` = '{$this->projectID}'");
		return $results;
	}
	
	
	private function setParents()
	{
		global $wpdb;
		foreach($this->getCategories() as $category)
		{
			if ($category->parent_id != 0)
			{
				$wp_parentID = $this->getWpID($category->parent_id);
				$wpdb->query("update `{$wpdb->prefix}term_taxonomy` set `parent` = '{$wp_parentID}' where `term_id` = '{$category->affiliate_id}'");
			}
		}	
	}
	
	private function getWpID($xmlID)
	{
		global $wpdb;
		return $wpdb->get_var("SELECT `affiliate_id` FROM `{$wpdb->prefix}importyml_category` WHERE `id` = '{$xmlID}'");
	}
	
	public function generateCategories()
	{
		global $wpdb;
		
		foreach($this->getCategories() as $category)
		{
			if ($category->affiliate_id == 0)
			{
				// Создаем новую term
				$wpdb->insert("{$wpdb->prefix}terms",
				array(
						'name' => $category->category_name,
						'slug' => ImportYml_Yml::translit($category->category_name)
				),
				array('%s','%s',));
	
				// Запоминаем ID нового термса
				$termID = $wpdb->insert_id;
				// Указываем WordPress, что это категория
				$wpdb->insert("{$wpdb->prefix}term_taxonomy",
				array('term_id' => $termID,'taxonomy' => "category"),
				array('%d','%s')
				);
	
				// Обновляем информацию о связке в таблицу категорий проекта
				$wpdb->update(
						"{$wpdb->prefix}importyml_category",
						array('affiliate_id' => $termID),
						array('id' => $category->id),
						array('%d'),
						array('%d')
				);
			}
		}
		
		$this->setParents();
		//$this->recalculate();

	}
	
	/**
	 * Перерасчет информации о категориях
	 */
	public function recalculate()
	{
		//Hack! Удаляем опцию, чтобы Wordpress её снова создал и сделал перерасчет
		delete_option('category_children');		
	}
}