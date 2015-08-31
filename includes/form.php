<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
?>
<div id="form_content">
	<div id="ts_load"><div class="three-quarters-loader"></div></div>
		<div id="ajax">
			<form id="ticket" onsubmit="submitTicket();return false;">
				<div>
					<span class="required2"><?php _e('Name', 'ticket-system-simple'); ?>:</span>
					<input id="add_name" name="name" class="required" required="required" type="text" />
					<span class="required2"><?php _e('E-Mail', 'ticket-system-simple'); ?>:</span>
					<input id="add_mail" name="mail" class="required" required="required" type="email" />
					<span><?php _e('Optional Field 1', 'ticket-system-simple'); ?>:</span>
					<input id="add_telefon" name="telefon" type="text" />
					<span><?php _e('Optional Field 2', 'ticket-system-simple'); ?>:</span>
					<input id="add_raum" name="raum" type="text" />
					<span><?php _e('Optional Field 3', 'ticket-system-simple'); ?>:</span>
					<input id="add_rechner" name="rechner" type="text" />
				</div>
				<div>
					<span class="required2"><?php _e('Problem', 'ticket-system-simple'); ?>:</span>
					<textarea id="add_problem" name="problem" class="required" required="required"></textarea>
					<!--div style="margin-bottom: 20px"><input type="checkbox" name="status" value="yes"> <?php _e('Status information by E-Mail', 'ticket-system-simple'); ?></input></div-->
					<?php
					// Terminfeld wenn eingeloggt
					if(isset($_COOKIE["username"]))
					{
					?>
						<span><?php _e('Appointment', 'ticket-system-simple'); ?>:</span>
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
					<input type="submit" id="go" value="<?php _e('Report problem', 'ticket-system-simple'); ?>" />
				</div>
			</form>
		</div>
</div>
