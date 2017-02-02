<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Um $wpdb nutzen zu können
global $wpdb;

$prob = sanitize_text_field($_POST["problem"]);
checkText($prob);

$opt1 = sanitize_text_field($_POST["opt1"]);
$opt2 = sanitize_text_field($_POST["opt2"]);
$opt3 = sanitize_text_field($_POST["opt3"]);
$datepicker = sanitize_text_field($_POST["datepicker"]);
$take = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['take'] ) ) );
$done = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['done'] ) ) );
$answer = implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['answer'] ) ) );
if(isset($_POST["link_mail"])){$link_mail = sanitize_text_field($_POST["link_mail"]);} else {$link_mail = '0';}



$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}sts_options SET ts_value = CASE 
    WHEN ts_option = 'problem' THEN %s
	WHEN ts_option = 'opt_field_1' THEN %s
    WHEN ts_option = 'opt_field_2' THEN %s
    WHEN ts_option = 'opt_field_3' THEN %s
    WHEN ts_option = 'datepicker' THEN %s
    WHEN ts_option = 'mail_take' THEN %s
    WHEN ts_option = 'mail_done' THEN %s
    WHEN ts_option = 'mail_answer' THEN %s
	WHEN ts_option = 'link_mail' THEN %s
	ELSE ts_value
END", $prob, $opt1, $opt2, $opt3, $datepicker, $take, $done, $answer, $link_mail));

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
?> 