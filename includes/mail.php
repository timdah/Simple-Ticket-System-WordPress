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

$absendername = stripslashes(sanitize_text_field($_POST["name"]));
$absendermail = stripslashes(sanitize_email($_POST["mail"]));
$title = stripslashes(sanitize_text_field($_POST["title"]));
$problem = stripslashes(implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['problem'] ) ) ) );
if(isset($_POST["rechner"])){$rechner = stripslashes(sanitize_text_field($_POST["rechner"]));} else {$rechner = NULL;}
if(isset($_POST["raum"])){$raum = stripslashes(sanitize_text_field($_POST["raum"]));} else {$raum = NULL;}
if(isset($_POST["telefon"])){$telefon = stripslashes(sanitize_text_field($_POST["telefon"]));} else {$telefon = NULL;}
if(isset($_POST["termin"])){$termin = sanitize_text_field($_POST["termin"]);} else {$termin = NULL;}
if(isset($_POST["status"]) && sanitize_text_field($_POST["status"]) == 'yes'){$status = 'yes';}else{$status = NULL;}

$zeit = date(get_option('date_format') . ', ' . get_option('time_format'));

// Neues Ticket in die Datenbank eintragen
$eintragen = $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}sts_tickets (name,mail,title,problem,zeit) VALUES (%s, %s, %s, %s, %s)", $absendername, $absendermail, $title, $problem, $zeit));

if($eintragen == true)
{
	$lastid = $wpdb->insert_id;

			if($rechner != NULL)
			{
				$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}sts_tickets SET rechner=%s WHERE id=%d", $rechner, $lastid));
			}
			if($raum != NULL)
			{
				$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}sts_tickets SET raum=%s WHERE id=%d", $raum, $lastid));
			}
			if($telefon != NULL)
			{
				$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}sts_tickets SET telefon=%s WHERE id=%d", $telefon, $lastid));
			}
			if($termin != NULL)
			{
				$time = strtotime($termin);
				$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}sts_tickets SET datepicker=%s WHERE id=%d", $termin, $lastid));
			}
			if($status != NULL)
			{
				$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}sts_tickets SET status='1' WHERE id=%d", $lastid));
			}			
		
	echo '<p>' . _e('Your problem has been successfully submitted.', 'simple-support-ticket-system') . '</p>';
	include_once(TS_DIR.'includes/create-link.php');
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