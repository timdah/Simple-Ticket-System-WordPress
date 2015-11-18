<?php
/*
Plugin Name: Support Ticket System
Plugin URI: http://en00x.github.io/Simple-Ticket-System-WordPress/
Author: Tim Dahlmanns
Description: Simple and fast ticket system to receive and store Problems of Customers or Visitors, with a great search function.
Version: 1.2.2
Domain Path: /languages
Text Domain: simple-support-ticket-system
License: GPL2

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program. If not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Copyright 2015 Tim Dahlmanns
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// define constants
define( 'TS_DIR', plugin_dir_path( __FILE__ ) );
define( 'TS_DIR_URL', plugin_dir_url(__FILE__) );
define( 'DB_VERSION', 103 );

// Use Wordpress Database
global $wpdb;

// Installation
function myplugin_activate() {
    include_once(TS_DIR.'includes/install.php');
}
register_activation_hook( __FILE__, 'myplugin_activate' );

// Update
function db_version_update() {
	if( is_admin() ) {
		include_once(TS_DIR.'includes/db-update.php');
	}
}
add_action( 'init', 'db_version_update' );


/* CSS */
function css_include_function() {
	wp_enqueue_style( 'it', TS_DIR_URL.'css/ts_system.css' );
	wp_enqueue_style( 'problem', TS_DIR_URL.'css/ts_form.css' );
	wp_enqueue_style( 'datepicker', TS_DIR_URL.'css/datepicker.css' );
}
add_action( 'wp_enqueue_scripts', 'css_include_function' );


/* Shortcodes */

// Login check
function login_check() {
	if ( is_user_logged_in() ) {
		if(!isset($_COOKIE["ts_username"])) {
			$current_user = wp_get_current_user();
			$user = '[WP]' . $current_user->user_login;
			wp_enqueue_script( 'cookie.js', TS_DIR_URL.'js/cookie.js', array('jquery') );
			echo "<script>
				jQuery(document).ready(function() {
					setcookie('" . $user . "');
				});</script>";
		}
	}
}

// [ts_form]
function form_func() {
	login_check();
	include_once(TS_DIR.'includes/form.php');
	function js_include_function() {
		wp_enqueue_script( 'datepicker.js', TS_DIR_URL.'js/bootstrap-datepicker.js' );
		wp_enqueue_script( 'datepicker.de.js', TS_DIR_URL.'js/bootstrap-datepicker.de.js' );
	}
	add_action( 'wp_footer', 'js_include_function' );
	wp_enqueue_script( 'form.js', TS_DIR_URL.'js/form.js', array('jquery') );
	wp_localize_script( 'form.js', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
}
add_shortcode( 'ts_form', 'form_func' );

// [ts_tickets]
function tickets_func() {
	login_check();
	include_once(TS_DIR.'includes/system.php');
	function js_include_function() {
		wp_enqueue_script( 'datepicker.js', TS_DIR_URL.'js/bootstrap-datepicker.js' );
		wp_enqueue_script( 'datepicker.de.js', TS_DIR_URL.'js/bootstrap-datepicker.de.js' );
	}
	add_action( 'wp_footer', 'js_include_function' );
	wp_enqueue_script( 'ticket.js', TS_DIR_URL.'js/ticket.js', array('jquery') );
	wp_localize_script( 'ticket.js', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
}
add_shortcode('ts_tickets', 'tickets_func');


/* Admin Options */

// Füge Menü hinzu
add_action( 'admin_menu', 'my_plugin_menu' );

// Ausgeführte funktion zum Menü hinzufügen
function my_plugin_menu() {
	$users_title = sprintf( __( 'User Administration', 'simple-support-ticket-system' ));
	$users_sub_name = sprintf( __( 'Users', 'simple-support-ticket-system' ));
	$general_title = sprintf( __( 'General Options', 'simple-support-ticket-system' ));
	$general_sub_name = sprintf( __( 'General', 'simple-support-ticket-system' ));
	add_menu_page('Ticket System Options', 'Ticket System', 'manage_options', 'Ticket_Allgemein', 'my_plugin_options', 'dashicons-tickets-alt', 27.1);
	$GLOBALS['page_general'] = add_submenu_page( 'Ticket_Allgemein', $general_title, $general_sub_name, 'manage_options', 'general-unique-identifier', 'general_plugin_options' );
	$GLOBALS['page_users'] = add_submenu_page( 'Ticket_Allgemein', $users_title, $users_sub_name, 'manage_options', 'users-unique-identifier', 'users_plugin_options' );
	remove_submenu_page('Ticket_Allgemein', 'Ticket_Allgemein');
	add_action('admin_enqueue_scripts', 'enqueue_admin_js');
}

// Lade Script wenn in Menü
function enqueue_admin_js($hook) {
	global $dir, $page_users, $page_general;
	if($page_users === $hook) {
		wp_enqueue_style( 'ts_users', TS_DIR_URL.'css/admin_users.css' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'options-user', TS_DIR_URL.'js/options-user.js', array('jquery') );
		wp_localize_script( 'options-user', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
	}	
	if($page_general === $hook) {
		wp_enqueue_style( 'ts_general', TS_DIR_URL.'css/admin_general.css' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'options-general', TS_DIR_URL.'js/options-general.js', array('jquery') );
		wp_localize_script( 'options-general', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
	}
}

// Inhalt der Menü Seite Users
function users_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include_once(TS_DIR.'includes/admin/options-user.php');
}

// Inhalt der Menü Seite General
function general_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include_once(TS_DIR.'includes/admin/options-general.php');
}

// Lade Übersetzungen
function myplugin_load_textdomain() {
  load_plugin_textdomain( 'simple-support-ticket-system', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_action( 'plugins_loaded', 'myplugin_load_textdomain' );



//********************* AJAX-Functions *********************
include_once(TS_DIR.'includes/form-functions.php');
include_once(TS_DIR.'includes/system-functions.php');
include_once(TS_DIR.'includes/admin/options-functions.php');
?>