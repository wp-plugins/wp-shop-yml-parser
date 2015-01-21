<?php

class wpmvc_table
{
	private $tableName = null;
	private $structure;
	public function __construct($tableName)
	{
-		$this->tableName = $tableName;
	}
	
	/**
	 *  Проверяет существует ли такая таблица
	 *  
	 * @return boolean
	 */
	public function exists()
	{
		global $wpdb;
		
		$results = $wpdb->get_results("SHOW TABLES",ARRAY_N);
		foreach($results as $row)
		{
			if ($row[0] == $this->tableName)
			{
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Устанавливает структуру таблицы переданную в массиве
	 * 
	 * @param array $structure
	 */
	public function setStructure(array $structure)
	{
		$this->structure = $structure;
	}
	
	/**
	 * Проверяет таблицы на соответсвие структуры
	 */
	public function check()
	{
		global $wpdb;
		$results = $wpdb->get_results("SHOW COLUMNS FROM `{$this->tableName}`");
		
		$errors = array();
		
		foreach($results as $cell)
		{
			$c = $this->getCell($cell->Field);
			if ($c){
				/**
				 * @todo Возможно здесь нужно будет сделать соответсвие типов
				 */
			}
			else
			{
				$errors[] = "В указанной структуре нет поля, который есть в таблице";
			}
		}
	}
	
	public function getCell($cellName)
	{
		foreach($this->structure as $cell)
		{
			if ($cell->name == $cellName)
			{
				return $cell;
			}
		}
		return false;
	}
}