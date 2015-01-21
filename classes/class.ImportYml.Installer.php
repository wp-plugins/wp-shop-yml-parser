<?php

class ImportYml_Installer extends wpmvc_installer
{
	public function __construct()
	{
		global $wpdb;
		parent::__construct();
		
		/**
	 	 * @todo Доделать класс wpmvc_table
		 */
		
		$t = new wpmvc_table("{$wpdb->prefix}importyml_project");
		$t->exists();
		
		$cells = array();
		$cells[] = new wpmvc_cell("id","int(11)",false);
		$cells[] = new wpmvc_cell("project_name","varchar(255)",false);
		$cells[] = new wpmvc_cell("project_file","varchar(255)",false);
		$cells[] = new wpmvc_cell("project_url","varchar(255)",false);
		$cells[] = new wpmvc_cell("project_added","timestamp",false,"CURRENT_TIMESTAMP");
		$cells[] = new wpmvc_cell("project_changed","timestamp",false,"'0000-00-00 00:00:00'");
		
			
		$t->setStructure($cells);
		$t->check();
		$this->createOptions();
		$this->createTables();
	}
	
	public function createTables()
	{
		global $wpdb;
		$wpdb->query("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}importyml_project` (
  					`id` int(11) NOT NULL AUTO_INCREMENT,
  					`project_name` varchar(255) NOT NULL,
  					`project_file` varchar(255) NOT NULL,
  					`project_url` varchar(255) NOT NULL,
  					`project_template` TEXT,
  					`project_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  					`project_changed` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  					PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8");
		 
		 $wpdb->query("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}importyml_category` (
  				`project_id` int(11) NOT NULL,
  				`id` int(11) NOT NULL,
  				`parent_id`	int(11),
  				`category_name` varchar(255) NOT NULL,
  				`affiliate_id` int(11) DEFAULT 0,
  				PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		 $wpdb->query("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}importyml_changed` (
  				`project_id` int(11) NOT NULL,
  				`id` int(11) NOT NULL auto_increment,
  				`changed`	timestamp,
				`updated_price` int(11),
				`updated_on` int(11),
				`updated_off` int(11),
				`updated_new` int(11),

  				PRIMARY KEY (`id`,`project_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
		 
		 $wpdb->query("CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}importyml_offer` (
			  `project_id` int(11) NOT NULL,
			  `id` int(11) NOT NULL,
			  `offer_name` varchar(255) NOT NULL,
			  `offer_description` varchar(255) NOT NULL,
			  `offer_category` int(11) NOT NULL,
			  `offer_currency` varchar(10) NOT NULL,
			  `offer_available` tinyint(4) NOT NULL,
			  `offer_url` varchar(255) NOT NULL,
			  `offer_price` float NOT NULL,
			  `offer_delivery` tinyint(4) NOT NULL,
			  `offer_xml` TEXT,
			  `affiliate_id` int(11) DEFAULT 0,
			  PRIMARY KEY (`id`,`project_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8");

	}
	
	private function createOptions()
	{
		add_option("importyml_cron_interval",'none');
		add_option("importyml_cron_is_new",0);
		wp_clear_scheduled_hook( 'ymp-import-event');
	}
}
