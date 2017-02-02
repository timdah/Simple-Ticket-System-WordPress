<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
global $wpdb;

$db_version = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'db_version'");
if($db_version == DB_VERSION) {
	return;
} else if($db_version < DB_VERSION){
	if($db_version < 102) {
		$wpdb->query("ALTER TABLE {$wpdb->prefix}sts_tickets
		ADD title VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
		;");		
		
		$wpdb->query("ALTER TABLE {$wpdb->prefix}sts_login
		ADD mail VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
		;");
		
		$wpdb->query("UPDATE {$wpdb->prefix}sts_options SET ts_value = '102' WHERE ts_option = 'db_version'");
	}
	if($db_version < 103) {
		$wpdb->query("ALTER TABLE {$wpdb->prefix}sts_tickets
		ADD datepicker VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL
		;");
		
		$wpdb->query("ALTER TABLE {$wpdb->prefix}sts_options MODIFY ts_value VARCHAR(50)");
		
		$wpdb->query("INSERT INTO {$wpdb->prefix}sts_options (ts_option, ts_value) VALUES 
		('opt_field_1', 'Optional Field 1 (Change in Settings)'), 
		('opt_field_2', 'Optional Field 2 (Change in Settings)'), 
		('opt_field_3', 'Optional Field 3 (Change in Settings)'), 
		('datepicker', 'Datepicker Field (Change in Settings)')");
		
		$wpdb->query("UPDATE {$wpdb->prefix}sts_options SET ts_value = '103' WHERE ts_option = 'db_version'");
	}
	if($db_version < 104) {
		$wpdb->query("INSERT INTO {$wpdb->prefix}sts_options (ts_option, ts_value) VALUES
		('problem', 'Problem (Change in Settings)'),
		('check_mail', '0'),
		('mail_answer', NULL),
		('mail_take', NULL),
		('mail_done', NULL)");
		
		$wpdb->query("ALTER TABLE {$wpdb->prefix}sts_tickets 
			MODIFY bemerkung VARCHAR(1000),
			MODIFY loesung VARCHAR(1000)
		");
		
		if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}sts_answers'") != $wpdb->prefix . 'sts_answers'){
			$wpdb->query("CREATE TABLE {$wpdb->prefix}sts_answers (
			id INTEGER UNSIGNED NULL AUTO_INCREMENT PRIMARY KEY,
			ticket_id INTEGER NOT NULL,
			index_antwort INTEGER NOT NULL,
			user VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
			antwort VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
			);");
		}
		
		$wpdb->query("UPDATE {$wpdb->prefix}sts_options SET ts_value = '104' WHERE ts_option = 'db_version'");
	}
	if($db_version < 105) {
		$wpdb->query("INSERT INTO {$wpdb->prefix}sts_options (ts_option, ts_value) VALUES ('link_mail', '0')");
		
		$wpdb->query("ALTER TABLE {$wpdb->prefix}sts_tickets
		ADD link VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
		;");
		
		$wpdb->query("UPDATE {$wpdb->prefix}sts_options SET ts_value = '105' WHERE ts_option = 'db_version'");
	}
	if($db_version < 106) {
		$wpdb->query("ALTER TABLE {$wpdb->prefix}sts_options MODIFY ts_value VARCHAR(1000)");
		
		$wpdb->query("UPDATE {$wpdb->prefix}sts_options SET ts_value = '106' WHERE ts_option = 'db_version'");
	}
}
?>
