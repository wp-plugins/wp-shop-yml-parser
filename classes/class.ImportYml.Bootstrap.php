<?php 
/**
 * 
 * @author Igor Bobko
 * @package Import Yml
 * @subpackage Bootstrap Class
 */
class ImportYml_Bootstrap
{
	public function __construct()
	{
		new ImportYml_Installer();
		new ImportYml_Ajax();
		ImportYml_Cron::getInstance();
		wp_enqueue_script("import-yml-js",ImportYml_url . "/import-yml.js");
		if (is_admin()) {
			$admin = new ImportYml_Admin();
		}
	}
}
?>
