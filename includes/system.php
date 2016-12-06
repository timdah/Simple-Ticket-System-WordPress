<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$dir_url = TS_DIR_URL;
$date = date(get_option('date_format'));
$timestamp = strtotime($date);
global $wpdb;

//Wenn die Session-Variable 'username' nicht gesetzt ist
if(!isset($_COOKIE["ts_username"]))
	{
?>
	<div id="ts_content">
	<?php
		if ( is_user_logged_in() ) {
			_e('Please wait, you are already logged in.', 'simple-support-ticket-system');
		}
	?>
		<div id="ts_load" style="width:60%;"><div class="three-quarters-loader"></div></div>
		<form id="ticket" onsubmit="login();return false;">
			<div>
				<span><?php _e('Username', 'simple-support-ticket-system'); ?></span>
				<input id="login_name" type="text" maxlength="50" name="username" autofocus style="text-transform: lowercase;">
				<span><?php _e('Password', 'simple-support-ticket-system'); ?></span>
				<input id="login_passwort" type="password" maxlength="50" name="password">
				<input id="go" type="submit" value="Login">
			</div>
		</form>
	</div>
		<?php
	}
else {
	$user = $_COOKIE["ts_username"];
?>
<div id="ts_content">
	<div id="nav">
		<!-- Buttons rufen verschiedene JavaScript Funktionen auf & übergeben Werte an diese -->
		<a href="javascript:allTickets('new');startInterval();indicator('0%');buttonCheck('1')"><?php _e('New Tickets', 'simple-support-ticket-system'); ?></a>
		<a href="javascript:allTickets('my');stopInterval();indicator('26%');buttonCheck('0')"><span><?php _e('My Tickets', 'simple-support-ticket-system'); ?></span></a>
		<a href="javascript:allTickets('open');stopInterval();indicator('52%');buttonCheck('0')"><span><?php _e('Open Tickets', 'simple-support-ticket-system'); ?></span></a>
		<a id="filter" href="javascript:expand();buttonCheck('0')"><span><?php _e('Filter', 'simple-support-ticket-system'); ?></span><div id="pseudo"></div></a>
		<div id="logout" title="<?php _e('Logout', 'simple-support-ticket-system'); ?>" onClick="javascript:logout();"></div>
	</div>
	<div style="width: calc(100% - 60px);">
		<div id="suche">
			<form onsubmit="filter();indicator('78%');stopInterval();return false">
				<select id="select">
					<option value="bearbeiter"><?php _e('Issuer', 'simple-support-ticket-system'); ?></option>
					<option value="mail"><?php _e('E-Mail', 'simple-support-ticket-system'); ?></option>
					<option selected value="geloest"><?php _e('Done', 'simple-support-ticket-system'); ?></option>
					<option value="loesung"><?php _e('Solution', 'simple-support-ticket-system'); ?></option>
					<option value="name"><?php _e('Name', 'simple-support-ticket-system'); ?></option>
					<option value="problem"><?php _e('Problem', 'simple-support-ticket-system'); ?></option>
					<option value="termin"><?php _e('Appointment', 'simple-support-ticket-system'); ?></option>
				</select>
				<input id="search" type="text" maxlength="50" name="search" onblur="this.placeholder = '<?php _e('contains', 'simple-support-ticket-system'); ?>...'" onfocus="this.placeholder = ''" placeholder="<?php _e('contains', 'simple-support-ticket-system'); ?>...">					
				<select id="order">
					<option value="DESC"><?php _e('Newest first', 'simple-support-ticket-system'); ?></option>
					<option value="ASC"><?php _e('Oldest first', 'simple-support-ticket-system'); ?></option>
				</select> 
				<button  class="button" type="button" onClick="javascript:filter();indicator('78%');stopInterval();"><?php _e('Apply filter', 'simple-support-ticket-system'); ?></button>
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
		$ticket = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='0' AND bearbeiter=%s AND termin_timestamp<%d
					UNION
				   SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='0' AND bearbeiter=%s AND termin_timestamp=%d
					UNION
				   SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='0' AND bearbeiter=%s AND termin IS NULL
				    UNION
				   SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='0' AND bearbeiter=%s AND termin_timestamp>%d ",
				   $user, $timestamp, $user, $timestamp, $user, $user, $timestamp));
		foreach($ticket as $row)
		{
		?>
			<div id="<?php echo esc_attr($row->id); ?>" class="query">
				<div class="ts_title">
					<div>
					<?php
						echo esc_html($row->title);
					?>
					</div>
				</div>
				<table class="ticket">
					<tr>
						<td>
						<?php
							echo esc_html($row->zeit);
						?>
						</td>
						<td style="text-align:right">
						<?php
							if(isset($row->datepicker))
							{
								echo $wpdb->get_var("SELECT ts_value FROM {$wpdb->prefix}sts_options WHERE ts_option = 'datepicker'");
								echo ": <b>" . esc_html($row->datepicker) . "</b>";
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
						<td class="b_top"><?php _e('Note', 'simple-support-ticket-system'); ?></td>
						<td class="b_top"><?php echo nl2br(esc_html($row->bemerkung)); ?></td>
					</tr>
					<?php } ?>
					<?php if($row->loesung != NULL){ ?>
					<tr>
						<td class="b_top"><?php _e('Solution', 'simple-support-ticket-system'); ?></td>
						<td class="b_top"><?php echo nl2br(esc_html($row->loesung)); ?></td>
					</tr>
					<?php } ?>
				</table>
				<table class="answers">
					<?php
					$answers = $wpdb->get_results($wpdb->prepare("SELECT antwort, user FROM {$wpdb->prefix}sts_answers WHERE ticket_id = %d ORDER BY index_antwort ASC", $row->id));
					foreach($answers as $query) {
						?>
						<tr <?php if($query->user != NULL) { ?> class="admin" <?php } ?>>
							<td>
								<b><i>
								<?php if($query->user != NULL) {
										echo '<span style="color:#e1550a">' . esc_html($query->user) . '</span> ';
									  } else {
										echo esc_html($row->name) . ' ';
									  }
									  _e('says', 'simple-support-ticket-system');
								?>:</i></b><br>
								<?php echo nl2br(esc_html($query->antwort)); ?>
							</td>
						</tr>
						<?php
					}
					?>
				</table>
				<div class="update">
				<script>jQuery(document).ready(function(){textarea('<?php echo esc_html($row->id); ?>');});</script>
					<form onsubmit="return false">
						<table style="width: 100%">
							<tr style="width: 100%">
								<td class="textarea" rowspan="2">
									<textarea maxLength="500" class="update_text" type="text" required="required"></textarea>
								</td>
								<td>
									<select class="select_2">
										<option value="loesung"><?php _e('Solution', 'simple-support-ticket-system'); ?></option>
										<option value="bemerkung"><?php _e('Note', 'simple-support-ticket-system'); ?></option>
										<option selected value="antwort"><?php _e('Answer', 'simple-support-ticket-system'); ?></option>
										<option value="problem"><?php _e('Problem', 'simple-support-ticket-system'); ?></option>
										<option value="termin"><?php _e('Appointment', 'simple-support-ticket-system'); ?></option>
										<?php if($_COOKIE["ts_admin"] == 1) { ?>
											<option value="bearbeiter"><?php _e('Issuer', 'simple-support-ticket-system'); ?></option>
										<?php } ?>
									</select>	
								</td>
							</tr>
							<tr>
								<td>
									<button class="button" type="button" onClick="javascript:update('<?php echo esc_html($row->id); ?>')"><?php _e('Insert', 'simple-support-ticket-system'); ?></button>
								</td>
							</tr>		
					</table>
					</form>
				</div>
				<div class="done" title="<?php _e('Finish this ticket', 'simple-support-ticket-system'); ?>" onClick="javascript:done('<?php echo esc_html($row->id); ?>')" style="background-image: url('<?php echo $dir_url; ?>img/done.png')"></div>
				<div style="width: 100%; display:flex; justify-content:center;">
					<div class="expand" onClick="javascript:expand2('<?php echo esc_html($row->id); ?>')" style="background-image: url('<?php echo $dir_url; ?>img/expand.png')"></div>
				</div>
				<?php
				if($row->bearbeiter != "unbekannt" && strtotime($row->termin) == strtotime($date) && $row->geloest == '0')
				{
				?>
					<div class="today_text">
						<p><span style="text-transform: uppercase;"><?php _e('today', 'simple-support-ticket-system'); ?></span></p>
					</div>
				<?php
				}
				if($row->bearbeiter != "unbekannt" && $row->termin != '' && strtotime($row->termin) < strtotime($date) && $row->geloest == '0')
				{
				?>
					<div class="warn_text">
						<p><span style="text-transform: uppercase;"><?php _e('overdue', 'simple-support-ticket-system'); ?></span></p>
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
										_e('TOMORROW', 'simple-support-ticket-system');
									} else {
										printf(__('Appointment in %s days', 'simple-support-ticket-system'), $days);
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
