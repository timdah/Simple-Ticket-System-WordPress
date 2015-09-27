<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Um $wpdb nutzen zu können
global $wpdb;

$opt1 = sanitize_text_field($_POST["opt1"]);
$opt2 = sanitize_text_field($_POST["opt2"]);
$opt3 = sanitize_text_field($_POST["opt3"]);
$datepicker = sanitize_text_field($_POST["datepicker"]);

$wpdb->query($wpdb->prepare("UPDATE wp_sts_options SET ts_value = CASE 
    WHEN ts_option = 'opt_field_1' THEN %s
    WHEN ts_option = 'opt_field_2' THEN %s
    WHEN ts_option = 'opt_field_3' THEN %s
    WHEN ts_option = 'datepicker' THEN %s
END", $opt1, $opt2, $opt3, $datepicker));
?> 