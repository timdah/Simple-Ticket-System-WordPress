 <?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Um $wpdb nutzen zu können
global $wpdb;

//Benutzername & Passwort aus Eingabe
$username = sanitize_text_field($_POST["username"]);
$passwort = md5(sanitize_text_field($_POST["passwort"]));

//Benutzername & Passwort überprüfen
$abfrage = "SELECT username, passwort, admin FROM wp_sts_login WHERE username = %s LIMIT 1";
$row = $wpdb->get_row($wpdb->prepare($abfrage, $username));

//Wenn Passwort korrekt
if($row && $row->passwort == $passwort) {
	//Setze Session-Variable 'username'
    $_SESSION["username"] = $username;
	
	// Setze Session-Variable 'admin'
	if($row->admin == 1) {
		$_SESSION["admin"] = 1;
	} else { 
		$_SESSION["admin"] = 0; 
	}
}
// Wenn Passwort nicht korrekt	
else if($row == NULL || $row->passwort != $passwort) { 
	echo '<p>' . _e('Username and/or password were incorrect.', 'ticket-system-simple') . '</p>';
}
?>