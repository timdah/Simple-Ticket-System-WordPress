<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Um $wpdb nutzen zu können
global $wpdb;

$check = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'check_mail'");
if($check == 0) {
	$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}sts_options SET ts_value = '1' WHERE ts_option = 'check_mail'"));
} else {
	$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}sts_options SET ts_value = '0' WHERE ts_option = 'check_mail'"));
}
?>