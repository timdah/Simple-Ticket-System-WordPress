<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function ADD_callback() {
	include_once(TS_DIR.'includes/admin/add-user.php');
	wp_die();
}
add_action( 'wp_ajax_ADD', 'ADD_callback' );

function DELETE_callback() {
	include_once(TS_DIR.'includes/admin/delete-user.php');
	wp_die();
}
add_action( 'wp_ajax_DELETE', 'DELETE_callback' );

function USERS_callback() {
	include_once(TS_DIR.'includes/admin/options-user.php');
	wp_die();
}
add_action( 'wp_ajax_USERS', 'USERS_callback' );

function SAVE_callback() {
	include_once(TS_DIR.'includes/admin/general-save.php');
	wp_die();
}
add_action( 'wp_ajax_SAVE', 'SAVE_callback' );

function GENERAL_LOAD_callback() {
	include_once(TS_DIR.'includes/admin/options-general.php');
	wp_die();
}
add_action( 'wp_ajax_GENERAL_LOAD', 'GENERAL_LOAD_callback' );

function MAIL_ACDE_callback() {
	include_once(TS_DIR.'includes/admin/activate-mail.php');
	wp_die();
}
add_action( 'wp_ajax_MAIL_ACDE', 'MAIL_ACDE_callback' );

function TEST_MAIL_callback() {
	$current_user = wp_get_current_user();
	$mail = $current_user = $current_user->user_email;
	$url = TS_DIR_URL.'includes/status.php';
	$data = array('what' => 'test', 'mail' => $mail);
	//open connection
	$ch = curl_init();
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, 1);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
	curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000);
	$result = curl_exec($ch);
	//close connection
	curl_close($ch);
}
add_action( 'wp_ajax_TEST_MAIL', 'TEST_MAIL_callback' );

?>