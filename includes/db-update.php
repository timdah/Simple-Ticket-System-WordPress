<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
global $wpdb;

$db_version = $wpdb->get_var("SELECT ts_value FROM wp_sts_options WHERE ts_option = 'db_version'");
if($db_version == DB_VERSION) {
	exit;
} else if($db_version < DB_VERSION){
	if($db_version < 102) {
		$wpdb->query("ALTER TABLE wp_sts_tickets
		ADD title VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
		;");		
		
		$wpdb->query("ALTER TABLE wp_sts_login
		ADD mail VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
		;");
		
		$wpdb->query("UPDATE wp_sts_options SET ts_value = '102' WHERE ts_option = 'db_version'");
	}
	if($db_version < 103) {
		$wpdb->query("ALTER TABLE wp_sts_tickets
		ADD datepicker VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL
		;");
		
		$wpdb->query("ALTER TABLE wp_sts_options MODIFY ts_value VARCHAR(50)");
		
		$wpdb->query("INSERT INTO wp_sts_options (ts_option, ts_value) VALUES 
		('opt_field_1', 'Optional Field 1 (Change in Settings)'), 
		('opt_field_2', 'Optional Field 2 (Change in Settings)'), 
		('opt_field_3', 'Optional Field 3 (Change in Settings)'), 
		('datepicker', 'Datepicker Field (Change in Settings)')");
		
		$wpdb->query("UPDATE wp_sts_options SET ts_value = '103' WHERE ts_option = 'db_version'");
	}
}
?>
