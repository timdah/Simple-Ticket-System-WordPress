<?php
// Zugriff einschränken
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
global $wpdb;

if(isset($_GET["tid"])) {
	$id = decrypt($_GET["tid"], "Tu7nBL2XDc4j5XB62XyiHGDP");
} else if(isset($_POST["id"])) {
	$id = $_POST["id"];
}

// functions
function decrypt($string, $key) {
  $result = '';
  $string = base64_decode($string);

  for($i=0; $i<strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)-ord($keychar));
    $result.=$char;
  }

  return $result;
}

$ticket = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}sts_tickets WHERE id=%d",$id));
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
		</table>
		<table class="answers" style="display:table">
			<?php
			$answers = $wpdb->get_results($wpdb->prepare("SELECT antwort, user FROM {$wpdb->prefix}sts_answers WHERE ticket_id = %d ORDER BY index_antwort ASC", $id));
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
		<div class="update" style="display: flex">
		<script>jQuery(document).ready(function(){textarea('<?php echo esc_html($row->id); ?>');});</script>
			<form onsubmit="return false">
				<div id="ts_load"><div class="three-quarters-loader"></div></div>
				<table style="width: 100%">
					<tr style="width: 100%">
						<td class="textarea" rowspan="2">
							<textarea maxLength="1000" class="update_text" type="text" required="required"></textarea>
						</td>
						<td>
							<select class="select_2">
								<option selected value="antwort"><?php _e('Answer', 'simple-support-ticket-system'); ?></option>
								<option value="problem"><?php _e('Problem', 'simple-support-ticket-system'); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<button class="button" type="button" onClick="javascript:update('<?php echo esc_html($row->id); ?>')"><?php _e('Submit', 'simple-support-ticket-system'); ?></button>
						</td>
					</tr>		
				</table>
			</form>
		</div>
	</div>
<?php } ?>