<?php 
/**
 * 
 * @author Igor Bobko
 */
class ImportYml_View extends stdClass
{
	private static $instance = false;
	
	private function __construct()
	{
		
	}
	
	static public function getInstance()
	{
		if (!self::$instance)
		{
			self::$instance = new self(); 
		}
		return self::$instance;
	}
	
	public function render($file)
	{
		$path = ImportYml_dir_views."/{$file}";
		if (file_exists($path))
		{
			include $path;	
		}
	}
}
