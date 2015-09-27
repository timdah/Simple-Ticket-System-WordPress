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
?>