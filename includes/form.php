<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
global $wpdb;

if(isset($_GET["tid"])) {
	include_once(TS_DIR.'includes/show-ticket.php');
} else {
	$prob = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'problem'");
	$opt1 = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'opt_field_1'");
	$opt2 = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'opt_field_2'");
	$opt3 = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'opt_field_3'");
	$datepicker = $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'datepicker'");
		
	if ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
		$form_name = $current_user->user_lastname . ', ' . $current_user->user_firstname;
		$form_mail = $current_user->user_email;
		$readonly = 'readonly';
	} else if (isset($_COOKIE["ts_username"])) {
		$user = $_COOKIE["ts_username"];
		$form_name = $wpdb->get_var("SELECT name FROM {$wpdb->prefix}sts_login WHERE username = '$user'");
		$form_mail = $wpdb->get_var("SELECT mail FROM {$wpdb->prefix}sts_login WHERE username = '$user'");
		$readonly = 'readonly';
	} else {
		$form_mail = '';
		$form_name = '';
		$readonly = '';
	}
	?>
	<div id="form_content">
		<div id="ts_load"><div class="three-quarters-loader"></div></div>
			<div id="ajax">
				<form id="ticket" onsubmit="submitTicket();return false;">
					<div>
						<span class="required2"><?php _e('Name:', 'simple-support-ticket-system'); ?></span>
						<input id="add_name" name="name" class="required" required="required" type="text" value="<?php echo $form_name; ?>" <?php echo $readonly; ?>/>
						<span class="required2"><?php _e('E-Mail:', 'simple-support-ticket-system'); ?></span>
						<input id="add_mail" name="mail" class="required" required="required" type="email" value="<?php echo $form_mail; ?>" <?php echo $readonly; ?>/>
						<?php if($opt1 != ''){ ?>
							<span><?php echo esc_html($opt1); ?>:</span>
							<input id="add_telefon" name="telefon" type="text" />
						<?php }
						if($opt2 != ''){ ?>
							<span><?php echo esc_html($opt2); ?>:</span>
							<input id="add_raum" name="raum" type="text" />
						<?php }
						if($opt3 != ''){ ?>
							<span><?php echo esc_html($opt3); ?>:</span>
							<input id="add_rechner" name="rechner" type="text" />
						<?php } ?>
					</div>
					<div>
						<span class="required2"><?php _e('Title:', 'simple-support-ticket-system'); ?></span>
						<input id="add_title" name="title" class="required" required="required" maxLength="50" type="text" />
						<span class="required2"><?php echo esc_html($prob); ?>:</span>
						<textarea id="add_problem" name="problem" class="required" required="required"></textarea>
						<?php if($datepicker != ''){ ?>
							<span><?php echo esc_html($datepicker) ?>:</span>
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
						<?php } 
						if($wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'check_mail'") == 1) {
						?>
						<div style="margin-bottom: 20px"><input type="checkbox" name="status" value="yes"> <?php _e('Status information by E-Mail', 'simple-support-ticket-system'); ?></input></div>
						<?php } ?>
						<input id="rcheck" type="text" name="rcheck" />
						<input type="submit" id="go" value="<?php _e('Submit', 'simple-support-ticket-system'); ?>" />
					</div>
				</form>
			</div>
	</div>
<?php
}
?>