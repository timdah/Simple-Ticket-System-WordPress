<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Um $wpdb nutzen zu können
global $wpdb;
?>
<h1 id="ts_head"><?php _e('User Administration', 'simple-support-ticket-system'); ?></h1>
<div id="ts_load"><div class="three-quarters-loader"></div></div>
<div id="ts_content">
	<div id="ajax_form">
		<form id="add_form" onsubmit="addUser();return false;">
			<?php _e('Your username (no umlauts):', 'simple-support-ticket-system'); ?><br>
			<input id="add_username" type="text" size="24" maxlength="50" name="username"><br><br>

			<?php _e('Your password:', 'simple-support-ticket-system'); ?><br>
			<input id="add_passwd" type="password" size="24" maxlength="50" name="passwort"><br>

			<?php _e('Repeat password:', 'simple-support-ticket-system'); ?><br>
			<input id="add_passwd2"type="password" size="24" maxlength="50" name="passwort2"><br><br>
			
			<?php _e('E-Mail:', 'simple-support-ticket-system'); ?><br>
			<input id="add_mail"type="email" size="24" maxlength="50" name="mail"><br><br>

			<!--?php _e('Salutation', 'simple-support-ticket-system'); ?>:<br>
			<input id="add_gender_male" type="radio" name="radio" value="Herr" checked><?php _e('Mr', 'simple-support-ticket-system'); ?><br>
			<input id="add_gender_female" type="radio" name="radio" value="Frau"><?php _e('Mrs', 'simple-support-ticket-system'); ?><br><br-->
			
			<?php _e('Last name:', 'simple-support-ticket-system'); ?><br>
			<input id="add_lastname" type="text" size="24" maxlength="30" name="name"><br><br>
			
			<?php _e('Admin:', 'simple-support-ticket-system'); ?>
			<input id="add_admin" type="checkbox" name="admin" value="1"><br>

			<p class="submit"><input class="button button-primary" type="submit" value="<?php _e('Add user', 'simple-support-ticket-system'); ?>"></input></p>
		</form>
	</div>
	<div id="ajax_users">
		<table id="ts_users">
	<?php
		$abfrage = $wpdb->get_results("SELECT id,username,name,admin FROM {$wpdb->prefix}sts_login");
		foreach($abfrage as $row) {
	?>  	<tr>
				<td><?php echo esc_html($row->name); ?> (<?php echo esc_html($row->username); ?>)</td>
				<td><?php if($row->admin == 1){echo 'admin';} ?></td>
				<td>
					<form onsubmit="deleteUser(<?php echo esc_html($row->id); ?>);return false;">
						<input class="button button-primary" type="submit" value="<?php _e('Delete', 'simple-support-ticket-system'); ?>"></input>
					</form>
				</td>
			 </tr>
	<?php
		}
	?>
		</table>
	</div>
</div>