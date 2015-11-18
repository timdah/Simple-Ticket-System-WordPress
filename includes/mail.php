<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
global $wpdb;
?>

<?php
// Check if JavaScript is manipulated
function checkText($str) {
	if(strlen(trim($str)) == 0) {
		echo '<p>' . _e('Let JavaScript do what it is supposed to do!', 'simple-support-ticket-system') . '</p>';
		echo '<p><form onsubmit="back();return false;"><input class="button" value="';
		_e('Back', 'simple-support-ticket-system');
		echo '" type="submit"></input></form></p>';
		// abort script
		exit;
	} else {
		return true;
	}
}

// Check for invalid Text
checkText($_POST["name"]); checkText($_POST["mail"]); checkText($_POST["title"]); checkText($_POST["problem"]);

$absendername = sanitize_text_field($_POST["name"]);
$absendermail = sanitize_email($_POST["mail"]);
$title = sanitize_text_field($_POST["title"]);
$problem = sanitize_text_field($_POST["problem"]);
if(isset($_POST["rechner"])){$rechner = sanitize_text_field($_POST["rechner"]);} else {$rechner = NULL;}
if(isset($_POST["raum"])){$raum = sanitize_text_field($_POST["raum"]);} else {$raum = NULL;}
if(isset($_POST["telefon"])){$telefon = sanitize_text_field($_POST["telefon"]);} else {$telefon = NULL;}
if(isset($_POST["termin"])){$termin = sanitize_text_field($_POST["termin"]);} else {$termin = NULL;}
if(isset($_POST["status"]) && sanitize_text_field($_POST["status"]) == 'yes'){$status = 'yes';}else{$status = NULL;}

$zeit = date(get_option('date_format') . ', ' . get_option('time_format'));

// Neues Ticket in die Datenbank eintragen
$eintragen = $wpdb->query($wpdb->prepare("INSERT INTO wp_sts_tickets (name,mail,title,problem,zeit) VALUES (%s, %s, %s, %s, %s)", $absendername, $absendermail, $title, $problem, $zeit));

if($eintragen == true)
{
	if($rechner != NULL || $raum != NULL || $telefon != NULL || $termin != NULL || $status != NULL)
	{
		$query = $wpdb->get_results($wpdb->prepare("SELECT id FROM wp_sts_tickets WHERE problem = %s LIMIT 1", $problem));
		foreach($query as $id)
		{
			if($rechner != NULL)
			{
				$wpdb->query($wpdb->prepare("UPDATE wp_sts_tickets SET rechner=%s WHERE id=%d", $rechner, $id->id));
			}
			if($raum != NULL)
			{
				$wpdb->query($wpdb->prepare("UPDATE wp_sts_tickets SET raum=%s WHERE id=%d", $raum, $id->id));
			}
			if($telefon != NULL)
			{
				$wpdb->query($wpdb->prepare("UPDATE wp_sts_tickets SET telefon=%s WHERE id=%d", $telefon, $id->id));
			}
			if($termin != NULL)
			{
				$time = strtotime($termin);
				$wpdb->query($wpdb->prepare("UPDATE wp_sts_tickets SET datepicker=%s WHERE id=%d", $termin, $id->id));
			}
			if($status != NULL)
			{
				$wpdb->query($wpdb->prepare("UPDATE wp_sts_tickets SET status='1' WHERE id=%d", $id->id));
			}			
		}
	}
	echo '<p>' . _e('Your problem has been successfully submitted.', 'simple-support-ticket-system') . '</p>';
	echo '<p><form onsubmit="back();return false;"><input class="button" value="';
	_e('Back', 'simple-support-ticket-system');
	echo '" type="submit"></input></form></p>';
}
else
{
	echo '<p>' . _e('Unfortunately, something went wrong, please contact the administrator.', 'simple-support-ticket-system') . '</p>';
	echo '<p><form onsubmit="back();return false;"><input class="button" value="';
	_e('Back', 'simple-support-ticket-system');
	echo '" type="submit"></input></form></p>';
}
?>