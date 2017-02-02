<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Um $wpdb nutzen zu können
global $wpdb;

$prob = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'problem'");
$opt1 = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'opt_field_1'");
$opt2 = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'opt_field_2'");
$opt3 = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'opt_field_3'");
$datepicker = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'datepicker'");
$take = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'mail_take'");
$done = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'mail_done'");
$answer = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'mail_answer'");
$link_mail = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'link_mail'");
?>
<h1 id="ts_head"><?php _e('General Options', 'simple-support-ticket-system'); ?></h1>
<div id="ts_load"><div class="three-quarters-loader"></div></div>
<div id="ts_content">
	<form id="add_form" onsubmit="addChanges();return false;">
		<div id="user_guide">
			<h1><?php _e('User Guide', 'simple-support-ticket-system'); ?></h1>
			<p><?php _e('First of all you have to create two new Pages or Posts. One for the ticket submition and one for the system to manage the tickets.
				Now, give them the titles you want and set the following content:', 'simple-support-ticket-system'); ?>
			</p>
			<p style="padding-left: 20px"><b>[ts_form] </b><?php _e('on the page for the ticket submition', 'simple-support-ticket-system'); ?></p>
			<p style="padding-left: 20px"><b>[ts_tickets] </b><?php _e('on the page for the ticket system', 'simple-support-ticket-system'); ?></p>
			<p><?php _e("That's all! To manage tickets you have to be logged in into Wordpress or with accounts you can create under 'Support System -> Users'", 'simple-support-ticket-system'); ?></p>
			<p><?php _e('Hover over buttons to get a short description of their function.', 'simple-support-ticket-system'); ?></p>
			<p><?php _e('If there are other things unclear, feel free to ask me in the support section :)', 'simple-support-ticket-system'); ?></p>
		</div>
		<hr>
		<h1 style="margin-top: 50px"><?php _e('Field Names', 'simple-support-ticket-system'); ?></h1>
		<table>
			<tr>
				<td>
					<?php _e('Title Problem Field:', 'simple-support-ticket-system'); ?>
				</td>
				<td>
					<input id="add_problem" type="text" size="32" maxlength="50" name="opt1" value="<?php echo $prob; ?>">
				</td>
			<tr>
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
		<h3><?php _e('An empty Title will hide this Field in the Submit-Form', 'simple-support-ticket-system'); ?></h3>
		<hr>
		<div id="mail">
			<h1><?php _e('E-Mail Notification', 'simple-support-ticket-system'); ?></h1>
			<h3><?php _e('This function only works, if your server has a correct mail configuration', 'simple-support-ticket-system'); ?></h3>
			<div id="mail_enable">
				<p>
				<?php
				$check = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'check_mail'");
				if($check == 0) {
				?>
					<button type="button" id="change_mail" onclick="activeDeactive()" class="button button-primary"><?php _e('Activate', 'simple-support-ticket-system'); ?></button>
				<?php
				} else {
				?>
					<button type="button" id="change_mail" onclick="activeDeactive()" class="button button-primary"><?php _e('Deactivate', 'simple-support-ticket-system'); ?></button>
				<?php 
				} 
				?>
				<button type="button" onclick="testMail()" class="button button-primary"><?php _e('Test Server *', 'simple-support-ticket-system'); ?></button><br>
				<?php _e('* Check your server by sending a Test-Mail to your mail address of this wordpress account', 'simple-support-ticket-system'); ?>
				</p>
				<?php
				if($check != 0) {
				?>
				<div id="enable_check">
					<div>
						<?php _e('Ticket-Link in E-Mails', 'simple-support-ticket-system'); ?>
						<input <?php if($link_mail == 1){echo "checked";} ?> type="checkbox" name="link_mail" value="1" />
					</div>
					<div class="field">
						<?php _e('Content of mail when ticket was taken:', 'simple-support-ticket-system'); ?>
						<textarea maxLength="1000" id="take" type="text" required="required"><?php echo $take; ?></textarea>
					</div>
					<div class="field">
						<?php _e('Content of mail when ticket is done:', 'simple-support-ticket-system'); ?>
						<textarea maxLength="1000" id="done" type="text" required="required"><?php echo $done; ?></textarea>
					</div>
					<div class="field">
						<?php _e('Content of mail after a new answer:', 'simple-support-ticket-system'); ?>
						<textarea maxLength="1000" id="answer" type="text" required="required"><?php echo $answer; ?></textarea>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
		<hr>
		<p class="submit"><input class="button button-primary" type="submit" value="<?php _e('Save Settings', 'simple-support-ticket-system'); ?>"></input></p>
	</form>
</div>