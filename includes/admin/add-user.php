<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Um $wpdb nutzen zu können
global $wpdb;

$username = sanitize_text_field($_POST["username"]);
$passwort = md5(sanitize_text_field($_POST["passwort"]));
$passwort2 = md5(sanitize_text_field($_POST["passwort2"]));
$mail = sanitize_email($_POST["mail"]);
$anrede = sanitize_text_field($_POST["anrede"]);
$name = sanitize_text_field($_POST["name"]);
if(isset($_POST["admin"])){$admin = sanitize_text_field($_POST["admin"]);} else {$admin = NULL;}

if($passwort != $passwort2 OR $username == "" OR $passwort == "" OR $name == "" OR $mail == "")
    {
    _e('Input errors. Please fill in all fields correctly.', 'simple-support-ticket-system');
	echo '<p><form onsubmit="back();return false;"><input class="button" value="';
	_e('Back', 'simple-support-ticket-system');
	echo '" type="submit"></input></form></p>';
    exit;
    }

$menge = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$wpdb->prefix}sts_login WHERE username = %s", $username));

if($menge == 0)
    {
    $eintrag = $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}sts_login (username, passwort, mail, anrede, name, admin) VALUES (%s, %s, %s, %s, %s, %d)", $username, $passwort, $mail, $anrede, $name, $admin));

    if($eintrag == true)
        {
        //echo "Benutzer <b>$username</b> wurde erstellt.";
		printf(__('User %s was created successfully', 'simple-support-ticket-system'), $username);
        }
    else
        {
        //echo "Fehler beim Speichern des Benutzers.";
		_e('Failed to save the user', 'simple-support-ticket-system');
        }


    }

else
    {
    //echo "Benutzername schon vorhanden.";
	_e('Username already exists', 'simple-support-ticket-system');
    }

echo '<p><form onsubmit="back();return false;"><input class="button" value="';
_e('Back', 'simple-support-ticket-system');
echo '" type="submit"></input></form></p>';
?> 