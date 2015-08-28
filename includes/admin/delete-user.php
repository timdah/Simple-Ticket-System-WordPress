<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Um $wpdb nutzen zu können
global $wpdb;

$id = sanitize_text_field($_POST["id"]);
$wpdb->delete('wp_sts_login', array('id' => $id));
?>