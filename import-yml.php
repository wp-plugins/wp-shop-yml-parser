<?php 
/*
 Plugin Name: WP Shop Import YML
 Plugin URI: http://wp-shop.ru/yandex-xml-parser/
 Description: Импорт контента из интернет-магазинов, имеющих Яндекс XML фид, с переносом товаров на вордпресс-магазин и последующей синхронизацией. 
 Author: www.wp-shop.ru
 Version: 0.1
 Author URI: http://www.wp-shop.ru
 */

if(!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php"); 
}



if (!defined("ImportYml")) {
	define("ImportYml",true);
	$ns = "wpmvc_".rand(1, 1000) . "_const";

	define( 'ImportYml_url', plugins_url("",__FILE__) );
	define( 'ImportYml_dir', dirname(realpath(__FILE__)));
	define( 'ImportYml_dir_classes' , ImportYml_dir ."/classes");
	define( 'ImportYml_dir_views' , ImportYml_dir ."/views");
	define( 'ImportYml_dir_ymls' , ImportYml_dir ."/ymls");

	if (!function_exists('disableMagicQuotes')) {	
		function disableMagicQuotes() {
			if (get_magic_quotes_gpc()) {
				$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
				while (list($key, $val) = each($process)) {
				foreach ($val as $k => $v) {
				    unset($process[$key][$k]);
				    if (is_array($v)) {
					$process[$key][stripslashes($k)] = $v;
					$process[] = &$process[$key][stripslashes($k)];
				    } else {
					$process[$key][stripslashes($k)] = stripslashes($v);
				    }
				}
			    }
			    unset($process);
			}
		}
	}
	disableMagicQuotes();

	function importYmlLoader($className) {
		$class = array();
		preg_match("/(\S+)_(\S+)/",$className,$class);
		if ($class[1] == "wpmvc") {
			$path = ImportYml_dir . "/library/wpmvc/" . $class[2] . ".php";
			if (file_exists($path)) {
				require_once $path;
				return;
			}
		}
	
		if (file_exists(ImportYml_dir_classes . "/class.ImportYml.".$class[2].".php")) {
			require_once ImportYml_dir_classes . "/class.ImportYml.".$class[2].".php";
		}
	}
	spl_autoload_register('importYmlLoader');

	new wpmvc_plugin();

}
?>
