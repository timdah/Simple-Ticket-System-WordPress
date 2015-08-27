<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
//Session Variablen aktivieren
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Um $wpdb nutzen zu können
global $wpdb;

$id = sanitize_text_field($_POST["id"]);
$wpdb->delete('wp_sts_login', array('id' => $id));
?>