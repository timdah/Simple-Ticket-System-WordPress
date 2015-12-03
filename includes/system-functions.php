<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function AJAX_callback() {
	include_once(TS_DIR.'includes/ajax.php');
	wp_die();
}
add_action( 'wp_ajax_nopriv_AJAX', 'AJAX_callback' );
add_action( 'wp_ajax_AJAX', 'AJAX_callback' );

function UPDATE_callback() {
	include_once(TS_DIR.'includes/update.php');
	wp_die();
}
add_action( 'wp_ajax_nopriv_UPDATE', 'UPDATE_callback' );
add_action( 'wp_ajax_UPDATE', 'UPDATE_callback' );

function LOGIN_callback() {
	include_once(TS_DIR.'includes/login.php');
	wp_die();
}
add_action( 'wp_ajax_nopriv_LOGIN', 'LOGIN_callback' );
add_action( 'wp_ajax_LOGIN', 'LOGIN_callback' );

function SYSTEM_callback() {
	include_once(TS_DIR.'includes/system.php');
	wp_die();
}
add_action( 'wp_ajax_nopriv_SYSTEM', 'SYSTEM_callback' );
add_action( 'wp_ajax_SYSTEM', 'SYSTEM_callback' );

function LOGOUT_callback() {
	include_once(TS_DIR.'includes/logout.php');
	wp_die();
}
add_action( 'wp_ajax_nopriv_LOGOUT', 'LOGOUT_callback' );
add_action( 'wp_ajax_LOGOUT', 'LOGOUT_callback' );

function COOKIE_callback() {
	setcookie("ts_username", $_POST["user"], 0, "/");
	wp_die();
}
add_action( 'wp_ajax_nopriv_COOKIE', 'COOKIE_callback' );
add_action( 'wp_ajax_COOKIE', 'COOKIE_callback' );

function ANSWER_callback() {
	include_once(TS_DIR.'includes/answer.php');
	wp_die();
}
add_action( 'wp_ajax_nopriv_ANSWER', 'ANSWER_callback' );
add_action( 'wp_ajax_ANSWER', 'ANSWER_callback' );
?>