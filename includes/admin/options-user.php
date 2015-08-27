<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
//Session Variablen aktivieren
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Um $wpdb nutzen zu können
global $wpdb;
?>
<h1 id="ts_head"><?php _e('User Administration', 'ticket-system-simple'); ?></h1>
<div id="ts_load"><div class="three-quarters-loader"></div></div>
<div id="ts_content">
	<div id="ajax_form">
		<form id="add_form" onsubmit="addUser();return false;">
			<?php _e('Your username (no umlauts)', 'ticket-system-simple'); ?>:<br>
			<input id="add_username" type="text" size="24" maxlength="50" name="username"><br><br>

			<?php _e('Your password', 'ticket-system-simple'); ?>:<br>
			<input id="add_passwd" type="password" size="24" maxlength="50" name="passwort"><br>

			<?php _e('Repeat password', 'ticket-system-simple'); ?>:<br>
			<input id="add_passwd2"type="password" size="24" maxlength="50" name="passwort2"><br><br>

			<?php _e('Salutation', 'ticket-system-simple'); ?>:<br>
			<input id="add_gender_male" type="radio" name="radio" value="Herr" checked><?php _e('Mr', 'ticket-system-simple'); ?><br>
			<input id="add_gender_female" type="radio" name="radio" value="Frau"><?php _e('Mrs', 'ticket-system-simple'); ?><br><br>
			
			<?php _e('Last name', 'ticket-system-simple'); ?>:<br>
			<input id="add_lastname" type="text" size="24" maxlength="30" name="name"><br><br>
			
			<?php _e('Admin', 'ticket-system-simple'); ?>:
			<input id="add_admin" type="checkbox" name="admin" value="1"><br>

			<p class="submit"><input class="button button-primary" type="submit" value="<?php _e('Add user', 'ticket-system-simple'); ?>"></input></p>
		</form>
	</div>
	<div id="ajax_users">
		<table id="ts_users">
	<?php
		$abfrage = $wpdb->get_results("SELECT id,username,name,admin FROM wp_sts_login");
		foreach($abfrage as $row) {
	?>  	<tr>
				<td><?php echo esc_html($row->name); ?> (<?php echo esc_html($row->username); ?>)</td>
				<td><?php if($row->admin == 1){echo 'admin';} ?></td>
				<td>
					<form onsubmit="deleteUser(<?php echo esc_html($row->id); ?>);return false;">
						<input class="button button-primary" type="submit" value="<?php _e('Delete', 'ticket-system-simple'); ?>"></input>
					</form>
				</td>
			 </tr>
	<?php
		}
	?>
		</table>
	</div>
</div>