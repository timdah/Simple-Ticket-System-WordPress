<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Um $wpdb nutzen zu können
global $wpdb;

$opt1 = $wpdb->get_var("SELECT ts_value FROM wp_sts_options WHERE ts_option = 'opt_field_1'");
$opt2 = $wpdb->get_var("SELECT ts_value FROM wp_sts_options WHERE ts_option = 'opt_field_2'");
$opt3 = $wpdb->get_var("SELECT ts_value FROM wp_sts_options WHERE ts_option = 'opt_field_3'");
$datepicker = $wpdb->get_var("SELECT ts_value FROM wp_sts_options WHERE ts_option = 'datepicker'");
?>
<h1 id="ts_head"><?php _e('General Options', 'simple-support-ticket-system'); ?></h1>
<div id="ts_load"><div class="three-quarters-loader"></div></div>
<div id="ts_content">
	<form id="add_form" onsubmit="addChanges();return false;">
		<table>
			<tr>
				<td>
					<?php _e('Title Optional Field 1:', 'simple-support-ticket-system'); ?>
				</td>
				<td>
					<input id="add_opt1" type="text" size="32" maxlength="50" name="opt1" value="<?php echo $opt1; ?>">
				</td>
			<tr>			
			<tr>
				<td>
					<?php _e('Title Optional Field 2:', 'simple-support-ticket-system'); ?>
				</td>
				<td>
					<input id="add_opt2" type="text" size="32" maxlength="50" name="opt2" value="<?php echo $opt2; ?>">
				</td>
			</tr>			
			<tr>
				<td>
					<?php _e('Title Optional Field 3:', 'simple-support-ticket-system'); ?>
				</td>
				<td>
					<input id="add_opt3" type="text" size="32" maxlength="50" name="opt3" value="<?php echo $opt3; ?>">
				</td>
			</tr>
			<tr>
				<td>
					<?php _e('Title Datepicker Field:', 'simple-support-ticket-system'); ?>
				</td>
				<td>
					<input id="add_datepicker"type="text" size="32" maxlength="50" name="datepick" value="<?php echo $datepicker; ?>">
				</td>
			</tr>
		</table>
		<hr>
		<h3><?php _e('An empty Title will hide this Field in the Submit-Form', 'simple-support-ticket-system'); ?></h3>
		<p class="submit"><input class="button button-primary" type="submit" value="<?php _e('Save Settings', 'simple-support-ticket-system'); ?>"></input></p>
	</form>
</div>