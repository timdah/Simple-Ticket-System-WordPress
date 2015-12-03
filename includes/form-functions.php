<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function MAIL_callback() {
	include_once(TS_DIR.'includes/mail.php');
	wp_die();
}
add_action( 'wp_ajax_nopriv_MAIL', 'MAIL_callback' );
add_action( 'wp_ajax_MAIL', 'MAIL_callback' );

function FORM_callback() {
	include_once(TS_DIR.'includes/form.php');
	wp_die();
}
add_action( 'wp_ajax_nopriv_FORM', 'FORM_callback' );
add_action( 'wp_ajax_FORM', 'FORM_callback' );

function SHOW_callback() {
	include_once(TS_DIR.'includes/show-ticket.php');
	wp_die();
}
add_action( 'wp_ajax_nopriv_SHOW', 'SHOW_callback' );
add_action( 'wp_ajax_SHOW', 'SHOW_callback' );
?>