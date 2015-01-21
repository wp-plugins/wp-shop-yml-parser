<?php

class ImportYml_Cron {
	protected static $_instance;
	public static function getInstance() {
        	if (self::$_instance === null) {
        		self::$_instance = new self;   
        	}
 		return self::$_instance;
	}

	private function __construct() {
		add_filter( 'cron_schedules', 'my_add_intervals' );
 		function my_add_intervals( $schedules ) {
			$schedules['1minute'] = array(
				'interval' => 60,
				'display' => __( '1minute' )
			);
			$schedules['half'] = array(
				'interval' => 1800,
				'display' => __('half of our')
			);
			$schedules['hour'] = array(
				'interval' => 3600,
				'display' => __('Once a hour')
			);
			$schedules['day'] = array(
				'interval' => 86400,
				'display' => __('Once a day')
			);
			$schedules['month'] = array(
				'interval' => 2635200,
				'display' => __('Once a month')
			);
			return $schedules;
		}
		add_action('ymp-import-event', array(&$this,'everyAction'));
		$period = get_option('importyml_cron_interval');
		$is_new = get_option('importyml_cron_is_new');
		if ($period!='none'&&$period!=''){
		if($is_new==1){wp_clear_scheduled_hook( 'ymp-import-event' );}
		if ( !wp_next_scheduled( 'ymp-import-event' ) ) {
				wp_schedule_event(time(),$period, 'ymp-import-event');
				update_option('importyml_cron_is_new', 0);
			}
		}
		wp_cron();
	}

	public function everyAction() {
			
		ob_start();
		global $wpdb;
		$r = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}importyml_project");
		foreach($r as $s) {
			new ImportYml_Update($s->id);
		}
		ob_get_clean();
	
	}
}
