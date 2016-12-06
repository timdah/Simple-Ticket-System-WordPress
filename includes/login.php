<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Um $wpdb nutzen zu können
global $wpdb;

//Benutzername & Passwort aus Eingabe
$username = sanitize_text_field($_POST["username"]);
$passwort = md5(sanitize_text_field($_POST["passwort"]));

//Benutzername & Passwort überprüfen
$abfrage = "SELECT username, passwort, admin FROM {$wpdb->prefix}sts_login WHERE username = %s LIMIT 1";
$row = $wpdb->get_row($wpdb->prepare($abfrage, $username));

//Wenn Passwort korrekt
if($row && $row->passwort == $passwort) {
	//Setze Cookie 'username'
	setcookie("ts_username", $username, 0, "/");//? "pass" : "fail";
	
	// Setze Cookie 'admin'
	if($row->admin == 1) {
		setcookie("ts_admin", 1, 0, "/");
	} else { 
		setcookie("ts_admin", 0, 0, "/");
	}
}
// Wenn Passwort nicht korrekt	
else if($row == NULL || $row->passwort != $passwort) { 
	echo '<p>' . _e('Username and/or password were incorrect.', 'simple-support-ticket-system') . '</p>';
}
?>