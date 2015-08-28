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
?>