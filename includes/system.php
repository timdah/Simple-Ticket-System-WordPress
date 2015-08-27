<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
//Session Variablen aktivieren
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$dir_url = $_SESSION["dir_url"];
$date = date(get_option('date_format'));
$timestamp = strtotime($date);
global $wpdb;


//Wenn die Session-Variable 'username' nicht gesetzt ist
if(!isset($_SESSION["username"]))
	{
?>
	<div id="ts_content">
		<div id="ts_load" style="width:60%;"><div class="three-quarters-loader"></div></div>
		<form id="ticket" onsubmit="login();return false;">
			<div>
				<span><?php _e('Username', 'ticket-system-simple'); ?></span>
				<input id="login_name" type="text" maxlength="50" name="username" autofocus style="text-transform: lowercase;">
				<span><?php _e('Password', 'ticket-system-simple'); ?></span>
				<input id="login_passwort" type="password" maxlength="50" name="password">
				<input id="go" type="submit" value="Login">
			</div>
		</form>
	</div>
		<?php
	}
else {
	$user = $_SESSION["username"];
?>
<div id="ts_content">
	<div id="nav">
		<!-- Buttons rufen verschiedene JavaScript Funktionen auf & übergeben Werte an diese -->
		<a href="javascript:allTickets('new');startInterval();indicator('0%');buttonCheck('1')"><?php _e('New Tickets', 'ticket-system-simple'); ?></a>
		<a href="javascript:allTickets('my');stopInterval();indicator('26%');buttonCheck('0')"><span><?php _e('My Tickets', 'ticket-system-simple'); ?></span></a>
		<a href="javascript:allTickets('open');stopInterval();indicator('52%');buttonCheck('0')"><span><?php _e('Open Tickets', 'ticket-system-simple'); ?></span></a>
		<a id="filter" href="javascript:expand();buttonCheck('0')"><span><?php _e('Filter', 'ticket-system-simple'); ?></span><div id="pseudo"></div></a>
		<div id="logout" onClick="javascript:logout();"></div>
	</div>
	<div style="width: calc(100% - 60px);">
		<div id="suche">
			<form onsubmit="filter();indicator('78%');stopInterval();return false">
				<select id="select">
					<option value="bearbeiter"><?php _e('Issuer', 'ticket-system-simple'); ?></option>
					<option value="mail"><?php _e('E-Mail', 'ticket-system-simple'); ?></option>
					<option selected value="geloest"><?php _e('Done', 'ticket-system-simple'); ?></option>
					<option value="loesung"><?php _e('Solution', 'ticket-system-simple'); ?></option>
					<option value="name"><?php _e('Name', 'ticket-system-simple'); ?></option>
					<option value="problem"><?php _e('Problem', 'ticket-system-simple'); ?></option>
					<option value="termin"><?php _e('Appointment', 'ticket-system-simple'); ?></option>
				</select>
				<input id="search" type="text" maxlength="50" name="search" onblur="this.placeholder = '<?php _e('contains', 'ticket-system-simple'); ?>...'" onfocus="this.placeholder = ''" placeholder="<?php _e('contains', 'ticket-system-simple'); ?>...">					
				<select id="order">
					<option value="DESC"><?php _e('Newest first', 'ticket-system-simple'); ?></option>
					<option value="ASC"><?php _e('Oldest first', 'ticket-system-simple'); ?></option>
				</select> 
				<button  class="button" type="button" onClick="javascript:filter();indicator('78%');stopInterval();"><?php _e('Apply filter', 'ticket-system-simple'); ?></button>
			</form>
		</div>
	</div>
	<div id="indicator"><div></div></div>
	<div class="spinner">
		<div id="ts_load"><div class="three-quarters-loader"></div></div>
	</div>	
	<div id="ajax">
		<?php
		//Verbindung zur Datenbank		
		//Abfrage der eigenen Tickets
		$ticket = $wpdb->get_results("SELECT * FROM wp_sts_tickets WHERE geloest='0' AND bearbeiter='$user' AND termin_timestamp<'$timestamp'
					UNION
				   SELECT * FROM wp_sts_tickets WHERE geloest='0' AND bearbeiter='$user' AND termin_timestamp='$timestamp'
					UNION
				   SELECT * FROM wp_sts_tickets WHERE geloest='0' AND bearbeiter='$user' AND termin IS NULL
					UNION
				   SELECT * FROM wp_sts_tickets WHERE geloest='0' AND bearbeiter='$user' AND termin_timestamp>'$timestamp' ");
		foreach($ticket as $row)
		{
		?>
			<div id="<?php echo esc_attr($row->id); ?>" class="query">
				<table class="ticket">
					<tr>
						<td>
						<?php
							echo esc_html($row->zeit);
						?>
						</td>
						<td style="text-align:right">
						<?php
							if(isset($row->termin))
							{
								_e('Appointment', 'ticket-system-simple');
								echo ": <b>" . esc_html($row->termin) . "</b>";
							}
						?>
						</td>
					</tr>
					<tr>
						<td>
							<?php 
								echo "<b>" . esc_html($row->name) . "</b>";
							?>							
						</td>
						<td rowspan="6">
							<?php 
								echo nl2br(esc_html($row->problem));
							?>
						</td>
					</tr>
					<tr>
						<td class="smaller">
							<?php 
								echo '<a href="mailto:' . esc_html($row->mail) . '">' . esc_html($row->mail) . '</a>';
							?>							
						</td>
					</tr>
					<tr>
						<td class="smaller">
							<?php 
								echo esc_html($row->telefon);
							?>							
						</td>
					</tr>
					<tr>
						<td class="smaller">
							<?php 
								echo esc_html($row->raum);
							?>							
						</td>
					</tr>
					<tr>
						<td class="smaller">
							<?php 
								echo esc_html($row->rechner);
							?>							
						</td>
					</tr>
					<tr>
						<td></td>
					</tr>
					<?php if($row->bemerkung != NULL){ ?>
					<tr>
						<td class="b_top"><?php _e('Note', 'ticket-system-simple'); ?></td>
						<td class="b_top"><?php echo nl2br(esc_html($row->bemerkung)); ?></td>
					</tr>
					<?php } ?>
					<?php if($row->loesung != NULL){ ?>
					<tr>
						<td class="b_top"><?php _e('Solution', 'ticket-system-simple'); ?></td>
						<td class="b_top"><?php echo nl2br(esc_html($row->loesung)); ?></td>
					</tr>
					<?php } ?>
				</table>
				<div class="update">
				<script>jQuery(document).ready(function(){textarea('<?php echo esc_html($row->id); ?>');});</script>
					<form onsubmit="return false">
						<table style="width: 100%">
							<tr style="width: 100%">
								<td class="textarea" rowspan="2">
									<textarea maxLength="500" class="update_text" type="text" required="required"><?php echo esc_textarea($row->bemerkung); ?></textarea>
								</td>
								<td>
									<select class="select_2">
										<option value="loesung"><?php _e('Solution', 'ticket-system-simple'); ?></option>
										<option selected value="bemerkung"><?php _e('Note', 'ticket-system-simple'); ?></option>
										<option value="problem"><?php _e('Problem', 'ticket-system-simple'); ?></option>
										<option value="termin"><?php _e('Appointment', 'ticket-system-simple'); ?></option>
										<?php if($_SESSION["admin"] == 1) { ?>
											<option value="bearbeiter"><?php _e('Issuer', 'ticket-system-simple'); ?></option>
										<?php } ?>
									</select>	
								</td>
							</tr>
							<tr>
								<td>
									<button class="button" type="button" onClick="javascript:update('<?php echo esc_html($row->id); ?>')"><?php _e('Insert', 'ticket-system-simple'); ?></button>
								</td>
							</tr>		
					</table>
					</form>
				</div>
				<div class="done" onClick="javascript:done('<?php echo esc_html($row->id); ?>')" style="background-image: url('<?php echo $dir_url; ?>img/done.png')"></div>
				<div style="width: 100%; display:flex; justify-content:center;">
					<div class="expand" onClick="javascript:expand2('<?php echo esc_html($row->id); ?>')" style="background-image: url('<?php echo $dir_url; ?>img/expand.png')"></div>
				</div>
				<?php
				if($row->bearbeiter != "unbekannt" && strtotime($row->termin) == strtotime($date) && $row->geloest == '0')
				{
				?>
					<div class="today_text">
						<p><span style="text-transform: uppercase;"><?php _e('today', 'ticket-system-simple'); ?></span></p>
					</div>
				<?php
				}
				if($row->bearbeiter != "unbekannt" && $row->termin != '' && strtotime($row->termin) < strtotime($date) && $row->geloest == '0')
				{
				?>
					<div class="warn_text">
						<p><span style="text-transform: uppercase;"><?php _e('overdue', 'ticket-system-simple'); ?></span></p>
					</div>
				<?php
				}
				if($row->termin != '' && strtotime($row->termin) > strtotime($date) && $row->geloest == '0')
				{
				?>
					<div class="until_text">
						<p>
								<?php
									$days = (strtotime($row->termin) - strtotime($date)) / 86400;
									if($days === 1)
									{
										_e('TOMORROW', 'ticket-system-simple');
									} else {
										printf(__('Appointment in %s days', 'ticket-system-simple'), $days);
									}							
								?> 
						</p>
					</div>
				<?php
				}
				?>
			</div>
		 <?php
		}
		 ?>
	</div>
</div>
<?php } ?>	
