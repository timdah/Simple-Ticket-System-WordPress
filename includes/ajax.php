<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Um $wpdb nutzen zu können
global $wpdb;
$user = $_COOKIE["ts_username"];
$date = date(get_option('date_format'));
$timestamp = strtotime($date);

// Abfrage für "Neue Tickets", "Meine Tickets" & "Offene Tickets"
if(isset($_POST["what"]))
{
	if(sanitize_text_field($_POST["what"] == "new"))
	{
		$ticket = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='0' AND bearbeiter='unbekannt' AND termin_timestamp<%d
					UNION
				   SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='0' AND bearbeiter='unbekannt' AND termin_timestamp=%d
					UNION
				   SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='0' AND bearbeiter='unbekannt' AND termin IS NULL
				    UNION
				   SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='0' AND bearbeiter='unbekannt' AND termin_timestamp>%d ",
				   $timestamp, $timestamp, $timestamp));
	} else if(sanitize_text_field($_POST["what"] == "open"))
	{
		$ticket = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='0' AND bearbeiter!='unbekannt' AND termin_timestamp<%d
					UNION
				   SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='0' AND bearbeiter!='unbekannt' AND termin_timestamp=%d
					UNION
				   SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='0' AND bearbeiter!='unbekannt' AND termin IS NULL
				    UNION
				   SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='0' AND bearbeiter!='unbekannt' AND termin_timestamp>%d ",
				   $timestamp, $timestamp, $timestamp));
	} else if(sanitize_text_field($_POST["what"] == "my"))
	{
		$ticket = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='0' AND bearbeiter=%s AND termin_timestamp<%d
					UNION
				   SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='0' AND bearbeiter=%s AND termin_timestamp=%d
					UNION
				   SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='0' AND bearbeiter=%s AND termin IS NULL
				    UNION
				   SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='0' AND bearbeiter=%s AND termin_timestamp>%d ",
				   $user, $timestamp, $user, $timestamp, $user, $user, $timestamp));
	}
}	

// Abfrage für Filter
if(isset($_POST["select"]))
{
	$select = sanitize_text_field($_POST["select"]);
	$select = esc_sql($select);
	$search = '%' . sanitize_text_field($_POST["search"]) . '%';
	$order = sanitize_text_field($_POST["order"]);
	$offset = 0;
	if(isset($_POST["offset"])){$offset = sanitize_text_field($_POST["offset"]);}
	$ticket = $wpdb->get_results($wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}sts_tickets WHERE `{$select}` LIKE %s ORDER BY id $order LIMIT 10 OFFSET %d",
				$search, $offset)
			  );
	if($select == 'termin')
	{
		$ticket = $wpdb->get_results($wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}sts_tickets WHERE `termin` IS NOT NULL AND `termin` != '' AND geloest='0' ORDER BY termin_timestamp ASC LIMIT 10 OFFSET %d",
					$offset)
				  );
	}
	if($select == 'geloest')
	{
		$ticket = $wpdb->get_results($wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}sts_tickets WHERE geloest='1' ORDER BY ende_timestamp $order LIMIT 10 OFFSET %d",
					$offset)
				  );
	}
}

// Abfrage für Änderung an Ticket
if(isset($_POST["id"]))
{
	$id = sanitize_text_field($_POST["id"]);
	$ticket = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}sts_tickets WHERE id=%d", $id));
}

