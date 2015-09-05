<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
global $wpdb;

$db_version = $wpdb->get_var("SELECT ts_value FROM wp_sts_options WHERE ts_option = 'db_version'");
if($db_version == DB_VERSION) {
	return;
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
}
?>
