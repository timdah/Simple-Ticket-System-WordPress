<?php
/*
Plugin Name: Simple Ticket System
Author: Tim Dahlmanns
Description: Simple and fast ticket system to receive and store Problems of Customers or Visitors, with a great search function.
Version: 1.0.0
Domain Path: /languages
Text Domain: ticket-system-simple
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
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$_SESSION["dir_url"] = plugin_dir_url(__FILE__);
$_SESSION["dir"] = plugin_dir_path(__FILE__);
$_SESSION["wp_dir"] = ABSPATH;
global $wpdb;


function myplugin_activate() {
    include_once($_SESSION["dir"].'includes/install.php');
}
register_activation_hook( __FILE__, 'myplugin_activate' );


/* CSS */
function css_include_function() {
	wp_enqueue_style( 'it', $_SESSION["dir_url"].'css/it.css' );
	wp_enqueue_style( 'problem', $_SESSION["dir_url"].'css/problem.css' );
	wp_enqueue_style( 'datepicker', $_SESSION["dir_url"].'css/datepicker.css' );
}
add_action( 'wp_enqueue_scripts', 'css_include_function' );


/* Shortcodes */
function form_func() {
	include_once($_SESSION["dir"].'includes/form.php');
	function js_include_function() {
		wp_enqueue_script( 'datepicker.js', $_SESSION["dir_url"].'js/bootstrap-datepicker.js' );
		wp_enqueue_script( 'datepicker.de.js', $_SESSION["dir_url"].'js/bootstrap-datepicker.de.js' );
	}
	add_action( 'wp_footer', 'js_include_function' );
	wp_enqueue_script( 'form.js', $_SESSION["dir_url"].'js/form.js', array('jquery') );
	wp_localize_script( 'form.js', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
}
add_shortcode( 'ts_form', 'form_func' );

function tickets_func() {
	include_once($_SESSION["dir"].'includes/system.php');
	function js_include_function() {
		wp_enqueue_script( 'datepicker.js', $_SESSION["dir_url"].'js/bootstrap-datepicker.js' );
		wp_enqueue_script( 'datepicker.de.js', $_SESSION["dir_url"].'js/bootstrap-datepicker.de.js' );
	}
	add_action( 'wp_footer', 'js_include_function' );
	wp_enqueue_script( 'ticket.js', $_SESSION["dir_url"].'js/ticket.js', array('jquery') );
	wp_localize_script( 'ticket.js', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
}
add_shortcode('ts_tickets', 'tickets_func');


/* Admin Options */

// Füge Menü hinzu
add_action( 'admin_menu', 'my_plugin_menu' );

// Ausgeführte funktion zum Menü hinzufügen
function my_plugin_menu() {
	$title = sprintf( __( 'User Administration', 'ticket-system-simple' ));
	$sub_name = sprintf( __( 'Users', 'ticket-system-simple' ));
	add_menu_page('Ticket System Options', 'Ticket System', 'manage_options', 'Ticket_Allgemein', 'my_plugin_options', 'dashicons-tickets-alt', 27.1);
	$GLOBALS['my_page'] = add_submenu_page( 'Ticket_Allgemein', $title, $sub_name, 'manage_options', 'my-unique-identifier', 'my_plugin_options' );
	remove_submenu_page('Ticket_Allgemein', 'Ticket_Allgemein');
	add_action('admin_enqueue_scripts', 'enqueue_admin_js');
}

// Lade Script wenn in Menü
function enqueue_admin_js($hook) {
	global $dir, $my_page;
	wp_enqueue_style( 'ts_admin', $_SESSION["dir_url"].'css/admin.css' );
	if($my_page === $hook) {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'options-user', $_SESSION["dir_url"].'js/options-user.js', array('jquery') );
		wp_localize_script( 'options-user', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
	}
}

// Inhalt der Menü Seite
function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	include_once($_SESSION["dir"].'includes/admin/options-user.php');
}

// Lade Übersetzungen
function myplugin_load_textdomain() {
  load_plugin_textdomain( 'ticket-system-simple', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_action( 'plugins_loaded', 'myplugin_load_textdomain' );



//********************* AJAX-Functions *********************
include_once($_SESSION["dir"].'includes/form-functions.php');
include_once($_SESSION["dir"].'includes/system-functions.php');
include_once($_SESSION["dir"].'includes/admin/options-user-functions.php');
?>
