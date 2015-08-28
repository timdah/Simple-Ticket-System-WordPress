<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Um $wpdb nutzen zu können
global $wpdb;

$username = sanitize_text_field($_POST["username"]);
$passwort = md5(sanitize_text_field($_POST["passwort"]));
$passwort2 = md5(sanitize_text_field($_POST["passwort2"]));
$anrede = sanitize_text_field($_POST["anrede"]);
$name = sanitize_text_field($_POST["name"]);
if(isset($_POST["admin"])){$admin = sanitize_text_field($_POST["admin"]);} else {$admin = NULL;}

if($passwort != $passwort2 OR $username == "" OR $passwort == "" OR $name == "")
    {
    _e('Input errors. Please fill in all fields correctly.', 'ticket-system-simple');
	echo '<p><form onsubmit="back();return false;"><input class="button" value="';
	_e('Back', 'ticket-system-simple');
	echo '" type="submit"></input></form></p>';
    exit;
    }

$menge = $wpdb->get_var($wpdb->prepare("SELECT id FROM wp_sts_login WHERE username = %s", $username));

if($menge == 0)
    {
    $eintrag = $wpdb->query($wpdb->prepare("INSERT INTO wp_sts_login (username, passwort, anrede, name, admin) VALUES (%s, %s, %s, %s, %d)", $username, $passwort, $anrede, $name, $admin));

    if($eintrag == true)
        {
        //echo "Benutzer <b>$username</b> wurde erstellt.";
		printf(__('User %s was created successfully', 'ticket-system-simple'), $username);
        }
    else
        {
        //echo "Fehler beim Speichern des Benutzers.";
		_e('Failed to save the user', 'ticket-system-simple');
        }


    }

else
    {
    //echo "Benutzername schon vorhanden.";
	_e('Username already exists', 'ticket-system-simple');
    }

echo '<p><form onsubmit="back();return false;"><input class="button" value="';
_e('Back', 'ticket-system-simple');
echo '" type="submit"></input></form></p>';
?> 