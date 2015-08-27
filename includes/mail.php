<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

//Session Variablen aktivieren
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<?php
// Um $wpdb nutzen zu können
global $wpdb;
$absendername = sanitize_text_field($_POST["name"]);
$absendermail = sanitize_email($_POST["mail"]);
$problem = sanitize_text_field($_POST["problem"]);
if(isset($_POST["rechner"])){$rechner = sanitize_text_field($_POST["rechner"]);} else {$rechner = NULL;}
if(isset($_POST["raum"])){$raum = sanitize_text_field($_POST["raum"]);} else {$raum = NULL;}
if(isset($_POST["telefon"])){$telefon = sanitize_text_field($_POST["telefon"]);} else {$telefon = NULL;}
if(isset($_POST["termin"])){$termin = sanitize_text_field($_POST["termin"]);} else {$termin = NULL;}
if(isset($_POST["status"]) && sanitize_text_field($_POST["status"]) == 'yes'){$status = 'yes';}else{$status = NULL;}

$zeit = date(get_option('date_format') . ', ' . get_option('time_format'));

// Neues Ticket in die Datenbank eintragen
$eintragen = $wpdb->query($wpdb->prepare("INSERT INTO wp_sts_tickets (name,mail,problem,zeit) VALUES (%s, %s, %s, %s)", $absendername, $absendermail, $problem, $zeit));

if($eintragen == true)
{
	if($rechner != NULL || $raum != NULL || $telefon != NULL || $termin != NULL || $status != NULL)
	{
		$query = $wpdb->get_results("SELECT id FROM wp_sts_tickets WHERE problem = '$problem' LIMIT 1");
		foreach($query as $id)
		{
			if($rechner != NULL)
			{
				$wpdb->query($wpdb->prepare("UPDATE wp_sts_tickets SET rechner=%s WHERE id='$id->id'", $rechner));
			}
			if($raum != NULL)
			{
				$wpdb->query($wpdb->prepare("UPDATE wp_sts_tickets SET raum=%s WHERE id='$id->id'", $raum));
			}
			if($telefon != NULL)
			{
				$wpdb->query($wpdb->prepare("UPDATE wp_sts_tickets SET telefon=%s WHERE id='$id->id'", $telefon));
			}
			if($termin != NULL)
			{
				$time = strtotime($termin);
				$wpdb->query($wpdb->prepare("UPDATE wp_sts_tickets SET termin=%s, termin_timestamp=%s WHERE id='$id->id'", $termin, $time));
			}
			if($status != NULL)
			{
				$wpdb->query("UPDATE wp_sts_tickets SET status='1' WHERE id='$id->id'");
			}			
		}
	}
	echo '<p>' . _e('Your problem has been successfully submitted.', 'ticket-system-simple') . '</p>';
	echo '<p><form onsubmit="back();return false;"><input class="button" value="';
	_e('Back', 'ticket-system-simple');
	echo '" type="submit"></input></form></p>';
}
else
{
	echo '<p>' . _e('Unfortunately, something went wrong, please contact the administrator.', 'ticket-system-simple') . '</p>';
	echo '<p><form onsubmit="back();return false;"><input class="button" value="';
	_e('Back', 'ticket-system-simple');
	echo '" type="submit"></input></form></p>';
}
?>