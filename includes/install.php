<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
global $wpdb;

if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}sts_login'") != $wpdb->prefix . 'sts_login'){
	$wpdb->query("CREATE TABLE {$wpdb->prefix}sts_login (
	id INTEGER UNSIGNED NULL AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	passwort VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	anrede VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	name VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	admin INTEGER NULL
	);");
}

if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}sts_tickets'") != $wpdb->prefix . 'sts_tickets'){
	$wpdb->query("CREATE TABLE {$wpdb->prefix}sts_tickets (
	id INTEGER UNSIGNED NULL AUTO_INCREMENT PRIMARY KEY,
	termin VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	name VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	mail VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	rechner VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	raum VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	telefon VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	problem VARCHAR(2000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	status VARCHAR(3) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	zeit VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	geloest INTEGER(1) NOT NULL DEFAULT '0',
	bearbeiter VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'unbekannt',
	bemerkung VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	loesung VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	ende VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	termin_timestamp VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	ende_timestamp VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL
	);");
}

if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}sts_options'") != $wpdb->prefix . 'sts_options'){
	$wpdb->query("CREATE TABLE {$wpdb->prefix}sts_options (
	ts_option VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL PRIMARY KEY,
	ts_value VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
	);");
	
	$wpdb->query("INSERT INTO {$wpdb->prefix}sts_options (ts_option, ts_value) VALUES ('db_version', '100')");
}
?>