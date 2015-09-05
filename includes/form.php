<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
global $wpdb;
	
if ( is_user_logged_in() ) {
	$current_user = wp_get_current_user();
	$form_name = $current_user->user_lastname . ', ' . $current_user->user_firstname;
	$form_mail = $current_user->user_email;
} else if (isset($_COOKIE["ts_username"])) {
	$user = $_COOKIE["ts_username"];
	$form_name = $wpdb->get_var("SELECT name FROM wp_sts_login WHERE username = '$user'");
	$form_mail = $wpdb->get_var("SELECT mail FROM wp_sts_login WHERE username = '$user'");
} else {
	$form_mail = '';
	$form_name = '';
}
?>
<div id="form_content">
	<div id="ts_load"><div class="three-quarters-loader"></div></div>
		<div id="ajax">
			<form id="ticket" onsubmit="submitTicket();return false;">
				<div>
					<span class="required2"><?php _e('Name:', 'simple-support-ticket-system'); ?></span>
					<input id="add_name" name="name" class="required" required="required" type="text" value="<?php echo $form_name; ?>"/>
					<span class="required2"><?php _e('E-Mail:', 'simple-support-ticket-system'); ?></span>
					<input id="add_mail" name="mail" class="required" required="required" type="email" value="<?php echo $form_mail; ?>"/>
					<span><?php _e('Optional Field 1:', 'simple-support-ticket-system'); ?></span>
					<input id="add_telefon" name="telefon" type="text" />
					<span><?php _e('Optional Field 2:', 'simple-support-ticket-system'); ?></span>
					<input id="add_raum" name="raum" type="text" />
					<span><?php _e('Optional Field 3:', 'simple-support-ticket-system'); ?></span>
					<input id="add_rechner" name="rechner" type="text" />
				</div>
				<div>
					<span class="required2"><?php _e('Title:', 'simple-support-ticket-system'); ?></span>
					<input id="add_title" name="title" class="required" required="required" maxLength="50" type="text" />
					<span class="required2"><?php _e('Problem:', 'simple-support-ticket-system'); ?></span>
					<textarea id="add_problem" name="problem" class="required" required="required"></textarea>
					<!--div style="margin-bottom: 20px"><input type="checkbox" name="status" value="yes"> <?php _e('Status information by E-Mail', 'simple-support-ticket-system'); ?></input></div-->
					<?php
					// Terminfeld wenn eingeloggt
					if(isset($_COOKIE["ts_username"]))
					{
					?>
						<span><?php _e('Appointment:', 'simple-support-ticket-system'); ?></span>
						<input id="add_termin" name="termin" type="text" type="text" maxLength="10" class="form-control">
						<script>
						jQuery(document).ready(function() {
							jQuery('.form-control').datepicker({
								//format: "dd.mm.yyyy",
								//language: "de",
								todayHighlight: true,
								autoclose: true
							});
						});
						</script>
					<?php
					}
					?>
					<input id="rcheck" type="text" name="rcheck" />
					<input type="submit" id="go" value="<?php _e('Report problem', 'simple-support-ticket-system'); ?>" />
				</div>
			</form>
		</div>
</div>