// Schleife für Aktuallisierung des gewünschten Bereichs
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
		<?php if($row->bearbeiter == $user || $_COOKIE["ts_admin"] == 1) { ?>
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
		<?php 
		} 
		if($row->bearbeiter == $user || $row->bearbeiter == 'unbekannt' || $_COOKIE["ts_admin"] == 1) { ?>
		<div class="update">
		<!-- Laden der Texte in Textfeld -->
		<script>textarea('<?php echo esc_html($row->id); ?>');</script>
			<form onsubmit="return false">
				<table style="width: 100%">
					<tr style="width: 100%">
						<td class="textarea" rowspan="2">
							<textarea maxLength="1000" class="update_text" type="text" required="required"></textarea>
						</td>
						<td>
							<select class="select_2">
								<?php if($row->bearbeiter != 'unbekannt') { ?>
									<option value="loesung"><?php _e('Solution', 'simple-support-ticket-system'); ?></option>
									<option selected value="antwort"><?php _e('Answer', 'simple-support-ticket-system'); ?></option>
								<?php } ?>
								<option value="bemerkung"><?php _e('Note', 'simple-support-ticket-system'); ?></option>
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
		<?php } ?>
		<?php 
			// Button um Ticket abzuschließen
			if($row->bearbeiter == $user && $row->geloest != '1')
			{
		?>
				<div class="done" title="<?php _e('Finish this ticket', 'simple-support-ticket-system'); ?>" onClick="javascript:done('<?php echo esc_html($row->id); ?>')" style="background-image: url('<?php echo TS_DIR_URL; ?>img/done.png')"></div>
		<?php
			} 
			// Button um Ticket zu nehmen
			else if($row->bearbeiter == 'unbekannt' && $row->geloest != '1')
			{
		?>
				<div class="done" title="<?php _e('Take this ticket', 'simple-support-ticket-system'); ?>" onClick="javascript:take('<?php echo esc_html($row->id); ?>')" style="background-image: url('<?php echo TS_DIR_URL; ?>img/take.png')"></div>
		<?php
			} 
			// Button um Ticket zu übernehmen
			else if($row->bearbeiter != 'unbekannt' && $row->bearbeiter != $user && $row->geloest != '1')
			{
		?>
				<div class="done" title="<?php _e('Adopt this ticket', 'simple-support-ticket-system'); ?>" onClick="javascript:change('<?php echo esc_html($row->id); ?>')" style="background-image: url('<?php echo TS_DIR_URL; ?>img/change.png')"></div>
		<?php
			}
			// Button um Ticket zurück zu holen
			else if($row->bearbeiter == $user && $row->geloest == '1' || $row->geloest == '1' && $_COOKIE["ts_admin"] == 1)
			{
		?>
				<div class="done" title="<?php _e('Get this ticket back', 'simple-support-ticket-system'); ?>" onClick="javascript:undo('<?php echo esc_html($row->id); ?>')" style="background-image: url('<?php echo TS_DIR_URL; ?>img/undo.png')"></div>
		<?php
			}
		?>
		<?php 
		// Anzeigen des Bearbeitungsfeld
		if($row->bearbeiter == $user || $row->bearbeiter == 'unbekannt' || $_COOKIE["ts_admin"] == 1) 
		{	
		?>
			<div style="width: 100%; display:flex; justify-content:center;">
				<div class="expand" onClick="javascript:expand2('<?php echo esc_html($row->id); ?>')" style="background-image: url('<?php echo TS_DIR_URL; ?>img/expand.png')"></div>
			</div>
		<?php 
		}
		// Von wem erledigt
		if(sanitize_text_field($_POST["what"]) != "open" && $row->bearbeiter != "unbekannt" && $row->geloest == '1')
		{
		?>
			<div class="done_text">
				<p><?php _e('done by', 'simple-support-ticket-system'); ?>: <span style="text-transform: uppercase;"><?php echo esc_html($row->bearbeiter); ?></span>, 
				<span><?php echo esc_html($row->ende); ?></span></p>
			</div>
		<?php
		}
		// Von wem in Bearbeitung
		if($row->bearbeiter != "unbekannt" && $row->bearbeiter != $user && $row->geloest == '0')
		{
		?>
			<div class="while_text">
				<p><?php _e('under examination by', 'simple-support-ticket-system'); ?>: <span style="text-transform: uppercase;"><?php echo esc_html($row->bearbeiter); ?></span></p>
			</div>
		<?php
		}
		// Wenn Termin & überfällig
		if($row->termin != '' && strtotime($row->termin) < strtotime($date) && $row->geloest == '0')
		{
		?>
			<div class="warn_text">
				<p><span style="text-transform: uppercase;"><?php _e('overdue', 'simple-support-ticket-system'); ?></span></p>
			</div>
		<?php
		}
		// Wenn Termin & heute
		if($row->termin != '' && strtotime($row->termin) == strtotime($date) && $row->geloest == '0')
		{
		?>
			<div class="today_text">
				<p><span style="text-transform: uppercase;"><?php _e('today', 'simple-support-ticket-system'); ?></span></p>
			</div>
		<?php
		}
		// Wenn Termin & countdown
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

// Ermittlen der Seitenzahlen
if(isset($_POST["select"]))
{
	$select = sanitize_text_field($_POST["select"]);
	$search = sanitize_text_field($_POST["search"]);
	$order = sanitize_text_field($_POST["order"]);
	$ticket = $wpdb->get_results($wpdb->prepare(
				"SELECT COUNT(*) AS count FROM {$wpdb->prefix}sts_tickets WHERE %s LIKE %s ORDER BY id $order",
				$select, $search)
			  );
	if($select == 'termin')
	{
		$ticket = $wpdb->get_results($wpdb->prepare(
					"SELECT COUNT(*) AS count FROM {$wpdb->prefix}sts_tickets WHERE %s LIKE %s AND geloest='0' ORDER BY termin_timestamp ASC",
					$select, $search)
				  );
	}
	if($select == 'geloest')
	{
		$ticket = $wpdb->get_results(
					"SELECT COUNT(*) AS count FROM {$wpdb->prefix}sts_tickets WHERE geloest='1' ORDER BY id $order"
				  );
	}
	
	// Abfrage
	foreach($ticket as $row)
	{
		// Seitenzahlen anzeigen, wenn mehr als eine
		$count = ceil($row->count / 10);
		if($count > 1)
		{
		?>
			<div id="sites_count">
			<?php
				for($i = 0; $i < $count; $i++)
				{
				?>
					<a id="sites_count_<?php echo $i; ?>" href="javascript:sitenumber('<?php echo $i * 10; ?>')"><?php echo $i+1; ?></a>
				<?php
				}
			?>
			</div>
		<?php
		}
	}
}
?>
<script>
	textareaCheck()
</script>